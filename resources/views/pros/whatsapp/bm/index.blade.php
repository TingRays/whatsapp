{{-- 继承模版 --}}
@extends('pros.console.layouts.master')

{{-- 页面标题 --}}
@section('title', 'BM账户列表')

{{-- 自定义页面样式 --}}
@section('styles')

@endsection

{{-- 自定义主体内容 --}}
@section('container')
    {!!
        \Abnermouke\Pros\Builders\Table\TableBuilder::BASIC()
        ->setFilters(function (\Abnermouke\Pros\Builders\Table\Tools\TableFilterBuilder $filterBuilder) {
            $filterBuilder->input('keyword', '关键词搜索')->placeholder('请输入ID/账户名称/平台编号/姓名/登录账号关键词检索');
            $filterBuilder->select('status', '账户状态')->options(\App\Model\Pros\WhatsApp\BusinessManager::TYPE_GROUPS['__status__']);
            $filterBuilder->date('updated_at', '更新时间')->col(5);
        })
        ->setTabs(function (\Abnermouke\Pros\Builders\Table\Tools\TableTabBuilder $tabBuilder) {
            $tabBuilder->create('all', '全部', route('whatsapp.console.bm.lists'));
            $tabBuilder->create('enabled', '正常启用', route('whatsapp.console.bm.lists', ['status' => \App\Model\Pros\WhatsApp\BusinessManager::STATUS_ENABLED]));
            $tabBuilder->create('disabled', '已禁用', route('whatsapp.console.bm.lists', ['status' => \App\Model\Pros\WhatsApp\BusinessManager::STATUS_DISABLED]));
        })
        ->setButtons(function (\Abnermouke\Pros\Builders\Table\Tools\TableButtonBuilder $buttonBuilder) {
            $buttonBuilder->form(route('whatsapp.console.bm.detail', ['id' => 0]), '添加账户')->theme('info');
            $buttonBuilder->redirect(route('whatsapp.console.merchant.all.index'), '所有商户管理')->icon('fa fa-list');
        })
        ->setActions(function (\Abnermouke\Pros\Builders\Table\Tools\TableActionBuilder $actionBuilder) {
            $actionBuilder->form(route('whatsapp.console.bm.detail', ['id' => '__ID__']), '编辑账户')->icon('fa fa-edit');
            $actionBuilder->redirect(route('whatsapp.console.merchant.index', ['bm_id' => '__ID__']), '商户管理')->icon('fa fa-list');
        })
        ->setItems(function (\Abnermouke\Pros\Builders\Table\Tools\TableItemBuilder $itemBuilder) {
            $itemBuilder->info('guard_name', '账户名称')->description('姓名：{nickname}，BM编号：{code}')->image('avatar');
            $itemBuilder->string('ac_number', '登录账号')->bold()->badge('primary');
            $itemBuilder->string('ac_email', '邮箱')->bold();
            $itemBuilder->string('ac_spare_email', '备用邮箱')->bold();
            $itemBuilder->string('age', '年龄')->bold();
            $itemBuilder->switch('status', '账号状态')->on(\App\Model\Pros\WhatsApp\BusinessManager::STATUS_ENABLED, route('whatsapp.console.bm.enable', ['id' => '__ID__']), 'post', \App\Model\Pros\Console\Admins::TYPE_GROUPS['__status__'][\App\Model\Pros\WhatsApp\BusinessManager::STATUS_ENABLED])->off(\App\Model\Pros\WhatsApp\BusinessManager::STATUS_DISABLED, route('whatsapp.console.bm.enable', ['id' => '__ID__']), 'post', \App\Model\Pros\WhatsApp\BusinessManager::TYPE_GROUPS['__status__'][\App\Model\Pros\WhatsApp\BusinessManager::STATUS_DISABLED])->after_refresh();
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
