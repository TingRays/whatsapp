<?php
/**
 * Power by abnermouke/easy-builder.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in Yunni Technology Co Ltd.
 * Date: 2022-10-25
 * Time: 11:57:54
*/

namespace App\Repository\Pros\Console;

use App\Model\Pros\Console\Nodes;
use Abnermouke\EasyBuilder\Module\BaseRepository;

/**
 * 节点信息数据仓库 for table [mysql:pros_nodes]
 * Class NodeRepository
 * @package App\Repository
*/
class NodeRepository extends BaseRepository
{
    /**
     * 构造函数
     * NodeRepository constructor.
     * @throws \Exception
    */
    public function __construct()
    {
        //实例化模型
        $model = new Nodes();
        //引入父级构造函数
        parent::__construct($model, Nodes::DB_CONNECTION);
    }

}
