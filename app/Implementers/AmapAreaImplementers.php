<?php

namespace App\Implementers;

use Abnermouke\EasyBuilder\Library\CodeLibrary;
use App\Model\Pros\System\AmapAreas;
use App\Repository\Pros\System\AmapAreaRepository;
use Illuminate\Support\Arr;
use GuzzleHttp\Client;

/**
 * 高德地图行政地区执行类
 * Class AmapAreaImplementers
 * @package App\Implementers\Amap
 */
class AmapAreaImplementers
{

    //请求种子链接
    private static $seed_link = 'https://restapi.amap.com/v3/config/district';

    //高德服务API KEY
    private static $amap_web_server_api_key;

    /**
     * 执行更新
     * @Author Abnermouke <abnermouke@outlook.com>
     * @Originate in Abnermouke's MBP
     * @Time 2022-07-22 23:08:53
     * @param string $amap_web_server_api_key
     * @return bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     */
    public static function run($amap_web_server_api_key = '')
    {
        //设置密钥
        self::$amap_web_server_api_key = $amap_web_server_api_key ? $amap_web_server_api_key : config('project.amap_web_server_api_key');
        //判断信息
        if (!self::$amap_web_server_api_key) {
            //返回失败
            return false;
        }
        //保存国家信息
        $area_id = self::storeArea($area_info = [
            'level' => AmapAreas::LEVEL_OF_COUNTRY,
            'code' => 100000,
            'guard_name' => '中华人民共和国',
            'parent_id' => 0,
            'coordinate' => ['116.3683244', '39.915085'],
            'names' => ['中华人民共和国'],
            'sub_area_count' => 0,
            'created_at' => auto_datetime(),
            'updated_at' => auto_datetime()
        ]);
        //查询行政地区
        if (!self::query($area_info['code'], $area_id, $area_info['level'], $area_info['names'])) {
            //返回失败
            return false;
        }
        //循环处理空地区信息
        for ($i = AmapAreas::LEVEL_OF_COUNTRY; $i < AmapAreas::LEVEL_OF_STREET; $i++) {
            //查询当前层级没有子集的地区
            if ($empty_areas = (new AmapAreaRepository())->get(['level' => (int)$i, 'sub_area_count' => 0], ['id', 'code', 'guard_name', 'names', 'coordinate'])) {
                //循环子集地区
                foreach ($empty_areas as $area) {
                    //获取名称信息
                    $names = object_2_array($area['names']);
                    //追加名称
                    array_unshift($names, '默认');
                    //保存默认子集信息
                    self::storeArea($area_info = [
                        'level' => $i + 1,
                        'code' => (int)$area['code'],
                        'guard_name' => '默认',
                        'parent_id' => (int)$area['id'],
                        'coordinate' => object_2_array($area['coordinate']),
                        'names' => $names,
                        'sub_area_count' => 0,
                        'created_at' => auto_datetime(),
                        'updated_at' => auto_datetime()
                    ]);
                }
            }
        }
        //返回成功
        return true;
    }

    /**
     * 查询地区信息
     * @Author Abnermouke <abnermouke@gmail.com>
     * @Originate in Abnermouke's MBP
     * @Time 2021-09-07 11:37:41
     * @param int $ad_code
     * @param int $area_id
     * @param int $area_level
     * @param array $area_names
     * @return false|int|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private static function query($ad_code = 0, $area_id = 0, $area_level = AmapAreas::LEVEL_OF_OTHER, $area_names = [])
    {
        //整理参数
        $params = [
            'keywords' => (int)$ad_code === 0 ? '' : (int)$ad_code,
            'key' => self::$amap_web_server_api_key,
            'subdistrict' => 1,
        ];
        //整理请求链接
        $query_link = self::$seed_link.(!empty($params) ? '?'.http_build_query($params) : '');
        //尝试发起请求
        try {
            //发起请求
            $response = (new Client())->get($query_link);
        } catch (\Exception $exception) {
            //返回失败
            return false;
        }
        //获取状态
        if ((int)$response->getStatusCode() !== CodeLibrary::CODE_SUCCESS) {
            //返回失败
            return false;
        }
        //获取结果集
        $results = $response->getBody()->getContents();
        //构建地址信息
        return self::buildArea(data_get(json_decode($results, true), 'districts.0.districts', []), ((int)$ad_code === 0 ? 100000 : (int)$ad_code), $area_id, $area_level, $area_names);
    }

    /**
     * 构建数据
     * @Author Abnermouke <abnermouke@gmail.com>
     * @Originate in Abnermouke's MBP
     * @Time 2021-09-03 15:22:14
     * @param $areas
     * @param int $parent_ad_code
     * @param int $area_id
     * @param int $area_level
     * @param array $area_names
     * @return int|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     */
    private static function buildArea($areas, $parent_ad_code = 0, $area_id, $area_level, $area_names = [])
    {
        //判断地区信息
        if ($areas) {
            //循环获取下级地区
            foreach ($areas as $k => $area) {
                //整理当前地区名称信息
                $names = $area_names;
                //新增当前地区信息
                array_unshift($names, $area['name']);
                //整理信息
                $area_info = [
                    'level' => (int)$area_level + 1,
                    'code' => (int)$area['adcode'],
                    'guard_name' => $area['name'],
                    'parent_id' => (int)$area_id,
                    'coordinate' => explode(',', $area['center']),
                    'names' => $names,
                    'sub_area_count' => 0,
                    'created_at' => auto_datetime(),
                    'updated_at' => auto_datetime()
                ];
                //保存地图信息
                $area_info['id'] = self::storeArea($area_info);
                //判断当前是否为街道
                if ($area_info['level'] < AmapAreas::LEVEL_OF_STREET && $area['level'] !== 'street') {
                    //获取下级
                    self::query($area['adcode'], (int)$area_info['id'], $area_info['level'], $area_info['names']);
                }
            }
        }
        //更新下级数量
        (new AmapAreaRepository())->update(['id' => (int)$area_id], ['sub_area_count' => (new AmapAreaRepository())->count(['parent_id' => (int)$area_id]), 'updated_at' => auto_datetime()]);
        //返回成功
        return $parent_ad_code;
    }

    /**
     * 保存地区信息
     * @Author Abnermouke <abnermouke@outlook.com>
     * @Originate in Abnermouke's MBP
     * @Time 2022-07-07 15:49:41
     * @param $area
     * @return array|int|mixed|string
     * @throws \Exception
     */
    private static function storeArea($area)
    {
        //查询基础查询功能
        $conditions = Arr::only($area, ['level', 'guard_name', 'parent_id']);
        //判断是否为省/市/国家地区
        if ((int)$area['level'] < AmapAreas::LEVEL_OF_AREA) {
            //添加查询条件
            $conditions['code'] = $area['code'];
        }
        //查询地区信息是否存在
        if (!$area_info = (new AmapAreaRepository())->row($conditions, ['id', 'guard_name'])) {
            //新增地区信息
            $area_id = (new AmapAreaRepository())->insertGetId($area);
        } else {
            //设置地区ID
            $area_id = (int)$area_info['id'];
            //判断地区名称是否修改
            if ($area['guard_name'] !== $area_info['guard_name']) {
                //更新地区信息
                (new AmapAreaRepository())->update(['id' => (int)$area_id], Arr::only($area, ['guard_name', 'coordinate', 'names', 'updated_at']));
            }
        }
        //返回地区ID
        return $area_id;
    }

}
