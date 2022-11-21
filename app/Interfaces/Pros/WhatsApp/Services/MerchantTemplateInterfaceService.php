<?php
/**
 * Power by abnermouke/easy-builder.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in Yunni Technology Co Ltd.
 * Date: 2022-10-26
 * Time: 15:09:18
*/

namespace App\Interfaces\Pros\WhatsApp\Services;

use Abnermouke\EasyBuilder\Library\CodeLibrary;
use Abnermouke\EasyBuilder\Library\Cryptography\AesLibrary;
use Abnermouke\EasyBuilder\Module\BaseService;
use Abnermouke\Pros\Builders\Form\FormBuilder;
use Abnermouke\Pros\Builders\Form\Tools\FormItemBuilder;
use Abnermouke\Pros\Builders\Table\TableBuilder;
use App\Model\Pros\WhatsApp\MerchantTemplates;
use App\Repository\Pros\WhatsApp\MassDispatchMerchantRepository;
use App\Repository\Pros\WhatsApp\MerchantTemplateRepository;
use App\Services\Pros\System\TemporaryFileService;
use App\Services\Pros\WhatsApp\MerchantMessagesLogService;
use Illuminate\Support\Arr;

/**
 * 商户模板接口逻辑服务容器
 * Class MerchantTemplateService
 * @package App\Interfaces\Pros\WhatsApp\Services
*/
class MerchantTemplateInterfaceService extends BaseService
{

    /**
    * 引入父级构造
    * MerchantTemplateInterfaceService constructor.
    * @param bool $pass 是否直接获取结果
    */
    public function __construct($pass = false) { parent::__construct($pass); }

    public function lists($request){
        //获取加密信息
        if (!$data = AesLibrary::decryptFormData($request->all())) {
            //返回失败
            return $this->fail(CodeLibrary::DATA_MISSING, '非法参数');
        }
        //整理查询条件
        $conditions = ['status' => ['=', MerchantTemplates::STATUS_ENABLED]];
        //判断是否筛选状态
        if ($request->exists('status')) {
            //设置默认条件
            $conditions['status'] = (int)$request->get('status', MerchantTemplates::STATUS_ENABLED);
        }
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
        $lists = (new MerchantTemplateRepository())->lists($conditions, ['*'], [], data_get($data, 'sorts', ['id' => 'desc']), '', (int)data_get($data, 'page', config('pros.table.default_page')), (int)data_get($data, 'page_size', config('pros.table.default_page_size')));
        //渲染表格内容
        $render = TableBuilder::CONTENT()->signature($data['signature'])->setLists($lists)->render();
        //返回成功
        return $this->success($render);
    }

