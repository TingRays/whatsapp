<div class="d-flex flex-column {{ $hidden ? 'd-none' : '' }} pros_form_{{ $sign }}_item_box" data-target="#pros_form_{{ $sign }}_item_{{ $field }}" data-required="{{ $required ? 1 : 0 }}" data-type="{{ $type }}" data-field="{{ $field }}" data-default-value="{{ $multiple ? json_encode(object_2_array($default_value), JSON_UNESCAPED_UNICODE|JSON_NUMERIC_CHECK) : $default_value }}">
    <label class="d-flex align-items-center fs-6 fw-bold mb-2">
        <span class="{{ $required ? 'required' : '' }}">{{ $guard_name }}</span>
        @if($tip)
            <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="{!! $tip !!}"></i>
        @endif
    </label>
    <select class="form-select form-select-solid" autocomplete="off" id="pros_form_{{ $sign }}_item_{{ $field }}"  {{ $multiple ? 'multiple' : '' }} data-placeholder="{{ $placeholder }}" {{ $readonly ? 'readonly' : '' }} {{ $disabled ? 'disabled' : '' }} name="{{ $field }}">
        @if(!$multiple)
            <option value="" @if($default_value === '') selected @endif>{{ $placeholder }}</option>
        @endif
        @foreach($options as $value => $guard_name)
            @if($multiple)
                <option value="{{ $value }}" {{ in_array($value, object_2_array($default_value)) ? 'selected' : '' }}>{{ $guard_name}}</option>
            @else
                <option value="{{ $value }}" @if($value === $default_value) selected @endif>{{ $guard_name }}</option>
            @endif
        @endforeach
    </select>
    @if($description)
        <div class="fs-7 fw-bold text-muted my-1">{!! $description !!}</div>
    @endif
    <div class="fs-7 fw-bold text-warning my-2 d-none" id="pros_form_{{ $sign }}_item_{{ $field }}_edited_warning">最近更新时间：<span class="edited_time fw-bold">{{ auto_datetime() }}</span></div>
</div>
