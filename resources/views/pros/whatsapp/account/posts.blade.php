{{-- 继承模版 --}}
@extends('pros.console.layouts.master')

{{-- 页面标题 --}}
@section('title', '导入用户')

{{-- 自定义页面样式 --}}
@section('styles')

@endsection

{{-- 自定义主体内容 --}}
@section('container')
    <div class="card card-custom">
        <div class="card-body p-0">
            <div class="row justify-content-center my-10 px-8 my-lg-15 px-lg-10">
                <div class="col-xl-12 col-xxl-7">
                    <h3 class="mb-2 font-weight-bold text-dark text-center">导入指定用户模版</h3>
                    <p class="text-center text-muted fs-10"><a href="{{ proxy_assets('static/medias/excel/accounts_import.xlsx', 'pros') }}" target="_blank">（下载模板）</a>点击按钮上传指定模版用户信息单，请确保按照此模版上传，系统自动将对应用户信息保存，备注、用户标签等可为空（允许群发为是和否）。</p>
                </div>
                <div class="col-xl-12">
                    <img src="{{ proxy_assets('static/medias/images/post_import_example.png', 'pros') }}" alt="Example" class="w-100 opacity-75" style="border-radius: 20px 20px 0 0">
                    <div class="d-flex justify-content-center py-10">
                        <button type="button" class="btn btn-light-primary font-weight-bolder px-10 py-3" id="import_trigger">
                            立即导入
                        </button>
                        <input type="file" id="import_file_input" data-query-url="{{ route('whatsapp.console.account.import') }}" class="d-none" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

{{-- 自定义页面弹窗 --}}
@section('popups')

@endsection

{{-- 自定义页面javascript --}}
@section('script')
    <script src="{{ proxy_assets('console/themes/default/js/pages/accounts/import.js', 'pros') }}"></script>
@endsection
