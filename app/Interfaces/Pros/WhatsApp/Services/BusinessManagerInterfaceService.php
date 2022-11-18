<?php
/**
 * Power by abnermouke/easy-builder.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in Yunni Technology Co Ltd.
 * Date: 2022-10-26
 * Time: 14:55:28
*/

namespace App\Interfaces\Pros\WhatsApp\Services;

use Abnermouke\EasyBuilder\Library\CodeLibrary;
use Abnermouke\EasyBuilder\Library\Cryptography\AesLibrary;
use Abnermouke\EasyBuilder\Module\BaseService;
use Abnermouke\Pros\Builders\Form\FormBuilder;
use Abnermouke\Pros\Builders\Form\Tools\FormItemBuilder;
use Abnermouke\Pros\Builders\Table\TableBuilder;
use App\Imports\Bm\BusinessManagersImport;
use App\Model\Pros\WhatsApp\BusinessManager;
use App\Model\Pros\WhatsApp\Merchants;
use App\Repository\Pros\WhatsApp\BusinessManagerRepository;
use App\Repository\Pros\WhatsApp\MerchantRepository;
use App\Services\Pros\Console\UploadService;
use App\Services\Pros\System\TemporaryFileService;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Maatwebsite\Excel\Facades\Excel;

/**
 * 商业管理（BM）账户接口逻辑服务容器
 * Class BusinessManagerService
 * @package App\Interfaces\Pros\WhatsApp\Services
*/
class BusinessManagerInterfaceService extends BaseService
{

    /**
    * 引入父级构造
    * BusinessManagerInterfaceService constructor.
    * @param bool $pass 是否直接获取结果
    */
    public function __construct($pass = false) { parent::__construct($pass); }

