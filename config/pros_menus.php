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
                    ],
                    [
                        'guard_name' => '系统配置',
                        'handler' => '',
                        'route' => ['name' => 'pros.console.systems.configs.index', 'params' => []],
                        'permission_nodes' => [],
                        'icon' => 'fa fa-cogs',
                    ],
                    [
                        'guard_name' => '行政区域',
                        'handler' => '',
                        'route' => ['name' => 'pros.console.systems.amap.areas', 'params' => []],
                        'permission_nodes' => [],
                        'icon' => 'fa fa-map',
                    ],
                    [
                        'guard_name' => '管理团队',
                        'handler' => '',
                        'route' => ['name' => 'pros.console.admins.index', 'params' => []],
                        'permission_nodes' => [],
                        'icon' => 'fa fa-users',
                        'menus' => [
                            [
                                'guard_name' => '管理员',
                                'handler' => '',
                                'route' => ['name' => 'pros.console.admins.index', 'params' => []],
                                'permission_nodes' => [],
                                'icon' => 'fa fa-user',
                            ],
                            [
                                'guard_name' => '权限角色',
                                'handler' => '',
                                'route' => ['name' => 'pros.console.admins.roles.index', 'params' => []],
                                'permission_nodes' => [],
                                'icon' => 'fa fa-id-card-alt',
                            ],
                        ],
                    ],
                    [
                        'guard_name' => '操作记录',
                        'handler' => '',
                        'route' => ['name' => '', 'params' => []],
                        'permission_nodes' => [],
                        'icon' => 'fa fa-list',
                        'menus' => [
                            [
                                'guard_name' => '管理员日志',
                                'handler' => '',
                                'route' => ['name' => 'pros.console.admins.logs.index', 'params' => []],
                                'permission_nodes' => [],
                                'icon' => 'fa fa-list-ol',
                            ],
                            [
                                'guard_name' => '短信日志',
                                'handler' => '',
                                'route' => ['name' => 'pros.console.systems.sms.index', 'params' => []],
                                'permission_nodes' => [],
                                'icon' => 'fa fa-sms',
                            ],
                        ],
                    ],
                    [
                        'guard_name' => '帮助文档',
                        'handler' => '',
                        'route' => ['name' => 'pros.console.help.docs.index', 'params' => []],
                        'permission_nodes' => [],
                        'icon' => 'fa fa-file-word',
                    ],
                ]
            ],
        ],
        [
            'guard_name' => 'WhatsApp',
            'handler' => '',
            'route' => ['name' => 'whatsapp.console.bm.index', 'params' => []],
            'permission_nodes' => ['whatsapp.console.merchant.index','whatsapp.console.merchant.all.index'],
            'icon' => '',
            'group_menus' => [
                [
                    [
                        'guard_name' => 'BM账户管理',
                        'handler' => '',
                        'route' => ['name' => '', 'params' => []],
                        'permission_nodes' => ['whatsapp.console.merchant.index','whatsapp.console.merchant.all.index'],
                        'icon' => 'fa fa-store',
                        'menus' => [
                            [
                                'guard_name' => 'BM账户',
                                'handler' => '',
                                'route' => ['name' => 'whatsapp.console.bm.index', 'params' => []],
                                'permission_nodes' => ['whatsapp.console.merchant.index','whatsapp.console.merchant.all.index'],
                                'icon' => 'fa fa-user',
                            ],
                            [
                                'guard_name' => 'BM导入',
                                'handler' => '',
                                'route' => ['name' => 'whatsapp.console.bm.posts', 'params' => []],
                                'permission_nodes' => [],
                                'icon' => 'fa fa-car',
                            ],
                        ],
                    ],
                    [
                        'guard_name' => '模板管理',
                        'handler' => '',
                        'route' => ['name' => 'whatsapp.console.template.index', 'params' => []],
                        'permission_nodes' => [],
                        'icon' => 'fa fa-envelope',
                    ],
                    [
                        'guard_name' => '消息群发',
                        'handler' => '',
                        'route' => ['name' => 'whatsapp.console.merchant.message.index', 'params' => []],
                        'permission_nodes' => ['whatsapp.console.merchant.message.detail'],
                        'icon' => 'fa fa-envelope',
                    ],
                    [
                        'guard_name' => '用户',
                        'handler' => '',
                        'route' => ['name' => '', 'params' => []],
                        'permission_nodes' => [],
                        'icon' => 'fa fa-users',
                        'menus' => [
                            [
                                'guard_name' => '用户管理',
                                'handler' => '',
                                'route' => ['name' => 'whatsapp.console.account.index', 'params' => []],
                                'permission_nodes' => [],
                                'icon' => 'fa fa-user',
                            ],
                            [
                                'guard_name' => '导入用户',
                                'handler' => '',
                                'route' => ['name' => 'whatsapp.console.account.posts', 'params' => []],
                                'permission_nodes' => [],
                                'icon' => 'fa fa-car',
                            ],
                            [
                                'guard_name' => 'TXT文本导入用户',
                                'handler' => '',
                                'route' => ['name' => 'whatsapp.console.account.posts.txt', 'params' => []],
                                'permission_nodes' => [],
                                'icon' => 'fa fa-car',
                            ],
                            [
                                'guard_name' => '用户标签',
                                'handler' => '',
                                'route' => ['name' => 'whatsapp.console.account.tag.index', 'params' => []],
                                'permission_nodes' => [],
                                'icon' => 'fa fa-tag',
                            ],
                        ],
                    ],
                ]
            ],
        ],
        [
            'guard_name' => '虚拟号',
            'handler' => '',
            'route' => ['name' => 'whatsapp.console.fictitious.index', 'params' => []],
            'permission_nodes' => [],
            'icon' => '',
            'group_menus' => [
                [
                    [
                        'guard_name' => '虚拟手机生成',
                        'handler' => '',
                        'route' => ['name' => 'whatsapp.console.fictitious.index', 'params' => []],
                        'permission_nodes' => [],
                        'icon' => 'fa fa-bug',
                    ],
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
        [
            'guard_name' => '页面群发',
            'handler' => '',
            'route' => ['name' => 'whatsapp.console.mass_dispatch.index', 'params' => []],
            'permission_nodes' => [],
            'icon' => '',
            'group_menus' => [
                [
                    [
                        'guard_name' => '群发列表',
                        'handler' => '',
                        'route' => ['name' => 'whatsapp.console.mass_dispatch.index', 'params' => []],
                        'permission_nodes' => [],
                        'icon' => 'fa fa-asterisk',
                    ],
                ]
            ],
        ],
    ],

];
