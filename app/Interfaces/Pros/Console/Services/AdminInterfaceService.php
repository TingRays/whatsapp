<?php
/**
 * Power by abnermouke/easy-builder.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in Yunni Technology Co Ltd.
 * Date: 2022-07-21
 * Time: 22:09:43
*/

namespace App\Interfaces\Pros\Console\Services;

use Abnermouke\EasyBuilder\Library\CodeLibrary;
use Abnermouke\EasyBuilder\Library\Cryptography\AesLibrary;
use Abnermouke\EasyBuilder\Library\Currency\QrLibrary;
use Abnermouke\EasyBuilder\Module\BaseService;
use Abnermouke\Pros\Builders\Form\FormBuilder;
use Abnermouke\Pros\Builders\Form\Tools\FormItemBuilder;
use Abnermouke\Pros\Builders\Table\TableBuilder;
use App\Handler\Cache\Data\Pros\System\ConfigCacheHandler;
use App\Model\Pros\Console\AdminOauthSignatures;
use App\Model\Pros\Console\Admins;
use App\Model\Pros\Console\Roles;
use App\Repository\Pros\Console\AdminRepository;
use App\Repository\Pros\Console\RoleRepository;
use App\Services\Pros\Console\AdminLogService;
use App\Services\Pros\Console\AdminOauthSignatureService;
use App\Services\Pros\Console\AdminService;
use App\Services\Pros\System\TemporaryFileService;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

/**
 * 管理员接口逻辑服务容器
 * Class AdminService
 * @package App\Interfaces\Pros\Console\Services
*/
class AdminInterfaceService extends BaseService
{

    /**
    * 引入父级构造
    * AdminInterfaceService constructor.
    * @param bool $pass 是否直接获取结果
    */
    public function __construct($pass = false) { parent::__construct($pass); }


    /**
     * 获取管理员列表
     * @Author Abnermouke <abnermouke@outlook.com>
     * @Originate in Abnermouke's MBP
     * @Time 2022-10-25 11:59:15
     * @param $request Request
     * @return array|bool
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
        $conditions = [Admins::TABLE_NAME.'.status' => ['!=', Admins::STATUS_DELETED]];
        //判断是否筛选状态
        if ($request->exists('status')) {
            //设置默认条件
            $conditions[Admins::TABLE_NAME.'.status'] = (int)$request->get('status', Admins::STATUS_ENABLED);
        }
        //判断筛选条件
        if ($filters = data_get($data, 'filters', [])) {
            //循环筛选条件
            foreach ($filters as $filter => $value) {
                //根据筛选项设置条件
                switch ($filter) {
                    case 'keyword':
                        $value && $conditions[implode('|', [Admins::TABLE_NAME.'.id', Admins::TABLE_NAME.'.username', Admins::TABLE_NAME.'.nickname', Admins::TABLE_NAME.'.mobile', Admins::TABLE_NAME.'.email'])] = ['like', '%'.$value.'%'];
                        break;
                    case 'role_id':
                        (int)$value > 0 && $conditions[Admins::TABLE_NAME.'.role_id'] = (int)$value;
                        break;
                    case 'updated_at':
                        $value && $conditions[Admins::TABLE_NAME.'.updated_at'] = ['date', $value];
                        break;
                }
            }
        }
        //查询列表
        $lists = (new AdminRepository())->lists($conditions, [Admins::TABLE_NAME.'.*', Roles::TABLE_NAME.'.guard_name as role_name'], [
            ['left', Roles::TABLE_NAME, Admins::TABLE_NAME.'.role_id', '=', Roles::TABLE_NAME.'.id']
        ], data_get($data, 'sorts', [Admins::TABLE_NAME.'.id' => 'desc']), '', (int)data_get($data, 'page', config('pros.table.default_page')), (int)data_get($data, 'page_size', config('pros.table.default_page_size')));
        //渲染表格内容
        $render = TableBuilder::CONTENT()->signature($data['signature'])->setLists($lists)->render();
        //返回成功
        return $this->success($render);
    }

    /**
     * 获取管理员详情
     * @Author Abnermouke <abnermouke@outlook.com>
     * @Originate in Abnermouke's MBP
     * @Time 2022-10-25 11:59:15
     * @param $id
     * @param $request Request
     * @return array|bool
     * @throws \Exception
     */
    public function detail($id, $request)
    {
        //渲染表单内容
        $render = FormBuilder::make()
            ->setSubmit(route('pros.console.admins.store', ['id' => (int)$id]))
            ->setItems(function (FormItemBuilder $builder) use ($id) {
                $builder->input('username', '用户名')->description('管理员信息生成成功后将不支持修改用户名，请确认后输入')->readonly((int)$id <= 0 ? false : true)->tip('用户名将作为登录账号使用')->required();
                $builder->input('password', '登录密码')->input_type('password')->description('如需修改登录密码请更改')->required();
                $builder->input('nickname', '昵称')->required();
                $builder->input('mobile', '手机号码')->input_type('number')->max_length(11)->description('根据不同权限角色，将推送指定信息至绑定手机号码')->required();
                $builder->input('email', '电子邮箱')->input_type('email')->description('根据不同权限角色，将推送指定信息至电子邮箱')->required();
                $builder->select('role_id', '权限角色')->options(array_column((new RoleRepository())->get([], ['id', 'guard_name']), 'guard_name', 'id'))->required();
                $builder->image('avatar', '头像')->size('200x200')->required()->dictionary('pros/console/admins/avatars');
            })
            ->setData((int)$id > 0 ? (new AdminRepository())->row(['id' => (int)$id]) : [])
            ->render();
        //返回成功
        return $this->success(['html' => $render]);
    }

