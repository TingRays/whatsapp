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
            $filterBuilder->input('keyword', '关键词搜索')->placeholder('请输入ID/手机号码等关键词检索');
            $filterBuilder->date('updated_at', '更新时间')->col(5);
        })
        ->setTabs(function (\Abnermouke\Pros\Builders\Table\Tools\TableTabBuilder $tabBuilder) use ($tel_code,$business_code) {
            $tabBuilder->create('all', '全部', route('whatsapp.console.mass_dispatch.lists',['tel_code'=>$tel_code,'business_code'=>$business_code]));
            $tabBuilder->create('enabled', '发送成功', route('whatsapp.console.mass_dispatch.lists', ['tel_code'=>$tel_code,'business_code'=>$business_code,'status' => \App\Model\Pros\WhatsApp\MassDispatch::STATUS_ENABLED]));
            $tabBuilder->create('disabled', '发送失败', route('whatsapp.console.mass_dispatch.lists', ['tel_code'=>$tel_code,'business_code'=>$business_code,'status' => \App\Model\Pros\WhatsApp\MassDispatch::STATUS_VERIFY_FAILED]));
        })
        ->setButtons(function (\Abnermouke\Pros\Builders\Table\Tools\TableButtonBuilder $buttonBuilder) {
            $buttonBuilder->redirect(route('whatsapp.console.mass_dispatch.posts'), '导入手机号')->icon('fa fa-car');
            $buttonBuilder->form(route('whatsapp.console.mass_dispatch_merchant.detail', ['id' => 0]), '发送模板获取')->icon('fa fa-hands-helping')->theme('info');
        })
        ->setActions(function (\Abnermouke\Pros\Builders\Table\Tools\TableActionBuilder $actionBuilder) {
            $actionBuilder->form(route('whatsapp.console.mass_dispatch.detail', ['id' => '__ID__']), '编辑用户')->icon('fa fa-edit');
        })
        ->setItems(function (\Abnermouke\Pros\Builders\Table\Tools\TableItemBuilder $itemBuilder) {
            $itemBuilder->info('mobile', '手机号码')->description('id：{id}')->image('avatar');
            $itemBuilder->string('result', '结果信息')->bold();
            $itemBuilder->option('status', '状态')->options(\App\Model\Pros\WhatsApp\MassDispatch::TYPE_GROUPS['__status__'], \Abnermouke\Pros\Builders\BuilderProvider::THEME_COLORS['status']);
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
