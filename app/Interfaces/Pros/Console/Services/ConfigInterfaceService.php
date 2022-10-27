<?php
/**
 * Power by abnermouke/easy-builder.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in Yunni Technology Co Ltd.
 * Date: 2022-07-22
 * Time: 14:50:18
*/

namespace App\Interfaces\Pros\Console\Services;

use Abnermouke\EasyBuilder\Library\CodeLibrary;
use Abnermouke\EasyBuilder\Library\Cryptography\AesLibrary;
use Abnermouke\EasyBuilder\Module\BaseService;
use App\Repository\Pros\System\ConfigRepository;
use App\Services\Pros\Console\AdminLogService;
use App\Services\Pros\System\ConfigService;
use Illuminate\Support\Arr;

/**
 * 系统配置接口逻辑服务容器
 * Class ConfigService
 * @package App\Interfaces\Pros\Console\Services
*/
class ConfigInterfaceService extends BaseService
{

    /**
    * 引入父级构造
    * ConfigInterfaceService constructor.
    * @param bool $pass 是否直接获取结果
    */
    public function __construct($pass = false) { parent::__construct($pass); }

    /**
     * 获取配置信息
     * @Author Abnermouke <abnermouke@outlook.com>
     * @Originate in Abnermouke's MBP
     * @Time 2022-10-25 11:59:15
     * @return array|bool
     * @throws \Exception
     */
    public function configs()
    {
        //获取全部配置
        if ($configs = (new ConfigRepository())->get([], ['alias', 'content'])) {
            //整理信息
            $configs = array_column($configs, 'content', 'alias');
        }
        //整理信息
        $configs['QINIU_PARAMS__domain'] = data_get($configs, 'QINIU_PARAMS.domain', '');
        $configs['QINIU_PARAMS__access_key'] = data_get($configs, 'QINIU_PARAMS.access_key', '');
        $configs['QINIU_PARAMS__access_secret'] = data_get($configs, 'QINIU_PARAMS.access_secret', '');
        $configs['QINIU_PARAMS__bucket'] = data_get($configs, 'QINIU_PARAMS.bucket', '');
        $configs['QINIU_PARAMS__visibility'] = data_get($configs, 'QINIU_PARAMS.visibility', 'public');
        $configs['WECHAT_OFFICE_ACCOUNT_PARAMS__app_id'] = data_get($configs, 'WECHAT_OFFICE_ACCOUNT_PARAMS.app_id', '');
        $configs['WECHAT_OFFICE_ACCOUNT_PARAMS__secret'] = data_get($configs, 'WECHAT_OFFICE_ACCOUNT_PARAMS.secret', '');
        $configs['WECHAT_OFFICE_ACCOUNT_PARAMS__token'] = data_get($configs, 'WECHAT_OFFICE_ACCOUNT_PARAMS.token', '');
        $configs['WECHAT_OFFICE_ACCOUNT_PARAMS__aes_key'] = data_get($configs, 'WECHAT_OFFICE_ACCOUNT_PARAMS.aes_key', '');
        $configs['SMS_ALI_PARAMS__access_key_id'] = data_get($configs, 'SMS_ALI_PARAMS.access_key_id', '');
        $configs['SMS_ALI_PARAMS__access_key_secret'] = data_get($configs, 'SMS_ALI_PARAMS.access_key_secret', '');
        $configs['SMS_ALI_PARAMS__sign_name'] = data_get($configs, 'SMS_ALI_PARAMS.sign_name', '');
        //返回配置信息
        return $this->success(compact('configs'));
    }

    /**
     * 保存配置信息
     * @Author Abnermouke <abnermouke@outlook.com>
     * @Originate in Abnermouke's MBP
     * @Time 2022-10-25 11:59:15
     * @param $request
     * @return array|bool
     * @throws \Exception
     */
    public function store($request)
    {
        //获取加密信息
        if (!$data = AesLibrary::decryptFormData($request->all())) {
            //返回失败
            return $this->fail(CodeLibrary::DATA_MISSING,'非法参数');
        }
        //获取所有内容
        $edited = $data['__edited__'];
        $data = $data['__data__'];
        //初始化配置信息
        $configs = [];
        //循环信息
        foreach ($data as $alias => $config) {
            //判断标识
            switch ($alias) {
                case 'WECHAT_OFFICE_ACCOUNT_PARAMS__app_id':
                case 'WECHAT_OFFICE_ACCOUNT_PARAMS__secret':
                case 'WECHAT_OFFICE_ACCOUNT_PARAMS__token':
                case 'WECHAT_OFFICE_ACCOUNT_PARAMS__aes_key':
                case 'SMS_ALI_PARAMS__access_key_id':
                case 'SMS_ALI_PARAMS__access_key_secret':
                case 'SMS_ALI_PARAMS__sign_name':
                case 'QINIU_PARAMS__domain':
                case 'QINIU_PARAMS__access_key':
                case 'QINIU_PARAMS__access_secret':
                case 'QINIU_PARAMS__bucket':
                case 'QINIU_PARAMS__visibility':
                    //配置信息
                    $configs[Arr::first(explode('__', $alias))][Arr::last(explode('__', $alias))] = $config;
                    //判断是否更改
                    if (in_array($alias, $edited) && !in_array(Arr::first(explode('__', $alias)), $edited)) {
                        //添加更改
                        $edited[] = Arr::first(explode('__', $alias));
                    }
                    break;
                default:
                    //设置配置
                    $configs[$alias] = $config;
                    break;
            }
        }
        //获取指定数据
        $configs = Arr::only($configs, $edited);
        //保存数据
        if (!($service = new ConfigService())->setConfigs($configs)) {
            //返回失败
            return $this->fail($service->getCode(), $service->getMessage(), $service->getExtra() );
        }
        //添加操作日志
        (new AdminLogService())->record('保存系统配置信息', $configs);
        //返回成功
        return $this->success($edited);
    }
}
