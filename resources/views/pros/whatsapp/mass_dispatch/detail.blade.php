{{-- 继承模版 --}}
@extends('pros.console.layouts.master')

{{-- 页面标题 --}}
@section('title', '商品详情')

{{-- 自定义页面样式 --}}
@section('styles')

@endsection

{{-- 自定义主体内容 --}}
@section('container')
    {!!
            \Abnermouke\Pros\Builders\Form\FormBuilder::make()
            ->setSubmit(route('pros.console.goods.store', ['id' => (int)$id]), '商品保存后系统将自动根据数据判断是否上架以及显示权重，是否继续提交？', '立即提交', route('pros.console.goods.index'))
            ->setBack(route('pros.console.goods.index'))
            ->setTitle((int)$id > 0 ? '编辑商品' : '添加商品')
            ->setDescription('<i class="fa fa-info-circle me-3 text-primary"></i>商品上下架由系统自动操作，满足所有上架条件即可自动上架，仓库存档商品为暂未满足上架状态条件商品（包括还未到自动上架时间或已至定时下架时间商品），如需某商品不进行自动上下架可删除，商品信息删除后可在回收站找回！')
            ->setItems(function (\Abnermouke\Pros\Builders\Form\Tools\FormItemBuilder $builder) {
                $builder->input('auth_token', '访问令牌')->required()->description('绑定商户/打赏用户将视为当前商品购买后在线打赏商户/用户，打赏省心宝将从此商户/用户账户中扣除，供应链商品统一分配，仅专区商品可自定义商户！');
                $builder->input('tel_code', '电话号码ID')->required();
                $builder->input('business_code', '商业账户ID')->required();
                $builder->input('remainder', '发送条数')->required();
                $builder->textarea('description', '商品描述');
                $builder->linkage('category_ids', '商品分类')->json_link(proxy_assets('jsons/goods/categories.json', 'static'))->required()->create(route('pros.console.goods.categories.detail', ['id' => 0, 'parent_id' => 0, 'all' => 1]))->level(3);
                $builder->select('brand_id', '商品品牌')->dynamic(proxy_assets('jsons/goods/brands.json', 'static'), route('pros.console.goods.brands.detail', ['id' => 0]))->required()->cols(6);
            })
            ->data($data)
            ->render();
    !!}
@endsection

{{-- 自定义页面弹窗 --}}
@section('popups')

@endsection

{{-- 自定义页面javascript --}}
@section('script')

@endsection
