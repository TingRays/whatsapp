@if($images = object_2_array(data_get($__data__, $field, [])))
    <div class="symbol-group symbol-hover mb-1">
        @foreach($images as $image)
            @if(\Abnermouke\EasyBuilder\Library\Currency\ValidateLibrary::link($image))
                <a data-fslightbox="gallery" class="symbol @if($circle) symbol-circle @endif symbol-{{ $size }}px" href="{{ $image }}">
                    <img src="{{ $image }}" alt="{{ $image }}" class="pros_table_image_preview" />
                </a>
            @else
                <div class="symbol @if($circle) symbol-circle @endif symbol-{{ $size }}px">
                    <div class="symbol-label bg-light-{{ \Illuminate\Support\Arr::random($bg_themes) }}">
                        <span class="fs-7">{{ abbr_pros_template($template, $__data__, $empty_text) }}</span>
                    </div>
                </div>
            @endif
        @endforeach
    </div>
@else
    <div class="text-{{ $theme }}">{!! $empty_text !!}</div>
@endif
@if($description = pros_decode_template($description, $__data__, ''))
    <div class="fs-7 text-muted mt-1">{!! $description !!}</div>
@endif
