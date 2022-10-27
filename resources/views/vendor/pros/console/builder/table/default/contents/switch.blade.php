<div class="form-check form-switch form-check-custom form-check-solid form-check-{{ $theme }} mt-1">
    @php
        $value = (int)data_get($__data__, $field, 0);
    @endphp
    <input class="form-check-input" type="checkbox" @if($value === (int)$on['value']) checked @endif data-on-query-url="{{ pros_decode_link($on['query_url'], $__data__, '') }}" data-on-params="{{ json_encode($on) }}" data-off-query-url="{{ pros_decode_link($off['query_url'], $__data__, '') }}" data-off-params="{{ json_encode($off) }}" data-after="{{ $after }}" />
    <label class="form-check-label text-muted @if($bold) fw-bold @endif">{{ $value === (int)$on['value'] ? $on['text'] : $off['text']}}</label>
</div>
@if($description = pros_decode_template($description, $__data__, ''))
    <div class="fs-7 text-muted mt-1">{!! $description !!}</div>
@endif

