<div class="d-flex flex-column {{ $hidden ? 'd-none' : '' }} pros_form_{{ $sign }}_item_box" data-target=".pros_form_{{ $sign }}_item_{{ $field }}_radio_item" data-required="{{ $required ? 1 : 0 }}" data-type="{{ $type }}" data-triggers="{{ isset($triggers) ? json_encode($triggers, JSON_UNESCAPED_UNICODE|JSON_NUMERIC_CHECK) : '' }}" data-field="{{ $field }}" data-default-value="{{ $default_value }}">
    <label class="d-flex align-items-center fs-6 fw-bold mb-2">
        <span class="{{ $required ? 'required' : '' }}">{{ $guard_name }}</span>
        @if($tip)
            <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="{!! $tip !!}"></i>
        @endif
    </label>
    <div class="table-responsive">
        <table class="table align-middle table-row-dashed fs-6 gy-5">
            <tbody class="text-gray-600 fw-bold">
                @foreach($options as $group_name => $groups)
                    @if($groups)
                        <tr>
                            <td class="text-gray-800">{{ $group_name }}</td>
                            <td>
                                <div class="d-flex">
                                    @foreach($groups as $value => $name)
                                        <label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20">
                                            <input class="form-check-input pros_form_{{ $sign }}_item_{{ $field }}_radio_item" type="radio" id="pros_form_{{ $sign }}_item_{{ $field }}_{{ $value }}" name="{{ $field }}" {{ $readonly ? 'readonly' : '' }} {{ $disabled ? 'disabled' : '' }} value="{{ $value }}" @if($default_value === $value) checked="checked" @endif>
                                            <span class="form-check-label">{{ $name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>
    @if($description)
        <div class="fs-7 fw-bold text-muted my-1">{!! $description !!}</div>
    @endif
    <div class="fs-7 fw-bold text-warning my-2 d-none" id="pros_form_{{ $sign }}_item_{{ $field }}_edited_warning">最近更新时间：<span class="edited_time fw-bold">{{ auto_datetime() }}</span></div>
</div>
