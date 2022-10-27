{{-- 继承模版 --}}
@extends('pros.console.layouts.master')

{{-- 页面标题 --}}
@section('title', '商户列表')

{{-- 自定义页面样式 --}}
@section('styles')

@endsection

{{-- 自定义主体内容 --}}
@section('container')
    {!!
        \Abnermouke\Pros\Builders\Table\TableBuilder::BASIC()
        ->setFilters(function (\Abnermouke\Pros\Builders\Table\Tools\TableFilterBuilder $filterBuilder) {
            $filterBuilder->select('global_roaming', '国际区号')->options([]);
            $filterBuilder->input('keyword', '关键词搜索')->placeholder('请输入ID/手机号码等关键词检索');
            $filterBuilder->date('updated_at', '更新时间')->col(5);
        })
        ->setTabs(function (\Abnermouke\Pros\Builders\Table\Tools\TableTabBuilder $tabBuilder) {
            $tabBuilder->create('all', '全部', route('whatsapp.console.account.lists'));
            $tabBuilder->create('enabled', '正常启用', route('whatsapp.console.account.lists', ['status' => \App\Model\Pros\WhatsApp\Accounts::STATUS_ENABLED]));
            $tabBuilder->create('disabled', '已禁用', route('whatsapp.console.account.lists', ['status' => \App\Model\Pros\WhatsApp\Accounts::STATUS_DISABLED]));
        })
        ->setButtons(function (\Abnermouke\Pros\Builders\Table\Tools\TableButtonBuilder $buttonBuilder) {
            $buttonBuilder->form(route('whatsapp.console.account.detail', ['id' => 0]), '添加用户')->theme('info');
        })
        ->setActions(function (\Abnermouke\Pros\Builders\Table\Tools\TableActionBuilder $actionBuilder) {
            $actionBuilder->form(route('whatsapp.console.account.detail', ['id' => '__ID__']), '编辑用户')->icon('fa fa-edit');
        })
        ->setItems(function (\Abnermouke\Pros\Builders\Table\Tools\TableItemBuilder $itemBuilder) {
            $itemBuilder->info('mobile', '手机号码')->description('国际区号：{global_roaming}')->image('avatar');
            $itemBuilder->option('gender', '性别')->options(\App\Model\Pros\WhatsApp\Accounts::TYPE_GROUPS['gender'], \Abnermouke\Pros\Builders\BuilderProvider::THEME_COLORS['switch']);
            $itemBuilder->string('tags', '标签')->bold();
            $itemBuilder->string('remarks', '备注')->bold();
            //$itemBuilder->string('source', '来源')->bold();
            $itemBuilder->switch('status', '账号状态')->on(\App\Model\Pros\WhatsApp\Merchants::STATUS_ENABLED, route('whatsapp.console.account.enable', ['id' => '__ID__']), 'post', \App\Model\Pros\WhatsApp\Merchants::TYPE_GROUPS['__status__'][\App\Model\Pros\WhatsApp\Merchants::STATUS_ENABLED])->off(\App\Model\Pros\WhatsApp\Merchants::STATUS_DISABLED, route('whatsapp.console.merchant.enable', ['id' => '__ID__']), 'post', \App\Model\Pros\WhatsApp\Merchants::TYPE_GROUPS['__status__'][\App\Model\Pros\WhatsApp\Merchants::STATUS_DISABLED])->after_refresh();
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
