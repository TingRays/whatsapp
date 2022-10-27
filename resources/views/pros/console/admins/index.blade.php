{{-- 继承模版 --}}
@extends('pros.console.layouts.master')

{{-- 页面标题 --}}
@section('title', '管理员列表')

{{-- 自定义页面样式 --}}
@section('styles')

@endsection

{{-- 自定义主体内容 --}}
@section('container')
    {!!
        \Abnermouke\Pros\Builders\Table\TableBuilder::BASIC()
        ->setFilters(function (\Abnermouke\Pros\Builders\Table\Tools\TableFilterBuilder $filterBuilder) {
            $filterBuilder->select('role_id', '权限角色')->options(array_column((new \App\Repository\Pros\Console\RoleRepository())->get([], ['id', 'guard_name']), 'guard_name', 'id'));
            $filterBuilder->input('keyword', '关键词搜索')->placeholder('请输入ID/用户名/昵称/手机号码/邮箱等关键词检索');
            $filterBuilder->date('updated_at', '更新时间')->col(5);
        })
        ->setTabs(function (\Abnermouke\Pros\Builders\Table\Tools\TableTabBuilder $tabBuilder) {
            $tabBuilder->create('all', '全部', route('pros.console.admins.lists'));
            $tabBuilder->create('enabled', '正常启用', route('pros.console.admins.lists', ['status' => \App\Model\Pros\Console\Admins::STATUS_ENABLED]));
            $tabBuilder->create('disabled', '已禁用', route('pros.console.admins.lists', ['status' => \App\Model\Pros\Console\Admins::STATUS_DISABLED]));
            $tabBuilder->create('trash', '回收站', route('pros.console.admins.lists', ['status' => \App\Model\Pros\Console\Admins::STATUS_DELETED]))->button_suffixes(false);
        })
        ->setButtons(function (\Abnermouke\Pros\Builders\Table\Tools\TableButtonBuilder $buttonBuilder) {
            $buttonBuilder->form(route('pros.console.admins.detail', ['id' => 0]), '添加管理员')->theme('info');
            $buttonBuilder->ajax(route('pros.console.admins.delete'), '删除选中管理员')->id_suffix('deleteSelected')->theme('danger')->confirmed('删除管理员后该账户将不可使用，可在回收站恢复启用状态，是否继续？');
        })
        ->setActions(function (\Abnermouke\Pros\Builders\Table\Tools\TableActionBuilder $actionBuilder) {
            $actionBuilder->form(route('pros.console.admins.detail', ['id' => '__ID__']), '编辑管理员')->icon('fa fa-edit');
            if ((int)(new \App\Handler\Cache\Data\Pros\System\ConfigCacheHandler())->get('CONSOLE_WECHAT_OAUTH') === \App\Model\Pros\System\Configs::SWITCH_ON) {
                $actionBuilder->modal(route('pros.console.admins.qrcode', ['id' => '__ID__']), '微信授权码')->bind_model_id('admin_wechat_oauth_qrcode_modal')->icon('fa fa-qrcode')->theme('info');
            }
        })
        ->setItems(function (\Abnermouke\Pros\Builders\Table\Tools\TableItemBuilder $itemBuilder) {
            $itemBuilder->info('nickname', '管理员信息')->description('电话：{mobile}，邮箱：{email}')->image('avatar');
            $itemBuilder->string('role_name', '权限角色')->bold()->badge('primary');
            $itemBuilder->string('wechat_open_id', '微信授权ID')->bold();
            $itemBuilder->switch('status', '账号状态')->on(\App\Model\Pros\Console\Admins::STATUS_ENABLED, route('pros.console.admins.enable', ['id' => '__ID__']), 'post', \App\Model\Pros\Console\Admins::TYPE_GROUPS['__status__'][\App\Model\Pros\Console\Admins::STATUS_ENABLED])->off(\App\Model\Pros\Console\Admins::STATUS_DISABLED, route('pros.console.admins.enable', ['id' => '__ID__']), 'post', \App\Model\Pros\Console\Admins::TYPE_GROUPS['__status__'][\App\Model\Pros\Console\Admins::STATUS_DISABLED])->after_refresh();
            $itemBuilder->string('login_count', '累计登录次数')->number()->theme('info')->sorting();
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
