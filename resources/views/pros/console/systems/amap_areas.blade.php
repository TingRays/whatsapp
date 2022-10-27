{{-- 继承模版 --}}
@extends('pros.console.layouts.master')

{{-- 页面标题 --}}
@section('title', '高德地图行政地区')

{{-- 自定义页面样式 --}}
@section('styles')

@endsection

{{-- 自定义主体内容 --}}
@section('container')
    <div class="card" id="amap_box">
        <div class="card-body p-lg-15">
            <div class="mb-13">
                <div class="mb-15">
                    <h4 class="fs-2x text-gray-800 w-bolder mb-6">中华人民共和国</h4>
                    @if($updated_at = (new \App\Repository\Pros\System\AmapAreaRepository())->max('updated_at'))<p class="fs-sm-7 text-primary">行政地区数据最新同步时间：{{ @$updated_at }}</p>@endif
                    <p class="fw-bold fs-4 text-gray-600 mb-2">行政区划是国家为便于行政管理而分级划分的区域，因此行政区划亦称行政区域，中华人民共和国的行政区划由省级行政区、地级行政区、县级行政区、乡级行政区组成。</p>
                </div>
                <div class="row mb-12">
                    @if(!(new \App\Handler\Cache\Data\Pros\System\ConfigCacheHandler())->get('AMAP_WEB_SERVER_API_KEY', ''))
                        {!!
                            \Abnermouke\Pros\Builders\Form\FormBuilder::make()
                            ->setSubmit(route('pros.console.systems.amap.sync'), '信息提交无误后将自动获取最新行政地区信息，该过程可能将持续3-5分钟，请勿离开本页面，是否继续操作？', '提交并立即同步')
                            ->setItems(function (\Abnermouke\Pros\Builders\Form\Tools\FormItemBuilder $builder) {
                                $builder->input('AMAP_WEB_SERVER_API_KEY', '高德地图Server服务Api Key')->placeholder('请输入高德地图Server服务Api Key')->description('高德Web服务API向开发者提供HTTP接口，通过这些接口使用各类型的地理数据服务，可点击链接 <a href="https://lbs.amap.com/api/webservice/guide/create-project/get-key" target="_blank">https://lbs.amap.com/api/webservice/guide/create-project/get-key</a> 根据提示进行操作。')->required()->clipboard();
                            })
                            ->render();
                        !!}
                    @else
                        @foreach((new \App\Repository\Pros\System\AmapAreaRepository())->get(['parent_id' => 100], ['id', 'level', 'guard_name', 'code'], [], ['code' => 'asc']) as $province)
                            <div class="col-md-3 pe-md-10 my-10 amap_box_items">
                                <h2 class="text-gray-800 fw-bolder mb-4">{{ $province['guard_name'] }}</h2>
                                <div class="h-350px overflow-auto">
                                    @foreach((new \App\Repository\Pros\System\AmapAreaRepository())->get(['parent_id' => (int)$province['id']], ['id', 'level', 'guard_name', 'code']) as $city)
                                        <div class="m-0 amap_box_items_line">
                                            <div class="d-flex align-items-center collapsible py-3 toggle collapsed mb-0" data-bs-toggle="collapse" data-bs-target="#city_{{ $city['id'] }}">
                                                <div class="btn btn-sm btn-icon mw-20px btn-active-color-primary me-5">
                                            <span class="svg-icon toggle-on svg-icon-primary svg-icon-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                    <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="5" fill="black"></rect>
                                                    <rect x="6.0104" y="10.9247" width="12" height="2" rx="1" fill="black"></rect>
                                                </svg>
                                            </span>
                                                    <span class="svg-icon toggle-off svg-icon-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                    <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="5" fill="black"></rect>
                                                    <rect x="10.8891" y="17.8033" width="12" height="2" rx="1" transform="rotate(-90 10.8891 17.8033)" fill="black"></rect>
                                                    <rect x="6.01041" y="10.9247" width="12" height="2" rx="1" fill="black"></rect>
                                                </svg>
                                            </span>
                                                </div>
                                                <h4 class="text-gray-700 fw-bolder cursor-pointer mb-0">{{ '[ '.$city['code'].' ] '.$city['guard_name'] }}</h4>
                                            </div>
                                            <div id="city_{{ $city['id'] }}" class="collapse fs-6 py-5 ms-1">
                                                <div class="d-flex flex-column text-gray-600">
                                                    @if($areas = (new \App\Repository\Pros\System\AmapAreaRepository())->get(['parent_id' => (int)$city['id']], ['id', 'level', 'guard_name', 'code']))
                                                        @foreach($areas as $area)
                                                            <div class="d-flex align-items-center py-2">
                                                                <span class="bullet bg-primary me-3"></span>{{ $area['guard_name'] }}
                                                            </div>
                                                        @endforeach
                                                    @else
                                                        <div class="d-flex align-items-center py-2">
                                                            <span class="bullet bg-primary me-3"></span><em>暂未更新到此地区下级行政区域信息</em>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="separator separator-dashed"></div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                        <div id="amap_buttons" class="d-none">
                            <button class="btn btn-sm btn-primary me-3 amap_button" id="sync_amap_data_button">
                                立即在线同步
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

{{-- 自定义页面弹窗 --}}
@section('popups')

@endsection

{{-- 自定义页面javascript --}}
@section('script')

@endsection
