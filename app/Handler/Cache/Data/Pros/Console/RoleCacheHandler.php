<?php
/**
 * Power by abnermouke/easy-builder.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in Yunni Technology Co Ltd.
 * Date: 2022-07-21
 * Time: 14:28:21
*/

namespace App\Handler\Cache\Data\Pros\Console;

use Abnermouke\EasyBuilder\Module\BaseCacheHandler;
use App\Model\Pros\Console\Roles;
use App\Repository\Pros\Console\NodeRepository;
use App\Repository\Pros\Console\RoleRepository;

/**
 * 管理员角色数据缓存处理器
 * Class RoleCacheHandler
 * @package App\Handler\Cache\Data\Pros\Console
 */
class RoleCacheHandler extends BaseCacheHandler
{

    //角色ID
    private $role_id;

    /**
     * 构造函数
     * RoleCacheHandler constructor.
     * @param $role_id
     * @throws \Exception
     */
    public function __construct($role_id)
    {
        //引入父级构造
        parent::__construct('pros:console:roles_data_cache:'.($this->role_id = (int)$role_id), 63795, 'file');
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
     * 检测权限
     * @Author Abnermouke <abnermouke@outlook.com>
     * @Originate in Abnermouke's MBP
     * @Time 2022-10-25 11:59:15
     * @param $request
     * @return bool
     * @throws \Exception
     */
    public function checkPermission($request)
    {
        //获取全部请求方式
        $methods = $request->route()->methods;
        //获取请求方式
        $method = $methods ? strtolower(head($methods)) : 'get';
        //验证权限
        return $this->hasPermission($method, $request->route()->getName());
    }

    /**
     * 确认是否有权限
     * @Author Abnermouke <abnermouke@outlook.com>
     * @Originate in Abnermouke's MBP
     * @Time 2022-10-25 11:59:15
     * @param $method
     * @param $route_name
     * @return bool
     * @throws \Exception
     */
    public function hasPermission($method, $route_name)
    {
        //判断信息
        if (!$this->cache) { return false; }
        //获取当前权限标示
        $alias = $method.'&'.$route_name;
        //判断是否是满权限
        if ((int)$this->cache['is_full_permission'] === Roles::SWITCH_ON) {
            //返回成功
            return true;
        }
        //设置节点信息
        $this->cache['permission_nodes'] = $this->currentPermissions();
        //判断是否在权限组内
        if (!$this->cache['permission_nodes'] || empty($this->cache['permission_nodes']) || !in_array($alias, $this->cache['permission_nodes'], true)) {
            //返回失败
            return false;
        }
        //验证成功
        return true;
    }

    /**
     * 获取当前所有节点
     * @Author Abnermouke <abnermouke@outlook.com>
     * @Originate in Abnermouke's MBP
     * @Time 2022-10-25 11:59:15
     * @return array|mixed|string
     * @throws \Exception
     */
    public function currentPermissions()
    {
        //判断信息
        if (!$this->cache) { return []; }
        //设置节点信息
        $this->cache['permission_nodes'] = object_2_array($this->cache['permission_nodes']);
        //判断是否是满权限
        if ((int)$this->cache['is_full_permission'] === Roles::SWITCH_ON) {
            //获取全部节点
            $this->cache['permission_nodes'] = (new NodeRepository())->pluck('alias');
        } else {
            //循环默认必须存在路由
            foreach (config('console_builder.nodes.default_node_aliases', []) as $alias) {
                //新增必须存在的权限节点
                array_push($this->cache['permission_nodes'], $alias);
            }
        }
        //返回节点信息
        return $this->cache['permission_nodes'];
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
            $repository = new RoleRepository();
            //初始化缓存数据
            if ($this->cache = $cache = $repository->row(['id' => (int)$this->role_id], ['is_full_permission', 'permission_nodes'])) {
                //保存缓存
                $this->save();
            }
        }
        //返回缓存信息
        return $cache;
    }

}