    /**
     * 保存管理员信息
     * @Author Abnermouke <abnermouke@outlook.com>
     * @Originate in Abnermouke's MBP
     * @Time 2022-10-25 11:59:15
     * @param $id
     * @param $request
     * @return array|bool
     * @throws \Exception
     */
    public function store($id, $request)
    {
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
        //判断是否更改图片
        if (in_array('avatar', $edited)) {
            //设置图片可用
            $info['avatar'] = (new TemporaryFileService())->pass(true)->enable($info['avatar']);
        }
        //添加修改时间
        $info['updated_at'] = auto_datetime();
        //判断是否为新增
        if ((int)$id <= 0) {
            //判断用户名是否可用
            if ((new AdminRepository())->exists(['username' => $info['username']])) {
                //返回失败
                return $this->fail(CodeLibrary::DATA_CREATE_FAIL, '用户名不可用');
            }
            //设置默认状态
            $info['status'] = Admins::STATUS_ENABLED;
            //设置默认值
            $info['login_ips'] = [$request->ip()];
            //添加信息
            $info['created_at'] = auto_datetime();
            //添加信息
            if (!$id = (new AdminRepository())->insertGetId($info)) {
                //返回失败
                return $this->fail(CodeLibrary::DATA_UPDATE_FAIL, '管理员创建失败');
            }
            //记录日志
            (new AdminLogService())->record( '新增管理员信息', compact('id'));
        } else {
            //修改信息
            if (!(new AdminRepository())->update(['id' => (int)$id], $info)) {
                //返回失败
                return $this->fail(CodeLibrary::DATA_UPDATE_FAIL, '修改失败');
            }
            //记录日志
            (new AdminLogService())->record( '更新管理员信息', compact('id'));
        }
        //判断是否更改密码
        if (in_array('password', $edited)) {
            //更改密码
            if (!($service = (new AdminService()))->change_password($id, $info['password'])) {
                //返回失败
                return $this->fail($service->getCode(), $service->getMessage());
            }
        }
        //返回成功
        return $this->success(compact('id'));
    }

