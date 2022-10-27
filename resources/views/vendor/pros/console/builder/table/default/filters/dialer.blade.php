<div class="col-lg-{{ $col_number }} pros_table_filter_item" data-type="dialer" data-field="{{ $field }}" data-target="#pros_table_{{ $sign }}_filter_of_{{ $field }}" data-default-value="{{ $default_value }}" data-bind-tabs="{{ json_encode($tabs) }}">
    <label class="fs-6 form-label fw-bolder text-dark">{{ $guard_name }}</label>
    <div class="position-relative" data-kt-dialer="true" data-kt-dialer-min="{{ $min }}" data-kt-dialer-max="{{ $max }}" data-kt-dialer-step="{{ $step }}" data-kt-dialer-decimals="{{ $decimals }}">
        <button type="button" class="btn btn-icon btn-active-color-gray-700 position-absolute translate-middle-y top-50 start-0" data-kt-dialer-control="decrease">
            <span class="svg-icon svg-icon-1">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                     fill="none">
                    <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="black"/>
                    <rect x="6.01041" y="10.9247" width="12" height="2" rx="1" fill="black"/>
                </svg>
            </span>
        </button>
        <input type="text" class="form-control form-control-solid border-0 ps-12" autocomplete="off" id="pros_table_{{ $sign }}_filter_of_{{ $field }}" data-kt-dialer-control="input" name="{{ $field }}" value="{{ $default_value }}"/>
        <button type="button" class="btn btn-icon btn-active-color-gray-700 position-absolute translate-middle-y top-50 end-0" data-kt-dialer-control="increase">
            <span class="svg-icon svg-icon-1">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                     fill="none">
                    <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="black"/>
                    <rect x="10.8891" y="17.8033" width="12" height="2" rx="1"
                          transform="rotate(-90 10.8891 17.8033)" fill="black"/>
                    <rect x="6.01041" y="10.9247" width="12" height="2" rx="1" fill="black"/>
                </svg>
            </span>
        </button>
    </div>
</div>
