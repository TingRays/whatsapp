<?php
/**
 * Power by abnermouke/easy-builder.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in Yunni Technology Co Ltd.
 * Date: 2022-07-23
 * Time: 15:29:48
*/

namespace App\Services\Pros\Console;

use Abnermouke\EasyBuilder\Module\BaseService;
use App\Model\Pros\Console\AdminOauthSignatures;
use App\Repository\Pros\Console\AdminOauthSignatureRepository;
use Illuminate\Support\Str;

/**
 * 管理员授权签名逻辑服务容器
 * Class AdminOauthSignatureService
 * @package App\Services\Pros\Console
*/
class AdminOauthSignatureService extends BaseService
{

    /**
    * 引入父级构造
    * AdminOauthSignatureService constructor.
    * @param bool $pass 是否直接获取结果
    */
    public function __construct($pass = false) { parent::__construct($pass); }


    /**
     * 创建授权码
     * @Author Abnermouke <abnermouke@outlook.com>
     * @Originate in Abnermouke's MBP
     * @Time 2022-10-25 11:59:15
     * @param $type int 授权类型
     * @param array $params 验证参数
     * @return array|bool
     * @throws \Exception
     */
    public function create($type, $params = [])
    {
        //整理参数
        $params['timestamp'] = time();
        $params['nonceStr'] = Str::random(10);
        //排序字符串
        krsort($params);
        //生成签名
        $signature = md5(json_encode($params, JSON_NUMERIC_CHECK));
        //生成授权记录
        $oauth = [
            'type' => (int)$type,
            'signature' => strtolower($signature),
            'params' => $params,
            'admin_id' => ((int)$type === AdminOauthSignatures::TYPE_OF_BIND ? data_get($params, 'id', 0) : 0),
            'status' => AdminOauthSignatures::STATUS_VERIFYING,
            'results' => [],
            'created_at' => auto_datetime(),
            'updated_at' => auto_datetime()
        ];
        //创建记录
        (new AdminOauthSignatureRepository())->insertGetId($oauth);
        //返回基础信息
        return $this->success($signature);
    }
}