    public function detail($id, $request){
        $info = (int)$id > 0 ? (new MerchantTemplateRepository())->row(['id' => (int)$id]) : [];
        //渲染表单内容
        $render = FormBuilder::make()
            ->setSubmit(route('whatsapp.console.merchant.store', ['id' => (int)$id]))
            ->setItems(function (FormItemBuilder $builder) use ($id) {
                $builder->input('bm_info', 'BM账户信息')->description('Meta的信息商务管理平台（BM）主账户信息')->readonly(true);
                $builder->input('guard_name', '商户名称')->description('系统内方便管理识别账户下的商户名称，与Meta账户无关系')->readonly((int)$id <= 0 ? false : true)->required();
                $builder->input('auth_token', '访问令牌')->description('接口访问密令')->required();
                $builder->input('global_roaming', '国际区号')->description('绑定手机的国际区号（+86）')->readonly((int)$id <= 0 ? false : true)->required();
                $builder->input('tel', '手机号')->description('发信人绑定的手机号')->readonly((int)$id <= 0 ? false : true)->tip('发送消息的绑定手机号')->required();
                $builder->input('tel_code', '电话号码编号')->description('发信人绑定的手机号对应编号')->readonly((int)$id <= 0 ? false : true)->required();
                $builder->input('business_code', '业务帐户编号')->description('WhatsApp Business 业务帐户编号')->readonly((int)$id <= 0 ? false : true)->required();
                $builder->input('remainder', '剩余发送量')->default_value(0)->input_type('number')->description('当前商户剩余发送消息量')->readonly(true)->required();
                $builder->select('status', '商户状态')->options(Merchants::TYPE_GROUPS['__status__'],Merchants::STATUS_ENABLED)->required();
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
        //判断是否为新增
        if ((int)$id <= 0) {
            //判断信息是否可用
            if ((new MerchantRepository())->exists(['guard_name' => $info['guard_name']])) {
                //返回失败
                return $this->fail(CodeLibrary::DATA_CREATE_FAIL, 'BM的商户名称已存在');
            }
            if ((new MerchantRepository())->exists(['global_roaming' => $info['global_roaming'],'tel' => $info['tel']])) {
                //返回失败
                return $this->fail(CodeLibrary::DATA_CREATE_FAIL, 'BM的商户手机已存在');
            }
            //设置商户信息
            $info['bm_id'] = $bm_id;
            //添加信息
            $info['created_at'] = auto_datetime();
            //添加信息
            if (!$id = (new MerchantRepository())->insertGetId($info)) {
                //返回失败
                return $this->fail(CodeLibrary::DATA_UPDATE_FAIL, 'BM的商户创建失败');
            }
        } else {
            //修改信息
            if (!(new MerchantRepository())->update(['id' => (int)$id], $info)) {
                //返回失败
                return $this->fail(CodeLibrary::DATA_UPDATE_FAIL, '修改失败');
            }
        }
        //返回成功
        return $this->success(compact('id'));
    }

    public function retrieveLists($mdm_id, $request){
        //获取加密信息
        if (!$data = AesLibrary::decryptFormData($request->all())) {
            //返回失败
            return $this->fail(CodeLibrary::DATA_MISSING, '非法参数');
        }
        //整理查询条件
        $conditions = ['status' => ['=', MerchantTemplates::STATUS_ENABLED]];
        //判断是否筛选状态
        if ($request->exists('status')) {
            //设置默认条件
            $conditions['status'] = (int)$request->get('status', MerchantTemplates::STATUS_ENABLED);
        }
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
        if ($mdm_id){
            $mdm_info = (new MassDispatchMerchantRepository())->row(['id'=>$mdm_id],['admin_id','auth_token','business_code']);
            if ($mdm_info){
                if ((new MerchantTemplateRepository())->count(['admin_id'=>$mdm_info['admin_id']]) <= 0){
                    $business_account_id = $mdm_info['business_code'];
                    $auth_token = $mdm_info['auth_token'];
                    $templates = (new MerchantMessagesLogService())->retrieveTemplates($business_account_id,$auth_token,3);
                    $templates = $templates['result']['data']['data']??[];
                    foreach ($templates as $template){
                        $header_content = $body = $footer = '';
                        $button = [];
                        $header_type = MerchantTemplates::HEADER_OF_NULL;
                        foreach ($template['components'] as $component){
                            if ($component['type'] === 'HEADER'){
                                if ($component['format'] === 'TEXT'){
                                    $header_type = MerchantTemplates::HEADER_OF_TEXT;
                                    $header_content = $component['text'];
                                }elseif ($component['format'] === 'IMAGE'){
                                    $header_type = MerchantTemplates::HEADER_OF_MEDIA_IMAGE;
                                }elseif ($component['format'] === 'DOCUMENT'){
                                    $header_type = MerchantTemplates::HEADER_OF_MEDIA_DOCUMENT;
                                }elseif ($component['format'] === 'VIDEO'){
                                    $header_type = MerchantTemplates::HEADER_OF_MEDIA_VIDEO;
                                }
                            }
                            if ($component['type'] === 'BODY'){
                                $body = $component['text'];
                            }
                            if ($component['type'] === 'FOOTER'){
                                $footer = $component['text'];
                            }
                            if ($component['type'] === 'BUTTONS'){
                                $button = $component;
                            }
                        }
                        $params = [
                            'template_id' => $template['id'],
                            'admin_id' => $mdm_info['admin_id'],
                            'object_id' => $business_account_id,
                            'type' => MerchantTemplates::TYPE_OF_MARKETING,
                            'title' => $template['name'],
                            'language' => $template['language'],
                            'header_type' => $header_type,
                            'header_content' => $header_content,
                            'body' => $body,
                            'footer' => $footer,
                            'button' => $button,
                            'status_type' => MerchantTemplates::TYPE_GROUPS['__status_type_str__'][$template['status']],
                            'status' => MerchantTemplates::TYPE_GROUPS['__status_type_str__'][$template['status']],
                            'updated_at' => auto_datetime(),
                        ];
                        if ((new MerchantTemplateRepository())->exists(['admin_id'=>$mdm_info['admin_id'],'template_id'=>$template['id']])){
                            (new MerchantTemplateRepository())->update(['admin_id'=>$mdm_info['admin_id'],'template_id'=>$template['id']],$params);
                        }else{
                            $params['created_at'] = auto_datetime();
                            (new MerchantTemplateRepository())->insertGetId($params);
                        }
                    }
                }
                $conditions = ['admin_id' => ['=', $mdm_info['admin_id']]];
            }else{
                $conditions = ['admin_id' => ['=', 0]];
            }
        }
        //查询列表
        $lists = (new MerchantTemplateRepository())->lists($conditions, ['*'], [], data_get($data, 'sorts', ['id' => 'desc']), '', (int)data_get($data, 'page', config('pros.table.default_page')), (int)data_get($data, 'page_size', config('pros.table.default_page_size')));
        //渲染表格内容
        $render = TableBuilder::CONTENT()->signature($data['signature'])->setLists($lists)->render();
        //返回成功
        return $this->success($render);
    }

    public function setFile($id, $request){
        $info = (int)$id > 0 ? (new MerchantTemplateRepository())->row(['id' => (int)$id]) : [];
        //渲染表单内容
        $render = FormBuilder::make()
            ->setSubmit(route('whatsapp.console.template.store_file', ['id' => (int)$id]))
            ->setItems(function (FormItemBuilder $builder) use ($id) {
                $builder->image('header_content', '图片')->cropper(false)->dictionary('merchant/template/pictures');
            })
            ->setData($info)
            ->render();
        //返回成功
        return $this->success(['html' => $render]);
    }

    public function storeFile($id, $request){
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
        if (!$id){
            //返回失败
            return $this->fail(CodeLibrary::DATA_MISSING, 'ID不能为空');
        }
        //获取更改项
        $info = Arr::only($data['__data__'], $data['__edited__']);
        //添加修改时间
        $info['header_content'] = (new TemporaryFileService(true))->enable(data_get($data, '__data__.header_content', ''));
        $info['updated_at'] = auto_datetime();
        //修改信息
        if (!(new MerchantTemplateRepository())->update(['id' => (int)$id], $info)) {
            //返回失败
            return $this->fail(CodeLibrary::DATA_UPDATE_FAIL, '修改失败');
        }
        //返回成功
        return $this->success(compact('id'));
    }
}
