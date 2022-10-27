<?php
/**
 * Power by abnermouke/easy-builder.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in Yunni Technology Co Ltd.
 * Date: 2022-07-21
 * Time: 14:29:46
*/

namespace App\Handler\Cache\Data\Pros\Console;

use Abnermouke\EasyBuilder\Module\BaseCacheHandler;
use App\Repository\Pros\Console\NodeRepository;

/**
 * 节点数据缓存处理器
 * Class NodeCacheHandler
 * @package App\Handler\Cache\Data\Pros\Console
 */
class NodeCacheHandler extends BaseCacheHandler
{
    /**
     * 构造函数
     * NodeCacheHandler constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        //引入父级构造
        parent::__construct('pros:console:nodes_data_cache', 85599, 'file');
        //初始化缓存
        $this->init();
    }

    /**
     * 刷新当前缓存
     * @Author Abnermouke <abnermouke@outlook.com>
     * @Originate in Yunni Technology Co Ltd.
     * @Time 2022-07-21 14:29:46
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
     * @Time 2022-07-21 14:29:46
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
            $repository = new NodeRepository();
            //初始化缓存数据
            if ($nodes = $repository->get([], ['alias', 'method', 'middleware', 'route_name', 'guard_name', 'group_name', 'action'])) {
                //设置缓存
                $this->cache = $cache = array_column($nodes, null, 'alias');
                //保存缓存
                $this->save();
            }
        }
        //返回缓存信息
        return $cache;
    }

}
