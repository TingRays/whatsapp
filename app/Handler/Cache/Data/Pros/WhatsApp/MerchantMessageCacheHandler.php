<?php
/**
 * Power by abnermouke/easy-builder.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in Yunni Technology Co Ltd.
 * Date: 2022-10-28
 * Time: 08:51:39
*/

namespace App\Handler\Cache\Data\Pros\WhatsApp;

use Abnermouke\EasyBuilder\Module\BaseCacheHandler;
use App\Repository\Pros\WhatsApp\MerchantMessageRepository;

/**
 * 商户发送消息数据缓存处理器
 * Class MerchantMessageCacheHandler
 * @package App\Handler\Cache\Data\Pros\WhatsApp
 */
class MerchantMessageCacheHandler extends BaseCacheHandler
{
    /**
     * 构造函数
     * MerchantMessageCacheHandler constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        //引入父级构造
        parent::__construct('pros:whatsapp:merchant_messages_data_cache', 52548, 'file');
        //初始化缓存
        $this->init();
    }

    /**
     * 刷新当前缓存
     * @Author Abnermouke <abnermouke@outlook.com>
     * @Originate in Yunni Technology Co Ltd.
     * @Time 2022-10-28 08:51:39
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
     * @Time 2022-10-28 08:51:39
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
            $repository = new MerchantMessageRepository();

            //TODO : 初始化缓存数据

        }
        //返回缓存信息
        return $cache;
    }

}
