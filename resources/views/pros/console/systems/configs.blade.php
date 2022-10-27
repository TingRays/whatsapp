{{-- 继承模版 --}}
@extends('pros.console.layouts.master')

{{-- 页面标题 --}}
@section('title', '系统配置')

{{-- 自定义页面样式 --}}
@section('styles')

@endsection

{{-- 自定义主体内容 --}}
@section('container')
    {!!
        \Abnermouke\Pros\Builders\Form\FormBuilder::make()
        ->setTitle('系统配置')
        ->setDescription('<i class="fa fa-info-circle text-success me-4"></i>温馨提示：更改App应用相关配置，系统将在一定时间内自动更新并开始使用最新配置进行执行操作。')
        ->setItems(function (\Abnermouke\Pros\Builders\Form\Tools\FormItemBuilder $builder) {
            $builder->input('APP_NAME', '应用/站点名称')->required();
            $builder->textarea('APP_DESCRIPTION', '应用/站点描述')->required();
            $builder->tags('APP_KEYWORDS', '应用/站点关键词')->required();
            $builder->image('APP_LOGO', 'LOGO')->size('500x500')->dictionary('configs/images')->required();
            $builder->image('APP_SHORTCUT_ICON', '小图标')->size('50x50')->dictionary('configs/images')->required();
            $builder->switch('CONSOLE_WECHAT_OAUTH', '开启控制台微信登录')->allow_text('开启')->on(\App\Model\Pros\System\Configs::SWITCH_ON, ['WECHAT_OFFICE_ACCOUNT_PARAMS__app_id', 'WECHAT_OFFICE_ACCOUNT_PARAMS__secret', 'WECHAT_OFFICE_ACCOUNT_PARAMS__token', 'WECHAT_OFFICE_ACCOUNT_PARAMS__aes_key'])->off(\App\Model\Pros\System\Configs::SWITCH_OFF);
            $builder->switch('QINIU_SYNC_AUTO', '文件资源自动上传七牛云')->allow_text('开启')->on(\App\Model\Pros\System\Configs::SWITCH_ON, ['QINIU_PARAMS__domain', 'QINIU_PARAMS__access_key', 'QINIU_PARAMS__access_secret', 'QINIU_PARAMS__bucket', 'QINIU_PARAMS__visibility'])->off(\App\Model\Pros\System\Configs::SWITCH_OFF);
            $builder->input('WECHAT_OFFICE_ACCOUNT_PARAMS__app_id', '微信公众号APPID')->clipboard()->required()->cols(6);
            $builder->input('WECHAT_OFFICE_ACCOUNT_PARAMS__secret', '微信公众号APP密钥')->clipboard()->required()->cols(6);
            $builder->input('WECHAT_OFFICE_ACCOUNT_PARAMS__token', '微信公众号通讯TOKEN')->clipboard()->required()->cols(6);
            $builder->input('WECHAT_OFFICE_ACCOUNT_PARAMS__aes_key', '微信公众号AES KEY')->clipboard()->required()->cols(6);
            $builder->input('QINIU_PARAMS__domain', '七牛访问域名')->input_type('url')->required();
            $builder->input('QINIU_PARAMS__access_key', '七牛云 AccessKey')->clipboard()->required()->cols(4);
            $builder->input('QINIU_PARAMS__access_secret', '七牛云 AccessKey Secret')->clipboard()->required()->cols(4);
            $builder->input('QINIU_PARAMS__bucket', '七牛云储存空间bucket')->clipboard()->required()->cols(4);
            $builder->radio('QINIU_PARAMS__visibility', '储存空间性质')->options_with_descriptions(['public' => '公有云', 'private' => '私有云'], ['public' => '可通过文件对象的 URL 直接访问。', 'private' => '文件对象的访问则必须获得拥有者的授权才能访问。'], 'public')->required();
            $builder->editor('PRIVACY_POLICY', '隐私政策');
            $builder->editor('USER_AGREEMENT', '用户服务协议');
            $builder->input('SMS_SECOND_FREQUENCY_LIMIT', '同一手机号码获取短信最小时间间隔')->number()->min(60)->default_value(60)->required()->append('秒')->cols(4);
            $builder->input('SMS_DAY_FREQUENCY_LIMIT', '同一手机号码每天可获取短信条数')->number()->min(0)->default_value(20)->required()->append('条')->cols(4);
            $builder->input('SMS_UNIVERSAL_CODE', '短信万能验证码')->default_value((string)auto_datetime('md'))->required()->clipboard()->cols(4);
            $builder->select('SMS_DEFAULT_GATEWAYS', '短信默认网关')->options(['ali_sms' => '阿里云SMS服务'])->required();
            $builder->input('SMS_ALI_PARAMS__access_key_id', 'AccessKey ID')->clipboard()->tip('用于标识用户')->cols(4);
            $builder->input('SMS_ALI_PARAMS__access_key_secret', 'AccessKey Secret')->clipboard()->tip('用于验证用户的密钥，AccessKey Secret必须保密!')->cols(4);
            $builder->input('SMS_ALI_PARAMS__sign_name', '短信签名')->clipboard()->tip('短信签名需特别申请，详看流程操作！')->cols(4);
            $builder->input('TEMPORARY_FILES_AUTO_DELETE_SECOND_LIMIT', '临时文件自动删除时间')->number()->min(3600)->max(86400)->append('秒')->required();
            $builder->input('AMAP_WEB_SERVER_API_KEY', '高德地图WEB服务API KEY')->clipboard();
        })
        ->setTabs(function (\Abnermouke\Pros\Builders\Form\Tools\FormTabBuilder $builder) {
            $builder->create('基本配置')->group(['APP_NAME', 'APP_DESCRIPTION', 'APP_KEYWORDS'])->group(['APP_SHORTCUT_ICON', 'APP_LOGO']);
            $builder->create('自动化配置')->group(['SMS_SECOND_FREQUENCY_LIMIT', 'SMS_DAY_FREQUENCY_LIMIT', 'SMS_UNIVERSAL_CODE', 'SMS_DEFAULT_GATEWAYS'], '短信配置')->group(['TEMPORARY_FILES_AUTO_DELETE_SECOND_LIMIT']);
            $builder->create('服务商配置')->group(['QINIU_SYNC_AUTO', 'QINIU_PARAMS__domain', 'QINIU_PARAMS__access_key', 'QINIU_PARAMS__access_secret', 'QINIU_PARAMS__bucket', 'QINIU_PARAMS__visibility'], '七牛云储存')->group(['CONSOLE_WECHAT_OAUTH', 'WECHAT_OFFICE_ACCOUNT_PARAMS__app_id', 'WECHAT_OFFICE_ACCOUNT_PARAMS__secret', 'WECHAT_OFFICE_ACCOUNT_PARAMS__token', 'WECHAT_OFFICE_ACCOUNT_PARAMS__aes_key'])->group(['SMS_ALI_PARAMS__access_key_id', 'SMS_ALI_PARAMS__access_key_secret', 'SMS_ALI_PARAMS__sign_name'], '阿里云短信配置')->group(['AMAP_WEB_SERVER_API_KEY']);
            $builder->create('相关协议')->group(['PRIVACY_POLICY', 'USER_AGREEMENT']);
        })
        ->setData($configs)
        ->setSubmit(route('pros.console.systems.configs.store'))
        ->render()
    !!}
@endsection

{{-- 自定义页面弹窗 --}}
@section('popups')

@endsection

{{-- 自定义页面javascript --}}
@section('script')

@endsection
