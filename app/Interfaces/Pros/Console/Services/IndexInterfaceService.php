<?php
/**
 * Power by abnermouke/easy-builder.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in Yunni Technology Co Ltd.
 * Date: 2022-07-22
 * Time: 14:45:37
 */

namespace App\Interfaces\Pros\Console\Services;

use Abnermouke\EasyBuilder\Module\BaseService;

/**
 * 首页接口逻辑服务容器
 * Class IndexService
 * @package App\Interfaces\Pros\Console\Services
 */
class IndexInterfaceService extends BaseService
{

    /**
     * 引入父级构造
     * IndexInterfaceService constructor.
     * @param bool $pass 是否直接获取结果
     */
    public function __construct($pass = false) { parent::__construct($pass); }

    /**
     * 获取首页基本信息
     * @Author Abnermouke <abnermouke@outlook.com>
     * @Originate in Abnermouke's MBP
     * @Time 2022-10-25 11:59:15
     * @param $request
     * @return array|bool
     * @throws \Exception
     */
    public function informations($request)
    {
        //设置统计假数据
        $dates = $users = $orders = $max = [];
        //获取倒计15天数据
        for ($i = 15; $i > 0; $i--) {
            //设置日期信息
            $dates[] = auto_datetime('Y-m-d', strtotime('-'.$i.'day'));
            //添加价格信息
            $users['insert'][] = rand(20, 100);
            $users['browser'][] = rand(200, 400);
            $users['paid'][] = rand(10, 80);
            $users['charge'][] = rand(10, 80);
            $users['insert_paid'][] = rand(5, 80);
            //添加订单信息
            $orders['pay'][] = rand(100000, 150000)/100;
            $orders['total'][] = rand(150000, 300000)/100;
            $orders['charge'][] = rand(100000, 150000)/100;
        }
        //设置最大信息
        $max = ['user' => 450, 'order' => 3200];
        //返回信息
        return $this->success(['statistics' => compact('dates', 'users', 'orders', 'max')]);
    }
}