    /**
     * 商业管理（BM）账户列表
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
        $conditions = ['status' => ['!=', BusinessManager::STATUS_DELETED]];
        //判断是否筛选状态
        if ($request->exists('status')) {
            //设置默认条件
            $conditions['status'] = (int)$request->get('status', BusinessManager::STATUS_ENABLED);
        }
        //判断筛选条件
        if ($filters = data_get($data, 'filters', [])) {
            //循环筛选条件
            foreach ($filters as $filter => $value) {
                //根据筛选项设置条件
                switch ($filter) {
                    case 'keyword':
                        $value && $conditions[implode('|', ['id', 'guard_name', 'code', 'nickname', 'ac_number'])] = ['like', '%'.$value.'%'];
                        break;
                    case 'updated_at':
                        $value && $conditions['updated_at'] = ['date', $value];
                        break;
                }
            }
        }
        //查询列表
        $lists = (new BusinessManagerRepository())->lists($conditions, ['*'], [], data_get($data, 'sorts', ['id' => 'desc']), '', (int)data_get($data, 'page', config('pros.table.default_page')), (int)data_get($data, 'page_size', config('pros.table.default_page_size')));
        //渲染表格内容
        $render = TableBuilder::CONTENT()->signature($data['signature'])->setLists($lists)->render();
        //返回成功
        return $this->success($render);
    }

    /**
     * 获取（BM）账户详情
     * @param $id
     * @param $request
     * @return array|bool
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function detail($id, $request){
        $info = (int)$id > 0 ? (new BusinessManagerRepository())->row(['id' => (int)$id]) : [];
        if (!$info){
            $count = (new BusinessManagerRepository())->count() + 1;
            if ($count<10){
                $count = '0'.$count;
            }
            $info['guard_name'] = 'BM-'.$count;
            $info['code'] = '1128022304774783';
            $info['nickname'] = 'Tamang Michi';
            $info['ac_number'] = '100012320310148';
            $info['ac_password'] = 'A@!xJuNHvPK';
            $info['ac_secret_key'] = '3ZBG7S6TDS2QDGIURWW3QZ6EWLAJVBXK';
            $info['ac_email'] = 'ayesha6f9dora@outlook.com';
            $info['ac_email_pwd'] = 'ioaFGjnI6ucVmmA#';
            $info['ac_spare_email'] = 'ayesha6f9dora@getnada.com';
            $info['age'] = '01/01/1976';
        }
        //渲染表单内容
        $render = FormBuilder::make()
            ->setSubmit(route('whatsapp.console.bm.store', ['id' => (int)$id]))
            ->setItems(function (FormItemBuilder $builder) use ($id) {
                $builder->input('guard_name', '账户名称')->description('系统内方便管理识别账户的名称，与Meta账户无关系')->readonly((int)$id <= 0 ? false : true)->required();
                $builder->input('code', '商务管理平台编号')->description('Meta的信息商务管理平台编号信息')->readonly((int)$id <= 0 ? false : true)->tip('是商务管理平台信息')->required();
                $builder->input('nickname', '姓名')->description('用户在Meta信息商务管理平台的姓名')->required();
                $builder->input('ac_number', '登录账号')->description('Meta信息商务管理平台的登录账号')->readonly((int)$id <= 0 ? false : true)->tip('作为BM登录账号使用')->required();
                $builder->input('ac_password', '登录密码')->description('Meta信息商务管理平台的登录账号的密码')->readonly((int)$id <= 0 ? false : true)->tip('作为BM登录账号的密码使用')->required();
                $builder->input('ac_secret_key', '密钥')->description('Meta账号登录验证码的接码密钥')->readonly((int)$id <= 0 ? false : true)->tip('作为BM账号登录接码使用')->required();
                $builder->input('auth_token', '访问令牌')->description('接口访问密令')->required();
                $builder->input('ac_email', '邮箱')->input_type('email')->description('Meta账号绑定的邮箱')->readonly((int)$id <= 0 ? false : true)->required();
                $builder->input('ac_email_pwd', '邮箱密码')->description('Meta账号绑定的邮箱的登录密码')->readonly((int)$id <= 0 ? false : true)->required();
                $builder->input('ac_spare_email', '备用邮箱')->description('可能是Meta账号绑定的邮箱的备用邮箱')->readonly((int)$id <= 0 ? false : true)->required();
                $builder->input('age', '年龄')->description('可能是账号的验证信息备注')->readonly((int)$id <= 0 ? false : true)->required();
                $builder->select('status', '账户状态')->options(BusinessManager::TYPE_GROUPS['__status__'],BusinessManager::STATUS_ENABLED)->required();
            })
            ->setData($info)
            ->render();
        //返回成功
        return $this->success(['html' => $render]);
    }

    /**
     * 保存（BM）账户信息
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
        if (!isset($info['guard_name'])){
            $info['guard_name'] = $data['__data__']['guard_name'];
        }
        //判断是否为新增
        if ((int)$id <= 0) {
            //判断信息是否可用
            if ((new BusinessManagerRepository())->exists(['guard_name' => $info['guard_name']])) {
                //返回失败
                return $this->fail(CodeLibrary::DATA_CREATE_FAIL, '账户名称已存在');
            }
            if ((new BusinessManagerRepository())->exists(['code' => $info['code']])) {
                //返回失败
                return $this->fail(CodeLibrary::DATA_CREATE_FAIL, '商务管理平台编号已存在');
            }
            if ((new BusinessManagerRepository())->exists(['ac_number' => $info['ac_number']])) {
                //返回失败
                return $this->fail(CodeLibrary::DATA_CREATE_FAIL, '登录账号已存在');
            }
            //添加信息
            $info['created_at'] = auto_datetime();
            //添加信息
            if (!$id = (new BusinessManagerRepository())->insertGetId($info)) {
                //返回失败
                return $this->fail(CodeLibrary::DATA_UPDATE_FAIL, '商业管理（BM）账户创建失败');
            }
        } else {
            //修改信息
            if (!(new BusinessManagerRepository())->update(['id' => (int)$id], $info)) {
                //返回失败
                return $this->fail(CodeLibrary::DATA_UPDATE_FAIL, '修改失败');
            }
            if (isset($info['auth_token'])){
                (new MerchantRepository())->update(['bm_id'=>(int)$id],['auth_token'=>$info['auth_token']]);
            }
        }
        //返回成功
        return $this->success(compact('id'));
    }

    /**
     * 修改（BM）账户状态
     * @param $id
     * @param $request
     * @return array|bool
     * @throws \Exception
     */
    public function enable($id, $request)
    {
        //获取加密信息
        if (!$data = AesLibrary::decryptFormData($request->all())) {
            //返回失败
            return $this->fail(CodeLibrary::DATA_MISSING, '非法参数');
        }
        //更改账户状态
        if (!(new BusinessManagerRepository())->update(['id' => (int)$id], ['status' => (int)$data['value'], 'updated_at' => auto_datetime()])) {
            //返回失败
            return $this->fail(CodeLibrary::DATA_UPDATE_FAIL, '更改失败');
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
        $remainder = $request->get('remainder');
        if (!$remainder){
            //返回失败
            return $this->fail(CodeLibrary::DATA_MISSING, '请先选择发送量！');
        }
        //获取文件结果
        $file = $service->getResult();
        //导入内容
        $sheets = Excel::import(new BusinessManagersImport(), $file['storage_name'], $file['storage_disk'])->toArray(new BusinessManagersImport, $file['storage_name'], $file['storage_disk']);
        //获取导入信息
        $posts = Arr::first($sheets);
        //整理失败数据
        $wrongs = $success = [];
        //循环导入信息
        foreach ($posts as $k => $post) {
            //判断是否第一项
            if ((int)$k > 0) {
                $auth_token = trim($post[0]);
                if (!$auth_token){
                    continue;
                }
                $global_roaming = '55';
                $mobile = str_replace(['+',' ','(',')','-','（','）'],'',trim($post[1]));

                if (strpos($mobile,$global_roaming) === 0){
                    $global_roaming_len = strlen($global_roaming);
                    $mobile = substr($mobile,$global_roaming_len);
                }
                $tel_code = str_replace(['+',' ','-'],'',trim($post[2]));
                $business_code = str_replace(['+',' ','-'],'',trim($post[3]));

                $bm = (new BusinessManagerRepository())->row(['auth_token' => $auth_token],['id','guard_name']);
                if (!$bm) {
                    $count = (new BusinessManagerRepository())->count() + 1;
                    if ($count<10){
                        $count = '0'.$count;
                    }
                    $bm_info = [
                        'guard_name' => 'BM-'.$count,
                        'code' => time().rand(999,9999),
                        'nickname' => 'Tamang Michi',
                        'ac_number' => time().rand(999,9999),
                        'ac_password' => 'A@!xJuNHvPK',
                        'ac_secret_key' => '3ZBG7S6TDS2QDGIURWW3QZ6EWLAJVBXK',
                        'auth_token' => $auth_token,
                        'ac_email' => 'ayesha6f9dora@outlook.com',
                        'ac_email_pwd' => 'ioaFGjnI6ucVmmA#',
                        'ac_spare_email' => 'ayesha6f9dora@getnada.com',
                        'age' => '01/01/1976',
                        'status' => BusinessManager::STATUS_ENABLED,
                        'created_at' => auto_datetime(),
                        'updated_at' => auto_datetime(),
                    ];
                    $guard_name = $bm_info['guard_name'];
                    $bm_id = (new BusinessManagerRepository())->insertGetId($bm_info);
                }else{
                    $bm_id = $bm['id'];
                    $guard_name = $bm['guard_name'];
                }
                if ((new MerchantRepository())->exists(['tel_code'=>$tel_code])){
                    //商户已存在 - 更新
                    $merchant_info = [
                        'global_roaming' => $global_roaming,
                        'tel' => $mobile,
                        'tel_code' => $tel_code,
                        'business_code' => $business_code,
                        'remainder' => $remainder,
                        'bm_status' => BusinessManager::STATUS_ENABLED,
                        'status' => Merchants::STATUS_ENABLED,
                        'updated_at' => auto_datetime(),
                    ];
                    (new MerchantRepository())->update(['tel_code'=>$tel_code],$merchant_info);
                }else{
                    //商户不存在 - 新增
                    $merchant_count = (new MerchantRepository())->count(['bm_id'=>$bm_id]) + 1;
                    if ($merchant_count<10){
                        $merchant_count = '0'.$merchant_count;
                    }
                    $merchant_info = [
                        'bm_id' => $bm_id,
                        'guard_name' => $guard_name.'的（'.$merchant_count.'）',
                        'auth_token' => $auth_token,
                        'global_roaming' => $global_roaming,
                        'tel' => $mobile,
                        'tel_code' => $tel_code,
                        'business_code' => $business_code,
                        'remainder' => $remainder,
                        'update_remainder_time' => 0,
                        'bm_status' => BusinessManager::STATUS_ENABLED,
                        'status' => Merchants::STATUS_ENABLED,
                        'created_at' => auto_datetime(),
                        'updated_at' => auto_datetime(),
                    ];
                    //添加信息
                    if (!$id = (new MerchantRepository())->insertGetId($merchant_info)) {
                        //返回失败
                        return $this->fail(CodeLibrary::DATA_UPDATE_FAIL, 'BM的商户创建失败');
                    }
                }
                //添加成功
                $success[] = $post;
            }
        }
        //整理返回结果
        $result = ['success' => count($success), 'wrong' => $wrongs, 'msg' => ('本次导入共' . (count($posts) - 1) . '条<br />成功：' . count($success) . ' 条，失败：' . count($wrongs) . ' 条')];
        //返回成功
        return $this->success($result);
    }
}
