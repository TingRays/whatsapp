<?php
/**
 * Power by abnermouke/easy-builder.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in Yunni Technology Co Ltd.
 * Date: 2022-07-21
 * Time: 14:16:10
*/

namespace App\Services\Pros\System;

use Abnermouke\EasyBuilder\Library\CodeLibrary;
use Abnermouke\EasyBuilder\Module\BaseService;
use App\Handler\Cache\Data\Pros\System\ConfigCacheHandler;
use App\Repository\Pros\System\ConfigRepository;
use Illuminate\Support\Facades\DB;

/**
 * 配置逻辑服务容器
 * Class ConfigService
 * @package App\Services\Pros\System
*/
class ConfigService extends BaseService
{

    /**
    * 引入父级构造
    * ConfigService constructor.
    * @param bool $pass 是否直接获取结果
    */
    public function __construct($pass = false) { parent::__construct($pass); }

    /**
     * 设置配置信息
     * @Author Abnermouke <abnermouke@outlook.com>
     * @Originate in Abnermouke's MBP
     * @Time 2022-10-25 11:59:15
     * @param $configs
     * @return array|bool
     * @throws \Exception
     */
    public function setConfigs($configs)
    {
        //判断是否存在图片修改
        if (in_array('APP_SHORTCUT_ICON', array_keys($configs))) {
            //设置图片可用
            $configs['APP_SHORTCUT_ICON'] = (new TemporaryFileService())->pass(true)->enable($configs['APP_SHORTCUT_ICON']);
        }
        //判断是否存在图片修改
        if (in_array('APP_LOGO', array_keys($configs))) {
            //设置图片可用
            $configs['APP_LOGO'] = (new TemporaryFileService())->pass(true)->enable($configs['APP_LOGO']);
        }
        //开始事务处理
        DB::beginTransaction();
        //尝试保存信息
        try {
            //循环配置
            foreach ($configs as $alias => $config) {
                //保存信息
                (new ConfigRepository())->update(['alias' => $alias], ['content' => $config, 'updated_at' => auto_datetime()]);
            }
            //提交事务
            DB::commit();
        } catch (\Exception $exception) {
            //回滚事务
            DB::rollBack();
            //返回错误
            return $this->fail(CodeLibrary::DATA_UPDATE_FAIL, $exception->getMessage());
        }
        //刷新缓存
        (new ConfigCacheHandler())->refresh();
        //返回成功
        return $this->success($configs);
    }
}
