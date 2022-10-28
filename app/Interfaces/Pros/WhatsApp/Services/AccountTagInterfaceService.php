<?php
/**
 * Power by abnermouke/easy-builder.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in Yunni Technology Co Ltd.
 * Date: 2022-10-26
 * Time: 15:15:49
*/

namespace App\Interfaces\Pros\WhatsApp\Services;

use Abnermouke\EasyBuilder\Library\CodeLibrary;
use Abnermouke\EasyBuilder\Library\Cryptography\AesLibrary;
use Abnermouke\EasyBuilder\Module\BaseService;
use Abnermouke\Pros\Builders\Form\FormBuilder;
use Abnermouke\Pros\Builders\Form\Tools\FormItemBuilder;
use Abnermouke\Pros\Builders\Table\TableBuilder;
use App\Repository\Pros\WhatsApp\AccountTagRepository;
use Illuminate\Support\Arr;

/**
 * 用户标签接口逻辑服务容器
 * Class AccountTagService
 * @package App\Interfaces\Pros\WhatsApp\Services
*/
class AccountTagInterfaceService extends BaseService
{

    /**
    * 引入父级构造
    * AccountTagInterfaceService constructor.
    * @param bool $pass 是否直接获取结果
    */
    public function __construct($pass = false) { parent::__construct($pass); }

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
                        $value && $conditions[implode('|', ['id', 'guard_name', 'alias'])] = ['like', '%'.$value.'%'];
                        break;
                    case 'updated_at':
                        $value && $conditions['updated_at'] = ['date', $value];
                        break;
                }
            }
        }
        //查询列表
        $lists = (new AccountTagRepository())->lists($conditions, ['*'], [], data_get($data, 'sorts', ['id' => 'desc']), '', (int)data_get($data, 'page', config('pros.table.default_page')), (int)data_get($data, 'page_size', config('pros.table.default_page_size')));
        //渲染表格内容
        $render = TableBuilder::CONTENT()->signature($data['signature'])->setLists($lists)->render();
        //返回成功
        return $this->success($render);
    }

    public function detail($id, $request){
        //渲染表单内容
        $render = FormBuilder::make()
            ->setSubmit(route('whatsapp.console.account.tag.store', ['id' => (int)$id]))
            ->setItems(function (FormItemBuilder $builder) use ($id) {
                $builder->input('alias', '标签标识')->required();
                $builder->input('guard_name', '标签名称')->required();
                $builder->textarea('description', '描述');
            })
            ->setData((int)$id > 0 ? (new AccountTagRepository())->row(['id' => (int)$id]) : [])
            ->render();
        //返回成功
        return $this->success(['html' => $render]);
    }

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
            if ((new AccountTagRepository())->exists(['alias' => $info['alias']])) {
                //返回失败
                return $this->fail(CodeLibrary::DATA_CREATE_FAIL, '标签标识已存在');
            }
            if ((new AccountTagRepository())->exists(['guard_name' => $info['guard_name']])) {
                //返回失败
                return $this->fail(CodeLibrary::DATA_CREATE_FAIL, '标签名称已存在');
            }
            //添加信息
            $info['created_at'] = auto_datetime();
            //添加信息
            if (!$id = (new AccountTagRepository())->insertGetId($info)) {
                //返回失败
                return $this->fail(CodeLibrary::DATA_UPDATE_FAIL, '标签创建失败');
            }
        } else {
            //修改信息
            if (!(new AccountTagRepository())->update(['id' => (int)$id], $info)) {
                //返回失败
                return $this->fail(CodeLibrary::DATA_UPDATE_FAIL, '修改失败');
            }
        }
        //返回成功
        return $this->success(compact('id'));
    }

    public function insertTag($guard_name = ''){
        $alias = auto_datetime('YmdHi');
        $description = '';
        if (!$guard_name){
            $guard_name = auto_datetime('Y-m-d H:i');
            $description = auto_datetime('Y-m-d H:i').'导入的用户标签';
        }
        if (($tag_id = (new AccountTagRepository())->find(['alias' => $alias],'id')) > 0) {
            //返回ID
            return $this->success(compact('tag_id'));
        }
        if (($tag_id = (new AccountTagRepository())->find(['guard_name' => $guard_name],'id')) > 0) {
            //返回ID
            return $this->success(compact('tag_id'));
        }
        $tag_id = (new AccountTagRepository())->insertGetId([
            'guard_name'=>$guard_name,
            'alias'=>$alias,
            'description'=>$description,
            'created_at'=>auto_datetime(),
            'updated_at'=>auto_datetime(),
        ]);
        //返回ID
        return $this->success(compact('tag_id'));
    }
}
