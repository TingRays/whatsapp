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
    <title>登录 - {{ $console_configs['APP_NAME'] }}</title>
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
    {{--页面样式--}}
    <link rel="stylesheet" href="{{ proxy_assets('console/themes/default/css/pages/oauth/sign-in.css', 'pros') }}">
</head>
<body id="pros_body">
    <div class="login-video-bg">
        @switch((int)date('m'))
            @case(1)
            @case(2)
            @case(11)
            @case(12)
                <video src="{{ proxy_assets('static/medias/videos/background_winter.mp4', 'pros') }}" muted="" loop="" autoplay=""></video>
            @break
            @default
            <video src="{{ proxy_assets('static/medias/videos/backgrounds/'.rand(1, count(\Illuminate\Support\Facades\File::allFiles(public_path('pros/static/medias/videos/backgrounds')))).'.mp4', 'pros') }}" muted="" loop="" autoplay=""></video>
            @break
        @endswitch
        <div class="mask"></div>
    </div>
    <div class="d-flex flex-column flex-root z-index-1">
        <div class="d-flex flex-column flex-column-fluid bgi-position-y-bottom position-x-center bgi-no-repeat bgi-size-contain bgi-attachment-fixed">
            <div class="d-flex flex-column flex-column-fluid mx-auto my-auto">
                <div class="w-lg-500px bg-body rounded shadow-sm p-10 p-lg-15 my-auto mx-auto" id="sign-in_box">
                    <form class="form w-100" id="sign-in_form" method="post" onsubmit="autoLogin($(this));return false;" data-query-login="{{ route('pros.console.oauth.sign.in') }}" data-redirect-uri="{{ $redirect_uri }}">
                        <div class="text-center mb-10">
                            <h1 class="text-dark mb-3">{!! $console_configs['APP_NAME'] !!}</h1>
                            <div class="text-gray-400 fw-bold fs-8">
                                {!! $console_configs['APP_DESCRIPTION'] !!}
                            </div>
                        </div>
                        <div class="fv-row mb-10">
                            <label class="form-label fs-6 fw-bolder text-dark">用户名</label>
                            <input class="form-control form-control-lg form-control-solid" type="text" required id="username" name="username" autocomplete="off" />
                        </div>
                        <div class="fv-row mb-10">
                            <div class="d-flex flex-stack mb-2">
                                <label class="form-label fw-bolder text-dark fs-6 mb-0">密码</label>
                            </div>
                            <input class="form-control form-control-lg form-control-solid" type="password" required id="password" name="password" autocomplete="off" />
                        </div>
                        <div class="text-center">
                            <button type="submit" id="sign-in_submit" class="btn btn-lg btn-primary w-100 mb-5 btn-hover-scale">立即登录</button>
                            @if($console_configs['WECHAT_OFFICE_ACCOUNT_PARAMS']['app_id'] && (int)$console_configs['CONSOLE_WECHAT_OAUTH'] === \App\Model\Pros\System\Configs::SWITCH_ON)
                                <div class="text-center text-muted text-uppercase fw-bolder mb-5">或者</div>
                                <a href="javascript:;" id="sign-in_wechat" data-query-signature="{{ route('pros.console.oauth.wechat.sign.in') }}" data-query-check="" class="btn btn-flex flex-center btn-light btn-lg w-100 mb-5 btn-hover-scale">
                                    <img alt="Logo" src="{{ proxy_assets('console/themes/default/media/svg/brand-logos/wechat.svg', 'pros') }}" class="h-20px me-3" />使用微信授权登录
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @if($console_configs['WECHAT_OFFICE_ACCOUNT_PARAMS']['app_id'] && (int)$console_configs['CONSOLE_WECHAT_OAUTH'] === \App\Model\Pros\System\Configs::SWITCH_ON)
        <div class="modal fade" id="modal_wechat_qr" tabindex="-1" style="display: none;" aria-hidden="true">
            <div class="modal-dialog mw-650px">
                <div class="modal-content">
                    <div class="modal-header pb-0 border-0 justify-content-end">
                        <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                                    <span class="svg-icon svg-icon-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="black"></rect>
                                            <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="black"></rect>
                                        </svg>
                                    </span>
                        </div>
                    </div>
                    <div class="modal-body scroll-y mx-5 mx-xl-18 pt-0 pb-15">
                        <div class="text-center mb-13">
                            <h1 class="mb-3">微信扫描二维码</h1>
                            <div class="text-muted fw-bold fs-5">打开手机微信APP，扫描屏幕显示的二维码或保存本地后识别</div>
                        </div>
                        <div class="btn btn-light-primary fw-bolder w-100 mb-8" data-bs-dismiss="modal">继续普通登录</div>
                        <div class="separator d-flex flex-center mb-8">
                            <span class="text-uppercase bg-body fs-7 fw-bold text-muted px-3">微信二维码</span>
                        </div>
                        <div class="mb-10 text-center">
                            <img src="" id="wechat_qrcode" class="h-300px" alt="">
                        </div>
                        <div class="d-flex flex-stack">
                            <div class="me-5 fw-bold">
                                <label class="fs-6">系统检测到登录状态后自动跳转</label>
                                <div class="fs-7 text-muted">扫码后请留意授权确认后提示信息是否为绑定成功。</div>
                            </div>
                            <label class="form-check form-switch form-check-custom form-check-solid">
                                <input class="form-check-input" type="checkbox" value="1" disabled checked="checked">
                                <span class="form-check-label fw-bold text-muted">同意</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</body>
<script src="{{ proxy_assets('console/themes/default/js/pages/oauth/sign-in.js', 'pros') }}"></script>
</html>
