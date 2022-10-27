<div class="pros_table_builder" id="pros_table_{{ $sign }}" data-mobile="{{ \Abnermouke\EasyBuilder\Library\Currency\DeviceLibrary::mobile() ? 1 : 0 }}" data-source-path="{{ proxy_assets('console/themes/default/builder', 'pros') }}" data-sign="{{ $sign }}" data-build="0" data-query-params="{{ json_encode(['signature' => $signature]) }}" data-query-url="{{ $query_url }}" data-query-method="{{ $query_method }}">
    @if($alerts || $filters)
        <div class="card mb-6">
            <div class="card-body">
                @if($alerts)
                    @foreach($alerts as $alert)
                        <div
                            class="alert alert-dismissible bg-light-{{ $alert['theme'] }} border border-{{ $alert['theme'] }} d-flex flex-column flex-sm-row px-5 py-2 mb-10">
                            <div class="pe-0">{!! $alert['text'] !!}</div>
                        </div>
                    @endforeach
                    <div class="separator separator-dashed mb-5"></div>
                @endif
                @if($filters)
                    <div class="row g-8 mb-5" id="pros_table_{{ $sign }}_filters">
                        @foreach($filters as $filter)
                            @include('vendor.pros.console.builder.table.default.filters.'.$filter['type'], $filter)
                        @endforeach
                    </div>
                    <div class="separator separator-dashed mb-5"></div>
                    <div class="row g-8">
                        <div class="d-flex align-items-center">
                            <button type="button" id="pros_table_{{ $sign }}_filter_to_submit" class="btn btn-sm btn-primary me-5">确认搜索</button>
                            <button type="button" id="pros_table_{{ $sign }}_filter_to_reset" class="btn btn-sm btn-light-dark me-5">清空条件</button>
                            <span class="text-muted">更改筛选项或排序项后请点击确认筛选按钮进行查询。</span>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @endif
    <div class="card" id="pros_{{ $sign }}_table_contents">
        @if($tabs)
            <div class="card-header card-header-stretch overflow-auto">
                <ul class="nav nav-stretch nav-line-tabs fw-bold border-transparent flex-nowrap" id="pros_table_{{ $sign }}_tabs">
                    @foreach($tabs as $k => $tab)
                        <li class="nav-item">
                            <div class="nav-link text-hover-primary cursor-pointer" data-alias="{{ $tab['alias'] }}" @if(is_array($tab['bind_button_id_suffixes'])) data-bind-button-suffixes="{{ json_encode($tab['bind_button_id_suffixes']) }}" @endif data-query-url="{{ $tab['query_url'] }}" data-query-method="{{ $tab['query_method'] }}">{{ $tab['title'] }} <span class="number">{{ !$tab['count']['auto_hidden'] ? '（'.number_format((int)$tab['count']['number']).'）' : ((int)$tab['count']['number'] !== 0 ? '（'.number_format((int)$tab['count']['number']).'）' : '') }}</span></div>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="card-body" id="pros_{{ $sign }}_table_contents">
            @if($buttons)
                <div class="col-sm-12 col-ls-12 mb-5" id="pros_table_{{ $sign }}_buttons">
                    @foreach($buttons as $button)
                        <button type="button" class="btn btn-{{ $button['theme'] }} btn-sm me-2 my-2 pros_table_button" data-type="{{ $button['type'] }}" @if($button['id_suffix']) id="pros_table_{{ $sign }}_button_of_{{ $button['id_suffix'] }}" data-did-suffix="{{ $button['id_suffix'] }}" @endif data-confirm-tip="{{ $button['confirm_tip'] }}" data-params="{{ json_encode($button['params']) }}" data-bind-checkbox="{{ $checkbox && $button['id_suffix'] && in_array($button['id_suffix'], $checkbox['trigger_button_id_suffix']) ? 1 : 0 }}">
                            @if($button['icon']) <i class="{{ $button['icon'] }} fs-7"></i> @endif {{ $button['guard_name'] }}
                        </button>
                    @endforeach
                </div>
            @endif
            <div class="col-sm-12 col-ls-12 mb-5">
                <div id="pros_table_{{ $sign }}_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer min-h-200px">
                    <div class="table-responsive">
                        <table id="pros_table_{{ $sign }}_box" class="table pros_table_box table-striped rounded gs-7 align-middle table-row-dashed text-left fs-6 gy-5" data-checkbox-field="{{ data_get($checkbox, 'field', '') }}">
                            @if($heads)
                                <thead class="border-gray-200 fs-5 fw-bold bg-lighten" id="pros_table_{{ $sign }}_thead">
                                    <tr>
                                        @if($append)
                                            <th class="w-10px export_ignore"></th>
                                        @endif
                                        @if($checkbox)
                                            <th class="w-10px pe-2 export_ignore">
                                                <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                                    <input class="form-check-input" id="pros_table_{{ $sign }}_select_all" type="checkbox"/>
                                                </div>
                                            </th>
                                        @endif
                                        @foreach($heads as $f => $name)
                                            <th data-field="{{ in_array($f, $sorts) ? $sort_fields[$f] : $f }}" class="{{ in_array($f, $sorts) ? 'pros_table_sorting_th cursor-pointer text-hover-primary' : '' }}" @if(in_array($f, $sorts)) data-pros-tool="sorting" data-sort="default" @endif>{{ $name }} @if(in_array($f, $sorts)) <i class="fa fa-sort fs-4"></i> @endif</th>
                                        @endforeach
                                        @if($actions)
                                            <th class="export_ignore">操作</th>
                                        @endif
                                    </tr>
                                </thead>
                            @endif
                            <tbody id="pros_table_{{ $sign }}_tbody">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @if($export || $pagination)
            <div class="card-footer">
                <div class="d-flex flex-wrap justify-content-between align-items-center my-1">
                    @if($export)
                        <ul class="nav nav-pills me-2 mb-2 mb-sm-0">
                            <li class="nav-item m-0 ms-2 my-2">
                                <a class="btn btn-sm btn-light btn-secondary" id="pros_table_{{ $sign }}_export" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="导出当前列表为EXCEL">
                                    <i class="la la-print"></i> 导出当前列表
                                </a>
                            </li>
                        </ul>
                    @endif
                    @if($pagination)
                        <div class="d-flex align-items-center flex-wrap" data-page="{{ $pagination['page'] }}" data-page-size="{{ $pagination['page_size'] }}" id="pros_table_{{ $sign }}_pagination_box">
                            <div class="d-flex me-5 mb-2 my-2 mb-sm-0">
                                <select id="pros_table_{{ $sign }}_pagination_of_page_size_select" data-control="select2" data-hide-search="true" data-placeholder="每页显示条数" class="form-select form-select-sm form-select-solid min-w-100px ms-2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                                    @foreach($pagination['allow_page_sizes'] as $ps)
                                        <option value="{{ $ps }}" @if($ps === $pagination['page_size']) selected @endif>每页{{ $ps }}条</option>
                                    @endforeach
                                </select>
                            </div>
                            <ul class="pagination pagination-outline my-2" id="pros_table_{{ $sign }}_pagination">

                            </ul>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>
<script>
    window.onload = function () {
        //引入实例对象
        createExtraJs('{{ proxy_assets('console/themes/default/builder/table-builder.js', 'pros') }}', $.table_builder, function () {
            //创建处理实例对象
            $.table_builder.init('{{ $sign }}');
        });
    };
</script>
