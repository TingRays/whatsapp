@if($lists['lists'])
    @foreach($lists['lists'] as $k => $list)
        <tr {!! $target ? 'class="'.$target.'" colspan="'.$column_count.'" data-target-alias="'.$trigger.'"' : '' !!}>
            @if($append)
                <td class="export_ignore">
                    <button type="button" class="btn btn-sm btn-icon btn-light btn-active-light-primary export_ignore toggle h-25px w-25px pros_table_{{ $sign }}_append_trigger" id="pros_table_{{ $sign }}_append_trigger_{{ md5(pros_decode_link($append['trigger'], $list, $k)) }}" data-method="{{ $append['method'] }}" data-trigger="{{ pros_decode_link($append['trigger'], $list, $append['trigger']) }}" data-column-count="{{ $column_count }}" data-open="0" data-trigger-alias="{{ md5(pros_decode_link($append['trigger'], $list, $k)) }}" data-target="pros_table_content_append_{{ md5(pros_decode_link($append['trigger'], $list, $k)) }}"><i class="fa fa-angle-right"></i></button>
                </td>
            @endif
            @if($checkbox)
                <td class="w-10px pe-2 export_ignore">
                    <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                        <input class="form-check-input pros_table_{{ $sign }}_select_item" type="checkbox" value="{{ data_get($list, $checkbox['field'], '') }}"/>
                    </div>
                </td>
            @endif
            @foreach($items as $setting)
                @if($setting['show'])
                    <td class="pros_{{ $sign }}_table_tbody_td {{ $target ? 'export_ignore' : '' }}" data-field="{{ $setting['field'] }}" data-type="{{ $setting['type'] }}">
                        @include('vendor.pros.console.builder.table.default.contents.'.$setting['type'], array_merge($setting, ['__data__' => $list]))
                    </td>
                @endif
            @endforeach
            @if($actions)
                <td class="text-right export_ignore pros_table_content_actions">
                    @foreach($actions as $action)
                        @if(pros_console_action_condition($action['conditions'], $list))
                            <a href="javascript:;" class="text-{{ $action['theme'] }} pros_table_content_action" data-type="{{ $action['type'] }}" data-confirm-tip="{{ $action['confirm_tip'] }}" data-query-url="{{ pros_decode_link($action['query_url'], $list, '') }}" data-params="{{ json_encode($action['params']) }}">
                                @if($action['icon']) <i class="{{ $action['icon'] }} text-{{ $action['theme'] }} fs-7"></i> @endif {{ $action['guard_name'] }}
                            </a>
                        @endif
                    @endforeach
                </td>
            @endif
        </tr>
        @if($append && $append['method'] == 'more')
            <tr class="bg-secondary text-dark d-none pros_table_content_append_{{ md5(pros_decode_link($append['trigger'], $list, $k)) }}">
                <td colspan="{{ $column_count }}">
                    <div class="row">
                        @foreach($items as $setting)
                            @if(!$setting['show'] && $setting['type'] == 'string')
                                @include('vendor.pros.console.builder.table.default.contents.string', array_merge($setting, ['__data__' => $list, '__append__' => true]))
                            @endif
                        @endforeach
                    </div>
                </td>
            </tr>
        @endif
    @endforeach
@else
    <tr {!! $target ? 'class="'.$target.' p-20" colspan="'.$column_count.'" data-target-alias="'.$trigger.'"' : 'class="p-20"' !!}>
        <td colspan="{{ $column_count }}">
            <div class="pt-lg-10 mt-5 mb-10 p-20 text-center">
                <h4 class="fw-bolder text-gray-800 mb-5">暂无相关数据展示</h4>
                <div class="fw-bold text-muted">No relevant data, please try to change the search criteria to see more highlights !</div>
            </div>
        </td>
    </tr>
@endif
