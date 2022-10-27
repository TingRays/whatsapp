{{-- 继承模版 --}}
@extends('pros.console.layouts.master')

{{-- 页面标题 --}}
@section('title', '短信记录')

{{-- 自定义页面样式 --}}
@section('styles')

@endsection

{{-- 自定义主体内容 --}}
@section('container')
    @if(data_get((new \App\Handler\Cache\Data\Pros\System\ConfigCacheHandler())->get('SMS_ALI_PARAMS', ''), 'access_key_id', false))
        {!!
            \Abnermouke\Pros\Builders\Table\TableBuilder::BASIC()
            ->setFilters(function (\Abnermouke\Pros\Builders\Table\Tools\TableFilterBuilder $builder) {
                $builder->input('keyword', '关键词搜索')->placeholder('请输入手机号码/短信内容/短信签名/模版ID/网关等关键词检索')->col(6);
                $builder->date('created_at', '创建时间');
            })->setItems(function (\Abnermouke\Pros\Builders\Table\Tools\TableItemBuilder $builder) {
                $builder->info('mobile', '短信信息')->description('{content}');
                $builder->string('gateway', '网关');
                $builder->string('sign_name', '签名')->bold()->badge('primary');
                $builder->string('template_id', '模版ID');
                $builder->string('result_code', '结果编码')->bold()->badge('success');
                $builder->string('ip', '操作IP')->bold();
                $builder->string('created_at', '发送时间')->date();
            })
            ->setTabs(function (\Abnermouke\Pros\Builders\Table\Tools\TableTabBuilder $builder) {
                $builder->create('ALL', '全部记录', route('pros.console.systems.sms.lists'));
                $builder->create('SUCCESS', '发送成功', route('pros.console.systems.sms.lists', ['status' => \App\Model\Pros\System\SmsLogs::STATUS_ENABLED]));
                $builder->create('FAIL', '发送失败', route('pros.console.systems.sms.lists', ['status' => \App\Model\Pros\System\SmsLogs::STATUS_DISABLED]));
            })
            ->render();
        !!}
    @else
        {!!
            \Abnermouke\Pros\Builders\Form\FormBuilder::make()
            ->setTitle('阿里云SMS短信配置')
            ->setDescription('推荐您默认使用阿里云SMS短信服务，短信的内容多用于企业向用户传递验证码、系统通知、会员服务等。可点击链接 <a href="https://help.aliyun.com/document_detail/337013.html" target="_blank">https://help.aliyun.com/document_detail/337013.html</a> 根据提示进行操作。')
            ->setItems(function (\Abnermouke\Pros\Builders\Form\Tools\FormItemBuilder $builder) {
                $builder->input('SMS_ALI_PARAMS__access_key_id', 'AccessKey ID')->clipboard()->tip('用于标识用户')->cols(6);
                $builder->input('SMS_ALI_PARAMS__access_key_secret', 'AccessKey Secret')->clipboard()->tip('用于验证用户的密钥，AccessKey Secret必须保密!')->cols(6);
                $builder->input('SMS_ALI_PARAMS__sign_name', '短信签名')->clipboard()->tip('短信签名需特别申请，详看流程操作！');
            })
            ->setSubmit(route('pros.console.systems.configs.store'))
            ->render();
        !!}
    @endif
@endsection

{{-- 自定义页面弹窗 --}}
@section('popups')

@endsection

{{-- 自定义页面javascript --}}
@section('script')

@endsection
