<div class="d-flex align-items-center">
    @if($image)
        <div class="symbol symbol-50px overflow-hidden me-3">
            @php
                $image = data_get($__data__, $image, $empty_text);
            @endphp
            @if(\Abnermouke\EasyBuilder\Library\Currency\ValidateLibrary::link($image))
                <a data-fslightbox="gallery" class="symbol" href="{{ $image }}">
                    <img src="{{ $image }}" class="pros_table_image_preview" alt="{{ $image }}" />
                </a>
            @else
                <div class="symbol-label bg-{{ \Illuminate\Support\Arr::random($bg_themes) }}">
                    <span class="fs-7">{{ abbr_pros_template($template, $__data__, $empty_text) }}</span>
                </div>
            @endif
        </div>
    @endif
    <div class="d-flex flex-column">
        <div class="text-{{ $theme }} @if($bold) fw-bold @endif">{!! pros_decode_template($template, $__data__, $empty_text) !!}</div>
        @if($description = pros_decode_template($description, $__data__, ''))
            <div class="fs-7 text-muted mt-1">{!! \Abnermouke\EasyBuilder\Library\Currency\ValidateLibrary::hasHtml($description) ? $description : \Illuminate\Support\Str::limit($description, 50) !!}</div>
        @endif
    </div>
</div>


