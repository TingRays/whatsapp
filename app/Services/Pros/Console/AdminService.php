<?php
/**
 * Power by abnermouke/easy-builder.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in Yunni Technology Co Ltd.
 * Date: 2022-07-21
 * Time: 14:25:00
*/

namespace App\Services\Pros\Console;

use Abnermouke\EasyBuilder\Library\CodeLibrary;
use Abnermouke\EasyBuilder\Module\BaseService;
use App\Repository\Pros\Console\AdminRepository;
use Illuminate\Support\Str;

/**
 * 管理员逻辑服务容器
 * Class AdminService
 * @package App\Services\Pros\Console
*/
class AdminService extends BaseService
{

    /**
    * 引入父级构造
    * AdminService constructor.
    * @param bool $pass 是否直接获取结果
    */
    public function __construct($pass = false) { parent::__construct($pass); }

    /**
     * 修改管理员密码
     * @Author Abnermouke <abnermouke@outlook.com>
     * @Originate in Abnermouke's MBP
     * @Time 2022-10-25 11:59:15
     * @param $admin_id
     * @param $new_password
     * @return array|bool
     * @throws \Exception
     */
    public function change_password($admin_id, $new_password)
    {
        //查询管理员信息
        if (!$admin = (new AdminRepository())->row(['id' => (int)$admin_id], ['id', 'username', 'mobile'])) {
            //返回失败
            return $this->fail(CodeLibrary::DATA_MISSING, '账户不存在');
        }
        //生成密钥盐
        $salt = Str::random(8);
        //生成信息
        $data = [
            'salt' => $salt,
            'password' => md5($admin['username'].$salt.$new_password),
            'updated_at' => auto_datetime()
        ];
        //更新管理员密码
        if (!(new AdminRepository())->update(['id' => (int)$admin_id], $data)) {
            //返回失败
            return $this->fail(CodeLibrary::DATA_UPDATE_FAIL, '密码修改失败');
        }
        //返回成功
        return $this->success(compact('admin_id'));

    }
}
