{{-- 继承模版 --}}
@extends('pros.console.layouts.master')

{{-- 页面标题 --}}
@section('title', '控制台首页')

{{-- 自定义页面样式 --}}
@section('styles')

@endsection

{{-- 自定义主体内容 --}}
@section('container')
    <div class="row g-5 g-xl-10 mb-10">
        <div class="col-xl-3">
            <div class="card card-flush h-xl-100">
                <div class="card-header rounded bgi-no-repeat bgi-size-cover bgi-position-y-top bgi-position-x-center align-items-start h-250px" style="background-image:url('{{ proxy_assets('console/themes/default/media/svg/shapes/top-green.png', 'pros') }}'">
                    <h3 class="card-title align-items-start flex-column text-white pt-15">
                        <span class="fw-bold fs-2x mb-3">欢迎您, {{ current_auth('nickname', config('pros.session_prefix')) }}</span>
                        <div class="fs-4 text-white">
                            {!! (new \App\Handler\Cache\Data\Abnermouke\Builders\SentenceCacheHandler())->random() !!}
                        </div>
                    </h3>
                </div>
                <div class="card-body mt-n20">
                    <div class="mt-n20 position-relative">
                        <div class="row g-3 g-lg-6">
                            <div class="col-6">
                                <div class="bg-gray-100 bg-opacity-70 rounded-2 px-6 py-5">
                                    <div class="symbol symbol-200px me-5 mb-8">
                                        <span class="fa fa-users text-primary"></span>
                                    </div>
                                    <div class="m-0">
                                        <span class="text-gray-700 fw-bolder d-block fs-2qx lh-1 ls-n1 mb-1">{{ (new \App\Repository\Pros\Console\AdminRepository())->count() }}</span>
                                        <span class="text-gray-500 fw-semibold fs-6">管理员数量</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="bg-gray-100 bg-opacity-70 rounded-2 px-6 py-5">
                                    <div class="symbol symbol-200px me-5 mb-8">
                                        <span class="fa fa-id-card-alt text-danger"></span>
                                    </div>
                                    <div class="m-0">
                                        <span class="text-gray-700 fw-bolder d-block fs-2qx lh-1 ls-n1 mb-1">{{ (new \App\Repository\Pros\Console\RoleRepository())->count() }}</span>
                                        <span class="text-gray-500 fw-semibold fs-6">权限角色</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="bg-gray-100 bg-opacity-70 rounded-2 px-6 py-5">
                                    <div class="symbol symbol-200px me-5 mb-8">
                                        <span class="fa fa-pencil-ruler text-warning"></span>
                                    </div>
                                    <div class="m-0">
                                        <span class="text-gray-700 fw-bolder d-block fs-2qx lh-1 ls-n1 mb-1">{{ (new \App\Repository\Pros\Console\NodeRepository())->count() }}</span>
                                        <span class="text-gray-500 fw-semibold fs-6">操作节点</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="bg-gray-100 bg-opacity-70 rounded-2 px-6 py-5">
                                    <div class="symbol symbol-200px me-5 mb-8">
                                        <span class="fa fa-cogs text-success"></span>
                                    </div>
                                    <div class="m-0">
                                        <span class="text-gray-700 fw-bolder d-block fs-2qx lh-1 ls-n1 mb-1">{{ (new \App\Repository\Pros\System\ConfigRepository())->count() }}</span>
                                        <span class="text-gray-500 fw-semibold fs-6">配置文件</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-7">
            <div class="row">
                <div class="col-sm-6 col-xl-4 mb-10">
                    <div class="card h-lg-100">
                        <div class="card-body d-flex justify-content-between align-items-start flex-column">
                            <div class="m-0">
                                <i class="fa fa-users fs-3x text-dark"></i>
                            </div>
                            <div class="d-flex flex-column my-3">
                                <span class="fw-bold fs-3x my-3 text-gray-800 lh-1 ls-n2">{{ rand(1000, 20000) }}</span>
                                <div class="m-0">
                                    <p class="fw-bold fs-6 text-gray-400 mb-1">用户访问量</p>
                                    <p class="fw-bold fs-8 text-gray-400">User Browser Count</p>
                                </div>
                            </div>
                            <span class="badge badge-dark fs-base ps-3">
								昨日 0 日环比 0%
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-4 mb-10">
                    <div class="card h-lg-100">
                        <div class="card-body d-flex justify-content-between align-items-start flex-column">
                            <div class="m-0">
                                <i class="fa fa-wallet fs-3x text-success"></i>
                            </div>
                            <div class="d-flex flex-column my-3">
                                <span class="fw-bold fs-3x my-3 text-gray-800 lh-1 ls-n2">{{ rand(0, 200) }}</span>
                                <div class="m-0">
                                    <p class="fw-bold fs-6 text-gray-400 mb-1">账号数量</p>
                                    <p class="fw-bold fs-8 text-gray-400">Order Count</p>
                                </div>
                            </div>
                            <span class="badge badge-success fs-base ps-3">
								昨日 2 日环比 +200%<span class="mx-2 fa fa-arrow-up fs-5"></span>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-4 mb-10">
                    <div class="card h-lg-100">
                        <div class="card-body d-flex justify-content-between align-items-start flex-column">
                            <div class="m-0">
                                <i class="fa fa-eye fs-3x text-success"></i>
                            </div>
                            <div class="d-flex flex-column my-3">
                                <span class="fw-bold fs-3x my-3 text-gray-800 lh-1 ls-n2">{{ rand(1000, 100000) }}</span>
                                <div class="m-0">
                                    <p class="fw-bold fs-6 text-gray-400 mb-1">发送数量</p>
                                    <p class="fw-bold fs-8 text-gray-400">Website Browser Count</p>
                                </div>
                            </div>
                            <span class="badge badge-success fs-base ps-3">
								环比增长 +23.96%<span class="mx-2 fa fa-arrow-up fs-5"></span>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-4 mb-10 mb-xl-0">
                    <div class="card h-lg-100">
                        <div class="card-body d-flex justify-content-between align-items-start flex-column">
                            <div class="m-0">
                                <i class="fa fa-user-circle fs-3x text-success"></i>
                            </div>
                            <div class="d-flex flex-column my-3">
                                <span class="fw-bold fs-3x my-3 text-gray-800 lh-1 ls-n2">{{ rand(10000, 100000) }}</span>
                                <div class="m-0">
                                    <p class="fw-bold fs-6 text-gray-400 mb-1">累计回复数量</p>
                                    <p class="fw-bold fs-8 text-gray-400">Total User Count</p>
                                </div>
                            </div>
                            <span class="badge badge-success fs-base ps-3">
								环比增长 +3.02%<span class="mx-2 fa fa-arrow-up fs-5"></span>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-xl-8 mb-10 mb-xl-0">
                    <div class="card h-lg-100">
                        <div class="card-body d-flex justify-content-between align-items-start flex-column">
                            <div class="m-0">
                                <i class="fa fa-money-bill-wave fs-3x text-danger"></i>
                            </div>
                            <div class="d-flex flex-column my-3">
                                <div class="fw-bold fs-3x my-3 text-gray-800 lh-1 ls-n2">{{ number_format(rand(1000000, 400000000)/100, 2) }}</div>
                                <div class="m-0">
                                    <p class="fw-bold fs-6 text-gray-400 mb-1">营业额</p>
                                    <p class="fw-bold fs-8 text-wrap text-gray-400">商品支付金额、充值金额、购买付费会员金额、线下收银金额</p>
                                </div>
                            </div>
                            <span class="badge badge-danger fs-base ps-3">
								环比减少 -63.54%<span class="mx-2 fa fa-arrow-down fs-5"></span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2">
            <div class="card card-flush h-xl-100">
                <div class="card-body d-flex flex-column justify-content-between pt-10 bgi-no-repeat bgi-size-cover bgi-position-x-center pb-0" style="background-position: 100% 50%; background-image:url('{{ proxy_assets('console/themes/default/media/stock/900x600/42.png', 'pros') }}')">
                    <div class="mb-5">
                        <div class="fs-2hx fw-bold text-gray-800 text-center mb-13">
                            <p class="fs-2 fs-lg-1">一个不错的工具</p>
                            <p class="text-danger fw-bold fs-2 fs-lg-1">Production</p>
                            <p class="text-muted fs-10">亩产三万六千九百斤。肥猪赛大象，全村宰一头,足够吃半年</p>
                        </div>
{{--                        <div class="text-center">--}}
{{--                            <a href="https://github.com/abnermouke/pros" target="_blank" class="btn btn-sm btn-dark fw-bold">Star On Github</a>--}}
{{--                        </div>--}}
                    </div>
                    <img class="mx-auto h-sm-200px h-xl-125px" src="{{ proxy_assets('console/themes/default/media/illustrations/misc/upgrade.png', 'pros') }}" alt="">
                </div>
            </div>
        </div>
    </div>
    <div class="row g-5 g-xl-10">
        <div class="col-xl-2 mb-10">
            <div class="card">
                <div class="card-body py-5">
                    <a href="{{ route('pros.console.admins.index') }}" target="_blank" class="d-flex flex-column justify-content-center">
                        <div class="icon_box mb-5 text-center">
                            <i class="fa fa-user-cog fs-3x text-info"></i>
                        </div>
                        <p class="text-dark fs-5 text-center my-0">管理员管理</p>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-xl-2 mb-10">
            <div class="card">
                <div class="card-body py-5">
                    <a href="{{ route('pros.console.admins.logs.index') }}" target="_blank" class="d-flex flex-column justify-content-center">
                        <div class="icon_box mb-5 text-center">
                            <i class="fa fa-list fs-3x text-primary"></i>
                        </div>
                        <p class="text-dark fs-5 text-center my-0">操作日志</p>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-xl-2 mb-10">
            <div class="card">
                <div class="card-body py-5">
                    <a href="{{ route('pros.console.systems.amap.areas') }}" target="_blank" class="d-flex flex-column justify-content-center">
                        <div class="icon_box mb-5 text-center">
                            <i class="fa fa-map fs-3x text-success"></i>
                        </div>
                        <p class="text-dark fs-5 text-center my-0">行政地区</p>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-xl-2 mb-10">
            <div class="card">
                <div class="card-body py-5">
                    <a href="{{ route('pros.console.systems.configs.index') }}" target="_blank" class="d-flex flex-column justify-content-center">
                        <div class="icon_box mb-5 text-center">
                            <i class="fa fa-cogs fs-3x text-danger"></i>
                        </div>
                        <p class="text-dark fs-5 text-center my-0">系统配置</p>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-xl-2 mb-10">
            <div class="card">
                <div class="card-body py-5">
                    <a href="{{ route('pros.console.admins.roles.index') }}" target="_blank" class="d-flex flex-column justify-content-center">
                        <div class="icon_box mb-5 text-center">
                            <i class="fa fa-id-card-alt fs-3x text-dark"></i>
                        </div>
                        <p class="text-dark fs-5 text-center my-0">权限角色</p>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-xl-2 mb-10">
            <div class="card">
                <div class="card-body py-5">
                    <a href="{{ route('pros.console.systems.sms.index') }}" target="_blank" class="d-flex flex-column justify-content-center">
                        <div class="icon_box mb-5 text-center">
                            <i class="fa fa-phone fs-3x text-warning"></i>
                        </div>
                        <p class="text-dark fs-5 text-center my-0">短信日志</p>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="row g-5 g-xl-10">
        <div class="col-xl-12 mb-xl-10">
            <div class="card card-bordered overflow-hidden h-lg-100">
                <div class="card-header py-2">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold-text-dark fs-1">订单量趋势</span>
                        <span class="text-gray-800 mt-3 fw-light fs-6">平台累计订单金额：<b class="text-success fs-3 fw-bold">{{ number_format(rand(1000000, 400000000)/100, 2) }}</b> </span>
                    </h3>
                </div>
                <div class="card-body d-flex align-items-center px-0">
                    <div id="order_chats" class="h-350px min-h-auto w-100 ps-4 pe-6" data-dates="{{ json_encode($statistics['dates']) }}" data-orders="{{ json_encode($statistics['orders']) }}" data-order-max="{{ $statistics['max']['order'] }}"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="row g-5 g-xl-10">
        <div class="col-xl-12 mb-xl-10">
            <div class="card card-bordered overflow-hidden h-lg-100">
                <div class="card-header py-2">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold-text-dark fs-1">用户概况</span>
                        <span class="text-gray-800 mt-3 fw-light fs-6">平台累计用户数量：<b class="text-primary fs-3 fw-bold">{{ rand(10000, 100000) }}</b> </span>
                    </h3>
                </div>
                <div class="card-body d-flex align-items-center px-0">
                    <div id="user_chats" class="h-350px min-h-auto w-100 ps-4 pe-6" data-dates="{{ json_encode($statistics['dates']) }}" data-users="{{ json_encode($statistics['users']) }}" data-user-max="{{ $statistics['max']['user'] }}"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

{{-- 自定义页面弹窗 --}}
@section('popups')

@endsection

{{-- 自定义页面javascript --}}
@section('script')
    <script src="{{ proxy_assets('console/themes/default/js/pages/index/index.js', 'pros') }}"></script>
@endsection
