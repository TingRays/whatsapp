<div class="d-flex flex-column {{ $hidden ? 'd-none' : '' }} pros_form_{{ $sign }}_item_box" data-target="#pros_form_{{ $sign }}_item_{{ $field }}" data-required="{{ $required ? 1 : 0 }}" data-type="{{ $type }}" data-field="{{ $field }}" data-upload-url="{{ $upload_url }}" data-upload-dictionary="{{ $dictionary }}" data-default-value="{{ $default_value }}">
    <label class="d-flex align-items-center fs-6 fw-bold mb-2">
        <span class="{{ $required ? 'required' : '' }}">{{ $guard_name }}</span>
        @if($tip)
            <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="{!! $tip !!}"></i>
        @endif
    </label>
    <div class="fv-row" id="pros_form_{{ $sign }}_item_{{ $field }}_upload_box">
        <div class="bg-light-{{ \Illuminate\Support\Arr::random(data_get($__colors__, 'theme_alias', ['primary'])) }} mb-2" style="{{ $default_value ? 'background: url(\''.$default_value.'\');' : '' }}background-size: 100%;background-repeat: no-repeat;background-position: center;height: {{ $box_height }}px;width: {{ $box_width }}px" id="pros_form_{{ $sign }}_item_{{ $field }}_wrapper">
        </div>
        <div class="fv-row mt-1">
            <input type="file" accept="{{ $accept }}" data-width="{{ $width }}" data-height="{{ $height }}" data-cropper="{{ $cropper ? 1 : 0 }}"  class="d-none" id="pros_form_{{ $sign }}_item_{{ $field }}_uploader" value="" autocomplete="off" {{ $readonly ? 'readonly' : '' }} {{ $disabled ? 'disabled' : '' }}>
            <input type="hidden" id="pros_form_{{ $sign }}_item_{{ $field }}" autocomplete="off" value="{{ $default_value }}">
            <button id="pros_form_{{ $sign }}_item_{{ $field }}_trigger" class="btn btn-light-primary btn-sm me-3 my-3">选择图片</button>
            <button id="pros_form_{{ $sign }}_item_{{ $field }}_remover" class="btn btn-light-danger btn-sm me-3 my-3">移除图片</button>
        </div>
    </div>
    @if($description)
        <div class="fs-7 fw-bold text-muted my-1">{!! $description !!}</div>
    @endif
    <div class="fs-7 fw-bold text-warning my-2 d-none" id="pros_form_{{ $sign }}_item_{{ $field }}_edited_warning">最近更新时间：<span class="edited_time fw-bold">{{ auto_datetime() }}</span></div>
</div>
