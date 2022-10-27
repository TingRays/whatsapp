<div class="pros_form_builder" id="pros_form_{{ $sign }}" data-mobile="{{ \Abnermouke\EasyBuilder\Library\Currency\DeviceLibrary::mobile() ? 1 : 0 }}" data-source-path="{{ proxy_assets('console/themes/default/builder', 'pros') }}" data-sign="{{ $sign }}" data-build="0">
    @if($title)<div id="pros_{{ $sign }}_form_title" class="fs-2 fw-bold mb-1">{{ $title }}</div>@endif
    @if($description)<div id="pros_{{ $sign }}_form_description" class="fs-9 mb-6 text-break">{!! $description !!}</div>@endif
    <div class="card">
        @if($tabs)
            <div class="card-header card-header-stretch overflow-auto">
                <ul class="nav nav-stretch nav-line-tabs fw-bold border-transparent flex-nowrap" id="pros_form_{{ $sign }}_tabs">
                    @foreach($tabs as $k => $tab)
                        <li class="nav-item" style="width: max-content">
                            <div class="nav-link text-hover-primary cursor-pointer" data-alias="{{ $tab['alias'] }}">{{ $tab['title'] }} </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="card-body" id="pros_form_{{ $sign }}_tab_panels">
            @foreach($panels as $alias => $groups)
                <div class="tab_panel pros_form_{{ $sign }}_panel d-none" id="pros_{{ $sign }}_form_tab_for_{{ $alias }}" data-alias="{{ $alias }}" role="tabpanel">
                    @foreach($groups as $k => $group)
                        @if((int)$k !== 0)
                            <div class="separator separator-dashed mb-10 mt-5"></div>
                        @endif
                        @if(data_get($group, 'title', false))
                            <div class="row">
                                <div class="col-lg-10 col-xl-8 offset-xl-2">
                                    <h3 class="fs-6 mb-10">{{ $group['title'] }}:</h3>
                                </div>
                            </div>
                        @endif
                        @if(data_get($group, 'alert_text', false))
                            <div class="row">
                                <div class="col-lg-10 col-xl-8 offset-xl-2">
                                    <div class="alert alert-dismissible d-flex flex-column flex-sm-row p-5 mb-10 bg-light-{{ $group['alert_theme'] }}">
                                        <div class="w-100">{!! $group['alert_text'] !!}</div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if($group['fields'])
                            <div class="row">
                                <div class="col-lg-10 col-xl-8 offset-xl-2">
                                    <div class="row">
                                        @foreach($group['fields'] as $field)
                                            @if($item = data_get($items, $field, false))
                                                <div class="form-group pros_{{ $sign }}_form_item mb-6 col-lg-{{ $item['cols']['lg'] }} col-md-{{ $item['cols']['md'] }} col-sm-{{ $item['cols']['sm'] }} col-xs-{{ $item['cols']['xs'] }}" data-type="{{ $item['type'] }}" data-field="{{ $item['field'] }}">
                                                    @include('vendor.pros.console.builder.form.default.items.'.$item['type'], array_merge(['sign' => $sign, '__colors__' => $colors], $item))
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            @endforeach
        </div>
        <div class="card-footer d-flex justify-content-center" id="pros_{{ $sign }}_form_footer_box">
            @if($back)
                <div id="pros_{{ $sign }}_form_back_button_box" class="mx-5">
                    <button type="button" id="pros_{{ $sign }}_form_back_button" class="btn btn-{{ $back['theme'] }} btn-sm me-2 my-2 pros_form_button" data-type="{{ $back['type'] }}" data-confirm-tip="{{ $back['confirm_tip'] }}" data-params="{{ json_encode($back['params']) }}">
                        @if($back['icon']) <i class="{{ $back['icon'] }} fs-7"></i> @endif {{ $back['guard_name'] }}
                    </button>
                </div>
            @endif
            @if($submit)
                <div id="pros_{{ $sign }}_form_submit_button_box">
                    <button type="button" id="pros_{{ $sign }}_form_submit_button" class="btn btn-{{ $submit['theme'] }} btn-sm me-2 my-2 pros_form_button" data-type="{{ $submit['type'] }}" data-confirm-tip="{{ $submit['confirm_tip'] }}" data-params="{{ json_encode($submit['params']) }}">
                        @if($submit['icon']) <i class="{{ $submit['icon'] }} fs-7"></i> @endif {{ $submit['guard_name'] }}
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>
<script>
    window.onload = function () {
        //引入实例对象
        createExtraJs('{{ proxy_assets('console/themes/default/builder/form-builder.js', 'pros') }}', $.form_builder, function () {
            //创建处理实例对象
            $.form_builder.init('{{ $sign }}');
        });
    };
</script>
