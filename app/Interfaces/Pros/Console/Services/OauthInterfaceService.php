<?php
/**
 * Power by abnermouke/easy-builder.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in Yunni Technology Co Ltd.
 * Date: 2022-07-23
 * Time: 14:56:35
*/

namespace App\Interfaces\Pros\Console\Services;

use Abnermouke\EasyBuilder\Library\CodeLibrary;
use Abnermouke\EasyBuilder\Library\Cryptography\AesLibrary;
use Abnermouke\EasyBuilder\Library\Currency\QrLibrary;
use Abnermouke\EasyBuilder\Library\Currency\ValidateLibrary;
use Abnermouke\EasyBuilder\Module\BaseService;
use App\Handler\Cache\Data\Pros\System\ConfigCacheHandler;
use App\Model\Pros\Console\AdminOauthSignatures;
use App\Model\Pros\Console\Admins;
use App\Repository\Pros\Console\AdminOauthSignatureRepository;
use App\Repository\Pros\Console\AdminRepository;
use App\Repository\Pros\Console\RoleRepository;
use App\Services\Pros\Console\AdminLogService;
use App\Services\Pros\Console\AdminOauthSignatureService;
use App\Services\Pros\System\TemporaryFileService;
use EasyWeChat\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

/**
 * 授权登录接口逻辑服务容器
 * Class OauthService
 * @package App\Interfaces\Pros\Console\Services
*/
class OauthInterfaceService extends BaseService
{

    /**
    * 引入父级构造
    * OauthInterfaceService constructor.
    * @param bool $pass 是否直接获取结果
    */
    public function __construct($pass = false) { parent::__construct($pass); }

    /**
     * 登录账户
     * @Author Abnermouke <abnermouke@outlook.com>
     * @Originate in Abnermouke's MBP
     * @Time 2022-10-25 11:59:15
     * @param $request Request
     * @return array|bool
     * @throws \Exception
     */
    public function sign_in($request)
    {
        //获取加密信息
        if (!$data = AesLibrary::decryptFormData($request->all())) {
            //返回失败
            return $this->fail(CodeLibrary::DATA_MISSING, '非法参数');
        }
        //查询账户数据
        $validation = Validator::make($data, [
            'username' => 'required',
            'password' => 'required',
        ], [], [
            'username' => '用户名',
            'password' => '密码',
        ]);
        //判断是否验证错误
        if ($validation->failed()) {
            //返回错误
            return $this->fail(CodeLibrary::VALIDATE_FAILED, $validation->errors()->first());
        }
        //查询账户信息
        if (!$admin = (new AdminRepository())->row(['username' => $data['username']], ['id', 'username', 'salt', 'status', 'password'])) {
            //返回失败
            return $this->fail(CodeLibrary::DATA_MISSING, '账户无效');
        }
        //判断密码是否正确
        if ($admin['password'] !== md5($data['username'].$admin['salt'].trim($data['password']))) {
            //返回失败
            return $this->fail(CodeLibrary::WITH_DO_NOT_ALLOW_STATE, '用户名或密码有误');
        }
        //判断状态
        if ((int)$admin['status'] !== Admins::STATUS_ENABLED) {
            //返回失败
            return $this->fail(CodeLibrary::WITH_DO_NOT_ALLOW_STATE, '状态异常，请联系管理员');
        }
        //登录信息
        return $this->autoLogin((int)$admin['id']);
    }

