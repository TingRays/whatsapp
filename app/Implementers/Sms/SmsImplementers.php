<?php

namespace App\Implementers\Sms;

use Abnermouke\EasyBuilder\Library\CodeLibrary;
use Abnermouke\EasyBuilder\Library\Currency\ValidateLibrary;
use Abnermouke\EasyBuilder\Module\BaseService;
use App\Handler\Cache\Data\Pros\System\ConfigCacheHandler;
use App\Model\Pros\System\SmsLogs;
use App\Services\Pros\System\SmsLogService;
use Overtrue\EasySms\EasySms;
use Overtrue\EasySms\Exceptions\NoGatewayAvailableException;

/**
 * 短信执行类
 * Class SmsImplementers
 * @package App\Implementers\Sms
 */
class SmsImplementers extends BaseService
{

    /*
     * 频率限制（每个手机多少秒内只能发送一次）
     */
    private $second_frequency_limit;

    /*
     * 频率限制（每个手机每天只能发几次）
     */
    private $day_frequency_limit;

    /**
     * 发送网关
     * @var
     */
    private $gateway;

    /**
     * 发送模版
     * @var
     */
    protected $template;

    /**
     * 构造函数
     * SmsImplementers constructor.
     * @param false $second_frequency_limit
     * @param false $day_frequency_limit
     * @throws \Exception
     */
    public function __construct($second_frequency_limit = false, $day_frequency_limit = false)
    {
        //引用父级构造
        parent::__construct(false);
        //设置频率限制（每个手机多少秒内只能发送一次，每天只能发几次）
        $this->second_frequency_limit = (int)$second_frequency_limit ? (int)$second_frequency_limit : (new ConfigCacheHandler())->get('SMS_SECOND_FREQUENCY_LIMIT', 60);
        $this->day_frequency_limit = (int)$day_frequency_limit ? (int)$day_frequency_limit : (new ConfigCacheHandler())->get('SMS_DAY_FREQUENCY_LIMIT', 10);
        //设置默认网关
        $this->gateway();
    }

    /**
     * 设置发送网关
     * @Author Abnermouke <abnermouke@outlook.com>
     * @Originate in Abnermouke's MBP
     * @Time 2022-05-16 17:58:36
     * @param false $gateway
     * @return $this
     * @throws \Exception
     */
    public function gateway($gateway = false)
    {
        //设置默认网关
        $this->gateway = !$gateway ? (new ConfigCacheHandler())->get('SMS_DEFAULT_GATEWAYS', 'ali_sms') : trim($gateway);
        //返回当前实例
        return $this;
    }

    /**
     * 设置发送模版
     * @Author Abnermouke <abnermouke@outlook.com>
     * @Originate in Abnermouke's MBP
     * @Time 2022-05-16 18:04:25
     * @param $template
     * @return $this
     */
    public function template($template)
    {
        //设置发送模版
        $this->template = $template;
        //返回当前实例
        return $this;
    }

    /**
     * 发送短信
     * @Author Abnermouke <abnermouke@outlook.com>
     * @Originate in Abnermouke's MBP
     * @Time 2022-05-16 18:46:08
     * @param $mobile
     * @param array $data
     * @param string $template
     * @return array|bool
     * @throws \Exception
     */
    public function send($mobile, $data = [], $template = '')
    {
        //设置发送模版
        $template && $this->template($template);
        //验证手机号码
        if (!ValidateLibrary::mobile($mobile)) {
            //返回失败
            return $this->fail(CodeLibrary::VALIDATE_FAILED, '手机号码有误');
        }
        //验证手机号码是否满足发送条件
        if (!($service = new SmsLogService())->verify($mobile, $this->second_frequency_limit, $this->day_frequency_limit)) {
            //返回失败
            return $this->fail($service->getCode(), $service->getMessage());
        }
        //根据网关选择发送对象
        switch ($this->gateway) {
            case 'ali_sms':
                //阿里云短信服务发送
                $result = $this->sendByAliyun($mobile, $data);
                break;
            default:
                //返回失败
                return $this->fail(CodeLibrary::WITH_DO_NOT_ALLOW_STATE, '未知短信网关');
                break;
        }
        //返回信息
        return $result;
    }

    /**
     * 使用阿里云发送信息
     * @Author Abnermouke <abnermouke@outlook.com>
     * @Originate in Abnermouke's MBP
     * @Time 2022-05-16 18:45:46
     * @param $mobile
     * @param array $data
     * @return array|bool
     * @throws \Exception
     */
    private function sendByAliyun($mobile, $data = [])
    {
        // 整理短信内容
        $content = config('easysms.gateways.aliyun.template_content.'.$this->template, '');
        //整理参数
        $params = [
            'content' => $content,
            'template' => $this->template,
            'data' => $data
        ];
        //获取阿里云短信服务配置
        $config = object_2_array((new ConfigCacheHandler())->get('SMS_ALI_PARAMS', []));
        //判断参数
        if (!data_get($config, 'access_key_id', '') || !data_get($config, 'access_key_secret', '') || !data_get($config, 'sign_name', '')) {
            //返回失败
            return $this->fail(CodeLibrary::WITH_DO_NOT_ALLOW_STATE, '无效短信网关');
        }
        //创建记录
        if (!$logger_id = ($service = new SmsLogService())->pass(true)->logger($mobile, $data, $this->template, $content, $this->gateway, $config['sign_name'])) {
            //返回失败
            return $this->fail($service->getCode(), $service->getMessage());
        }
        //获取系统配置
        $system_config = config('easysms');
        //设置动态参数
        $system_config['gateways']['aliyun'] = array_merge($system_config['gateways']['aliyun'], $config);
        //生成处理实例
        $app = new EasySms($system_config);
        //尝试发送短信
        try {
            //发送短信
            if (!$app->send($mobile, $params)) {
                //更新状态
                (new SmsLogService())->update($logger_id, SmsLogs::STATUS_DISABLED, CodeLibrary::NETWORK_ERROR);
                //返回失败
                return $this->fail(CodeLibrary::NETWORK_ERROR, '网络错误，发送失败');
            }
        } catch (NoGatewayAvailableException $exception) {
            //更新状态
            (new SmsLogService())->update($logger_id, SmsLogs::STATUS_DISABLED, CodeLibrary::REQUEST_PARAM_VERIFY_FAILED);
            //返回失败
            return $this->fail(CodeLibrary::REQUEST_PARAM_VERIFY_FAILED, '未知网关');
        } catch (\Exception $exception) {
            //更新状态
            (new SmsLogService())->update($logger_id, SmsLogs::STATUS_DISABLED, CodeLibrary::CODE_LOGIC_ERROR);
            //返回失败
            return $this->fail(CodeLibrary::CODE_LOGIC_ERROR, '发送失败');
        }
        //更新状态
        (new SmsLogService())->update($logger_id, SmsLogs::STATUS_ENABLED, CodeLibrary::CODE_SUCCESS);
        //返回发送成功
        return $this->success(compact('mobile'));
    }

}
