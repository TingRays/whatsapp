<div class="col-lg-{{ $col_number }} pros_table_filter_item" data-type="select" data-field="{{ $field }}" data-target="#pros_table_{{ $sign }}_filter_of_{{ $field }}" data-default-value="{{ $default_value }}" data-bind-tabs="{{ json_encode($tabs) }}">
    <label class="fs-6 form-label fw-bolder text-dark">{{ $guard_name }}</label>
    <select class="form-select form-select-solid" id="pros_table_{{ $sign }}_filter_of_{{ $field }}" name="{{ $field }}" data-control="select2"  data-allow-clear="true">
        <option value="">选择{{ $guard_name }}</option>
        @foreach($options as $value => $name)
            <option value="{{ $value }}" @if($value == $default_value) selected @endif>{{ $name }}</option>
        @endforeach
    </select>
</div>
