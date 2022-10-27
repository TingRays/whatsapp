<?php
/**
 * Power by abnermouke/easy-builder.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in Yunni Technology Co Ltd.
 * Date: 2022-07-22
 * Time: 23:16:27
*/

namespace App\Interfaces\Pros\Console\Services;

use Abnermouke\EasyBuilder\Library\CodeLibrary;
use Abnermouke\EasyBuilder\Library\Cryptography\AesLibrary;
use Abnermouke\EasyBuilder\Module\BaseService;
use App\Handler\Cache\Data\Pros\System\ConfigCacheHandler;
use App\Repository\Pros\System\ConfigRepository;
use App\Services\Pros\Console\AdminLogService;
use App\Services\Pros\System\AmapAreaService;

/**
 * 高德地图行政地区接口逻辑服务容器
 * Class AmapAreaService
 * @package App\Interfaces\Pros\Console\Services
*/
class AmapAreaInterfaceService extends BaseService
{

    /**
    * 引入父级构造
    * AmapAreaInterfaceService constructor.
    * @param bool $pass 是否直接获取结果
    */
    public function __construct($pass = false) { parent::__construct($pass); }

    /**
     * 同步最新高德地图行政地区信息
     * @Author Abnermouke <abnermouke@outlook.com>
     * @Originate in Abnermouke's MBP
     * @Time 2022-10-25 11:59:15
     * @param $request
     * @return array|bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     */
    public function sync($request)
    {
        //获取加密信息
        if (!$data = AesLibrary::decryptFormData($request->all())) {
            //返回失败
            return $this->fail(CodeLibrary::DATA_MISSING,'非法参数');
        }
        //判断是否上传key
        if ($amap_web_server_api_key = trim(data_get($data, '__data__.AMAP_WEB_SERVER_API_KEY', ''))) {
            //保存配置
            (new ConfigRepository())->update(['alias' => 'AMAP_WEB_SERVER_API_KEY'], ['content' => $amap_web_server_api_key, 'updated_at' => auto_datetime()]);
            //添加日志
            (new AdminLogService())->record('配置高德地图WEB服务API KEY', compact('amap_web_server_api_key'));
            //刷新缓存
            (new ConfigCacheHandler())->refresh();
        }
        //继续同步
        if (!($service = new AmapAreaService())->sync()) {
            //返回错误
            return $this->fail($service->getCode(), $service->getMessage());
        }
        //返回成功
        return $this->success($service->getResult());
    }
}
