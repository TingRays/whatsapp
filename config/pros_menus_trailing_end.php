<?php

/**
 * Power by abnermouke/pros.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in YunniTec.
 */

return [


    /*
   |--------------------------------------------------------------------------
   | Pros default menus setting
   |--------------------------------------------------------------------------
   |
   | The default pros menus settings
   |
   */

    [

        [
            'guard_name' => '控制台',
            'handler' => '',
            'route' => ['name' => 'pros.console.index', 'params' => []],
            'permission_nodes' => [],
            'icon' => '',
            'group_menus' => [
                [
                    [
                        'guard_name' => '首页',
                        'handler' => '',
                        'route' => ['name' => 'pros.console.index', 'params' => []],
                        'permission_nodes' => [],
                        'icon' => 'fa fa-home',
                    ]
                ]
            ],
        ],
        [
            'guard_name' => '粉丝管理',
            'handler' => '',
            'route' => ['name' => 'whatsapp.console.fans_manage.index', 'params' => []],
            'permission_nodes' => [],
            'icon' => '',
            'group_menus' => [
                [
                    [
                        'guard_name' => '我的粉',
                        'handler' => '',
                        'route' => ['name' => 'whatsapp.console.fans_manage.index', 'params' => []],
                        'permission_nodes' => [],
                        'icon' => 'fa fa-asterisk',
                    ],
                    [
                        'guard_name' => '粉的管理分组',
                        'handler' => '',
                        'route' => ['name' => 'whatsapp.console.fans_manage.group.index', 'params' => []],
                        'permission_nodes' => [],
                        'icon' => 'fa fa-id-card-alt',
                    ],
                ]
            ],
        ],
    ],

];
