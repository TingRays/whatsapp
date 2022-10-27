<div class="d-flex flex-column {{ $hidden ? 'd-none' : '' }} pros_form_{{ $sign }}_item_box" data-target="#pros_form_{{ $sign }}_item_{{ $field }}" data-required="{{ $required ? 1 : 0 }}" data-type="{{ $type }}" data-field="{{ $field }}" data-default-value="{{ $default_value }}">
    <label class="d-flex align-items-center fs-6 fw-bold mb-2">
        <span class="{{ $required ? 'required' : '' }}">{{ $guard_name }}</span>
        @if($tip)
            <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="{!! $tip !!}"></i>
        @endif
    </label>
    @if(isset($prepend) || isset($append) || $clipboard || $target)
        <div class="input-group input-group-solid">
    @endif

            @if(isset($prepend))
                 <span class="input-group-text">
                    @if(!empty($prepend['icon']))
                         <i class="{{ $prepend['icon'] }}  fs-3" data-bs-toggle="tooltip" title="{{ $prepend['content'] }}"></i>
                     @else
                         {{ $prepend['content'] }}
                     @endif
                </span>
            @endif

            <input type="{{ isset($input_type) ? $input_type : 'text' }}" data-input-mode="{{ isset($input_mode) ? $input_mode : 'text' }}" class="form-control form-control-solid" {!! isset($format) ? 'data-format="'.$format.'"' : '' !!} {!! isset($range) ? 'data-date-range="'.($range ? 1 : 0).'"' : '' !!} {!! isset($ratio) ? 'data-price-ratio="'.(int)$ratio.'"' : '' !!} {!! isset($decimal) ? 'data-price-decimal="'.(int)$decimal.'"' : '' !!} autocomplete="off" id="pros_form_{{ $sign }}_item_{{ $field }}" placeholder="{{ isset($placeholder) ? $placeholder : '' }}" value="{{ $default_value }}" name="{{ $field }}" {!! isset($max_length) && (int)$max_length > 0 ? 'maxlength="'.$max_length.'"' : '' !!} {!! isset($max) ? 'max="'.$max.'"' : '' !!} {!! isset($min) ? 'min="'.$min.'"' : '' !!} {{ $readonly ? 'readonly' : '' }} {{ $disabled ? 'disabled' : '' }}/>

        @if(isset($append))
            <span class="input-group-text">
                @if(!empty($append['icon']))
                    <i class="{{ $append['icon'] }} fs-3" data-bs-toggle="tooltip" title="{{ $append['content'] }}"></i>
                @else
                    {{ $append['content'] }}
                @endif
            </span>
        @endif
        @if($clipboard)
            <span class="input-group-text pros_{{ $sign }}_form_item_tool" data-action-type="clipboard" data-clipboard-target="#pros_form_{{ $sign }}_item_{{ $field }}">
                 <i class="fa fa-copy cursor-pointer fs-3" data-bs-toggle="tooltip" title="复制内容"></i>
            </span>
        @endif
        @if($target)
            <span class="input-group-text pros_{{ $sign }}_form_item_tool" data-action-type="link" data-link-target="#pros_form_{{ $sign }}_item_{{ $field }}">
                 <i class="fa fa-link cursor-pointer fs-3" data-bs-toggle="tooltip" title="跳转链接"></i>
            </span>
        @endif

    @if(isset($prepend) || isset($append) || $clipboard || $target)
        </div>
    @endif
    @if($description)
        <div class="fs-7 fw-bold text-muted my-1">{!! $description !!}</div>
    @endif
    <div class="fs-7 fw-bold text-warning my-2 d-none" id="pros_form_{{ $sign }}_item_{{ $field }}_edited_warning">最近更新时间：<span class="edited_time fw-bold">{{ auto_datetime() }}</span></div>
</div>
