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
use App\Exports\Accounts\WrongAccountsExport;
use App\Imports\Accounts\AccountsImport;
use App\Model\Pros\WhatsApp\Accounts;
use App\Repository\Pros\WhatsApp\AccountRepository;
use App\Repository\Pros\WhatsApp\AccountTagRepository;
use App\Services\Pros\Console\UploadService;
use App\Services\Pros\System\TemporaryFileService;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Maatwebsite\Excel\Facades\Excel;

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
        //整理查询条件
        $conditions = ['status' => ['!=', Accounts::STATUS_DELETED]];
        //判断是否筛选状态
        if ($request->exists('status')) {
            //设置默认条件
            $conditions['status'] = (int)$request->get('status', Accounts::STATUS_ENABLED);
        }
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
        if ($info['global_roaming']){
            $info['global_roaming'] = str_replace(['+',' '],'',trim($info['global_roaming']));
        }
        if ($info['mobile']){
            $info['mobile'] = str_replace(['+',' ','(',')','-','（','）'],'',trim($info['mobile']));
        }
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

    /**
     * 批量导入用户手机号
     * @param Request $request
     * @return array|bool
     * @throws \Exception
     */
    public function import(Request $request)
    {
        //上传文件
        if (!($service = new UploadService())->upload($request)) {
            //返回失败
            return $this->fail($service->getCode(), $service->getMessage());
        }
        //获取文件结果
        $file = $service->getResult();
        //导入内容
        $sheets = Excel::import(new AccountsImport(), $file['storage_name'], $file['storage_disk'])->toArray(new AccountsImport, $file['storage_name'], $file['storage_disk']);
        //获取导入信息
        $posts = Arr::first($sheets);
        //整理失败数据
        $wrongs = $success = $tag_ids = [];
        //循环导入信息
        foreach ($posts as $k => $post) {
            //判断是否第一项
            if ((int)$k > 0) {
                //查询物流公司
                if ((new AccountRepository())->exists(['global_roaming' => trim($post[0]),'mobile' => trim($post[1])])) {
                    //设置错误原因
                    $post[] = '该用户已存在';
                    //设置失败
                    $wrongs[] = $post;
                    //跳出当前循环
                    continue;
                }
                if (empty($tag_ids)){
                    ($service = new AccountTagInterfaceService())->insertTag($post[3]);
                    $tag_ids[] = $service->getResult()['tag_id'];
                }
                $global_roaming = str_replace(['+',' '],'',trim($post[0]));
                $mobile = str_replace(['+',' ','(',')','-','（','）'],'',trim($post[1]));
                $params = [
                    'global_roaming' => $global_roaming,
                    'mobile' => $mobile,
                    'gender' => Accounts::GENDER_OF_UNKNOWN,
                    'tag_ids' => $tag_ids,
                    'remarks' => $post[4],
                    'source' => Accounts::SOURCE_OF_IMPORT,
                    //'status' => Accounts::STATUS_DISABLED,
                    'created_at' => auto_datetime(),
                    'updated_at' => auto_datetime(),
                ];
                //if (trim($post[2]) == '是'){
                    $params['status'] = Accounts::STATUS_ENABLED;
                //}
                (new AccountRepository())->insertGetId($params);
                //添加成功
                $success[] = $post;
            }
        }
        //整理返回结果
        $result = ['success' => count($success), 'wrong' => $wrongs, 'msg' => ('本次导入共' . (count($posts) - 1) . '条<br />成功：' . count($success) . ' 条，失败：' . count($wrongs) . ' 条')];
        //判断是否存在错误发货单信息
        if ($wrongs) {
            //生成临时文件
            $temp_file = (new TemporaryFileService(true))->temporary('accounts/exports/wrongs/' . $file['file_info']['basename']);
            //保存文件
            Excel::store(new WrongAccountsExport($wrongs), $temp_file['file']['storage_name'], $temp_file['file']['storage_disk'], \Maatwebsite\Excel\Excel::XLSX);
            //设置链接
            $result['link'] = $temp_file['file']['link'];
            //设置提示
            $result['msg'] .= '<br /><br />错误文档已自动导出，请留意最后一栏失败原因并及时处理！';
        }
        //返回成功
        return $this->success($result);
    }
}
