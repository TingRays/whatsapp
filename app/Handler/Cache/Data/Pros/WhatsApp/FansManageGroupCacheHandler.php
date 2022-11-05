<?php
/**
 * Power by abnermouke/easy-builder.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in Yunni Technology Co Ltd.
 * Date: 2022-11-04
 * Time: 20:11:37
*/

namespace App\Handler\Cache\Data\Pros\WhatsApp;

use Abnermouke\EasyBuilder\Module\BaseCacheHandler;
use App\Repository\Pros\WhatsApp\FansManageGroupRepository;

/**
 * 粉丝管理分组数据缓存处理器
 * Class FansManageGroupCacheHandler
 * @package App\Handler\Cache\Data\Pros\WhatsApp
 */
class FansManageGroupCacheHandler extends BaseCacheHandler
{
    /**
     * 构造函数
     * FansManageGroupCacheHandler constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        //引入父级构造
        parent::__construct('pros:whatsapp:fans_manage_group_data_cache', 78579, 'file');
        //初始化缓存
        $this->init();
    }

    /**
     * 刷新当前缓存
     * @Author Abnermouke <abnermouke@outlook.com>
     * @Originate in Yunni Technology Co Ltd.
     * @Time 2022-11-04 20:11:37
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
     * @Time 2022-11-04 20:11:37
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
            $repository = new FansManageGroupRepository();

            //TODO : 初始化缓存数据

        }
        //返回缓存信息
        return $cache;
    }

}
