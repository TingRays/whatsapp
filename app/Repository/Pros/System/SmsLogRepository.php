<?php
/**
 * Power by abnermouke/easy-builder.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in Yunni Technology Co Ltd.
 * Date: 2022-10-25
 * Time: 11:57:54
*/

namespace App\Repository\Pros\System;

use App\Model\Pros\System\SmsLogs;
use Abnermouke\EasyBuilder\Module\BaseRepository;

/**
 * 短信记录信息数据仓库 for table [mysql:pros_sms_logs]
 * Class SmsLogRepository
 * @package App\Repository
*/
class SmsLogRepository extends BaseRepository
{
    /**
     * 构造函数
     * SmsLogRepository constructor.
     * @throws \Exception
    */
    public function __construct()
    {
        //实例化模型
        $model = new SmsLogs();
        //引入父级构造函数
        parent::__construct($model, SmsLogs::DB_CONNECTION);
    }

}
