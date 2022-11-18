{{-- 继承模版 --}}
@extends('pros.console.layouts.master')

{{-- 页面标题 --}}
@section('title', '所有的商户列表')

{{-- 自定义页面样式 --}}
@section('styles')

@endsection

{{-- 自定义主体内容 --}}
@section('container')
    {!!
        \Abnermouke\Pros\Builders\Table\TableBuilder::BASIC()
        ->setFilters(function (\Abnermouke\Pros\Builders\Table\Tools\TableFilterBuilder $filterBuilder) {
            $filterBuilder->select('global_roaming', '国际区号')->options([]);
            $filterBuilder->input('keyword', '关键词搜索')->placeholder('请输入ID/商户名称/手机号/电话号码编号/业务帐户编号等关键词检索');
            $filterBuilder->date('updated_at', '更新时间')->col(5);
        })
        ->setTabs(function (\Abnermouke\Pros\Builders\Table\Tools\TableTabBuilder $tabBuilder) {
            $tabBuilder->create('all', '全部', route('whatsapp.console.merchant.all.lists'));
            $tabBuilder->create('enabled', '正常启用', route('whatsapp.console.merchant.all.lists', ['status' => \App\Model\Pros\WhatsApp\Merchants::STATUS_ENABLED]));
            $tabBuilder->create('disabled', '已禁用', route('whatsapp.console.merchant.all.lists', ['status' => \App\Model\Pros\WhatsApp\Merchants::STATUS_DISABLED]));
        })
        ->setButtons(function (\Abnermouke\Pros\Builders\Table\Tools\TableButtonBuilder $buttonBuilder) {
            //$buttonBuilder->form(route('whatsapp.console.merchant.detail', ['id' => 0]), '添加商户')->theme('info');
        })
        ->setActions(function (\Abnermouke\Pros\Builders\Table\Tools\TableActionBuilder $actionBuilder) {
            $actionBuilder->form(route('whatsapp.console.merchant.detail', ['bm_id' => '__BM_ID__','id' => '__ID__']), '编辑商户')->icon('fa fa-edit');
        })
        ->setItems(function (\Abnermouke\Pros\Builders\Table\Tools\TableItemBuilder $itemBuilder) {
            $itemBuilder->info('guard_name', '商户名称')->description('国际区号：{global_roaming}，手机号：{tel}')->image('avatar');
            $itemBuilder->string('tel_code', '电话号码编号')->bold()->badge('primary');
            $itemBuilder->string('business_code', '业务帐户编号')->bold();
            $itemBuilder->string('remainder', '剩余发送量')->bold();
            $itemBuilder->option('status', '账号状态')->options(\App\Model\Pros\WhatsApp\Merchants::TYPE_GROUPS['__status__'], \Abnermouke\Pros\Builders\BuilderProvider::THEME_COLORS['status']);
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
