<?php
/**
 * Power by abnermouke/easy-builder.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in Yunni Technology Co Ltd.
 * Date: 2022-07-21
 * Time: 14:30:36
*/

namespace App\Services\Pros\Console;

use Abnermouke\EasyBuilder\Module\BaseService;
use App\Repository\Pros\Console\AdminLogRepository;

/**
 * 管理员操作日志逻辑服务容器
 * Class AdminLogService
 * @package App\Services\Pros\Console
*/
class AdminLogService extends BaseService
{

    /**
    * 引入父级构造
    * AdminLogService constructor.
    * @param bool $pass 是否直接获取结果
    */
    public function __construct($pass = false) { parent::__construct($pass); }

    /**
     * 记录日志
     * @Author Abnermouke <abnermouke@outlook.com>
     * @Originate in Abnermouke's MBP
     * @Time 2022-10-25 11:59:15
     * @param $content
     * @param array $params
     * @param int $admin_id
     * @param false $ip
     * @return array|bool
     * @throws \Exception
     */
    public function record($content, $params = [], $admin_id = 0, $ip = false)
    {
        //整理日志信息
        $log = [
            'admin_id' => (int)$admin_id > 0 ? $admin_id : current_auth('id', config('pros.session_prefix', 'abnermouke:pros:console:auth')),
            'content' => $content,
            'params' => init_request_params($params),
            'ip' => $ip ? $ip : request()->ip(),
            'created_at' => auto_datetime(),
            'updated_at' => auto_datetime()
        ];
//        //判断当前处理模式
//        if ((int)$log['admin_id'] <= 0 || (int)$log['admin_id'] === 101) {
//            //返回成功
//            return $this->success($log);
//        }
        //创建操作日志
        (new AdminLogRepository())->insertGetId($log);
        //返回成功
        return $this->success($log);
    }
}
