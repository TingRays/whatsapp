<div class="d-flex flex-column {{ $hidden ? 'd-none' : '' }} pros_form_{{ $sign }}_item_box" data-target="#pros_form_{{ $sign }}_item_{{ $field }}" data-required="{{ $required ? 1 : 0 }}" data-type="{{ $type }}" data-field="{{ $field }}" data-upload-url="{{ $upload_url }}" data-upload-dictionary="{{ $dictionary }}" data-default-value="{{ json_encode(object_2_array($default_value), JSON_UNESCAPED_UNICODE) }}">
    <label class="d-flex align-items-center fs-6 fw-bold mb-2">
        <span class="{{ $required ? 'required' : '' }}">{{ $guard_name }}</span>
        @if($tip)
            <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="{!! $tip !!}"></i>
        @endif
    </label>
    <div class="dropzone-queue dropzone py-10 px-5 bg-light-{{ \Illuminate\Support\Arr::random(data_get($__colors__, 'theme_alias', ['primary'])) }}" id="pros_form_{{ $sign }}_item_{{ $field }}_upload_box">
        <div class="text-center text-muted {{ object_2_array($default_value) ? 'd-none' : '' }}" id="pros_form_{{ $sign }}_item_{{ $field }}_upload_without_item">暂无图片</div>
        <div class="dropzone-items d-flex justify-content-center flex-wrap" id="pros_form_{{ $sign }}_item_{{ $field }}_upload_items">
            @foreach(object_2_array($default_value) as $link)
                <div class="dropzone-item mx-3 my-3 p-0 mt-0 pros_form_{{ $sign }}_item_{{ $field }}_upload_item bg-light-primary" data-link="{{ $link }}">
                    <a class="d-block overlay" href="javascript:;">
                        <div class="overlay-wrapper bgi-no-repeat bgi-position-center bgi-size-cover" style="background-image:url('{{ $link }}');height: {{ $box_height }}px;width: {{ $box_width }}px">
                        </div>
                        <div class="overlay-layer card-rounded bg-dark bg-opacity-25 shadow">
                            <i class="fa fa-trash-alt text-danger fs-2x pros_form_{{ $sign }}_item_{{ $field }}_upload_item_remover"></i>
                            @if(!\Abnermouke\EasyBuilder\Library\Currency\DeviceLibrary::mobile())
                                <i class="fa fa-bars text-warning fs-2x ms-5"></i>
                            @endif
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
    <input type="file" accept="{{ $accept }}" data-width="{{ $width }}" data-height="{{ $height }}" data-box-height="{{ $box_height }}" data-box-width="{{ $box_width }}" {{  !$cropper ? 'multiple' : '' }} data-cropper="{{ $cropper ? 1 : 0 }}" class="d-none" id="pros_form_{{ $sign }}_item_{{ $field }}_uploader" value="" autocomplete="off" {{ $readonly ? 'readonly' : '' }} {{ $disabled ? 'disabled' : '' }}>
    <input type="hidden" id="pros_form_{{ $sign }}_item_{{ $field }}" autocomplete="off" value="{{ json_encode($default_value) }}">
    <button id="pros_form_{{ $sign }}_item_{{ $field }}_trigger" class="btn btn-light-primary btn-sm me-3 my-3">选择图片</button>
    <span class="text-muted">鼠标移至图片上可对文件进行删除、排序等操作，长按排序按钮可上下左右拖动更改文件位置，展示顺序以实际上传速度为准。</span>
    @if($description)
        <div class="fs-7 fw-bold text-muted my-1">{!! $description !!}</div>
    @endif
    <div class="fs-7 fw-bold text-warning my-2 d-none" id="pros_form_{{ $sign }}_item_{{ $field }}_edited_warning">最近更新时间：<span class="edited_time fw-bold">{{ auto_datetime() }}</span></div>
</div>
