<!DOCTYPE html>
<!--
Author: Abnermouke <abnerouke@outlook.com>
Product Description: abnermouke/pros - Pros是Abnermouke倾心打造的一款高效的Laravel项目启动方案，无需重复构建基础结构/框架，开发高效，体验优良！
Github: https://github.com/abnermouke/pro
Contact: abnermouke@outlook.com
-->
<html lang="{{ config('app.locale', 'zh') == 'zh-cn' ? 'zh' : config('app.locale', 'zh') }}">
@php
    $console_configs = (new \App\Handler\Cache\Data\Pros\System\ConfigCacheHandler())->get();
@endphp
<head>
    <title>{{ $code }} - {{ $console_configs['APP_NAME'] }}</title>
    <meta charset="utf-8" />
    <meta name="description" content="{{ $console_configs['APP_DESCRIPTION'] }}" />
    <meta name="keywords" content="{{ implode(',', object_2_array($console_configs['APP_KEYWORDS'])) }}" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="canonical" href="{{ config('app.url') }}" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="website-url" content="{{ config('app.url') }}">
    <meta name="aes-iv" content="{{ config('project.aes.iv') }}">
    <meta name="aes-encrypt-key" content="{{ auto_datetime('Ymd').config('project.aes.encrypt_key_suffix') }}">
    <meta name="current_client_ip" content="{{ request()->getClientIp() }}">
    <meta name="current_route_name" content="{{ request()->route()->getName() }}">
    {{-- 全局样式：BEGIN --}}
    <link href="{{ proxy_assets('console/themes/default/plugins/global/plugins.bundle.css', 'pros') }}" rel="stylesheet" type="text/css" />
    <link href="{{proxy_assets('console/themes/default/css/style.bundle.css', 'pros')}}" rel="stylesheet" type="text/css" />
    <link href="{{ proxy_assets('console/themes/default/css/common.css', 'pros') }}" rel="stylesheet" type="text/css" />
    {{-- 全局样式：END --}}
    {{-- 全局JAVASCRIPT：BEGIN --}}
    <script src="{{ proxy_assets('console/themes/default/plugins/cryptojs/aes.js', 'pros') }}"></script>
    <script src="{{ proxy_assets('console/themes/default/plugins/cryptojs/pad-zeropadding-min.js', 'pros') }}"></script>
    <script src="{{ proxy_assets('console/themes/default/plugins/global/plugins.bundle.js', 'pros') }}"></script>
    <script src="{{ proxy_assets('console/themes/default/js/common.js', 'pros') }}"></script>
    <script src="{{ proxy_assets('console/themes/default/js/layouts.js', 'pros') }}"></script>
    <script src="{{ proxy_assets('console/themes/default/js/scripts.bundle.js', 'pros') }}"></script>
    {{-- 全局JAVASCRIPT：END --}}
    <link rel="shortcut icon" href="{{ $console_configs['APP_SHORTCUT_ICON'] }}" />
</head>
<body id="pros_body" class="auth-bg">
    <div class="d-flex flex-column flex-root">
        <div class="d-flex flex-column flex-center flex-column-fluid p-10">
            <img src="{{ proxy_assets('console/themes/default/media/illustrations/sketchy-1/18.png', 'pros') }}" alt="" class="mw-100 mb-10 h-lg-350px" />
            <h1 class="fw-bold mb-10" style="color: #A3A3C7">{{ '[ '.$code.' ] '.$message }}</h1>
            <a href="{{ $redirect_uri }}" class="btn btn-primary">立即返回</a>
        </div>
    </div>
</body>
</html>
