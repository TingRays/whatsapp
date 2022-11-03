<!DOCTYPE html>
<html lang="{{ config('app.locale', 'zh') == 'zh-cn' ? 'zh' : config('app.locale', 'zh') }}">
@php
    $console_configs = (new \App\Handler\Cache\Data\Pros\System\ConfigCacheHandler())->get();
@endphp
<head>
    <title>隐私政策</title>
    <meta charset="utf-8" />
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
    {{--页面样式--}}
    <link rel="stylesheet" href="{{ proxy_assets('console/themes/default/css/pages/oauth/sign-in.css', 'pros') }}">
</head>
<body id="pros_body">
    <div class="card">
        <div class="card-body p-lg-17">
            <div class="mb-18">
                <div class="mb-10">
                    <div class="text-center mb-15">
                        <h3 class="fs-2hx text-dark mb-5">服务条款</h3>
                        <div class="fs-5 fw-semibold text-gray-600">
                            {{$body}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="{{ proxy_assets('console/themes/default/js/pages/oauth/sign-in.js', 'pros') }}"></script>
</html>
