<div class="col-lg-{{ $col_number }} pros_table_filter_item" data-type="tags" data-field="{{ $field }}" data-target="#pros_table_{{ $sign }}_filter_of_{{ $field }}" data-default-value="{{ $default_value }}" data-bind-tabs="{{ json_encode($tabs) }}">
    <label class="fs-6 form-label fw-bolder text-dark">{{ $guard_name }}</label>
    <input type="text" class="form-control form-control form-control-solid" autocomplete="off" name="{{ $field }}" placeholder="{{ $placeholder }}" id="pros_table_{{ $sign }}_filter_of_{{ $field }}" value="{!! $default_value !!}" />
</div>
