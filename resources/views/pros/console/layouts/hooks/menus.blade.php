@if($menus)
    @foreach($menus as $menu)
        <div @if(data_get($menu, 'menus', false)) data-kt-menu-trigger="{default:'click', lg: 'hover'}" data-kt-menu-placement="right-start" @endif class="menu-item {!! data_get($menu, 'menus', false) ? 'menu-lg-down-accordion' : '' !!}">
            @if(data_get($menu, 'menus', false))
                <span class="menu-link py-5">
                @else
                    @php
                        $permission_nodes = object_2_array(data_get($menu, 'permission_nodes', []));
                        $menu['route']['name'] && $permission_nodes[] = 'get&'.$menu['route']['name'];
                    @endphp
                <a class="menu-link py-5" data-route-name="{{ $menu['route']['name'] }}" data-permission-nodes="{{ json_encode($permission_nodes, JSON_UNESCAPED_UNICODE) }}" href="{{ !empty($menu['route']['name']) ? route($menu['route']['name'], $menu['route']['params']) : 'javascript:;' }}">
            @endif
                @if(data_get($menu, 'icon', ''))
                    <span class="menu-icon">
                        <span class="{{ $menu['icon'] }}"></span>
                    </span>
                @else
                    <span class="menu-bullet">
                        <span class="bullet bullet-dot"></span>
                    </span>
                @endif
                <span class="menu-title">{{ $menu['guard_name'] }}</span>
                {!! data_get($menu, 'menus', false) ? '<span class="menu-arrow"></span>' : '' !!}
            @if(data_get($menu, 'menus', false))
                </span>
            @else
                </a>
            @endif
            @if(data_get($menu, 'menus', false))
                <div class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown menu-active-bg menu-rounded-0 py-lg-4 w-lg-225px">
                    @include('pros.console.layouts.hooks.menus', ['menus' => data_get($menu, 'menus', [])])
                </div>
            @endif
        </div>
    @endforeach
@endif
