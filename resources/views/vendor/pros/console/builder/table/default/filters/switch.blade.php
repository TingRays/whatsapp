<div class="col-lg-{{ $col_number }} pros_table_filter_item" data-type="switch" data-field="{{ $field }}" data-target="#pros_table_{{ $sign }}_filter_of_{{ $field }}" data-default-value="{{ $default_value }}" data-bind-tabs="{{ json_encode($tabs) }}">
    <label class="fs-6 form-label fw-bolder text-dark">{{ $guard_name }}</label>
    <div class="form-check form-switch form-check-custom form-check-solid mt-1">
        <input class="form-check-input" id="pros_table_{{ $sign }}_filter_of_{{ $field }}" name="{{ $field }}" type="checkbox" @if($on_value == $default_value) checked @endif data-on-value="{{ $on_value }}" data-off-value="{{ $off_value }}"/>
    </div>
</div>
