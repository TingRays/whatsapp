<?php

/**
 * Power by abnermouke/pros.
 * User: Abnermouke <abnermouke@outlook.com>
 * Originate in YunniTec.
 */

return [


    /*
   |--------------------------------------------------------------------------
   | Pros default setting and Basic setting
   |--------------------------------------------------------------------------
   |
   | The default pros settings
   |
   */

    //Session前缀
    'session_prefix' => 'abnermouke:pros:console:auth',

    //节点配置信息
    'nodes' => [
        //检索命名空间（可指定多个目录）
        'index_namespaces' => [
            //ConsoleBuilder 默认文件目录
            'App\Interfaces\Pros\Console\Controllers'
        ],
        //检索中间件标识或中间件Class地址（可指定多个中间件）
        'index_route_middleware' => ['abnermouke.pros.console.auth'],
        //排除不检测路由（可指定多个路由）
        'ignore_route_names' => [],
        //默认必须存在的路由（可指定多个路由）
        'default_node_aliases' => ['get&pros.console.index', 'post&pros.console.admins.change.password', 'post&pros.console.uploader', 'get&pros.console.uploader.ueditor'],
        //控制器组名后缀（可指定多个），移除后缀后所剩内容为该控制器权限组名
        'controller_group_name_suffix' => '基础控制器',
    ],

    //表格构建器配置信息
    'table' => [
        //默认列表单页获取条数
        'default_page_size' => 20,
        //默认列表开始页码
        'default_page' => 1,
    ],

    //百度编辑器上传配置
    'ueditor_upload' => [
        /* 图片上传配置 */
        "imageActionName" => "uploadimage",
        "imageFieldName" => "upfile",
        "imageMaxSize" => 104857600,
        "imageAllowFiles" => [".png", ".jpg", ".jpeg", ".gif", ".bmp"],
        "imageCompressEnable" => true,
        "imageCompressBorder" => 620,
        "imageInsertAlign" => "none",
        "imageUrlPrefix" => "",
        "imagePathFormat" => "pros/ueditor/images",

        /* 上传视频配置 */
        "videoActionName" => "uploadmedia",
        "videoFieldName" => "upfile",
        "videoPathFormat" => "pros/ueditor/medias",
        "videoUrlPrefix" => "",
        "videoMaxSize" => 10737418240,
        "videoAllowFiles" => [
            ".flv", ".swf", ".mkv", ".avi", ".rm", ".rmvb", ".mpeg", ".mpg",
            ".ogg", ".ogv", ".mov", ".wmv", ".mp4", ".webm", ".mp3", ".wav", ".mid", ".mpga"
        ],

        /* 上传文件配置 */
        "fileActionName" => "uploadfile",
        "fileFieldName" => "upfile",
        "filePathFormat" => "pros/ueditor/files",
        "fileUrlPrefix" => "",
        "fileMaxSize" => 10737418240,
        "fileAllowFiles" => [
            ".png", ".jpg", ".jpeg", ".gif", ".bmp",
            ".flv", ".swf", ".mkv", ".avi", ".rm", ".rmvb", ".mpeg", ".mpg",
            ".ogg", ".ogv", ".mov", ".wmv", ".mp4", ".webm", ".mp3", ".wav", ".mid",
            ".rar", ".zip", ".tar", ".gz", ".7z", ".bz2", ".cab", ".iso",
            ".doc", ".docx", ".xls", ".xlsx", ".ppt", ".pptx", ".pdf", ".txt", ".md", ".xml", ".mpga"
        ],
    ],

];
