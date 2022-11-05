<?php
/**
 * Power by abnermouke/easy-builder.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in Yunni Technology Co Ltd.
 * Date: 2022-11-04
 * Time: 20:13:04
*/

namespace App\Interfaces\Pros\WhatsApp\Services;

use Abnermouke\EasyBuilder\Library\CodeLibrary;
use Abnermouke\EasyBuilder\Library\Cryptography\AesLibrary;
use Abnermouke\EasyBuilder\Module\BaseService;
use Abnermouke\Pros\Builders\Form\FormBuilder;
use Abnermouke\Pros\Builders\Form\Tools\FormItemBuilder;
use Abnermouke\Pros\Builders\Table\TableBuilder;
use App\Model\Pros\WhatsApp\FansManageGroup;
use App\Repository\Pros\WhatsApp\FansManageGroupRepository;
use Illuminate\Support\Arr;

/**
 * 粉丝管理分组接口逻辑服务容器
 * Class FansManageGroupService
 * @package App\Interfaces\Pros\WhatsApp\Services
*/
class FansManageGroupInterfaceService extends BaseService
{

    /**
    * 引入父级构造
    * FansManageGroupInterfaceService constructor.
    * @param bool $pass 是否直接获取结果
    */
    public function __construct($pass = false) { parent::__construct($pass); }

    /**
     * 粉丝管理分组列表
     * @param $request
     * @return array|bool
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function lists($request){
        //获取加密信息
        if (!$data = AesLibrary::decryptFormData($request->all())) {
            //返回失败
            return $this->fail(CodeLibrary::DATA_MISSING, '非法参数');
        }
        //整理查询条件
        //设置默认条件
        $conditions = ['status'=>FansManageGroup::STATUS_ENABLED];
        $admin_id = current_auth('id', config('pros.session_prefix', 'abnermouke:pros:console:auth'));
        $conditions['admin_id'] = $admin_id;
        //判断筛选条件
        if ($filters = data_get($data, 'filters', [])) {
            //循环筛选条件
            foreach ($filters as $filter => $value) {
                //根据筛选项设置条件
                switch ($filter) {
                    case 'keyword':
                        $value && $conditions[implode('|', ['id', 'title'])] = ['like', '%'.$value.'%'];
                        break;
                    case 'updated_at':
                        $value && $conditions['updated_at'] = ['date', $value];
                        break;
                }
            }
        }
        //查询列表
        $lists = (new FansManageGroupRepository())->lists($conditions, ['*'], [], data_get($data, 'sorts', ['id' => 'desc']), '', (int)data_get($data, 'page', config('pros.table.default_page')), (int)data_get($data, 'page_size', config('pros.table.default_page_size')));

        //渲染表格内容
        $render = TableBuilder::CONTENT()->signature($data['signature'])->setLists($lists)->render();
        //返回成功
        return $this->success($render);
    }

    /**
     * 粉丝管理分组详情
     * @param $id
     * @param $request
     * @return array|bool
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function detail($id, $request){
        //渲染表单内容
        $render = FormBuilder::make()
            ->setSubmit(route('whatsapp.console.fans_manage.group.store',['id'=>$id]))
            ->setItems(function (FormItemBuilder $builder) {
                $builder->input('title', '分组标题')->description('分组标题')->required();
                $builder->textarea('content', '备注')->row(5);
            })
            ->setData((int)$id > 0 ? (new FansManageGroupRepository())->row(['id' => (int)$id]) : [])
            ->render();
        //返回成功
        return $this->success(['html' => $render]);
    }

    /**
     * 保存粉丝管理分组
     * @param $id
     * @param $request
     * @return array|bool
     * @throws \Exception
     */
    public function store($id, $request){
        //获取加密信息
        if (!$data = AesLibrary::decryptFormData($request->all())) {
            //返回失败
            return $this->fail(CodeLibrary::DATA_MISSING, '非法参数');
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
        //判断是否为新增
        if ((int)$id <= 0) {
            $admin_id = current_auth('id', config('pros.session_prefix', 'abnermouke:pros:console:auth'));
            //判断信息是否可用
            if ((new FansManageGroupRepository())->exists(['title' => $info['title'],'admin_id'=>$admin_id])) {
                //返回失败
                return $this->fail(CodeLibrary::DATA_CREATE_FAIL, '分组标题已存在');
            }
            //添加信息
            $info['code'] = (new FansManageGroupRepository())->uniqueCode('code', 'string', 8, true);
            $info['admin_id'] = $admin_id;
            $info['created_at'] = auto_datetime();
            //添加信息
            if (!$id = (new FansManageGroupRepository())->insertGetId($info)) {
                //返回失败
                return $this->fail(CodeLibrary::DATA_UPDATE_FAIL, '分组标题创建失败');
            }
        } else {
            //修改信息
            if (!(new FansManageGroupRepository())->update(['id' => (int)$id], $info)) {
                //返回失败
                return $this->fail(CodeLibrary::DATA_UPDATE_FAIL, '修改失败');
            }
        }
        //返回成功
        return $this->success(compact('id'));
    }
}
