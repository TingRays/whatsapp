<?php
/**
 * Power by abnermouke/easy-builder.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in Yunni Technology Co Ltd.
 * Date: 2022-07-23
 * Time: 02:18:37
*/

namespace App\Handler\Cache\Data\Abnermouke\Builders;

use Abnermouke\EasyBuilder\Module\BaseCacheHandler;
use App\Model\Abnermouke\Builders\Sentences;
use App\Repository\Abnermouke\Builders\SentenceRepository;
use Illuminate\Support\Arr;

/**
 * easy_builder语录句子数据缓存处理器
 * Class SentenceCacheHandler
 * @package App\Handler\Cache\Data\Abnermouke\Builders
 */
class SentenceCacheHandler extends BaseCacheHandler
{
    /**
     * 构造函数
     * SentenceCacheHandler constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        //引入父级构造
        parent::__construct('abnermouke:builders:sentences_data_cache', 14788, 'file');
        //初始化缓存
        $this->init();
    }

    /**
     * 随机获取一条
     * @Author Abnermouke <abnermouke@outlook.com>
     * @Originate in Abnermouke's MBP
     * @Time 2022-10-25 11:59:15
     * @return mixed|string
     */
    public function random()
    {
        //判断缓存
        if (!$this->cache) {
            //返回默认
            return '不管多么险峻的高山，总是为不畏艰难的人留下一条攀登的路。';
        }
        //随机获取
        return Arr::random($this->cache)['sentence_cn'];
    }

    /**
     * 刷新当前缓存
     * @Author Abnermouke <abnermouke@outlook.com>
     * @Originate in Yunni Technology Co Ltd.
     * @Time 2022-10-25 11:59:15
     * @return array
     * @throws \Exception
    */
    public function refresh()
    {
        //删除缓存
        $this->clear();
        //初始化缓存
        return $this->init();
    }

    /**
     * 初始化缓存
     * @Author Abnermouke <abnermouke@outlook.com>
     * @Originate in Yunni Technology Co Ltd.
     * @Time 2022-10-25 11:59:15
     * @return array
     * @throws \Exception
    */
    private function init()
    {
        //获取缓存
        $cache = $this->cache;
        //判断缓存信息
        if (!$cache || empty($this->cache)) {
            //引入Repository
            $repository = new SentenceRepository();
            //初始化缓存数据
            if ($cache = $this->cache = $repository->limit([], ['date', 'sentence_cn', 'sentence_en'], [], ['id' => Sentences::RANDOM_ORDER_BY], '', 1, 50)) {
                //保存缓存
                $this->save();
            }
        }
        //返回缓存信息
        return $cache;
    }

}
