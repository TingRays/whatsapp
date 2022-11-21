<?php
/**
 * Power by abnermouke/easy-builder.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in Yunni Technology Co Ltd.
 * Date: 2022-11-21
 * Time: 00:00:55
*/

namespace App\Interfaces\Pros\WhatsApp\Services;

use Abnermouke\EasyBuilder\Library\CodeLibrary;
use Abnermouke\EasyBuilder\Library\Cryptography\AesLibrary;
use Abnermouke\EasyBuilder\Module\BaseService;
use Abnermouke\Pros\Builders\Form\FormBuilder;
use Abnermouke\Pros\Builders\Form\Tools\FormItemBuilder;
use Abnermouke\Pros\Builders\Table\TableBuilder;
use App\Exports\Accounts\WrongAccountsExport;
use App\Imports\MassDispatch\MassDispatchImport;
use App\Model\Pros\WhatsApp\MassDispatch;
use App\Model\Pros\WhatsApp\MerchantMessagesLogs;
use App\Model\Pros\WhatsApp\MerchantTemplates;
use App\Repository\Pros\WhatsApp\MassDispatchMerchantRepository;
use App\Repository\Pros\WhatsApp\MassDispatchRepository;
use App\Repository\Pros\WhatsApp\MerchantTemplateRepository;
use App\Services\Pros\Console\UploadService;
use App\Services\Pros\System\TemporaryFileService;
use App\Services\Pros\WhatsApp\MerchantMessagesLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Maatwebsite\Excel\Facades\Excel;

/**
 * 群发信息接口逻辑服务容器
 * Class MassDispatchService
 * @package App\Interfaces\Pros\WhatsApp\Services
*/
class MassDispatchInterfaceService extends BaseService
{

    /**
    * 引入父级构造
    * MassDispatchInterfaceService constructor.
    * @param bool $pass 是否直接获取结果
    */
    public function __construct($pass = false) { parent::__construct($pass); }

