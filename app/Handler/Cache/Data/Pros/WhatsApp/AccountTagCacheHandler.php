<?php
/**
 * Power by abnermouke/easy-builder.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in Yunni Technology Co Ltd.
 * Date: 2022-10-26
 * Time: 15:14:56
*/

namespace App\Handler\Cache\Data\Pros\WhatsApp;

use Abnermouke\EasyBuilder\Module\BaseCacheHandler;
use App\Repository\Pros\WhatsApp\AccountTagRepository;

/**
 * 用户标签数据缓存处理器
 * Class AccountTagCacheHandler
 * @package App\Handler\Cache\Data\Pros\WhatsApp
 */
class AccountTagCacheHandler extends BaseCacheHandler
{
    /**
     * 构造函数
     * AccountTagCacheHandler constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        //引入父级构造
        parent::__construct('pros:whatsapp:account_tags_data_cache', 30356, 'file');
        //初始化缓存
        $this->init();
    }

    /**
     * 刷新当前缓存
     * @Author Abnermouke <abnermouke@outlook.com>
     * @Originate in Yunni Technology Co Ltd.
     * @Time 2022-10-26 15:14:56
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
     * @Time 2022-10-26 15:14:56
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
            $repository = new AccountTagRepository();

            //TODO : 初始化缓存数据

        }
        //返回缓存信息
        return $cache;
    }

}