    /**
     * 登录账户
     * @Author Abnermouke <abnermouke@outlook.com>
     * @Originate in Abnermouke's MBP
     * @Time 2022-10-25 11:59:15
     * @param $admin_id int 管理员ID
     * @param bool $update_log 是否更新日志
     * @return array|bool
     * @throws \Exception
     */
    public function autoLogin($admin_id, $update_log = true)
    {
        //查询管理员信息
        if (!$auth = (new AdminRepository())->row(['id' => (int)$admin_id], ['id', 'username', 'avatar', 'nickname', 'mobile', 'email', 'role_id', 'login_ips', 'wechat_open_id', 'login_count'])) {
            //返回失败
            return $this->fail(CodeLibrary::DATA_MISSING, '账户无效');
        }
        //查询角色相关信息
        $auth = array_merge($auth, (new RoleRepository())->row(['id' => (int)$auth['role_id']], ['guard_name as role_name', "alias as role_alias", 'is_full_permission as role_full_permission']));
        //初始化累积登录IP
        $auth['login_ips'] = object_2_array($auth['login_ips']);
        //设置常用IP
        $auth['ip'] = \request()->ip();
        //判断是否更新日志
        if ($update_log) {
            //记录日志
            $log = (new AdminLogService())->pass(true)->record('登录成功', [], (int)$admin_id);
            //判断IP是否存在
            if (!in_array($log['ip'], $auth['login_ips'])) {
                //更新操作IP
                $auth['login_ips'][] = $log['ip'];
            }
            //更新登录信息
            (new AdminRepository())->update(['id' => (int)$admin_id], ['login_count' => ($auth['login_count'] = count($auth['login_ips'])), 'login_ips' => $auth['login_ips'], 'updated_at' => auto_datetime()]);
        }
        //整理昵称首字母
        $auth['nickname_abbr'] = mb_substr($auth['nickname'], 0, 1);
        //判断是否为中文
        if (ValidateLibrary::hasZh($auth['nickname_abbr'])) {
            //转换英文
            $auth['nickname_abbr'] = pinyin_abbr($auth['nickname_abbr']);
        }
        //整理大写
        $auth['nickname_abbr'] = strtoupper($auth['nickname_abbr']);
        //设置session
        set_current_auth($auth, config('pros.session_prefix', 'abnermouke:pros:console:auth'));
        //返回信息
        return $this->success($auth);
    }

