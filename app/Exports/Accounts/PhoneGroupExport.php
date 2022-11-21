<?php

namespace App\Exports\Accounts;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

/**
 * 错误用户导出
 */
class PhoneGroupExport implements FromArray, WithColumnWidths
{
    //用户数据
    private $accounts;

    /**
     * 构造函数
     * ExpressesExport constructor.
     * @param $accounts
     */
    public function __construct($accounts)
    {
        //设置订单信息
        $this->accounts = $accounts;
    }

    /**
     * 整理数据
     * @Author Abnermouke <abnermouke@outlook.com>
     * @Originate in Abnermouke's MBP
     * @Time 2022-08-28 11:50:15
     * @return \string[][]
     * @throws \Exception
     */
    public function array(): array
    {
        //整理数据
        $data = [
            ['First name', 'Last name', 'Email', 'Country code', 'Phone', 'Labels', 'Notes', 'Custom Field 1', 'Campo Personalizado 2'],
        ];
        //循环订单集合
        foreach ($this->accounts as $k => $account) {
            //设置数据
            $data[] = $account;
        }
        //返回数据
        return $data;
    }

    /**
     * 自定义每栏宽度
     * @Author Abnermouke <abnermouke@outlook.com>
     * @Originate in Abnermouke's MBP
     * @Time 2022-08-28 04:19:24
     * @return int[]
     */
    public function columnWidths(): array
    {
        //返回各宽度
        return [
            'A' => 20,
            'B' => 15,
            'C' => 20,
            'D' => 15,
            'E' => 20,
            'F' => 20,
            'G' => 20,
            'H' => 20,
            'I' => 20,
        ];
    }
}
