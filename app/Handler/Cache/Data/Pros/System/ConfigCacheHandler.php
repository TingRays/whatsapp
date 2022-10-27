<?php
/**
 * Power by abnermouke/easy-builder.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in Yunni Technology Co Ltd.
 * Date: 2022-07-21
 * Time: 14:16:10
*/

namespace App\Handler\Cache\Data\Pros\System;

use Abnermouke\EasyBuilder\Module\BaseCacheHandler;
use App\Repository\Pros\System\ConfigRepository;

/**
 * 配置数据缓存处理器
 * Class ConfigCacheHandler
 * @package App\Handler\Cache\Data\Pros\System
 */
class ConfigCacheHandler extends BaseCacheHandler
{
    /**
     * 构造函数
     * ConfigCacheHandler constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        //引入父级构造
        parent::__construct('pros:system:configs_data_cache', 35783, 'file');
        //初始化缓存
        $this->init();
    }

    /**
     * 刷新当前缓存
     * @Author Abnermouke <abnermouke@outlook.com>
     * @Originate in Yunni Technology Co Ltd.
     * @Time 2022-10-25 11:59:15
     * @return array
     * @throws \Exception
    */
    public function refresh()
    {
        //删除缓存
        $this->clear();
        //初始化缓存
        return $this->init();
    }

    /**
     * 初始化缓存
     * @Author Abnermouke <abnermouke@outlook.com>
     * @Originate in Yunni Technology Co Ltd.
     * @Time 2022-10-25 11:59:15
     * @return array
     * @throws \Exception
    */
    private function init()
    {
        //获取缓存
        $cache = $this->cache;
        //判断缓存信息
        if (!$cache || empty($this->cache)) {
            //引入Repository
            $repository = new ConfigRepository();
            //初始化缓存数据
            if ($configs = $repository->get([], ['alias', 'content'])) {
                //设置信息
                $this->cache = $cache = array_column($configs, 'content', 'alias');
                //保存缓存
                $this->save();
            }
        }
        //返回缓存信息
        return $cache;
    }

}