    /**
     * 更改账号状态
     * @Author Abnermouke <abnermouke@outlook.com>
     * @Originate in Abnermouke's MBP
     * @Time 2022-10-25 11:59:15
     * @param $id
     * @param $request Request
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
        if (!(new AdminRepository())->update(['id' => (int)$id], ['status' => (int)$data['value'], 'updated_at' => auto_datetime()])) {
            //返回失败
            return $this->fail(CodeLibrary::DATA_UPDATE_FAIL, '更改失败');
        }
        //记录日志
        (new AdminLogService())->record('更改管理员状态为：'.$data['text'], $data);
        //返回成功
        return $this->success(compact('id'));
    }

    /**
     * 修改支付密码
     * @Author Abnermouke <abnermouke@outlook.com>
     * @Originate in Abnermouke's MBP
     * @Time 2022-10-25 11:59:15
     * @param $request
     * @return array|bool
     * @throws \Exception
     */
    public function change_password($request)
    {
        //获取加密信息
        if (!$data = AesLibrary::decryptFormData($request->all())) {
            //返回失败
            return $this->fail(CodeLibrary::DATA_MISSING, '非法参数');
        }
        //查询账户数据
        $validation = Validator::make($data, [
            'password' => 'required|min:6',
            'password_confirmed' => 'required|min:6',
        ], [], [
            'password.required' => '请输入密码',
            'password_confirmed' => '请确认输入密码',
            'password.min' => '密码至少6位',
        ]);
        //判断是否验证错误
        if ($validation->failed()) {
            //返回错误
            return $this->fail(CodeLibrary::VALIDATE_FAILED, $validation->errors()->first());
        }
        //判断密码是否相等
        if ($data['password'] !== $data['password_confirmed']) {
            //返回错误
            return $this->fail(CodeLibrary::VALIDATE_FAILED, '前后密码不一致');
        }
        //更改密码
        if (!($service = new AdminService())->change_password(current_auth('id', config('pros.session_prefix', 'abnermouke:pros:console:auth')),$data['password'])) {
            //返回错误
            return $this->fail($service->getCode(), $service->getMessage());
        }
        //记录日志
        (new AdminLogService())->record('修改密码', $service->getResult());
        //返回成功
        return $this->success($service->getResult());
    }

    /**
     * 删除管理员账户
     * @Author Abnermouke <abnermouke@outlook.com>
     * @Originate in Abnermouke's MBP
     * @Time 2022-10-25 11:59:15
     * @param $request Request
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
        //获取处理ID
        if ($ids = object_2_array(data_get($data, 'id', []))) {
            //设置管理员状态
            (new AdminRepository())->update(['id' => ['in', $ids]], ['status' => Admins::STATUS_DELETED, 'updated_at' => auto_datetime()]);
            //记录日志
            (new AdminLogService())->record('删除管理员', $ids);
        }
        //返回成功
        return $this->success($ids);
    }

    /**
     * 获取授权绑定二维码
     * @Author Abnermouke <abnermouke@outlook.com>
     * @Originate in Abnermouke's MBP
     * @Time 2022-10-25 11:59:15
     * @param $id
     * @param $request
     * @return array|bool
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     * @throws \Exception
     */
    public function qrcode($id, $request)
    {
        //创建授权记录
        $signature = (new AdminOauthSignatureService(true))->create(AdminOauthSignatures::TYPE_OF_BIND, ['id' => (int)$id]);
        //整理授权链接
        $oauth_uri = route('pros.console.oauth.wechat.signature', compact('signature'));
        //判断环境
        if (config('app.env', 'local') == 'production') {
            //生成二维码
            $qrcode = QrLibrary::create($oauth_uri, 'admins/oauth/wechat/qrcode/'.$signature.'.png', 300, true);
            //设置临时文件（两小时后到期）
            (new TemporaryFileService())->temporary($qrcode['storage_name'], $qrcode['storage_disk'], 7200);
            //获取访问链接
            $qrcode_link = Storage::disk($qrcode['storage_disk'])->url($qrcode['storage_name']);
        } else {
            //线下获取LOGO
            $qrcode_link = (new ConfigCacheHandler())->get('APP_LOGO', proxy_assets('static/medias/logos/logo-square.png', 'pros'));
        }
        //生成检测链接
        $check_link = route('pros.console.oauth.wechat.signature.check', ['signature' => $signature]);
        //渲染modal内容
        $html = view()->make('vendor.pros.console.hooks.admins.qrcode', compact('qrcode_link', 'check_link', 'signature'))->render();
        //返回结构
        return $this->success(compact('html'));
    }
}
