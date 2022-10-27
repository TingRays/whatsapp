<div class="d-flex flex-column {{ $hidden ? 'd-none' : '' }} pros_form_{{ $sign }}_item_box" data-target="#pros_form_{{ $sign }}_item_{{ $field }}" data-required="{{ $required ? 1 : 0 }}" data-type="{{ $type }}" data-field="{{ $field }}" data-default-value="{{ $default_value }}">
    <label class="d-flex align-items-center fs-6 fw-bold mb-2">
        <span class="{{ $required ? 'required' : '' }}">{{ $guard_name }}</span>
        @if($tip)
            <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="{!! $tip !!}"></i>
        @endif
    </label>
    <textarea class="form-select form-select-solid" autocomplete="off" id="pros_form_{{ $sign }}_item_{{ $field }}"  placeholder="{{ isset($placeholder) ? $placeholder : '' }}" rows="{{ isset($row) ? $row : 3 }}" {!! isset($max_length) && (int)$max_length > 0 ? 'maxlength="'.$max_length.'"' : '' !!} {{ $readonly ? 'readonly' : '' }} {{ $disabled ? 'disabled' : '' }} name="{{ $field }}">{!! $default_value !!}</textarea>
    @if(isset($clipboard) && $clipboard)
        <button class="btn btn-light-primary btn-sm my-3 pros_{{ $sign }}_form_item_tool" data-action-type="clipboard" data-clipboard-target="#pros_form_{{ $sign }}_item_{{ $field }}">复制内容</button>
    @endif
    @if($description)
        <div class="fs-7 fw-bold text-muted my-1">{!! $description !!}</div>
    @endif
    <div class="fs-7 fw-bold text-warning my-2 d-none" id="pros_form_{{ $sign }}_item_{{ $field }}_edited_warning">最近更新时间：<span class="edited_time fw-bold">{{ auto_datetime() }}</span></div>
</div>
