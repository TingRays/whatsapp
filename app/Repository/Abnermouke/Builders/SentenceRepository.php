<?php
/**
 * Power by abnermouke/easy-builder.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in Yunni Technology Co Ltd.
 * Date: 2022-10-25
 * Time: 11:57:54
*/

namespace App\Repository\Abnermouke\Builders;

use App\Model\Abnermouke\Builders\Sentences;
use Abnermouke\EasyBuilder\Module\BaseRepository;

/**
 * easy_builder语录句子信息数据仓库 for table [mysql:aeb_sentences]
 * Class SentenceRepository
 * @package App\Repository
*/
class SentenceRepository extends BaseRepository
{
    /**
     * 构造函数
     * SentenceRepository constructor.
     * @throws \Exception
    */
    public function __construct()
    {
        //实例化模型
        $model = new Sentences();
        //引入父级构造函数
        parent::__construct($model, Sentences::DB_CONNECTION);
    }

}
