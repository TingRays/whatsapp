<?php
/**
 * Power by abnermouke/easy-builder.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in Yunni Technology Co Ltd.
 * Date: 2022-07-22
 * Time: 23:56:52
*/

namespace App\Services\Pros\System;

use Abnermouke\EasyBuilder\Library\CodeLibrary;
use Abnermouke\EasyBuilder\Module\BaseService;
use App\Model\Pros\System\SmsLogs;
use App\Repository\Pros\System\SmsLogRepository;

/**
 * 短信记录逻辑服务容器
 * Class SmsLogService
 * @package App\Services\Pros\System
*/
class SmsLogService extends BaseService
{

    /**
    * 引入父级构造
    * SmsLogService constructor.
    * @param bool $pass 是否直接获取结果
    */
    public function __construct($pass = false) { parent::__construct($pass); }


    /**
     * 创建短信发送日志
     * @Author Abnermouke <abnermouke@outlook.com>
     * @Originate in Abnermouke's MBP
     * @Time 2022-10-25 11:59:15
     * @param $mobile
     * @param array $data
     * @param string $template_id
     * @param string $content
     * @param string $gateway
     * @param string $sign_name
     * @return array|bool
     * @throws \Exception
     */
    public function logger($mobile, $data = [], $template_id = '', $content = '', $gateway = '', $sign_name = '')
    {
        //整理信息
        $log = [
            'gateway' => $gateway,
            'mobile' => $mobile,
            'sign_name' => $sign_name,
            'template_id' => $template_id,
            'data' => object_2_array($data),
            'content' => $content,
            'status' => SmsLogs::STATUS_VERIFYING,
            'result_code' => 0,
            'ip' => request()->getClientIp(),
            'created_at' => auto_datetime(),
            'updated_at' => auto_datetime()
        ];
        //创建记录
        if (!$log_id = (new SmsLogRepository())->insertGetId($log)) {
            //返回失败
            return $this->fail(CodeLibrary::DATA_CREATE_FAIL, '创建失败');
        }
        return $this->success($log_id);
    }

    /**
     * 更新短信发送状态
     * @Author Abnermouke <abnermouke@outlook.com>
     * @Originate in Abnermouke's MBP
     * @Time 2022-10-25 11:59:15
     * @param $log_id
     * @param $status
     * @param int $result_code
     * @return array|bool
     * @throws \Exception
     */
    public function update($log_id, $status, $result_code = 0)
    {
        //更新发送状态
        (new SmsLogRepository())->update(['id' => (int)$log_id], ['status' => (int)$status, 'result_code' => $result_code, 'updated_at' => auto_datetime()]);
        //返回成功
        return $this->success(compact('log_id'));
    }

    /**
     * 验证手机号码是否满足发送条件
     * @Author Abnermouke <abnermouke@outlook.com>
     * @Originate in Abnermouke's MBP
     * @Time 2022-10-25 11:59:15
     * @param $mobile
     * @param int $second_frequency_limit
     * @param int $day_frequency_limit
     * @return array|bool
     * @throws \Exception
     */
    public function verify($mobile, $second_frequency_limit = 60, $day_frequency_limit = 10)
    {
        //获取上一条发送记录
        if ($last = (new SmsLogRepository())->row(['mobile' => $mobile], ['id', 'created_at'], ['created_at' => 'desc'])) {
            //获取时间戳
            $last_time = to_time($last['created_at']);
            //判断是否超出频率
            if (((int)$last_time + (int)$second_frequency_limit) > time()) {
                //返回失败
                return $this->fail(CodeLibrary::VALIDATE_FAILED, '短信发送需间隔'.$second_frequency_limit.'秒');
            }
            //判断当天发送条数
            if ((new SmsLogRepository())->count(['mobile' => $mobile, 'status' => SmsLogs::STATUS_ENABLED, 'created_at' => ['date', auto_datetime('Y-m-d')]]) >= (int)$day_frequency_limit) {
                //返回失败
                return $this->fail(CodeLibrary::VALIDATE_FAILED, '每天每号最多发送'.$day_frequency_limit.'条');
            }
        }
        //返回成功
        return $this->success(compact('mobile'));
    }
}
