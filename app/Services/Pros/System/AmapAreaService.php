<?php
/**
 * Power by abnermouke/easy-builder.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in Yunni Technology Co Ltd.
 * Date: 2022-07-22
 * Time: 23:06:16
*/

namespace App\Services\Pros\System;

use Abnermouke\EasyBuilder\Library\CodeLibrary;
use Abnermouke\EasyBuilder\Module\BaseService;
use App\Handler\Cache\Data\Pros\System\ConfigCacheHandler;
use App\Implementers\AmapAreaImplementers;

/**
 * 高德地图行政地区逻辑服务容器
 * Class AmapAreaService
 * @package App\Services\Pros\System
*/
class AmapAreaService extends BaseService
{

    /**
    * 引入父级构造
    * AmapAreaService constructor.
    * @param bool $pass 是否直接获取结果
    */
    public function __construct($pass = false) { parent::__construct($pass); }

    /**
     * 同步最新高德地区行政地区信息
     * @Author Abnermouke <abnermouke@outlook.com>
     * @Originate in Abnermouke's MBP
     * @Time 2022-10-25 11:59:15
     * @return array|bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     */
    public function sync()
    {
        //同步最新行政地区
        if (!AmapAreaImplementers::run((new ConfigCacheHandler())->get('AMAP_WEB_SERVER_API_KEY'))) {
            //返回失败
            return $this->fail(CodeLibrary::CODE_LOGIC_ERROR, '数据同步失败');
        }

        //TODO : 如对行政地址库做特殊处理，请单独使用表进行处理，避免影响同步结构

        //返回成功
        return $this->success(['time' => auto_datetime()]);
    }
}
