<div id="kt_header" style="" class="header align-items-stretch">
    <div class="container-fluid d-flex align-items-stretch justify-content-between">
        <div class="d-flex align-items-center d-lg-none ms-n2 me-2" title="Show aside menu">
            <div class="btn btn-icon btn-active-light-primary w-30px h-30px w-md-40px h-md-40px" id="kt_aside_mobile_toggle">
                <span class="svg-icon svg-icon-1">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path d="M21 7H3C2.4 7 2 6.6 2 6V4C2 3.4 2.4 3 3 3H21C21.6 3 22 3.4 22 4V6C22 6.6 21.6 7 21 7Z" fill="black" />
                        <path opacity="0.3" d="M21 14H3C2.4 14 2 13.6 2 13V11C2 10.4 2.4 10 3 10H21C21.6 10 22 10.4 22 11V13C22 13.6 21.6 14 21 14ZM22 20V18C22 17.4 21.6 17 21 17H3C2.4 17 2 17.4 2 18V20C2 20.6 2.4 21 3 21H21C21.6 21 22 20.6 22 20Z" fill="black" />
                    </svg>
                </span>
            </div>
        </div>

        <div class="d-flex align-items-center flex-grow-1 flex-lg-grow-0">
            <a href="javascript:;" class="d-lg-none fs-3 text-dark fw-bold">
                {{ (new \App\Handler\Cache\Data\Pros\System\ConfigCacheHandler())->get('APP_NAME', 'Pros 控制台管理系统') }}
            </a>
        </div>
        <div class="aside-toggle align-items-center d-none d-lg-flex me-3 active cursor-pointer" data-kt-toggle="true" data-kt-toggle-state="active" data-kt-toggle-target="body" data-kt-toggle-name="aside-minimize">
            <span class="svg-icon svg-icon-1 rotate-180">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <path opacity="0.5" d="M14.2657 11.4343L18.45 7.25C18.8642 6.83579 18.8642 6.16421 18.45 5.75C18.0358 5.33579 17.3642 5.33579 16.95 5.75L11.4071 11.2929C11.0166 11.6834 11.0166 12.3166 11.4071 12.7071L16.95 18.25C17.3642 18.6642 18.0358 18.6642 18.45 18.25C18.8642 17.8358 18.8642 17.1642 18.45 16.75L14.2657 12.5657C13.9533 12.2533 13.9533 11.7467 14.2657 11.4343Z" fill="black" />
                    <path d="M8.2657 11.4343L12.45 7.25C12.8642 6.83579 12.8642 6.16421 12.45 5.75C12.0358 5.33579 11.3642 5.33579 10.95 5.75L5.40712 11.2929C5.01659 11.6834 5.01659 12.3166 5.40712 12.7071L10.95 18.25C11.3642 18.6642 12.0358 18.6642 12.45 18.25C12.8642 17.8358 12.8642 17.1642 12.45 16.75L8.2657 12.5657C7.95328 12.2533 7.95328 11.7467 8.2657 11.4343Z" fill="black" />
                </svg>
            </span>
        </div>
        <div class="d-flex align-items-stretch justify-content-between flex-lg-grow-1">
            <div class="d-flex align-items-stretch" id="kt_header_nav">
                <div class="header-menu align-items-stretch" data-kt-drawer="true" data-kt-drawer-name="header-menu" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="{default:'200px', '300px': '250px'}" data-kt-drawer-direction="end" data-kt-drawer-toggle="#kt_header_menu_mobile_toggle" data-kt-swapper="true" data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_body', lg: '#kt_header_nav'}">
                    <div class="menu menu-lg-rounded menu-column menu-lg-row menu-state-bg menu-title-gray-700 menu-state-title-primary menu-state-icon-primary menu-state-bullet-primary menu-arrow-gray-400 fw-bold my-5 my-lg-0 align-items-stretch pros_header_menus_box" id="#kt_header_menu" data-kt-menu="true">
                        @foreach(config('pros_menus', []) as $menus)
                            @foreach($menus as $menu)
                                <div data-kt-menu-trigger="click" data-kt-menu-placement="bottom-start" class="menu-item menu-lg-down-accordion me-lg-1 pros_header_menus_items">
                                    <span class="menu-link py-3">
                                        <span class="menu-title">{{ $menu['guard_name'] }}</span>
                                        <span class="menu-arrow d-lg-none"></span>
                                    </span>
                                    @if(count($menu['group_menus']) > 1)
                                        <div class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown w-100 w-lg-600px p-2 p-lg-5">
                                            <div class="row" data-kt-menu-dismiss="true">
                                                @foreach($menu['group_menus'] as $sub_menus)
                                                    <div class="col-lg-{{ (int)(12/count($menu['group_menus'])) }} border-left-lg-1 px-0">
                                                        <div class="menu-inline menu-column menu-active-bg pros_header_menus_subs">
                                                            @include('pros.console.layouts.hooks.menus', ['menus' => $sub_menus])
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @else
                                        <div class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown menu-rounded-0 py-lg-4 w-lg-225px pros_header_menus_subs">
                                            @foreach($menu['group_menus'] as $sub_menus)
                                                @include('pros.console.layouts.hooks.menus', ['menus' => $sub_menus])
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        @endforeach
                    </div>
                </div>
            </div>
            @php
                $current_auth = current_auth(false, config('pros.session_prefix'));
            @endphp
            <div class="d-flex align-items-stretch flex-shrink-0">
                <div class="d-flex align-items-center ms-1">
                    @if(pros_console_has_permission('pros.console.systems.refresh.nodes', 'post'))
                        <a href="javascript:;" id="kt-refresh_console_nodes" data-query-url="{{ route('pros.console.systems.refresh.nodes') }}" class="btn btn-icon bg-hover-opacity-10 w-35px h-35px h-md-40px w-md-40px" data-bs-toggle="tooltip" data-bs-placement="bottom" title="刷新系统权限节点">
                            <i class="fonticon-repeat fs-2"></i>
                        </a>
                    @endif
                </div>
                <div class="d-flex align-items-center ms-1" id="kt_header_user_menu_toggle">
                    <div class="btn btn-flex align-items-center bg-hover-white bg-hover-opacity-10 py-2 px-2 px-md-3" data-kt-menu-trigger="click" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
                        <div class="d-none d-md-flex flex-column align-items-end justify-content-center me-2 me-md-4">
                            <span class="opacity-75 fs-8 fw-bold lh-1 mb-1">{{ data_get($current_auth, 'nickname', '未知') }}</span>
                            <span class="fs-8 fw-bolder lh-1">{{ data_get($current_auth, 'role_name', '未知') }}</span>
                        </div>
                        <div class="symbol symbol-30px symbol-md-40px">
                            @if(!\Abnermouke\EasyBuilder\Library\Currency\ValidateLibrary::link(data_get($current_auth, 'avatar', '')))
                                <div class="symbol-label fs-2 fw-bold text-success">{{ data_get($current_auth, 'nickname_abbr', 'X') }}</div>
                            @else
                                <img src="{{ data_get($current_auth, 'avatar', '') }}" alt="image" />
                            @endif
                        </div>
                    </div>
                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-primary fw-bold py-4 fs-6 w-275px" data-kt-menu="true">
                        <div class="menu-item px-3">
                            <div class="menu-content d-flex align-items-center px-3">
                                <div class="symbol symbol-50px me-5">
                                    @if(!\Abnermouke\EasyBuilder\Library\Currency\ValidateLibrary::link(data_get($current_auth, 'avatar', '')))
                                        <div class="symbol-label fs-2 fw-bold text-success">{{ data_get($current_auth, 'nickname_abbr', 'X') }}</div>
                                    @else
                                        <img src="{{ data_get($current_auth, 'avatar', '') }}" alt="image" />
                                    @endif
                                </div>
                                <div class="d-flex flex-column">
                                    <div class="fw-bolder d-flex align-items-center fs-5">{{ data_get($current_auth, 'nickname', '未知') }}
                                        <span class="badge badge-light-success fw-bolder fs-8 px-2 py-1 ms-2">ID：{{ data_get($current_auth, 'id', 0) }}</span></div>
                                    <a href="javascript:;" class="fw-bold text-muted text-hover-primary fs-7">{{ data_get($current_auth, 'email', '未知') }}</a>
                                </div>
                            </div>
                        </div>
                        <div class="separator my-2"></div>
                        <div class="menu-item px-5">
                            <a href="javascript:;" id="kt-edit_admin_password" class="menu-link px-5">修改密码</a>
                        </div>
                        <div class="menu-item px-5">
                            <a href="javascript:;" class="menu-link px-5">
                                <span class="menu-text">操作IP</span>
                                <span class="menu-badge">
                                    <span class="badge badge-light-danger pe-2 fw-bolder fs-7">{{ data_get($current_auth, 'ip', '0.0.0.0') }}</span>
                                </span>
                            </a>
                        </div>
                        <div class="separator my-2"></div>
                        <div class="menu-item px-5">
                            <a href="{{ route('pros.console.oauth.sign.out') }}" class="menu-link px-5">退出登录</a>
                        </div>
                    </div>
                </div>
                <div class="d-flex align-items-center d-lg-none ms-2 me-n3" title="Show header menu">
                    <div class="btn btn-icon btn-active-light-primary w-30px h-30px w-md-40px h-md-40px" id="kt_header_menu_mobile_toggle">
                        <span class="svg-icon svg-icon-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path d="M13 11H3C2.4 11 2 10.6 2 10V9C2 8.4 2.4 8 3 8H13C13.6 8 14 8.4 14 9V10C14 10.6 13.6 11 13 11ZM22 5V4C22 3.4 21.6 3 21 3H3C2.4 3 2 3.4 2 4V5C2 5.6 2.4 6 3 6H21C21.6 6 22 5.6 22 5Z" fill="black" />
                                <path opacity="0.3" d="M21 16H3C2.4 16 2 15.6 2 15V14C2 13.4 2.4 13 3 13H21C21.6 13 22 13.4 22 14V15C22 15.6 21.6 16 21 16ZM14 20V19C14 18.4 13.6 18 13 18H3C2.4 18 2 18.4 2 19V20C2 20.6 2.4 21 3 21H13C13.6 21 14 20.6 14 20Z" fill="black" />
                            </svg>
                        </span>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
