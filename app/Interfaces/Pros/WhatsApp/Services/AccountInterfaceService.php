<?php
/**
 * Power by abnermouke/easy-builder.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in Yunni Technology Co Ltd.
 * Date: 2022-10-26
 * Time: 15:14:30
*/

namespace App\Interfaces\Pros\WhatsApp\Services;

use Abnermouke\EasyBuilder\Library\CodeLibrary;
use Abnermouke\EasyBuilder\Library\Cryptography\AesLibrary;
use Abnermouke\EasyBuilder\Module\BaseService;
use Abnermouke\Pros\Builders\Form\FormBuilder;
use Abnermouke\Pros\Builders\Form\Tools\FormItemBuilder;
use Abnermouke\Pros\Builders\Table\TableBuilder;
use App\Model\Pros\WhatsApp\Accounts;
use App\Repository\Pros\WhatsApp\AccountRepository;
use App\Repository\Pros\WhatsApp\AccountTagRepository;
use Illuminate\Support\Arr;

/**
 * 用户接口逻辑服务容器
 * Class AccountService
 * @package App\Interfaces\Pros\WhatsApp\Services
*/
class AccountInterfaceService extends BaseService
{

    /**
    * 引入父级构造
    * AccountInterfaceService constructor.
    * @param bool $pass 是否直接获取结果
    */
    public function __construct($pass = false) { parent::__construct($pass); }

    /**
     * 用户账户列表
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
        $conditions = [];
        //判断筛选条件
        if ($filters = data_get($data, 'filters', [])) {
            //循环筛选条件
            foreach ($filters as $filter => $value) {
                //根据筛选项设置条件
                switch ($filter) {
                    case 'keyword':
                        $value && $conditions[implode('|', ['id', 'mobile'])] = ['like', '%'.$value.'%'];
                        break;
                    case 'updated_at':
                        $value && $conditions['updated_at'] = ['date', $value];
                        break;
                }
            }
        }
        //查询列表
        $lists = (new AccountRepository())->lists($conditions, ['*'], [], data_get($data, 'sorts', ['id' => 'desc']), '', (int)data_get($data, 'page', config('pros.table.default_page')), (int)data_get($data, 'page_size', config('pros.table.default_page_size')));
        //循环列表
        foreach ($lists['lists'] as $k => $list) {
            //设置列表
            $lists['lists'][$k]['tags'] = object_2_array($list['tag_ids']) ? (new AccountTagRepository())->pluck('guard_name', ['id' => ['in', object_2_array($list['tag_ids'])]]) : '';
        }
        //渲染表格内容
        $render = TableBuilder::CONTENT()->signature($data['signature'])->setLists($lists)->render();
        //返回成功
        return $this->success($render);
    }

    public function detail($id, $request){
        //渲染表单内容
        $render = FormBuilder::make()
            ->setSubmit(route('whatsapp.console.account.store', ['id' => (int)$id]))
            ->setItems(function (FormItemBuilder $builder) use ($id) {
                $builder->input('global_roaming', '国际区号')->description('绑定手机的国际区号（+86）')->readonly((int)$id <= 0 ? false : true)->required();
                $builder->input('mobile', '手机号码')->description('用戶的手机号')->required();
                $builder->radio('gender', '性别')->options(Accounts::TYPE_GROUPS['gender'])->required();
                $builder->select('tag_ids', '用户标签')->multiple()->options(array_column((new AccountTagRepository())->get([], ['id', 'guard_name']), 'guard_name', 'id'));
                $builder->textarea('remarks', '备注信息')->row(3);
                $builder->select('status', '账户状态')->options(Accounts::TYPE_GROUPS['__status__'],Accounts::STATUS_ENABLED)->required();
            })
            ->setData((int)$id > 0 ? (new AccountRepository())->row(['id' => (int)$id]) : [])
            ->render();
        //返回成功
        return $this->success(['html' => $render]);
    }

    /**
     * 保存用户信息
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
            //判断信息是否可用
            if ((new AccountRepository())->exists(['global_roaming' => $info['global_roaming'],'mobile' => $info['mobile']])) {
                //返回失败
                return $this->fail(CodeLibrary::DATA_CREATE_FAIL, '用户已存在');
            }
            //添加信息
            $info['created_at'] = auto_datetime();
            //添加信息
            if (!$id = (new AccountRepository())->insertGetId($info)) {
                //返回失败
                return $this->fail(CodeLibrary::DATA_UPDATE_FAIL, '用户创建失败');
            }
        } else {
            //修改信息
            if (!(new AccountRepository())->update(['id' => (int)$id], $info)) {
                //返回失败
                return $this->fail(CodeLibrary::DATA_UPDATE_FAIL, '修改失败');
            }
        }
        //返回成功
        return $this->success(compact('id'));
    }

    /**
     * 更改用户状态
     * @param $id
     * @param $request
     * @return array|bool
     * @throws \Exception
     */
    public function enable($id, $request){
        //获取加密信息
        if (!$data = AesLibrary::decryptFormData($request->all())) {
            //返回失败
            return $this->fail(CodeLibrary::DATA_MISSING, '非法参数');
        }
        //更改账户状态
        if (!(new AccountRepository())->update(['id' => (int)$id], ['status' => (int)$data['value'], 'updated_at' => auto_datetime()])) {
            //返回失败
            return $this->fail(CodeLibrary::DATA_UPDATE_FAIL, '更改失败');
        }
        //返回成功
        return $this->success(compact('id'));
    }
}
