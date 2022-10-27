{{-- 继承模版 --}}
@extends('pros.console.layouts.master')

{{-- 页面标题 --}}
@section('title', '帮助文档')

{{-- 自定义页面样式 --}}
@section('styles')

@endsection

{{-- 自定义主体内容 --}}
@section('container')
    {!!
            \Abnermouke\Pros\Builders\Table\TableBuilder::BASIC()
            ->setButtons(function (\Abnermouke\Pros\Builders\Table\Tools\TableButtonBuilder $builder) {
                $builder->form(route('pros.console.help.docs.detail', ['id' => 0]), '新增文档')->icon('fa fa-plus')->size('xl');
                $builder->ajax(route('pros.console.help.docs.delete'), '删除选中文档')->icon('fa fa-trash')->theme('danger')->id_suffix('deleteSelected')->confirmed('帮助文档删除后不可恢复，是否继续？');
            })
            ->setItems(function (\Abnermouke\Pros\Builders\Table\Tools\TableItemBuilder $builder) {
                $builder->info('title', '文档信息')->description('{description}');
                $builder->string('alias', '标识')->bold()->badge('primary');
                $builder->option('type', '文档类型')->bold()->options(\App\Model\Pros\System\HelpDocs::TYPE_GROUPS['type'], \Abnermouke\Pros\Builders\BuilderProvider::THEME_COLORS['status']);
                $builder->string('updated_at', '更新时间')->date('friendly')->sorting();
            })
            ->setFilters(function (\Abnermouke\Pros\Builders\Table\Tools\TableFilterBuilder $builder) {
                $builder->input('keyword', '关键词搜索')->placeholder('请输入标题/标识/内容等关键词检索')->col(4);
                $builder->select('type', '文档类型')->options(\App\Model\Pros\System\HelpDocs::TYPE_GROUPS['type'])->col(4);
                $builder->date('updated_at', '更新时间')->format('friendly');
            })
            ->setActions(function (\Abnermouke\Pros\Builders\Table\Tools\TableActionBuilder $builder) {
                $builder->form(route('pros.console.help.docs.detail', ['id' => '__ID__']), '编辑文档')->icon('fa fa-edit')->size('xl');
            })
            ->setQuery(route('pros.console.help.docs.lists'))
            ->checkbox('id', ['deleteSelected'])
            ->export()
            ->pagination()
            ->render();
        !!}
@endsection

{{-- 自定义页面弹窗 --}}
@section('popups')

@endsection

{{-- 自定义页面javascript --}}
@section('script')

@endsection

