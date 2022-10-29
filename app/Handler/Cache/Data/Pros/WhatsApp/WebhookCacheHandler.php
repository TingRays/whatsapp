<?php
/**
 * Power by abnermouke/easy-builder.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in Yunni Technology Co Ltd.
 * Date: 2022-10-29
 * Time: 10:03:34
*/

namespace App\Handler\Cache\Data\Pros\WhatsApp;

use Abnermouke\EasyBuilder\Module\BaseCacheHandler;
use App\Repository\Pros\WhatsApp\WebhookRepository;

/**
 * Webhooks记录数据缓存处理器
 * Class WebhookCacheHandler
 * @package App\Handler\Cache\Data\Pros\WhatsApp
 */
class WebhookCacheHandler extends BaseCacheHandler
{
    /**
     * 构造函数
     * WebhookCacheHandler constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        //引入父级构造
        parent::__construct('pros:whatsapp:webhooks_data_cache', 73761, 'file');
        //初始化缓存
        $this->init();
    }

    /**
     * 刷新当前缓存
     * @Author Abnermouke <abnermouke@outlook.com>
     * @Originate in Yunni Technology Co Ltd.
     * @Time 2022-10-29 10:03:34
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
     * @Time 2022-10-29 10:03:34
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
            $repository = new WebhookRepository();

            //TODO : 初始化缓存数据

        }
        //返回缓存信息
        return $cache;
    }

}
