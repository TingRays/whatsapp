<?php
/**
 * Power by abnermouke/easy-builder.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in Yunni Technology Co Ltd.
 * Date: 2022-07-21
 * Time: 22:10:17
*/

namespace App\Interfaces\Pros\Console\Services;

use Abnermouke\EasyBuilder\Library\CodeLibrary;
use Abnermouke\EasyBuilder\Library\Cryptography\AesLibrary;
use Abnermouke\EasyBuilder\Module\BaseService;
use Abnermouke\Pros\Builders\Form\FormBuilder;
use Abnermouke\Pros\Builders\Form\Tools\FormItemBuilder;
use Abnermouke\Pros\Builders\Table\TableBuilder;
use App\Model\Pros\Console\Roles;
use App\Repository\Pros\Console\AdminRepository;
use App\Repository\Pros\Console\NodeRepository;
use App\Repository\Pros\Console\RoleRepository;
use App\Services\Pros\Console\AdminLogService;
use Illuminate\Support\Arr;

/**
 * 管理员权限角色接口逻辑服务容器
 * Class RoleService
 * @package App\Interfaces\Pros\Console\Services
*/
class RoleInterfaceService extends BaseService
{

    /**
    * 引入父级构造
    * RoleInterfaceService constructor.
    * @param bool $pass 是否直接获取结果
    */
    public function __construct($pass = false) { parent::__construct($pass); }

    /**
     * 获取权限角色列表
     * @Author Abnermouke <abnermouke@outlook.com>
     * @Originate in Abnermouke's MBP
     * @Time 2022-10-25 11:59:15
     * @param $request
     * @return array|bool
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function lists($request)
    {
        //获取加密信息
        if (!$data = AesLibrary::decryptFormData($request->all())) {
            //返回失败
            return $this->fail(CodeLibrary::DATA_MISSING, '非法参数');
        }
        //整理查询条件
        $conditions = [];
        //判断筛选条件
        if ($filters = data_get($data, 'filters', [])) {
            //循环筛选条件
            foreach ($filters as $filter => $value) {
                //根据筛选项设置条件
                switch ($filter) {
                    case '__keyword__':
                        $value && $conditions[implode('|', ['guard_name', 'alias'])] = ['like', '%'.$value.'%'];
                        break;
                    case 'is_locked':
                        (int)$value > 0 && $conditions['is_locked'] = (int)$value;
                        break;
                    case 'is_full_permission':
                        (int)$value > 0 && $conditions['is_full_permission'] = (int)$value;
                        break;
                    case 'created_at':
                        $value && $conditions['created_at'] = ['date', $value];
                        break;
                    case 'updated_at':
                        $value && $conditions['updated_at'] = ['date', $value];
                        break;
                }
            }
        }
        //整理排序规格
        $sort_rules = data_get($data, 'sorts', ['id' => 'desc']);
        //查询列表
        $lists = (new RoleRepository())->lists($conditions, [], [], $sort_rules, '', (int)data_get($data, 'page', config('pros.table.default_page')), (int)data_get($data, 'page_size', config('pros.table.default_page_size')));
        //查询列表
        if ($lists && !empty($lists['lists'])) {
            //循环列表
            foreach ($lists['lists'] as $k => $list) {
                //查询列表
                if ($avatars = (new AdminRepository())->get(['role_id' => (int)$list['id']], ['nickname', 'avatar'])) {
                    //循环信息
                    foreach ($avatars as $kk => $avatar) {
                        //设置信息
                        $avatars[$kk] = !empty($avatar['avatar']) ? $avatar['avatar'] : $avatar['nickname'];
                    }
                }
                //设置信息
                $lists['lists'][$k]['avatars'] = object_2_array($avatars);
            }
        }
        //渲染表格内容
        $render = TableBuilder::CONTENT()->signature($data['signature'])->setLists($lists)->render();
        //返回成功
        return $this->success($render);
    }

    /**
     * 获取权限角色详情
     * @Author Abnermouke <abnermouke@outlook.com>
     * @Originate in Abnermouke's MBP
     * @Time 2022-10-25 11:59:15
     * @param $id
     * @param $request
     * @return array|bool
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function detail($id, $request)
    {
        //根据分组名分组节点信息
        $nodes = (new NodeRepository())->get([], ['alias', 'method', 'route_name', 'guard_name', 'action', 'group_name'], [], ['created_at' => 'asc']);
        //整理信息
        $groups = [];
        //循环节点信息
        foreach ($nodes as $k => $node) {
            //判断是否存在分组
            if (!isset($groups[$node['group_name']])) {
                //设置默认分组
                $groups[$node['group_name']] = [];
            }
            //判断节点名称
            if (!in_array($node['group_name'], ['首页', '上传'])) {
                //替换名称信息
                $node['guard_name'] = str_replace($node['group_name'], '', $node['guard_name']);
            }
            //判断节点是否必须存在
            if (!in_array($node['alias'], config('console_builder.nodes.default_node_aliases', []))) {
                //添加节点
                $groups[$node['group_name']][$node['alias']] = $node['guard_name'];
            }
            //释放内存
            unset($nodes[$k]);
        }
        //渲染表单内容
        $render = FormBuilder::make()
            ->setSubmit(route('pros.console.admins.roles.store', ['id' => (int)$id]))
            ->setItems(function (FormItemBuilder $builder) use ($id, $groups) {
                $builder->input('guard_name', '角色名称')->required();
                $builder->input('alias', '角色标识')->description('角色标识需唯一，生成成功后将不支持修改，请确认后输入')->readonly((int)$id <= 0 ? false : true)->required();
                $builder->switch('is_full_permission', '是否满权限')->description('满权限角色无需配置任何权限即可使用后台所有功能，并可删除锁定角色')->allow_text('满权限')->on(Roles::SWITCH_ON)->off(Roles::SWITCH_OFF, ['permission_nodes']);
                $builder->checkbox('permission_nodes', '权限节点')->options_with_groups($groups);
            })
            ->setData((int)$id > 0 ? (new RoleRepository())->row(['id' => (int)$id]) : [])
            ->render();
        //返回成功
        return $this->success(['html' => $render]);
    }

    /**
     * 保存权限角色信息
     * @Author Abnermouke <abnermouke@outlook.com>
     * @Originate in Abnermouke's MBP
     * @Time 2022-10-25 11:59:15
     * @param $id
     * @param $request
     * @return array|bool
     * @throws \Exception
     */
    public function store($id, $request)
    {
        //获取加密信息
        if (!$data = AesLibrary::decryptFormData($request->all())) {
            //返回失败
            return $this->fail(CodeLibrary::DATA_MISSING,'非法参数');
        }
        //判断更改项
        if (!($edited = $data['__edited__'])) {
            //返回失败
            return $this->fail(CodeLibrary::DATA_MISSING, '信息无更新');
        }
        //获取更改项
        $info = Arr::only($data['__data__'], $data['__edited__']);
        //添加修改时间
        $info['updated_at'] = auto_datetime();
        //判断是否设置为满权限
        if ((int)$data['__data__']['is_full_permission'] === Roles::SWITCH_OFF) {
            //判断节点是否全部选中
            if ((int)count($data['__data__']['permission_nodes']) >= (new NodeRepository())->count()) {
                //默认设置为满权限
                $info['is_full_permission'] = Roles::SWITCH_ON;
            }
        }
        //判断是否为新增
        if ((int)$id <= 0) {
            //判断标识是否可用
            if ((new RoleRepository())->exists(['alias' => $info['alias']])) {
                //返回失败
                return $this->fail(CodeLibrary::DATA_CREATE_FAIL, '角色标识已被占用，请更改');
            }
            //添加信息
            $info['is_locked'] = Roles::SWITCH_OFF;
            $info['created_at'] = auto_datetime();
            //添加信息
            if (!$id = (new RoleRepository())->insertGetId($info)) {
                //返回失败
                return $this->fail(CodeLibrary::DATA_UPDATE_FAIL, '信息创建失败');
            }
            //添加日志
            (new AdminLogService())->record('新增管理员权限角色信息', compact('id'));
        } else {
            //修改信息
            if (!(new RoleRepository())->update(['id' => (int)$id], $info)) {
                //返回失败
                return $this->fail(CodeLibrary::DATA_UPDATE_FAIL, '修改失败');
            }
            //添加日志
            (new AdminLogService())->record('更新管理员权限角色信息', compact('id'));
        }
        //返回成功
        return $this->success(compact('id'));
    }