    /**
     * 获取微信授权二维码
     * @Author Abnermouke <abnermouke@outlook.com>
     * @Originate in Abnermouke's MBP
     * @Time 2022-10-25 11:59:15
     * @param $request Request
     * @return array|bool
     * @throws \Exception
     */
    public function wechat_qrcode_sign_in($request)
    {
        //创建授权记录
        $signature = (new AdminOauthSignatureService(true))->create(AdminOauthSignatures::TYPE_OF_SIGN_IN);
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
            $qrcode_link = (new ConfigCacheHandler())->get('APP_LOGO', proxy_assets('medias/logos/logo@1x.png', 'abnermouke'));
        }
        //生成检测链接
        $check_link = route('pros.console.oauth.wechat.signature.check', ['signature' => $signature]);
        //返回参数
        return $this->success(compact('signature', 'qrcode_link', 'check_link'));
    }

    /**
     * 跳转微信授权
     * @Author Abnermouke <abnermouke@outlook.com>
     * @Originate in Abnermouke's MBP
     * @Time 2022-10-25 11:59:15
     * @param $signature
     * @param $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */
    public function wechat_qrcode_oauth($signature, $request)
    {
        //判断是否引入easywechat
        if (!File::exists('vendor/overtrue/laravel-wechat')) {
            //返回失败
            return pros_console_abort_error(CodeLibrary::DATA_MISSING, '请联系开发者引入：overtrue/laravel-wechat支持');
        }
        //获取配置
        $config = (new ConfigCacheHandler())->get('WECHAT_OFFICE_ACCOUNT_PARAMS', []);
        //实例化应用
        $app = Factory::officialAccount($config);
        //获取授权链接
        $redirect = $app->oauth->redirect(route('pros.console.oauth.wechat.signature.callback', ['signature' => $signature]));
        //返回跳转链接
        return redirect($redirect);
    }

    /**
     * 检测微信授权状态
     * @Author Abnermouke <abnermouke@outlook.com>
     * @Originate in Abnermouke's MBP
     * @Time 2022-10-25 11:59:15
     * @param $signature string 授权签名
     * @param $requerst
     * @return array|bool
     * @throws \Exception
     */
    public function wechat_qrcode_check($signature, $requerst)
    {
        //查询签名状态
        $oauth = (new AdminOauthSignatureRepository())->row(compact('signature'));
        //判断状态
        if ((int)data_get($oauth, 'status', AdminOauthSignatures::STATUS_VERIFYING) !== AdminOauthSignatures::STATUS_ENABLED) {
            //返回失败
            return $this->fail(CodeLibrary::WITH_DO_NOT_ALLOW_STATE, '授权等待中...');
        }
        //判断类型
        switch ((int)$oauth['type']) {
            //登录
            case AdminOauthSignatures::TYPE_OF_SIGN_IN:
                //自动登录账号
                $this->autoLogin((int)$oauth['admin_id']);
                break;
            //绑定
            case AdminOauthSignatures::TYPE_OF_BIND:
                //更新管理员信息
                (new AdminRepository())->update(['id' => (int)$oauth['admin_id']], data_get($oauth, 'results', ['updated_at' => auto_datetime()]));
                break;
        }
        //返回成功
        return $this->success(data_get($oauth, 'results', []));
    }

    /**
     * 微信授权登录回调
     * @Author Abnermouke <abnermouke@outlook.com>
     * @Originate in Abnermouke's MBP
     * @Time 2022-10-25 11:59:15
     * @param $signature
     * @param $code
     * @return array|bool
     * @throws \Exception
     */
    public function wechat_qrcode_callback($signature, $code)
    {
        //判断是否引入easywechat
        if (!File::exists('vendor/overtrue/laravel-wechat')) {
            //返回失败
            return $this->fail(CodeLibrary::DATA_MISSING, '请联系开发者引入：overtrue/laravel-wechat支持');
        }
        //查询签名状态
        if (!$oauth = (new AdminOauthSignatureRepository())->row(compact('signature'))) {
            //返回失败
            return $this->fail(CodeLibrary::DATA_MISSING, '授权信息不存在');
        }
        //判断是否已超时
        if (time() - 300 > (int)data_get($oauth, 'params.timestamp', 0)) {
            //返回错误
            return $this->fail(CodeLibrary::REQUEST_EXPIRED, '超时请求，请重新生成二维码');
        }
        //获取配置
        $config = (new ConfigCacheHandler())->get('WECHAT_OFFICE_ACCOUNT_PARAMS', []);
        //获取微信授权信息
        if (!$user = Factory::officialAccount($config)->oauth->userFromCode($code)) {
            //返回失败
            return $this->fail(CodeLibrary::NETWORK_ERROR, '授权失败');
        }
        //判断类型
        switch ((int)$oauth['type']) {
            //登录
            case AdminOauthSignatures::TYPE_OF_SIGN_IN:
                //查询OPEN-ID是否已存在
                if ($admin_id = (new AdminRepository())->find(['wechat_open_id' => $user->getId()], 'id')) {
                    //设置管理员ID
                    (new AdminOauthSignatureRepository())->update(['id' => (int)$oauth['id']], ['admin_id' => (int)$admin_id, 'status' => AdminOauthSignatures::STATUS_ENABLED, 'results' => $user->getRaw(), 'updated_at' => auto_datetime()]);
                } else {
                    //返回失败
                    return $this->fail(CodeLibrary::DATA_MISSING, '如需申请管理员身份，请先联系平台！');
                }
                break;
            //绑定
            case AdminOauthSignatures::TYPE_OF_BIND:
                //更新管理员信息
                $admin = [
                    'wechat_open_id' => $user->getId(),
                    'avatar' => $user->getAvatar(),
                    'nickname' => filter_emoji($user->getNickname()),
                    'email' => $user->getEmail(),
                    'updated_at' => auto_datetime(),
                ];
                //判断是否存在邮箱
                if (empty($admin['email'])) {
                    //移除邮箱
                    unset($admin['email']);
                }
                //更新授权信息
                (new AdminOauthSignatureRepository())->update(['id' => (int)$oauth['id']], ['status' => AdminOauthSignatures::STATUS_ENABLED, 'results' => $admin, 'updated_at' => auto_datetime()]);
                break;
        }
        //返回成功
        return $this->success(compact('signature'));
    }

}
