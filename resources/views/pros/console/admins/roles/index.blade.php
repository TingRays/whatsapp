{{-- 继承模版 --}}
@extends('pros.console.layouts.master')

{{-- 页面标题 --}}
@section('title', '权限角色')

{{-- 自定义页面样式 --}}
@section('styles')

@endsection

{{-- 自定义主体内容 --}}
@section('container')
    {!!
        \Abnermouke\Pros\Builders\Table\TableBuilder::BASIC()
        ->setFilters(function (\Abnermouke\Pros\Builders\Table\Tools\TableFilterBuilder $filterBuilder) {
            $filterBuilder->input('keyword', '关键词搜索')->placeholder('请输入权限角色名称/标识等关键词检索');
            $filterBuilder->date('created_at', '创建时间');
            $filterBuilder->date('updated_at', '更新时间');
            $filterBuilder->switch('is_locked', '锁定')->on(\App\Model\Pros\Console\Roles::SWITCH_ON)->off(\App\Model\Pros\Console\Roles::SWITCH_OFF);
            $filterBuilder->switch('is_full_permission', '满权限角色')->on(\App\Model\Pros\Console\Roles::SWITCH_ON)->off(\App\Model\Pros\Console\Roles::SWITCH_OFF);
        })
        ->setButtons(function (\Abnermouke\Pros\Builders\Table\Tools\TableButtonBuilder $buttonBuilder) {
            $buttonBuilder->form(route('pros.console.admins.roles.detail', ['id' => 0]), '添加权限角色')->size('xl');
            $buttonBuilder->ajax(route('pros.console.admins.roles.delete'), '删除选中权限角色')->id_suffix('deleteSelected')->theme('danger')->confirmed('请先更换绑定此权限角色的管理员角色，锁定权限角色仅支持满权限账户删除，是否核对无误？');
        })
        ->setActions(function (\Abnermouke\Pros\Builders\Table\Tools\TableActionBuilder $actionBuilder) {
            $actionBuilder->form(route('pros.console.admins.roles.detail', ['id' => '__ID__']), '编辑角色')->icon('fa fa-edit')->size('xl');
        })
        ->setItems(function (\Abnermouke\Pros\Builders\Table\Tools\TableItemBuilder $itemBuilder) {
            $itemBuilder->info('guard_name', '权限角色')->description('{alias}');
            $itemBuilder->option('is_locked', '是否锁定')->options(\App\Model\Pros\Console\Roles::TYPE_GROUPS['__switch__'], \Abnermouke\Pros\Builders\BuilderProvider::THEME_COLORS['switch'])->description('锁定角色仅超级管理员可删除');
            $itemBuilder->switch('is_full_permission', '是否满权限')->on(\App\Model\Pros\Console\Roles::STATUS_ENABLED, route('pros.console.admins.roles.full.permissions', ['id' => '__ID__']), 'post', '满权限')->off(\App\Model\Pros\Console\Admins::STATUS_DISABLED, route('pros.console.admins.roles.full.permissions', ['id' => '__ID__']), 'post', '部分权限');
            $itemBuilder->image('avatars', '绑定管理员')->circle();
            $itemBuilder->string('updated_at', '更新时间')->date('friendly')->sorting();
        })
        ->checkbox('id', ['deleteSelected'])
        ->setQuery(route('pros.console.admins.roles.lists'))
        ->pagination()
        ->export()
        ->render();
     !!}
@endsection

{{-- 自定义页面弹窗 --}}
@section('popups')

@endsection

{{-- 自定义页面javascript --}}
@section('script')

@endsection
