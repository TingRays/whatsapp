<div class="d-flex flex-column {{ $hidden ? 'd-none' : '' }} pros_form_{{ $sign }}_item_box" data-target="#pros_form_{{ $sign }}_item_{{ $field }}" data-required="{{ $required ? 1 : 0 }}" data-type="{{ $type }}" data-triggers="{{ isset($triggers) ? json_encode($triggers, JSON_UNESCAPED_UNICODE|JSON_NUMERIC_CHECK) : '' }}" data-field="{{ $field }}" data-default-value="{{ $default_value }}">
    <div class="me-5">
        <label class="fs-6 fw-bold">
            <span class="{{ $required ? 'required' : '' }}">{{ $guard_name }}</span>
            @if($tip)
                <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="{!! $tip !!}"></i>
            @endif
        </label>
        @if($description)
            <div class="fs-7 fw-bold my-2 text-muted">{!! $description !!}</div>
        @endif
    </div>
    <label class="form-check form-switch form-check-custom form-check-solid mt-5">
        <input class="form-check-input" type="checkbox" id="pros_form_{{ $sign }}_item_{{ $field }}" name="{{ $field }}" {{ $readonly ? 'readonly' : '' }} {{ $disabled ? 'disabled' : '' }} @if($on == $default_value) checked="checked" @endif data-on-value="{{ $on }}" data-off-value="{{ $off }}"/>
        <span class="form-check-label fw-bold text-muted">{{ $allow_text }}</span>
    </label>
</div>
<div class="fs-7 fw-bold text-warning my-2 d-none" id="pros_form_{{ $sign }}_item_{{ $field }}_edited_warning">最近更新时间：<span class="edited_time fw-bold">{{ auto_datetime() }}</span></div>
