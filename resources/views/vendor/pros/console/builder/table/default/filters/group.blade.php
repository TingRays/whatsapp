<div class="col-lg-{{ $col_number }} pros_table_filter_item" data-type="group" data-field="{{ $field }}" data-target=".pros_table_{{ $sign }}_filter_of_{{ $field }}" data-default-value="{{ $default_value }}" data-bind-tabs="{{ json_encode($tabs) }}">
    <label class="fs-6 form-label fw-bolder text-dark">{{ $guard_name }}</label>
    <div class="nav-group nav-group-fluid">
        @foreach($options as $value => $name)
            <label>
                <input type="radio" class="btn-check pros_table_{{ $sign }}_filter_of_{{ $field }}" name="{{ $field }}" value="{{ $value }}" @if($value == $default_value) checked="checked" @endif />
                <span class="btn btn-sm btn-color-muted btn-active btn-active-primary fw-bolder px-4">{{ $name }}</span>
            </label>
        @endforeach
    </div>
</div>
