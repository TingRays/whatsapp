<?php
/**
 * Power by abnermouke/easy-builder.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in Yunni Technology Co Ltd.
 * Date: 2022-10-26
 * Time: 15:04:26
*/

namespace App\Interfaces\Pros\WhatsApp\Services;

use Abnermouke\EasyBuilder\Library\CodeLibrary;
use Abnermouke\EasyBuilder\Library\Cryptography\AesLibrary;
use Abnermouke\EasyBuilder\Module\BaseService;
use Abnermouke\Pros\Builders\Form\FormBuilder;
use Abnermouke\Pros\Builders\Form\Tools\FormItemBuilder;
use Abnermouke\Pros\Builders\Table\TableBuilder;
use App\Model\Pros\WhatsApp\Merchants;
use App\Repository\Pros\WhatsApp\BusinessManagerRepository;
use App\Repository\Pros\WhatsApp\MerchantRepository;
use Illuminate\Support\Arr;

/**
 * BM的商户接口逻辑服务容器
 * Class MerchantService
 * @package App\Interfaces\Pros\WhatsApp\Services
*/
class MerchantInterfaceService extends BaseService
{

    /**
    * 引入父级构造
    * MerchantInterfaceService constructor.
    * @param bool $pass 是否直接获取结果
    */
    public function __construct($pass = false) { parent::__construct($pass); }

    public function lists($bm_id, $request){
        //获取加密信息
        if (!$data = AesLibrary::decryptFormData($request->all())) {
            //返回失败
            return $this->fail(CodeLibrary::DATA_MISSING, '非法参数');
        }
        //整理查询条件
        $conditions = ['status' => ['!=', Merchants::STATUS_DELETED]];
        //判断是否筛选状态
        if ($request->exists('status')) {
            //设置默认条件
            $conditions['status'] = (int)$request->get('status', Merchants::STATUS_ENABLED);
        }
        //判断筛选条件
        if ($filters = data_get($data, 'filters', [])) {
            //循环筛选条件
            foreach ($filters as $filter => $value) {
                //根据筛选项设置条件
                switch ($filter) {
                    case 'keyword':
                        $value && $conditions[implode('|', ['id', 'guard_name', 'tel', 'tel_code', 'business_code'])] = ['like', '%'.$value.'%'];
                        break;
                    case 'global_roaming':
                        (int)$value > 0 && $conditions['global_roaming'] = (int)$value;
                        break;
                    case 'updated_at':
                        $value && $conditions['updated_at'] = ['date', $value];
                        break;
                }
            }
        }
        $conditions['bm_id'] = $bm_id;
        //查询列表
        $lists = (new MerchantRepository())->lists($conditions, ['*'], [], data_get($data, 'sorts', ['id' => 'desc']), '', (int)data_get($data, 'page', config('pros.table.default_page')), (int)data_get($data, 'page_size', config('pros.table.default_page_size')));
        //渲染表格内容
        $render = TableBuilder::CONTENT()->signature($data['signature'])->setLists($lists)->render();
        //返回成功
        return $this->success($render);
    }

    /**
     * 获取BM的商户详情
     * @param $id
     * @param $request
     * @return array|bool
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function detail($bm_id, $id, $request){
        //渲染表单内容
        $bm_info = (new BusinessManagerRepository())->row(['id'=>$bm_id],['guard_name','code','nickname','auth_token']);
        if ($id){
            $info = (int)$id > 0 ? (new MerchantRepository())->row(['id' => (int)$id]) : [];
        }else{
            $count = (new MerchantRepository())->count() + 1;
            if ($count<10){
                $count = '0'.$count;
            }
            $info['guard_name'] = $bm_info['guard_name'].' 的'.'（'.$count.'）';
        }
        $info['bm_info'] = $bm_info['guard_name'].'（'.$bm_info['code'].' - '.$bm_info['nickname'].'）';
        $info['auth_token'] = $bm_info['auth_token'];
        $render = FormBuilder::make()
            ->setSubmit(route('whatsapp.console.merchant.store', ['bm_id' => $bm_id,'id' => (int)$id]))
            ->setItems(function (FormItemBuilder $builder) use ($id) {
                $builder->input('bm_info', 'BM账户信息')->description('Meta的信息商务管理平台（BM）主账户信息')->readonly(true);
                $builder->input('guard_name', '商户名称')->description('系统内方便管理识别账户下的商户名称，与Meta账户无关系')->readonly((int)$id <= 0 ? false : true)->required();
                $builder->input('auth_token', '访问令牌')->description('接口访问密令')->readonly(true)->required();
                $builder->input('global_roaming', '国际区号')->description('绑定手机的国际区号（+86）')->required();
                $builder->input('tel', '手机号')->description('发信人绑定的手机号')->tip('发送消息的绑定手机号')->required();
                $builder->input('tel_code', '电话号码编号')->description('发信人绑定的手机号对应编号')->required();
                $builder->input('business_code', '业务帐户编号')->description('WhatsApp Business 业务帐户编号')->required();
                $builder->input('remainder', '剩余发送量')->default_value(50)->input_type('number')->description('当前商户剩余发送消息量')->required();
                $builder->select('status', '商户状态')->options(Merchants::TYPE_GROUPS['__status__'],Merchants::STATUS_ENABLED)->required();
            })
            ->setData($info)
            ->render();
        //返回成功
        return $this->success(['html' => $render]);
    }

    /**
     * 保存BM的商户信息
     * @param $bm_id
     * @param $id
     * @param $request
     * @return array|bool
     * @throws \Exception
     */
    public function store($bm_id, $id, $request){
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
        if (!isset($info['guard_name'])){
            $info['guard_name'] = $data['__data__']['guard_name'];
        }
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
            $auth_token = (new BusinessManagerRepository())->find(['id'=>$bm_id],'auth_token');
            //设置商户信息
            $info['bm_id'] = $bm_id;
            //访问令牌
            $info['auth_token'] = $auth_token;
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

    /**
     * 修改商户状态
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
        if (!(new MerchantRepository())->update(['id' => (int)$id], ['status' => (int)$data['value'], 'updated_at' => auto_datetime()])) {
            //返回失败
            return $this->fail(CodeLibrary::DATA_UPDATE_FAIL, '更改失败');
        }
        //返回成功
        return $this->success(compact('id'));
    }
}
