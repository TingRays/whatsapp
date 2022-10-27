<div class="d-flex flex-column {{ $hidden ? 'd-none' : '' }} pros_form_{{ $sign }}_item_box" data-target="#pros_form_{{ $sign }}_item_{{ $field }}" data-required="{{ $required ? 1 : 0 }}" data-type="{{ $type }}" data-field="{{ $field }}" data-default-value="#pros_form_{{ $sign }}_item_{{ $field }}_default_value_content" data-upload-url="{{ $upload_url }}">
    <label class="d-flex align-items-center fs-6 fw-bold mb-2">
        <span class="{{ $required ? 'required' : '' }}">{{ $guard_name }}</span>
        @if($tip)
            <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="{!! $tip !!}"></i>
        @endif
    </label>
    <div class="d-none" id="pros_form_{{ $sign }}_item_{{ $field }}_default_value_content">{!! $default_value !!}</div>
    <textarea id="pros_form_{{ $sign }}_item_{{ $field }}" cols="30" rows="10" class="d-none">{!! $default_value !!}</textarea>
    <script id="pros_form_{{ $sign }}_item_{{ $field }}_ueditor_container" placeholder="{{ isset($placeholder) ? $placeholder : '' }}" rows="{{ isset($row) ? (int)$row : 3 }}"  {!! isset($max_length) && (int)$max_length > 0 ? 'maxlength="'.$max_length.'"' : '' !!} {{ $readonly ? 'readonly' : '' }} {{ $disabled ? 'disabled' : '' }} name="{{ $field }}">{!! $default_value !!}</script>
    @if($description)
        <div class="fs-7 fw-bold text-muted my-1">{!! $description !!}</div>
    @endif
    <div class="fs-7 fw-bold text-warning my-2 d-none" id="pros_form_{{ $sign }}_item_{{ $field }}_edited_warning">最近更新时间：<span class="edited_time fw-bold">{{ auto_datetime() }}</span></div>
</div>
