<div class="d-flex flex-column {{ $hidden ? 'd-none' : '' }} pros_form_{{ $sign }}_item_box" data-target=".pros_form_{{ $sign }}_item_{{ $field }}_radio_item" data-required="{{ $required ? 1 : 0 }}" data-type="{{ $type }}" data-triggers="{{ isset($triggers) ? json_encode($triggers, JSON_UNESCAPED_UNICODE|JSON_NUMERIC_CHECK) : '' }}" data-field="{{ $field }}" data-default-value="{{ $default_value  }}">
    <label class="d-flex align-items-center fs-6 fw-bold mb-2">
        <span class="{{ $required ? 'required' : '' }}">{{ $guard_name }}</span>
        @if($tip)
            <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="{!! $tip !!}"></i>
        @endif
    </label>
    <div class="fv-row fv-plugins-icon-container fv-plugins-bootstrap5-row-valid">
        @foreach($options as $value => $guard_name)
            <label class="d-flex flex-stack mb-5 cursor-pointer my-5">
                <span class="d-flex align-items-center me-2">
                    <span class="symbol symbol-50px me-6">
                        @if(!\Abnermouke\EasyBuilder\Library\Currency\ValidateLibrary::link(($image = data_get($option_images, $value, ''))))
                            <div class="symbol-label bg-light-info">{{ abbr_pros_string($image) }}</div>
                        @else
                            <img src="{{ $image }}" alt="image" />
                        @endif
                    </span>
                    <span class="d-flex flex-column">
                        <span class="fw-bolder fs-6">{{ $guard_name }}</span>
                        <span class="fs-7 text-muted text-break">{!! data_get($option_descriptions, $value, '') !!}</span>
                    </span>
                </span>
                <span class="form-check form-check-custom form-check-solid">
                    <input class="form-check-input pros_form_{{ $sign }}_item_{{ $field }}_radio_item" type="radio" id="pros_form_{{ $sign }}_item_{{ $field }}_{{ $value }}" name="{{ $field }}" {{ $readonly ? 'readonly' : '' }} {{ $disabled ? 'disabled' : '' }} value="{{ $value }}" @if($default_value === $value) checked="checked" @endif>
                </span>
            </label>
        @endforeach
    </div>
    @if($description)
        <div class="fs-7 fw-bold text-muted my-1">{!! $description !!}</div>
    @endif
    <div class="fs-7 fw-bold text-warning my-2 d-none" id="pros_form_{{ $sign }}_item_{{ $field }}_edited_warning">最近更新时间：<span class="edited_time fw-bold">{{ auto_datetime() }}</span></div>
</div>