    public function lists($tel_code='',$business_code='',$request){
        //获取加密信息
        if (!$data = AesLibrary::decryptFormData($request->all())) {
            //返回失败
            return $this->fail(CodeLibrary::DATA_MISSING, '非法参数');
        }
        //整理查询条件
        $conditions = ['status' => ['!=', MassDispatch::STATUS_DELETED]];
        //判断是否筛选状态
        if ($request->exists('status')) {
            //设置默认条件
            $conditions['status'] = (int)$request->get('status', MassDispatch::STATUS_ENABLED);
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
        if ($tel_code){
            $conditions = ['tel_code' => ['=', $tel_code]];
        }
        if ($business_code){
            $conditions = ['business_code' => ['=', $business_code]];
        }
        //查询列表
        $lists = (new MassDispatchRepository())->lists($conditions, ['*'], [], data_get($data, 'sorts', ['id' => 'desc']), '', (int)data_get($data, 'page', config('pros.table.default_page')), (int)data_get($data, 'page_size', config('pros.table.default_page_size')));
        //渲染表格内容
        $render = TableBuilder::CONTENT()->signature($data['signature'])->setLists($lists)->render();
        //返回成功
        return $this->success($render);
    }

    public function detail($id, $request){
        $info = (int)$id > 0 ? (new MassDispatchRepository())->row(['id' => (int)$id]) : [];
        //渲染表单内容
        $render = FormBuilder::make()
            ->setSubmit(route('whatsapp.console.mass_dispatch.store', ['id' => (int)$id]))
            ->setItems(function (FormItemBuilder $builder) use ($id) {
                $builder->input('mobile', '手机号码')->description('要发送手机的号码');
            })
            ->setData($info)
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
        //修改信息
        if (!(new MassDispatchRepository())->update(['id' => (int)$id], $info)) {
            //返回失败
            return $this->fail(CodeLibrary::DATA_UPDATE_FAIL, '修改失败');
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
        //获取文件结果
        $file = $service->getResult();
        //导入内容
        $sheets = Excel::import(new MassDispatchImport(), $file['storage_name'], $file['storage_disk'])->toArray(new MassDispatchImport, $file['storage_name'], $file['storage_disk']);
        //获取导入信息
        $posts = Arr::first($sheets);
        //整理失败数据
        $wrongs = $success = [];
        //循环导入信息
        foreach ($posts as $k => $post) {
            $mobile = str_replace(['+',' ','(',')','-','（','）'],'',trim($post[0]));
            if ((new MassDispatchRepository())->exists(['mobile' => $mobile])) {
                //设置错误原因
                $post[] = '该用户已存在';
                //设置失败
                $wrongs[] = $post;
                //跳出当前循环
                continue;
            }
            $params = [
                'admin_id' => current_auth('id', config('pros.session_prefix', 'abnermouke:pros:console:auth')),
                'mobile' => $mobile,
                'status' => MassDispatch::STATUS_VERIFYING,
                'created_at' => auto_datetime(),
                'updated_at' => auto_datetime(),
            ];
            (new MassDispatchRepository())->insertGetId($params);
            //添加成功
            $success[] = $post;
        }
        //整理返回结果
        $result = ['success' => count($success), 'wrong' => $wrongs, 'msg' => ('本次导入共' . (count($posts) - 1) . '条<br />成功：' . count($success) . ' 条，重复：' . count($wrongs) . ' 条')];
        //判断是否存在错误发货单信息
        //返回成功
        return $this->success($result);
    }

    public function sendTemplateMassage($id, $mdm_id, $request){
        if (!$id){
            //返回失败
            return $this->fail(CodeLibrary::DATA_MISSING, '模板ID不能为空');
        }
        if (!$mdm_id){
            //返回失败
            return $this->fail(CodeLibrary::DATA_MISSING, '商户ID不能为空');
        }
        $template = (new MerchantTemplateRepository())->row(['id'=>$id]);
        if ($template['status_type'] != MerchantTemplates::STATUS_ENABLED){
            //返回失败
            return $this->fail(CodeLibrary::DATA_MISSING, '模板未通过');
        }
        $merchant = (new MassDispatchMerchantRepository())->row(['id'=>$mdm_id]);
        $remainder = $merchant['remainder'];
        $mobiles = (new MassDispatchRepository())->limit(['admin_id'=>$merchant['admin_id'],'status'=>MassDispatch::STATUS_VERIFYING],['id','mobile','merchant_messages_id','type','template_id'],[],['id'=>'desc'],[],1,$remainder);
        //更新消息发送状态 - 发送中
        $mobile_ids = array_column($mobiles,'id');
        (new MassDispatchMerchantRepository())->update(['id'=>['in',$mobile_ids]],['status'=>MassDispatch::STATUS_DISABLED,'updated_at'=>auto_datetime()]);

        $components = [];
        if ($template['header_type'] === MerchantTemplates::HEADER_OF_MEDIA_IMAGE){
            if (empty($template['header_content'])){
                //返回失败
                return $this->fail(CodeLibrary::DATA_MISSING, '图片未设置');
            }
            $components = [
                [
                    'type' => 'header',
                    'parameters' => [
                        [
                            'type' => 'image',
                            'image' => [
                                'link' => $template['header_content'],
                            ],
                        ]
                    ]
                ],
            ];
        }
        $templates = [
            'title' => $template['title'],
            'language' => $template['title'],
            'components' => $components,
        ];
        foreach ($mobiles as $mobile){
            $result = (new MerchantMessagesLogService())->sendMessageTemplates($merchant['tel_code'],$merchant['auth_token'],$templates,$mobile['mobile']);
            $status = MassDispatch::STATUS_VERIFY_FAILED;
            $message_id = '';
            if($result['result']['status']){
                $status = MassDispatch::STATUS_ENABLED;
                $message_id = $result['result']['data']['messages'][0]['id']??'';
            }
            $remainder--;
            $params = [
                'auth_token'=>$merchant['auth_token'],
                'tel_code'=>$merchant['tel_code'],
                'business_code'=>$merchant['business_code'],
                'remainder'=>$remainder,
                'result'=>$result['result']??[],
                'message_id'=>$message_id,
                'status'=>$status,
                'updated_at'=>auto_datetime(),
            ];
            (new MassDispatchRepository())->update(['id'=>$mobile['id']],$params);
        }

        //返回成功
        $after = route('whatsapp.console.mass_dispatch.lists',['tel_code'=>$merchant['tel_code'],'business_code'=>$merchant['business_code']]);
        return $this->success(compact('after'));
    }
}
