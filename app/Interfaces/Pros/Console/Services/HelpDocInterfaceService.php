<?php
/**
 * Power by abnermouke/easy-builder.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in Yunni Technology Co Ltd.
 * Date: 2022-07-23
 * Time: 16:15:12
*/

namespace App\Interfaces\Pros\Console\Services;

use Abnermouke\EasyBuilder\Library\CodeLibrary;
use Abnermouke\EasyBuilder\Library\Cryptography\AesLibrary;
use Abnermouke\EasyBuilder\Module\BaseService;
use Abnermouke\Pros\Builders\Form\FormBuilder;
use Abnermouke\Pros\Builders\Form\Tools\FormItemBuilder;
use Abnermouke\Pros\Builders\Table\TableBuilder;
use App\Model\Pros\System\HelpDocs;
use App\Repository\Pros\System\HelpDocRepository;
use App\Services\Pros\Console\AdminLogService;
use Illuminate\Support\Arr;

/**
 * 帮助文档接口逻辑服务容器
 * Class HelpDocService
 * @package App\Interfaces\Pros\Console\Services
*/
class HelpDocInterfaceService extends BaseService
{

    /**
    * 引入父级构造
    * HelpDocInterfaceService constructor.
    * @param bool $pass 是否直接获取结果
    */
    public function __construct($pass = false) { parent::__construct($pass); }

    /**
     * 获取列表
     * @Author Abnermouke <abnermouke@outlook.com>
     * @Originate in Abnermouke's MBP
     * @Time 2022-10-25 11:59:15
     * @param $request
     * @return array|bool
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     * @throws \Exception
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
                    case 'keyword':
                        $value && $conditions[implode('|', ['title', 'alias', 'content'])] = ['like', '%'.$value.'%'];
                        break;
                    case 'type':
                        (int)$value > 0 && $conditions['type'] = (int)$value;
                        break;
                    case 'updated_at':
                        $value && $conditions['updated_at'] = ['between', $value];
                        break;
                }
            }
        }
        //查询列表
        $lists = (new HelpDocRepository())->lists($conditions, [], [], data_get($data, 'sorts', ['id' => 'desc']), '', (int)data_get($data, 'page', config('pros.table.default_page')), (int)data_get($data, 'page_size', config('pros.table.default_page_size')));
        //查询列表
        if ($lists && !empty($lists['lists'])) {
            //循环列表
            foreach ($lists['lists'] as $k => $list) {
                //设置信息
                $lists['lists'][$k]['description'] = string_to_text($list['content'], 200);
            }
        }
        //渲染表格内容
        $render = TableBuilder::CONTENT()->signature($data['signature'])->setLists($lists)->render();
        //返回成功
        return $this->success($render);
    }

    /**
     * 获取详情
     * @Author Abnermouke <abnermouke@outlook.com>
     * @Originate in Abnermouke's MBP
     * @Time 2022-10-25 11:59:15
     * @param $id
     * @param $request
     * @return array|bool
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function detail($id, $request)
    {
        //渲染表单内容
        $render = FormBuilder::make()
            ->setSubmit(route('pros.console.help.docs.store', ['id' => (int)$id]))
            ->setItems(function (FormItemBuilder $builder) {
                $builder->input('title', '文档标题')->required();
                $builder->input('alias', '文档标识')->description('文档标识可用于快速检索，请尽量保持其唯一性')->required()->clipboard();
                $builder->radio('type', '文档类型')->options(HelpDocs::TYPE_GROUPS['type'])->required();
                $builder->editor('content', '内容')->required();
            })
            ->setData((int)$id > 0 ? (new HelpDocRepository())->row(['id' => (int)$id]) : [])
            ->render();
        //返回成功
        return $this->success(['html' => $render]);
    }

    /**
     * 保存信息
     * @Author Abnermouke <abnermouke@outlook.com>
     * @Originate in Abnermouke's MBP
     * @Time 2022-10-25 11:59:15
     * @param $doc_id
     * @param $request
     * @return array|bool
     * @throws \Exception
     */
    public function store($doc_id, $request)
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
        //判断是否为新增
        if ((int)$doc_id <= 0) {
            //添加默认信息
            $info['created_at'] = auto_datetime();
            //添加信息
            if (!$doc_id = (new HelpDocRepository())->insertGetId($info)) {
                //返回失败
                return $this->fail(CodeLibrary::DATA_UPDATE_FAIL, '信息创建失败');
            }
            //添加日志
            (new AdminLogService())->record('新增帮助文档成功', compact('doc_id'));
        } else {
            //修改信息
            if (!(new HelpDocRepository())->update(['id' => (int)$doc_id], $info)) {
                //返回失败
                return $this->fail(CodeLibrary::DATA_UPDATE_FAIL, '修改失败');
            }
            //添加日志
            (new AdminLogService())->record('编辑帮助文档成功', array_merge(compact('doc_id'), $info));
        }
        //返回成功
        return $this->success(compact('doc_id'));
    }

    /**
     * 删除信息
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
        //获取ID
        $ids = data_get($data, 'id', '');
        //判断类型
        $ids = is_string($ids) ? explode(',', $ids) : $ids;
        //删除信息
        if (!(new HelpDocRepository())->delete(['id' => ['in', $ids]])) {
            //返回失败
            return $this->fail(CodeLibrary::DATA_DELETE_FAIL, '网络错误，删除失败');
        }
        //添加日志
        (new AdminLogService())->record('删除帮助文档', compact('ids'));
        //返回成功
        return $this->success($ids);
    }
}
