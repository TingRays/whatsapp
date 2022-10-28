{{-- 继承模版 --}}
@extends('pros.console.layouts.master')

{{-- 页面标题 --}}
@section('title', '发布用户消息')

{{-- 自定义页面样式 --}}
@section('styles')

@endsection

{{-- 自定义主体内容 --}}
@section('container')
    {!!
            \Abnermouke\Pros\Builders\Form\FormBuilder::make()
            ->setSubmit(route('whatsapp.console.merchant.message.store'), '消息将立即发送至用户消息通知中，是否继续？', '立即发送', route('whatsapp.console.merchant.message.index'))
            ->setTitle('发布消息')
            ->setDescription('<i class="fa fa-info-circle me-3 text-primary"></i>消息发布可分为群发与单独发送，群发将一次性发送给指定用户（群体），单独发送将只发送给指定用户，请根据实际需求进行选择！')
            ->setItems(function (\Abnermouke\Pros\Builders\Form\Tools\FormItemBuilder $builder) use ($accounts, $account_tags, $templates, $default_title) {
                $builder->input('title', '消息标题')->default_value($default_title)->required();
                $builder->select('template_id', '消息模板')->options($templates)->required();
                $builder->radio('method', '发送方式')->options(['single' => '单独发送', 'group' => '指定用户', 'tags' => '标签用户'])->required()->default_value('single')->trigger('single', ['account_id'])->trigger('group', ['account_ids'])->trigger('tags', ['account_tag_id']);
                $builder->select('account_id', '接收用户')->required()->options($accounts)->description('单独发送仅可选择一个有效激活用户进行发送');
                $builder->select('account_ids', '指定用户')->required()->options($accounts)->multiple(true)->description('可选择多个用户进行消息发送');
                $builder->select('account_tag_id', '用户标签')->required()->options($account_tags)->description('可同时向统一用户标签群体进行消息发送，该标签下所有用户均会收到当前消息通知');
                $builder->switch('mode', '定时发送')->description('开启则在规定时间发送，否则立即发送')->on(\App\Model\Pros\WhatsApp\MerchantMessages::MODE_OF_TIMING, ['timing_send_time'])->off(\App\Model\Pros\WhatsApp\MerchantMessages::MODE_OF_IMMEDIATELY);
                $builder->input('timing_send_time', '发送时间')->readonly()->required()->date_format('Y-m-d H:i:00');
            })
            ->setTabs(function (\Abnermouke\Pros\Builders\Form\Tools\FormTabBuilder $builder) {
                $builder->create('消息发布')->group(['title','template_id'])->group(['method', 'account_id', 'account_ids', 'account_tag_id'])->group(['mode', 'timing_send_time']);
            })
            ->render();
        !!}
@endsection

{{-- 自定义页面弹窗 --}}
@section('popups')

@endsection

{{-- 自定义页面javascript --}}
@section('script')

@endsection
