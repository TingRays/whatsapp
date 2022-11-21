<?php
/**
 * Power by abnermouke/easy-builder.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in Yunni Technology Co Ltd.
 * Date: 2022-11-21
 * Time: 15:12:21
*/

namespace App\Interfaces\Pros\WhatsApp\Services;

use Abnermouke\EasyBuilder\Library\CodeLibrary;
use Abnermouke\EasyBuilder\Library\Cryptography\AesLibrary;
use Abnermouke\EasyBuilder\Module\BaseService;
use Abnermouke\Pros\Builders\Form\FormBuilder;
use Abnermouke\Pros\Builders\Form\Tools\FormItemBuilder;
use App\Model\Pros\WhatsApp\MassDispatchMerchant;
use App\Repository\Pros\WhatsApp\MassDispatchMerchantRepository;
use Illuminate\Support\Arr;

/**
 * 群发信息接口逻辑服务容器
 * Class MassDispatchMerchantService
 * @package App\Interfaces\Pros\WhatsApp\Services
*/
class MassDispatchMerchantInterfaceService extends BaseService
{

    /**
    * 引入父级构造
    * MassDispatchMerchantInterfaceService constructor.
    * @param bool $pass 是否直接获取结果
    */
    public function __construct($pass = false) { parent::__construct($pass); }

    public function detail($id, $request){
        //渲染表单内容
        $render = FormBuilder::make()
            ->setSubmit(route('whatsapp.console.mass_dispatch_merchant.store', ['id' => (int)$id]))
            ->setItems(function (FormItemBuilder $builder) use ($id) {
                $builder->input('auth_token', '访问令牌')->description('绑定手机的国际区号（+86）')->readonly((int)$id <= 0 ? false : true)->required();
                $builder->input('tel_code', '电话号码ID')->description('用戶的手机号')->required();
                $builder->input('business_code', '商业账户ID')->description('用戶的手机号')->required();
                $builder->select('remainder', '发送条数')->options([0=>0,50=>50,250=>250,1000=>1000])->default_value(0)->required();
            })
            ->setData((int)$id > 0 ? (new MassDispatchMerchantRepository())->row(['id' => (int)$id]) : [])
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
        if ($data['__data__']['remainder'] <= 0){
            //返回失败
            return $this->fail(CodeLibrary::DATA_MISSING, '请选择发送条数');
        }
        //获取更改项
        $info = Arr::only($data['__data__'], $data['__edited__']);
        //添加修改时间
        $info['updated_at'] = auto_datetime();
        //判断是否为新增
        if ((int)$id <= 0) {
            //添加信息
            $info['admin_id'] = current_auth('id', config('pros.session_prefix', 'abnermouke:pros:console:auth'));
            $info['created_at'] = auto_datetime();
            $info['status'] = MassDispatchMerchant::STATUS_ENABLED;
            //添加信息
            if (!$id = (new MassDispatchMerchantRepository())->insertGetId($info)) {
                //返回失败
                return $this->fail(CodeLibrary::DATA_UPDATE_FAIL, '用户创建失败');
            }
        } else {
            //修改信息
            if (!(new MassDispatchMerchantRepository())->update(['id' => (int)$id], $info)) {
                //返回失败
                return $this->fail(CodeLibrary::DATA_UPDATE_FAIL, '修改失败');
            }
        }
        //返回成功
        $after = route('whatsapp.console.template.retrieve_index',['mdm_id'=>$id]);
        return $this->success(compact('after'));
    }

}
