<div class="d-flex flex-column {{ $hidden ? 'd-none' : '' }} pros_form_{{ $sign }}_item_box" data-target="#pros_form_{{ $sign }}_item_{{ $field }}" data-required="{{ $required ? 1 : 0 }}" data-type="{{ $type }}" data-field="{{ $field }}" data-upload-url="{{ $upload_url }}" data-upload-dictionary="{{ $dictionary }}" data-default-value="{{ json_encode(object_2_array($default_value), JSON_UNESCAPED_UNICODE) }}">
    <label class="d-flex align-items-center fs-6 fw-bold mb-2">
        <span class="{{ $required ? 'required' : '' }}">{{ $guard_name }}</span>
        @if($tip)
            <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="{!! $tip !!}"></i>
        @endif
    </label>
    <div class="dropzone-queue dropzone py-10 px-5 bg-light-{{ \Illuminate\Support\Arr::random(data_get($__colors__, 'theme_alias', ['primary'])) }}" id="pros_form_{{ $sign }}_item_{{ $field }}_upload_box">
        <div class="text-center text-muted {{ object_2_array($default_value) ? 'd-none' : '' }}" id="pros_form_{{ $sign }}_item_{{ $field }}_upload_without_item">暂无文件</div>
        <div class="dropzone-items" id="pros_form_{{ $sign }}_item_{{ $field }}_upload_items">
            @foreach(object_2_array($default_value) as $link)
                <div class="dropzone-item mx-3 my-3 mt-0 pros_form_{{ $sign }}_item_{{ $field }}_upload_item" data-link="{{ $link }}">
                    <a class="d-block overlay w-100 align-items-center" href="javascript:;">
                        <div class="overlay-wrapper text-gray-800 p-5">{{ \Abnermouke\EasyBuilder\Library\Currency\ValidateLibrary::link($link) ? substr(parse_url($link)['path'], 1, strlen(parse_url($link)['path'])) : '' }}</div>
                        <div class="overlay-layer card-rounded bg-dark bg-opacity-20 shadow">
                            <i class="fa fa-eye text-primary fs-3 pros_form_{{ $sign }}_item_{{ $field }}_upload_item_previewer" data-bs-toggle="tooltip" data-bs-dismiss="click" title="预览/查看文件内容"></i>
                            <i class="fa fa-trash-alt text-danger fs-3 ms-5 pros_form_{{ $sign }}_item_{{ $field }}_upload_item_remover" data-bs-toggle="tooltip" data-bs-dismiss="click" title="删除当前文件"></i>
                            @if(!\Abnermouke\EasyBuilder\Library\Currency\DeviceLibrary::mobile())
                                <i class="fa fa-bars text-warning fs-3 ms-5 pros_form_{{ $sign }}_item_{{ $field }}_upload_item_mover" data-bs-toggle="tooltip" data-bs-dismiss="click" title="长按拖动排序"></i>
                            @endif
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
    <input type="file" accept="{{ $accept }}" {{  $multiple ? 'multiple' : '' }} class="d-none" id="pros_form_{{ $sign }}_item_{{ $field }}_uploader" value="" autocomplete="off" {{ $readonly ? 'readonly' : '' }} {{ $disabled ? 'disabled' : '' }}>
    <input type="hidden" id="pros_form_{{ $sign }}_item_{{ $field }}" data-single="{{ $single ? 1 : 0 }}" autocomplete="off" value="{{ json_encode($default_value) }}">
    <button id="pros_form_{{ $sign }}_item_{{ $field }}_trigger" class="btn btn-light-primary btn-sm me-3 my-3">选择文件</button>
    <span class="text-muted">文件右侧可对当前文件进行预览、排序、删除等操作，展示顺序以实际上传速度为准。</span>
    @if($description)
        <div class="fs-7 fw-bold text-muted my-1">{!! $description !!}</div>
    @endif
    <div class="fs-7 fw-bold text-warning my-2 d-none" id="pros_form_{{ $sign }}_item_{{ $field }}_edited_warning">最近更新时间：<span class="edited_time fw-bold">{{ auto_datetime() }}</span></div>
</div>
