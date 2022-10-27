@php
    $value = data_get($__data__, $field, '');
@endphp
<div class="badge badge-{{ data_get($themes, $value, 'primary') }} badge-sm text-center @if($bold) fw-bold @endif">{{ data_get($options, $value, $empty_text) }}</div>
@if($description = pros_decode_template($description, $__data__, ''))
    <div class="fs-7 text-muted mt-1">{!! $description !!}</div>
@endif

