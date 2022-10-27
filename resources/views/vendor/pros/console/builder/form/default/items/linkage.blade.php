<div class="d-flex flex-column {{ $hidden ? 'd-none' : '' }} pros_form_{{ $sign }}_item_box" data-target="#pros_form_{{ $sign }}_item_{{ $field }}" data-required="{{ $required ? 1 : 0 }}" data-type="{{ $type }}" data-field="{{ $field }}" data-default-key="{{ $default_key_value }}" data-json-path="{{ $json_link }}" data-level="{{ $level }}" data-names="{{ json_encode($names) }}" data-default-value="{{ json_encode(object_2_array($default_value), JSON_UNESCAPED_UNICODE|JSON_NUMERIC_CHECK) }}" data-create-form-query="{{  json_encode(isset($create_form) ? $create_form : []) }}">
    <label class="d-flex align-items-center justify-content-between fs-6 fw-bold mb-2">
        <div class="left_side">
            <span class="{{ $required ? 'required' : '' }}">{{ $guard_name }}</span>
            @if($tip)
                <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="{!! $tip !!}"></i>
            @endif
        </div>
        @if(isset($create_form) && !empty($create_form['query_url']))
            <div class="right_side">
                <a class="text-nowrap" href="javascript:;" id="pros_form_{{ $sign }}_item_linkage_{{ $field }}_create_trigger">立即新增</a>
            </div>
        @endif
    </label>
    <input type="hidden" id="pros_form_{{ $sign }}_item_{{ $field }}" autocomplete="off" value="{{ json_encode(object_2_array($default_value), JSON_UNESCAPED_UNICODE|JSON_NUMERIC_CHECK) }}">
    <div class="row fv-row" id="pros_form_{{ $sign }}_item_{{ $field }}_box">
        @for($i = 1; $i <= (int)$level; $i++)
            <div class="col-{{ (int)$item_col }} mb-3">
                <select name="{{ $field }}_{{ $i }}" data-level="{{ $i }}" data-keys="[]" class="form-select form-select-solid pros_form_{{ $sign }}_item_{{ $field }}_linkage_item" data-placeholder="{{ $placeholder }}" {{ $readonly ? 'readonly' : '' }} {{ $disabled ? 'disabled' : '' }}>
                    <option value="{{ $default_key_value }}" selected class="default_option">请选择</option>
                </select>
            </div>
        @endfor
    </div>
    @if($description)
        <div class="fs-7 fw-bold text-muted my-1">{!! $description !!}</div>
    @endif
    <div class="fs-7 fw-bold text-warning my-2 d-none" id="pros_form_{{ $sign }}_item_{{ $field }}_edited_warning">最近更新时间：<span class="edited_time fw-bold">{{ auto_datetime() }}</span></div>
</div>