    /**
     * 快捷设置满权限
     * @Author Abnermouke <abnermouke@outlook.com>
     * @Originate in Abnermouke's MBP
     * @Time 2022-10-25 11:59:15
     * @param $id
     * @param $request
     * @return array|bool
     * @throws \Exception
     */
    public function full_permissions($id, $request)
    {
        //获取加密信息
        if (!$data = AesLibrary::decryptFormData($request->all())) {
            //返回失败
            return $this->fail(CodeLibrary::DATA_MISSING, '非法参数');
        }
        //更改账户状态
        if (!(new RoleRepository())->update(['id' => (int)$id], ['is_full_permission' => (int)$data['value'], 'updated_at' => auto_datetime()])) {
            //返回失败
            return $this->fail(CodeLibrary::DATA_UPDATE_FAIL, '更改失败');
        }
        //记录日志
        (new AdminLogService())->record('更改管理员权限角色为：'.$data['text'], $data);
        //返回成功
        return $this->success(compact('id'));
    }

    /**
     * 删除管理员权限角色
     * @Author Abnermouke <abnermouke@outlook.com>
     * @Originate in Abnermouke's MBP
     * @Time 2022-10-25 11:59:15
     * @param $request
     * @return array|bool
     * @throws \Exception
     */
    public function delete($request)
    {
        //获取加密信息
        if (!$data = AesLibrary::decryptFormData($request->all())) {
            //返回失败
            return $this->fail(CodeLibrary::DATA_MISSING, '非法参数');
        }
        //获取处理ID
        if ($ids = object_2_array(data_get($data, 'id', []))) {
            //判断是否存在绑定管理员
            if ((new AdminRepository())->exists(['role_id' => ['in', $ids]])) {
                //返回失败
                return $this->fail(CodeLibrary::DATA_EXISTS, '绑定管理员未清空');
            }
            //判断是否满权限
            if ((int)current_auth('role_full_permission', config('pros.session_prefix')) !== Roles::SWITCH_ON && ($role_names = (new RoleRepository())->pluck('guard_name', ['id' => ['in', $ids], 'is_locked' => Roles::SWITCH_ON]))) {
                //返回失败
                return $this->fail(CodeLibrary::WITH_DO_NOT_ALLOW_STATE, implode(', ', $role_names).'已锁定，您的权限不可删除');
            }
            //删除角色信息
            if (!(new RoleRepository())->delete(['id' => ['in', $ids]])) {
                //返回失败
                return $this->fail(CodeLibrary::DATA_DELETE_FAIL, '网络错误，删除失败');
            }
            //记录日志
            (new AdminLogService())->record('删除管理员权限角色', $ids);
        }
        //返回成功
        return $this->success($ids);
    }
}
