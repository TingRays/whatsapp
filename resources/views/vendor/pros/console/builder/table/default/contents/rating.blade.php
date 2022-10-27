<div class="rating">
    @php
        $rating_value = (int)data_get($__data__, $field, 0);
    @endphp
    @for($i = 1; $i <=5; $i++)
        <div class="rating-label me-1 {{ (int)$rating_value >= (int)$i ? 'checked' : '' }}">
            <i class="bi bi-star-fill fs-6s"></i>
        </div>
    @endfor
</div>
@if($description = pros_decode_template($description, $__data__, ''))
    <div class="fs-7 text-muted mt-1 @if($bold) fw-bold @endif">{!! $description !!}</div>
@endif

