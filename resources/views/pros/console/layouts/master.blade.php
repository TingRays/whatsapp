<!DOCTYPE html>
<!--
Author: Abnermouke <abnerouke@outlook.com>
Product Description: abnermouke/pros - Pros是Abnermouke倾心打造的一款高效的Laravel项目启动方案，无需重复构建基础结构/框架，开发高效，体验优良！
Github: https://github.com/abnermouke/pro
Contact: abnermouke@outlook.com
-->
<html lang="{{ config('app.locale', 'zh') == 'zh-cn' ? 'zh' : config('app.locale', 'zh') }}">
    <head>
        <title>@yield('title', '控制台') - {{ (new \App\Handler\Cache\Data\Pros\System\ConfigCacheHandler())->get('APP_NAME', 'PROS') }}</title>
        <meta charset="utf-8" />
        <meta name="description" content="{{ (new \App\Handler\Cache\Data\Pros\System\ConfigCacheHandler())->get('APP_DESCRIPTION', 'PROS') }}" />
        <meta name="keywords" content="{{ implode(',', (new \App\Handler\Cache\Data\Pros\System\ConfigCacheHandler())->get('APP_KEYWORDS', [])) }}" />
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
        <link rel="shortcut icon" href="{{ (new \App\Handler\Cache\Data\Pros\System\ConfigCacheHandler())->get('APP_SHORTCUT_ICON', proxy_assets('static/medias/logos/favicon.png', 'pros')) }}" />
        {{-- 自定义样式 --}}
        @yield('styles')
    </head>
    <body id="pros_body" class="header-fixed header-tablet-and-mobile-fixed toolbar-enabled toolbar-fixed aside-enabled aside-fixed" style="--kt-toolbar-height:55px;--kt-toolbar-height-tablet-and-mobile:55px" data-kt-aside-minimize="on">
        <div class="d-none" id="kt_permission_nodes_box">{!! json_encode((new \App\Repository\Pros\Console\NodeRepository())->pluck('alias'), JSON_UNESCAPED_UNICODE) !!}</div>
        <div class="d-flex flex-column flex-root">
            <div class="page d-flex flex-row flex-column-fluid">
                {{-- 引入左侧菜单栏 --}}
                @include('pros.console.layouts.aside')
                <div class="wrapper d-flex flex-column flex-row-fluid" id="kt_wrapper">
                    {{-- 引入顶部 --}}
                    @include('pros.console.layouts.header')
                    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
                        {{-- 引入工具栏 --}}
                        {{-- @include('pros.console.layouts.toolbars')--}}
                        <div id="kt_content_container" class="container-fluid">
                            {{-- 自定义主体内容 --}}
                            @yield('container')
                        </div>
                    </div>
                    {{-- 引入底部 --}}
                    @include('pros.console.layouts.footer')
                </div>
            </div>
        </div>
        {{-- 引入右部提示 --}}
        @include('pros.console.layouts.right_tip')
        {{-- 引入滑动到顶部 --}}
        @include('pros.console.layouts.scrolltop')
        {{-- 引入公共弹窗 --}}
        @include('pros.console.layouts.popups')
        {{-- 自定义弹窗 --}}
        @yield('popups')
    </body>
    {{-- 自定义javascript --}}
    @yield('script')
</html>
