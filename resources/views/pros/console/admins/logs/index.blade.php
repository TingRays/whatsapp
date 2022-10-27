{{-- 继承模版 --}}
@extends('pros.console.layouts.master')

{{-- 页面标题 --}}
@section('title', '管理员操作日志列表')

{{-- 自定义页面样式 --}}
@section('styles')

@endsection

{{-- 自定义主体内容 --}}
@section('container')
    {!!
        \Abnermouke\Pros\Builders\Table\TableBuilder::BASIC()
        ->setFilters(function (\Abnermouke\Pros\Builders\Table\Tools\TableFilterBuilder $filterBuilder) {
            $filterBuilder->select('admin_id', '管理员')->options(array_column((new \App\Repository\Pros\Console\AdminRepository())->get([], ['id', 'nickname']), 'nickname', 'id'));
            $filterBuilder->input('keyword', '关键词搜索')->placeholder('请输入管理员昵称/用户名/操作内容等关键词检索');
            $filterBuilder->date('created_at', '创建时间')->col(5);
        })
        ->setItems(function (\Abnermouke\Pros\Builders\Table\Tools\TableItemBuilder $itemBuilder) {
            $itemBuilder->info('content', '管理员信息')->description('{nickname}')->image('avatar');
            $itemBuilder->string('mobile', '联系电话')->bold()->badge('primary');
            $itemBuilder->string('email', '联系邮箱')->bold()->badge('info');
            $itemBuilder->string('ip', '操作IP')->bold()->badge('warning');
            $itemBuilder->string('created_at', '创建时间')->date('friendly')->sorting(true, \App\Model\Pros\Console\AdminLogs::TABLE_NAME);
        })
        ->setQuery(route('pros.console.admins.logs.lists'))
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
