@switch($formatting)
    @case('amount')
        @php
            $value = data_get($__data__, $field, $empty_text);
            if (($value !== $empty_text)) {
                 if ((int)$ratio > 0) {
                    $value = floatval($value) / (int)$ratio;
                 }
                 $value = number_format(floatval($value), (int)$decimal);
            }
        @endphp
    @break
    @case('date')
        @php
            $value = data_get($__data__, $field, $empty_text);
            $value = ($value !== $empty_text) ? ($format == 'friendly' ? friendly_time($value) : auto_datetime($format, $value)) : $value;
        @endphp
    @break
    @case('number')
        @php
            $value = data_get($__data__, $field, $empty_text);
            $value = ($value !== $empty_text) ? number_format($value) : $value;
        @endphp
    @break
    @default
        @php
            $value = data_get($__data__, $field, $empty_text);
        @endphp
    @break
@endswitch
@php
    $__data__[$field] = $value;
    $value = pros_decode_template($template, $__data__, $empty_text);
@endphp
@if(isset($__append__) && $__append__)
    <div class="col-sm-3 my-5">{{ $guard_name }}：{!! $value ? $value : $empty_text !!}</div>
@else
    @if($badge)
        <div class="badge badge-light-{{ $badge }} p-4 fs-7 @if($bold) fw-bold @endif">{!! $value ? $value : $empty_text !!}</div>
    @else
        <div class="text-{{ $theme }} @if($bold) fw-bold @endif">{!! $value ? $value : $empty_text !!}</div>
    @endif
    @if($description = pros_decode_template($description, $__data__, ''))
        <div class="fs-7 text-muted mt-1">{!! $description !!}</div>
    @endif
@endif

