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
        ->addAlert('<i class="fa fa-info-circle me-3 text-primary"></i>分组连接：是当前分组的所有号码随机跳，号码连接：是当前号码跳WhatsApp页面')
        ->setFilters(function (\Abnermouke\Pros\Builders\Table\Tools\TableFilterBuilder $filterBuilder) use ($admin_id) {
            $filterBuilder->select('group_id', '粉丝分组')->options(array_column((new \App\Repository\Pros\WhatsApp\FansManageGroupRepository())->get(['admin_id'=>$admin_id], ['id', 'title']), 'title', 'id'));
            $filterBuilder->input('keyword', '关键词搜索')->placeholder('请输入ID/手机号等关键词检索');
            $filterBuilder->date('updated_at', '更新时间')->col(5);
        })
        ->setTabs(function (\Abnermouke\Pros\Builders\Table\Tools\TableTabBuilder $tabBuilder) {
            $tabBuilder->create('all', '全部', route('whatsapp.console.fans_manage.lists'));
        })
        ->setButtons(function (\Abnermouke\Pros\Builders\Table\Tools\TableButtonBuilder $buttonBuilder) {
            $buttonBuilder->form(route('whatsapp.console.fans_manage.detail', ['id' => 0]), '添加粉号')->theme('info');
            $buttonBuilder->redirect(route('whatsapp.console.fans_manage.posts'), '批量导入')->theme('info');
        })
        ->setActions(function (\Abnermouke\Pros\Builders\Table\Tools\TableActionBuilder $actionBuilder) {
            $actionBuilder->redirect('__JUMP_URL__', '查看')->icon('fa fa-eye text-info')->theme('info')->target(true);
            $actionBuilder->form(route('whatsapp.console.fans_manage.detail', ['id' => '__ID__']), '编辑')->icon('fa fa-edit');
            $actionBuilder->ajax(route('whatsapp.console.fans_manage.enable', ['id' => '__ID__']), '删除')->icon('fa fa-eraser text-danger')->theme('danger')->confirmed('该操作将删除此号码，是否继续删除？')->condition('status', [\App\Model\Pros\WhatsApp\FansManage::STATUS_ENABLED]);
        })
        ->setItems(function (\Abnermouke\Pros\Builders\Table\Tools\TableItemBuilder $itemBuilder) {
            $itemBuilder->string('mobile', '手机号')->bold()->badge('primary');
            $itemBuilder->string('group_name', '分组')->bold();
            $itemBuilder->string('group_url', '分组连接')->bold();
            $itemBuilder->string('jump_url', '号码连接')->bold();
            $itemBuilder->option('status', '状态')->bold()->options(\App\Model\Pros\WhatsApp\FansManage::TYPE_GROUPS['__status__'], \Abnermouke\Pros\Builders\BuilderProvider::THEME_COLORS['status']);
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
