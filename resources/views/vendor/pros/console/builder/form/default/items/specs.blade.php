<div class="d-flex flex-column {{ $hidden ? 'd-none' : '' }} pros_form_{{ $sign }}_item_box" data-target="#pros_form_{{ $sign }}_item_{{ $field }}" data-required="{{ $required ? 1 : 0 }}" data-type="{{ $type }}" data-field="{{ $field }}" data-default-value="{{ json_encode(object_2_array(data_get($default_value, 'specs', [])), JSON_UNESCAPED_UNICODE|JSON_NUMERIC_CHECK) }}" data-json-path="{{ $json_file_link }}" data-names="{{ json_encode($names) }}" data-create-form-query="{{ json_encode($create_form) }}">
    <label class="d-flex align-items-center fs-6 fw-bold mb-2">
        <span class="{{ $required ? 'required' : '' }}">{{ $guard_name }}</span>
        @if($tip)
            <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="{!! $tip !!}"></i>
        @endif
    </label>
    <input type="hidden" id="pros_form_{{ $sign }}_item_{{ $field }}" autocomplete="off" value="{{ json_encode(object_2_array($default_value), JSON_UNESCAPED_UNICODE) }}">
    <div class="d-flex flex-row align-items-center justify-content-between mb-5">
        <select class="form-select form-select-solid" autocomplete="off" id="pros_form_{{ $sign }}_item_specs_{{ $field }}_spec_selector" data-placeholder="请选择商品规格" {{ $readonly ? 'readonly' : '' }} {{ $disabled ? 'disabled' : '' }} name="{{ $field }}"></select>
        <button class="btn btn-sm btn-primary text-nowrap ms-5" id="pros_form_{{ $sign }}_item_specs_{{ $field }}_confirmed_trigger">确认添加规格选择</button>
        <button class="btn btn-sm btn-secondary text-nowrap ms-5" id="pros_form_{{ $sign }}_item_specs_{{ $field }}_create_trigger">新增规格</button>
    </div>
    @if($description)
        <div class="fs-7 fw-bold text-muted my-1">{!! $description !!}</div>
    @endif
    <div class="fs-7 fw-bold text-warning my-2 d-none" id="pros_form_{{ $sign }}_item_{{ $field }}_edited_warning">最近更新时间：<span class="edited_time fw-bold">{{ auto_datetime() }}</span></div>
    <div class="d-flex flex-column" id="pros_form_{{ $sign }}_item_specs_{{ $field }}_specs_box">
        <div class="d-flex flex-row align-items-center mb-5">
            <button class="btn btn-sm btn-success text-nowrap" id="pros_form_{{ $sign }}_item_specs_{{ $field }}_items_build_trigger">立即生成规格</button>
        </div>
        <p class="fs-8 text-muted">点击需要使用的规格属性，确认后点击立即生成对应商品规格信息。</p>
    </div>
    <div class="d-flex flex-column" id="pros_form_{{ $sign }}_item_specs_{{ $field }}_specs_with_values_input_box">
        <div class="separator separator-dashed my-5"></div>
        <label class="d-flex align-items-center justify-content-between mb-5">
            <div class="fs-6 fw-bold">商品属性</div>
            <button class="btn btn-sm btn-secondary text-nowrap ms-5 {{ object_2_array($default_value) ? '' : 'd-none' }}" id="pros_form_{{ $sign }}_item_specs_{{ $field }}_items_build_setting_all_trigger">批量设置规格属性</button>
        </label>
        <div class="table-responsive">
            <table class="table table-row-bordered table-rounded border gs-7 gy-7 gx-7 mb-0 fw-bolder text-gray-700 text-nowrap" id="pros_form_{{ $sign }}_item_specs_{{ $field }}_specs_with_values_table">
                <thead class="d-none" id="pros_form_{{ $sign }}_item_specs_{{ $field }}_specs_with_values_template">
                    <tr id="pros_form_{{ $sign }}_item_specs_{{ $field }}_specs_with_values_thead_template">
                        <th>__SPECS_NAMES__</th>
                        @foreach($columns as $column)
                            <th>{{ $column['guard_name'] }}</th>
                        @endforeach
                    </tr>
                    <tr id="pros_form_{{ $sign }}_item_specs_{{ $field }}_specs_with_values_row_template">
                        <td>__SPECS_VALUES__</td>
                        @foreach($columns as $column)
                            <td>
                                @switch($column['type'])
                                    @case('input')
                                        <input class="form-control form-control-solid pros_form_{{ $sign }}_item_specs_{{ $field }}_value_rows_item_value min-w-250px" type="{{ data_get($column, 'input_type', 'text') }}" data-type="{{ $column['type'] }}" data-key="{{ $column['key'] }}" placeholder="请输入{{ $column['guard_name'] }}">
                                    @break
                                    @case('image')
                                        <input class="form-control form-control-solid pros_form_{{ $sign }}_item_specs_{{ $field }}_value_rows_item_value pros_form_{{ $sign }}_item_specs_{{ $field }}_value_rows_image_item d-none" type="text" data-type="{{ $column['type'] }}" data-key="{{ $column['key'] }}">
                                        <input type="file" class="d-none pros_form_{{ $sign }}_item_specs_{{ $field }}_value_rows_image_uploader" accept="image/*" data-upload-url="{{ $column['upload_url'] }}" data-upload-dictionary="{{ $column['dictionary'] }}">
                                        <div class="bg-light-{{ \Illuminate\Support\Arr::random(data_get($__colors__, 'theme_alias', ['primary'])) }} cursor-pointer mb-2 pros_form_{{ $sign }}_item_specs_{{ $field }}_value_rows_image_box" data-bs-toggle="tooltip" data-bs-placement="bottom" title="点击更改图片" style="background-size: 100%;background-repeat: no-repeat;background-position: center;height: 40px;width: 40px"></div>
                                    @break
                                @endswitch
                            </td>
                        @endforeach
                    </tr>
                </thead>
                <thead id="pros_form_{{ $sign }}_item_specs_{{ $field }}_specs_with_values_thead">
                    @if(data_get($default_value, 'specs', []))
                        @foreach($default_value['names'] as $name)
                            <th>{{ $name }}</th>
                        @endforeach
                        @foreach($columns as $column)
                            <th>{{ $column['guard_name'] }}</th>
                        @endforeach
                    @endif
                </thead>
                <tbody id="pros_form_{{ $sign }}_item_specs_{{ $field }}_specs_with_values_rows">
                    @if(data_get($default_value, 'specs', []))
                        @foreach($default_value['specs'] as $spec)
                            <tr class="pros_form_{{ $sign }}_item_specs_{{ $field }}_specs_with_values_row" data-value-keys="{{ json_encode($spec['values']) }}">
                                @foreach($spec['values'] as $value)
                                    <td class="text-primary fw-bold" style="line-height: 40px">{{ $default_value['values'][$value] }}</td>
                                @endforeach
                                    @foreach($columns as $column)
                                        <td>
                                            @switch($column['type'])
                                                @case('input')
                                                <input class="form-control form-control-solid pros_form_{{ $sign }}_item_specs_{{ $field }}_value_rows_item_value min-w-250px" type="{{ data_get($column, 'input_type', 'text') }}" data-type="{{ $column['type'] }}" data-key="{{ $column['key'] }}" placeholder="请输入{{ $column['guard_name'] }}" value="{{ data_get($spec, $column['key'], '') }}">
                                                @break
                                                @case('image')
                                                <input class="form-control form-control-solid pros_form_{{ $sign }}_item_specs_{{ $field }}_value_rows_item_value pros_form_{{ $sign }}_item_specs_{{ $field }}_value_rows_image_item d-none" type="text" data-type="{{ $column['type'] }}" data-key="{{ $column['key'] }}" value="{{ data_get($spec, $column['key'], '') }}">
                                                <input type="file" class="d-none pros_form_{{ $sign }}_item_specs_{{ $field }}_value_rows_image_uploader" accept="image/*" data-upload-url="{{ $column['upload_url'] }}" data-upload-dictionary="{{ $column['dictionary'] }}">
                                                <div class="bg-light-{{ \Illuminate\Support\Arr::random(data_get($__colors__, 'theme_alias', ['primary'])) }} cursor-pointer mb-2 pros_form_{{ $sign }}_item_specs_{{ $field }}_value_rows_image_box" data-bs-toggle="tooltip" data-bs-placement="bottom" title="点击更改图片" style="background: url({!! data_get($spec, $column['key'], '') !!});background-size: 100%;background-repeat: no-repeat;background-position: center;height: 40px;width: 40px"></div>
                                                @break
                                            @endswitch
                                        </td>
                                    @endforeach
                            </tr>
                        @endforeach
                    @endif
                    <tr class="border-bottom border-bottom-dashed {{ object_2_array($default_value) ? 'd-none' : '' }} bg-light-{{ \Illuminate\Support\Arr::random(data_get($__colors__, 'theme_alias', ['primary'])) }}" id="pros_form_{{ $sign }}_item_specs_{{ $field }}_specs_with_values_empty_row">
                        <td class="p-10 text-center" data-field-colspan="{{ count($columns) }}" colspan="{{ count($columns) }}">暂未配置任何规格</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="d-none" id="pros_form_{{ $sign }}_item_specs_{{ $field }}_specs_setting_all_template">
        @foreach($columns as $column)
            <div class="form-group mb-5">
                <label class="d-flex align-items-center fs-6 fw-bold mb-2">
                    <span class="required">{{ $column['guard_name'] }}</span>
                </label>
                @switch($column['type'])
                    @case('input')
                        <input class="form-control form-control-solid pros_form_{{ $sign }}_item_specs_{{ $field }}_value_rows_item_value" type="{{ data_get($column, 'input_type', 'text') }}" data-type="{{ $column['type'] }}" data-key="{{ $column['key'] }}" placeholder="请输入{{ $column['guard_name'] }}">
                    @break
                    @case('image')
                    <input class="form-control form-control-solid pros_form_{{ $sign }}_item_specs_{{ $field }}_value_rows_item_value pros_form_{{ $sign }}_item_specs_{{ $field }}_value_rows_image_item d-none" type="text" data-type="{{ $column['type'] }}" data-key="{{ $column['key'] }}">
                    <input type="file" class="d-none pros_form_{{ $sign }}_item_specs_{{ $field }}_value_rows_image_uploader" accept="image/*" data-upload-url="{{ $column['upload_url'] }}" data-upload-dictionary="{{ $column['dictionary'] }}">
                    <div class="bg-light-{{ \Illuminate\Support\Arr::random(data_get($__colors__, 'theme_alias', ['primary'])) }} cursor-pointer mb-2 pros_form_{{ $sign }}_item_specs_{{ $field }}_value_rows_image_box" data-bs-toggle="tooltip" data-bs-placement="bottom" title="点击更改图片" style="background-size: 100%;background-repeat: no-repeat;background-position: center;height: 100px;width: 100px"></div>
                    @break
                @endswitch
            </div>
        @endforeach
    </div>
</div>
