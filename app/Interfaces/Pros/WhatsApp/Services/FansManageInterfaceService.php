<?php
/**
 * Power by abnermouke/easy-builder.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in Yunni Technology Co Ltd.
 * Date: 2022-11-04
 * Time: 20:04:16
*/

namespace App\Interfaces\Pros\WhatsApp\Services;

use Abnermouke\EasyBuilder\Library\CodeLibrary;
use Abnermouke\EasyBuilder\Library\Cryptography\AesLibrary;
use Abnermouke\EasyBuilder\Module\BaseService;
use Abnermouke\Pros\Builders\Form\FormBuilder;
use Abnermouke\Pros\Builders\Form\Tools\FormItemBuilder;
use Abnermouke\Pros\Builders\Table\TableBuilder;
use App\Model\Pros\WhatsApp\FansManage;
use App\Repository\Pros\WhatsApp\FansManageGroupRepository;
use App\Repository\Pros\WhatsApp\FansManageRepository;
use App\Services\Pros\Console\UploadService;
use App\Services\Pros\System\TemporaryFileService;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

/**
 * 粉丝管理接口逻辑服务容器
 * Class FansManageService
 * @package App\Interfaces\Pros\WhatsApp\Services
*/
class FansManageInterfaceService extends BaseService
{

    /**
    * 引入父级构造
    * FansManageInterfaceService constructor.
    * @param bool $pass 是否直接获取结果
    */
    public function __construct($pass = false) { parent::__construct($pass); }

    /**
     * 粉丝管理列表
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
        $conditions = ['status'=>FansManage::STATUS_ENABLED];
        $admin_id = current_auth('id', config('pros.session_prefix', 'abnermouke:pros:console:auth'));
        $conditions['admin_id'] = $admin_id;
        //判断筛选条件
        if ($filters = data_get($data, 'filters', [])) {
            //循环筛选条件
            foreach ($filters as $filter => $value) {
                //根据筛选项设置条件
                switch ($filter) {
                    case 'keyword':
                        $value && $conditions[implode('|', ['id', 'mobile'])] = ['like', '%'.$value.'%'];
                        break;
                    case 'group_id':
                        $value && $conditions['group_id'] = $value;
                        break;
                    case 'updated_at':
                        $value && $conditions['updated_at'] = ['date', $value];
                        break;
                }
            }
        }
        //查询列表
        $lists = (new FansManageRepository())->lists($conditions, ['*'], [], data_get($data, 'sorts', ['id' => 'desc']), '', (int)data_get($data, 'page', config('pros.table.default_page')), (int)data_get($data, 'page_size', config('pros.table.default_page_size')));
        $group_ids = array_column($lists['lists'],'group_id');
        $group_arr = (new FansManageGroupRepository())->get(['id'=>['in',$group_ids]],['id','title','code']);
        $group_key_arr = [];
        foreach ($group_arr as $group){
            $group_key_arr[$group['id']] = ['title'=>$group['title'],'code'=>$group['code']];
        }
        foreach ($lists['lists'] as &$list){
            $list['group_name'] = $group_key_arr[$list['group_id']]['title']??'-';
            $list['group_url'] = 'https://jump.whatsqunfa.com/'.$group_key_arr[$list['group_id']]['code'];
            $list['jump_url'] = 'https://api.whatsapp.com/send?phone='.$list['mobile'];
        }
        //渲染表格内容
        $render = TableBuilder::CONTENT()->signature($data['signature'])->setLists($lists)->render();
        //返回成功
        return $this->success($render);
    }

    /**
     * 粉丝管理详情
     * @param $id
     * @param $request
     * @return array|bool
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function detail($id, $request){
        $admin_id = current_auth('id', config('pros.session_prefix', 'abnermouke:pros:console:auth'));
        //渲染表单内容
        $render = FormBuilder::make()
            ->setSubmit(route('whatsapp.console.fans_manage.store',['id'=>$id]))
            ->setItems(function (FormItemBuilder $builder) use ($admin_id) {
                $builder->select('group_id', '分组')->options(array_column((new FansManageGroupRepository())->get(['admin_id'=>$admin_id], ['id', 'title']), 'title', 'id'));
                $builder->input('mobile', '手机号码')->description('手机号码')->required();
                $builder->select('status', '账户状态')->options(FansManage::TYPE_GROUPS['__status__'],FansManage::STATUS_ENABLED)->required();
            })
            ->setData((int)$id > 0 ? (new FansManageRepository())->row(['id' => (int)$id]) : [])
            ->render();
        //返回成功
        return $this->success(['html' => $render]);
    }

    /**
     * 保存粉丝管理
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
            if ((new FansManageRepository())->exists(['mobile' => $info['mobile'],'admin_id'=>$admin_id])) {
                //返回失败
                return $this->fail(CodeLibrary::DATA_CREATE_FAIL, '手机号已存在');
            }
            //添加信息
            $info['admin_id'] = $admin_id;
            $info['created_at'] = auto_datetime();
            //添加信息
            if (!$id = (new FansManageRepository())->insertGetId($info)) {
                //返回失败
                return $this->fail(CodeLibrary::DATA_UPDATE_FAIL, '创建失败');
            }
        } else {
            //修改信息
            if (!(new FansManageRepository())->update(['id' => (int)$id], $info)) {
                //返回失败
                return $this->fail(CodeLibrary::DATA_UPDATE_FAIL, '修改失败');
            }
        }
        //返回成功
        return $this->success(compact('id'));
    }

    public function import(Request $request)
    {
        //上传文件
        if (!($service = new UploadService())->upload($request)) {
            //返回失败
            return $this->fail($service->getCode(), $service->getMessage());
        }
        $group_id = $request->get('group_id');
        if (!$group_id){
            //返回失败
            return $this->fail(CodeLibrary::DATA_MISSING, '请先选择粉分组！');
        }
        //获取文件结果
        $file = $service->getResult();
        $file_path = Storage::disk($file['storage_disk'])->path($file['storage_name']);
        if (!file_exists($file_path)) {
            //返回失败
            return $this->fail($service->getCode(), '文件丢失请重试！');
        }
        $fp = fopen($file_path, "r");
        $str = fread($fp, filesize($file_path));//指定读取大小，这里把整个文件内容读取出来
        $str = str_replace("\r\n", ",", $str);
        $posts = explode(',',$str);

        $admin_id = current_auth('id', config('pros.session_prefix', 'abnermouke:pros:console:auth'));
        //整理失败数据
        $wrongs = $success = [];
        //循环导入信息
        foreach ($posts as $mobile) {
            if (empty($mobile)){
                continue;
            }
            //查询手机号
            if ((new FansManageRepository())->exists(['mobile' => $mobile,'admin_id'=>$admin_id])) {
                //设置错误原因
                $post[] = '该用户已存在';
                //设置失败
                $wrongs[] = $post;
                //跳出当前循环
                continue;
            }
            $params = [
                'admin_id' => $admin_id,
                'group_id' => $group_id,
                'mobile' => $mobile,
                'status' => FansManage::STATUS_ENABLED,
                'created_at' => auto_datetime(),
                'updated_at' => auto_datetime(),
            ];
            (new FansManageRepository())->insertGetId($params);
            //添加成功
            $success[] = $mobile;
        }
        //整理返回结果
        $result = ['link'=>route('whatsapp.console.fans_manage.index'), 'msg' => ('本次导入共' . (count($posts)) . '条<br />成功：' . count($success) . ' 条，已存在重复：' . count($wrongs) . ' 条')];
        //返回成功
        return $this->success($result);
    }
}
