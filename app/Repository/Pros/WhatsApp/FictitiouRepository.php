<?php
/**
 * Power by abnermouke/easy-builder.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in Yunni Technology Co Ltd.
 * Date: 2022-11-03
 * Time: 17:25:45
*/

namespace App\Repository\Pros\WhatsApp;

use App\Model\Pros\WhatsApp\Fictitious;
use Abnermouke\EasyBuilder\Module\BaseRepository;

/**
 * 虚拟手机号信息数据仓库 for table [mysql:wa_fictitious]
 * Class FictitiouRepository
 * @package App\Repository
*/
class FictitiouRepository extends BaseRepository
{
    /**
     * 构造函数
     * FictitiouRepository constructor.
     * @throws \Exception
    */
    public function __construct()
    {
        //实例化模型
        $model = new Fictitious();
        //引入父级构造函数
        parent::__construct($model, Fictitious::DB_CONNECTION);
    }

}
