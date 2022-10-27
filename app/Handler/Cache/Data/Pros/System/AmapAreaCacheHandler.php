<?php
/**
 * Power by abnermouke/easy-builder.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in Yunni Technology Co Ltd.
 * Date: 2022-10-25
 * Time: 11:57:54
*/

namespace App\Handler\Cache\Data\Pros\System;

use Abnermouke\EasyBuilder\Module\BaseCacheHandler;
use App\Repository\Pros\System\AmapAreaRepository;

/**
 * 高德地图行政地区数据缓存处理器
 * Class AmapAreaCacheHandler
 * @package App\Handler\Cache\Data\Pros\System
 */
class AmapAreaCacheHandler extends BaseCacheHandler
{
    /**
     * 构造函数
     * AmapAreaCacheHandler constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        //引入父级构造
        parent::__construct('pros:system:amap_areas_data_cache', 49795, 'file');
        //初始化缓存
        $this->init();
    }

    /**
     * 刷新当前缓存
     * @Author Abnermouke <abnermouke@outlook.com>
     * @Originate in Yunni Technology Co Ltd.
     * @Time 2022-10-25 11:57:54
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
     * @Time 2022-10-25 11:57:54
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
            $repository = new AmapAreaRepository();

            //TODO : 初始化缓存数据

        }
        //返回缓存信息
        return $cache;
    }

}
