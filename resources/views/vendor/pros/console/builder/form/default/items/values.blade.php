<div class="d-flex flex-column {{ $hidden ? 'd-none' : '' }} pros_form_{{ $sign }}_item_box" data-target="#pros_form_{{ $sign }}_item_{{ $field }}" data-required="{{ $required ? 1 : 0 }}" data-type="{{ $type }}" data-field="{{ $field }}" data-default-value="{{ json_encode(object_2_array($default_value), JSON_UNESCAPED_UNICODE|JSON_NUMERIC_CHECK) }}">
    <label class="d-flex align-items-center fs-6 fw-bold mb-2">
        <span class="{{ $required ? 'required' : '' }}">{{ $guard_name }}</span>
        @if($tip)
            <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="{!! $tip !!}"></i>
        @endif
    </label>
    <input type="hidden" id="pros_form_{{ $sign }}_item_{{ $field }}" autocomplete="off" value="{{ json_encode(object_2_array($default_value), JSON_UNESCAPED_UNICODE) }}">
    <div class="fv-row" id="pros_form_{{ $sign }}_item_{{ $field }}_toolbar">
        <a href="javascript:;" id="pros_form_{{ $sign }}_item_{{ $field }}_values_insert" class="btn btn-light-primary btn-sm me-3 my-3">添加</a>
        <a href="javascript:;" id="pros_form_{{ $sign }}_item_{{ $field }}_values_delete_all" class="btn btn-light-danger btn-sm me-3 my-3">删除全部</a>
    </div>
    <table class="table g-5 gs-0 mb-0 fw-bolder text-gray-700">
        <thead>
        <tr class="border-bottom fs-7 fw-bolder text-gray-700 text-uppercase">
            @foreach($columns as $column)
                <th>{{ $column['guard_name'] }}</th>
            @endforeach
            <th class="text-end">操作</th>
        </tr>
        </thead>
        <thead class="d-none" id="pros_form_{{ $sign }}_item_{{ $field }}_values_template">
        <tr class="border-bottom border-bottom-dashed pros_form_{{ $sign }}_item_{{ $field }}_values_row">
            @foreach($columns as $column)
                <td>
                    @switch($column['type'])
                        @case('input')
                        <input class="form-control form-control-solid pros_form_{{ $sign }}_item_{{ $field }}_values_item" type="{{ data_get($column, 'input_type', 'text') }}" data-type="{{ $column['type'] }}" data-key="{{ $column['key'] }}" placeholder="请输入{{ $column['guard_name'] }}">
                        @break
                        @case('select')
                        <select class="form-control form-control-solid value_append_item pros_form_{{ $sign }}_item_{{ $field }}_values_item" {{ $column['multiple'] ? 'multiple' : '' }} data-control="select2" data-type="{{ $column['type'] }}" data-key="{{ $column['key'] }}"  data-placeholder="请选择{{ $column['guard_name'] }}">
                            @foreach($column['options'] as $value => $name)
                                <option value="{{ $value }}">{{ $name}}</option>
                            @endforeach
                        </select>
                        @break
                        @case('switch')
                        <label class="form-check form-switch form-check-custom form-check-solid">
                            <input class="form-check-input pros_form_{{ $sign }}_item_{{ $field }}_values_item" type="checkbox" data-on-value="{{ $column['on_value'] }}" data-off-value="{{ $column['off_value'] }}" data-type="{{ $column['type'] }}" data-key="{{ $column['key'] }}">
                        </label>
                        @break
                    @endswitch
                </td>
            @endforeach
            <td class="pt-5 text-end">
                <button type="button" class="btn btn-sm btn-icon btn-active-color-danger pros_form_{{ $sign }}_item_{{ $field }}_values_trigger_delete"><i class="la la-trash fs-2"></i></button>
            </td>
        </tr>
        </thead>
        <tbody id="pros_form_{{ $sign }}_item_{{ $field }}_values_box">
        @foreach(object_2_array($default_value) as $column_values)
            <tr class="border-bottom border-bottom-dashed pros_form_{{ $sign }}_item_{{ $field }}_values_row">
                @foreach($columns as $column)
                    <td>
                        @switch($column['type'])
                            @case('input')
                            <input class="form-control form-control-solid pros_form_{{ $sign }}_item_{{ $field }}_values_item" value="{{ data_get($column_values, $column['key'], '') }}" type="{{ data_get($column, 'input_type', 'text') }}" data-type="{{ $column['type'] }}" data-key="{{ $column['key'] }}" placeholder="请输入{{ $column['guard_name'] }}">
                            @break
                            @case('select')
                            <select class="form-control form-control-solid value_default_item pros_form_{{ $sign }}_item_{{ $field }}_values_item" {{ $column['multiple'] ? 'multiple' : '' }} data-type="{{ $column['type'] }}" data-key="{{ $column['key'] }}" data-placeholder="请选择{{ $column['guard_name'] }}">
                                @foreach($column['options'] as $value => $name)
                                    @if($column['multiple'])
                                        <option value="{{ $value }}" {{ in_array($value, data_get($column_values, $column['key'], [])) ? 'selected' : '' }}>{{ $name}}</option>
                                    @else
                                        <option value="{{ $value }}" {{ data_get($column_values, $column['key'], '') === $value ? 'selected' : '' }}>{{ $name}}</option>
                                    @endif
                                @endforeach
                            </select>
                            @break
                            @case('switch')
                            <label class="form-check form-switch form-check-custom form-check-solid">
                                <input class="form-check-input pros_form_{{ $sign }}_item_{{ $field }}_values_item" type="checkbox" data-on-value="{{ $column['on_value'] }}" {{ data_get($column_values, $column['key'], '') === $column['on_value'] ? 'checked' : '' }} data-off-value="{{ $column['off_value'] }}" data-type="{{ $column['type'] }}" data-key="{{ $column['key'] }}">
                            </label>
                            @break
                        @endswitch
                    </td>
                @endforeach
                <td class="pt-5 text-end">
                    <button type="button" class="btn btn-sm btn-icon btn-active-color-danger pros_form_{{ $sign }}_item_{{ $field }}_values_trigger_delete"><i class="la la-trash fs-2"></i></button>
                </td>
            </tr>
        @endforeach
        <tr class="border-bottom border-bottom-dashed {{ object_2_array($default_value) ? 'd-none' : '' }} bg-light-{{ \Illuminate\Support\Arr::random(data_get($__colors__, 'theme_alias', ['primary'])) }}" id="pros_form_{{ $sign }}_item_{{ $field }}_values_empty_row">
            <td class="p-10 text-center" colspan="{{ count($columns) + 1 }}">暂未配置任何内容</td>
        </tr>
        </tbody>
    </table>
    @if($description)
        <div class="fs-7 fw-bold text-muted my-1">{!! $description !!}</div>
    @endif
    <div class="fs-7 fw-bold text-warning my-2 d-none" id="pros_form_{{ $sign }}_item_{{ $field }}_edited_warning">最近更新时间：<span class="edited_time fw-bold">{{ auto_datetime() }}</span></div>
</div>
