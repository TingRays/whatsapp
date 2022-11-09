{{-- 继承模版 --}}
@extends('pros.console.layouts.master')

{{-- 页面标题 --}}
@section('title', '群发列表')

{{-- 自定义页面样式 --}}
@section('styles')

@endsection

{{-- 自定义主体内容 --}}
@section('container')
    {!!
        \Abnermouke\Pros\Builders\Table\TableBuilder::BASIC()
        ->setFilters(function (\Abnermouke\Pros\Builders\Table\Tools\TableFilterBuilder $filterBuilder) {
            $filterBuilder->input('keyword', '关键词搜索')->placeholder('请输入ID/标题等关键词检索');
            $filterBuilder->date('updated_at', '更新时间')->col(5);
        })
        ->setTabs(function (\Abnermouke\Pros\Builders\Table\Tools\TableTabBuilder $tabBuilder) {
            $tabBuilder->create('all', '全部', route('whatsapp.console.merchant.message.lists'));
            $tabBuilder->create('enabled', '即时发送', route('whatsapp.console.merchant.message.lists', ['mode' => \App\Model\Pros\WhatsApp\MerchantMessages::MODE_OF_IMMEDIATELY]));
            $tabBuilder->create('enabled', '定时发送', route('whatsapp.console.merchant.message.lists', ['mode' => \App\Model\Pros\WhatsApp\MerchantMessages::MODE_OF_TIMING]));
        })
        ->setButtons(function (\Abnermouke\Pros\Builders\Table\Tools\TableButtonBuilder $buttonBuilder) {
            $buttonBuilder->redirect(route('whatsapp.console.merchant.message.detail'), '发布新消息');
        })
        ->setActions(function (\Abnermouke\Pros\Builders\Table\Tools\TableActionBuilder $actionBuilder) {
            $actionBuilder->modal(route('whatsapp.console.merchant.message.accounts', ['id' => '__ID__']), '接收用户')->bind_model_id('message_accounts_modal')->after_none();
        })
        ->setItems(function (\Abnermouke\Pros\Builders\Table\Tools\TableItemBuilder $itemBuilder) {
            $itemBuilder->info('title', '标题')->description('发送类型：{type_str}，送达方式：{mode_str}')->image('avatar');
            $itemBuilder->string('template_title', '模板名称')->bold()->badge('primary');
            $itemBuilder->string('type_str', '类型')->bold();
            $itemBuilder->string('mode_str', '模式')->bold();
            $itemBuilder->string('timing_send_time', '发送时间')->bold();
            $itemBuilder->option('status', '状态')->options(\App\Model\Pros\WhatsApp\MerchantMessages::TYPE_GROUPS['__status__'], \Abnermouke\Pros\Builders\BuilderProvider::THEME_COLORS['status']);
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
    <div class="modal fade pros_table_{{ $model_sign = \Illuminate\Support\Str::random(10) }}_bind_modal" id="message_accounts_modal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header py-5">
                    <h5 class="modal-title">接收人员</h5>
                    <button type="button" class="btn btn-icon btn-sm btn-active-light-primary ms-2 pros_table_form_modal_close_icon" data-bs-dismiss="modal" aria-label="Close" id="message_accounts_modal_close_icon"><i aria-hidden="true" class="fa fa-times"></i></button>
                </div>
                <div class="mh-700px overflow-auto p-10">
                    <div class="table-responsive">
                        <table id="pros_table_{{ $model_sign }}_box" class="table pros_table_box rounded gs-7 align-middle table-row-dashed text-left fs-6 gy-5">
                            <thead class="border-gray-200 fs-5 fw-bold bg-lighten" id="pros_table_{{ $model_sign }}_thead">
                            <tr>
                                <th>商户</th>
                                <th>用户电话</th>
                                <th>发送类型</th>
                                <th>状态</th>
                                <th>发送时间</th>
{{--                                <th>是否已读</th>--}}
                            </tr>
                            </thead>
                            <tbody id="pros_table_{{ $model_sign }}_tbody" class="modal-body">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

{{-- 自定义页面javascript --}}
@section('script')

@endsection
