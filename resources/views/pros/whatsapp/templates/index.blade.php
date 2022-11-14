{{-- 继承模版 --}}
@extends('pros.console.layouts.master')

{{-- 页面标题 --}}
@section('title', '生成虚拟手机号')

{{-- 自定义页面样式 --}}
@section('styles')

@endsection

{{-- 自定义主体内容 --}}
@section('container')
    {!!
        \Abnermouke\Pros\Builders\Table\TableBuilder::BASIC()
        ->setFilters(function (\Abnermouke\Pros\Builders\Table\Tools\TableFilterBuilder $filterBuilder) use ($region_code) {
            //$filterBuilder->select('global_roaming', '区号')->options($region_code);
            $filterBuilder->input('keyword', '关键词搜索')->placeholder('请输入ID/模板名称');
            $filterBuilder->select('status', '状态')->options(\App\Model\Pros\WhatsApp\Fictitious::TYPE_GROUPS['__status__']);
            $filterBuilder->date('updated_at', '更新时间')->col(5);
        })
        ->setTabs(function (\Abnermouke\Pros\Builders\Table\Tools\TableTabBuilder $tabBuilder) {
            $tabBuilder->create('all', '全部', route('whatsapp.console.fictitious.lists'));
        })
        ->setButtons(function (\Abnermouke\Pros\Builders\Table\Tools\TableButtonBuilder $buttonBuilder) {
            $buttonBuilder->form(route('whatsapp.console.fictitious.detail',['id' => 0]), '添加模板')->theme('info');
        })
        ->setActions(function (\Abnermouke\Pros\Builders\Table\Tools\TableActionBuilder $actionBuilder) {
            $actionBuilder->form(route('whatsapp.console.merchant.detail', ['id' => '__ID__']), '编辑商户')->icon('fa fa-edit');
        })
        ->setItems(function (\Abnermouke\Pros\Builders\Table\Tools\TableItemBuilder $itemBuilder) {
            $itemBuilder->string('title', '模板名称')->bold()->badge('primary');
            $itemBuilder->string('type', '模板类型')->bold();
            $itemBuilder->string('language', '模板语言')->bold();
            $itemBuilder->option('status', '状态')->bold()->options(\App\Model\Pros\WhatsApp\Fictitious::TYPE_GROUPS['__status__'], \Abnermouke\Pros\Builders\BuilderProvider::THEME_COLORS['status']);
            $itemBuilder->string('updated_at', '更新时间')->date('friendly')->sorting();
        })
        ->checkbox('id', ['deleteSelected'])
        ->pagination()
        ->export()
        ->render();
     !!}
@endsection

{{-- 自定义页面弹窗 --}}
@section('popups')
    <div class="modal fade pros_table_form_modal" id="admin_wechat_oauth_qrcode_modal">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-dialog mw-650px">
                <div class="modal-content">
                    <div class="modal-header pb-0 border-0 justify-content-end">
                        <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal" id="admin_wechat_oauth_qrcode_modal_close_icon">
                            <i class="fa fa-times"></i>
                        </div>
                    </div>
                    <div class="modal-body scroll-y mx-5 mx-xl-18 pt-0 pb-15">

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

{{-- 自定义页面javascript --}}
@section('script')

@endsection
