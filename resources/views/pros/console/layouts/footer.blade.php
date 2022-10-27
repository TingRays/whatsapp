<div class="footer py-4 d-flex flex-lg-column" id="kt_footer">
    <div class="container-fluid d-flex flex-column flex-md-row align-items-center justify-content-between">
        <div class="text-dark order-2 order-md-1">
            <span class="text-muted fw-bold me-1">{{date('Y')}}©</span>
            <a href="{{ config('app.url') }}" target="_blank" class="text-gray-800 text-hover-primary">{{ (new \App\Handler\Cache\Data\Pros\System\ConfigCacheHandler())->get('APP_NAME', 'Pros') }}</a>
        </div>
        <ul class="menu menu-gray-600 menu-hover-primary fw-bold order-1">
            <li class="menu-item">
                <a href="https://github.com/abnermouke/pros" target="_blank" class="menu-link px-2">Github</a>
            </li>
            <li class="menu-item">
                <a href="https://github.com/abnermouke" target="_blank" class="menu-link px-2">Abnermouke</a>
            </li>
        </ul>
    </div>
</div>
