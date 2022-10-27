<div class="text-center mb-13">
    <h1 class="mb-3">微信扫描二维码</h1>
    <div class="text-muted fw-bold fs-5">打开手机微信APP，扫描屏幕显示的二维码或保存本地后识别</div>
    <div class="text-muted fw-bold fs-7">扫码成功请留意授权后提示信息</div>
</div>
<div class="btn btn-light-primary fw-bolder w-100 mb-8" data-bs-dismiss="modal">取消授权绑定</div>
<div class="separator d-flex flex-center mb-8">
    <span class="text-uppercase bg-body fs-7 fw-bold text-muted px-3">微信二维码</span>
</div>
<div class="mb-10 text-center">
    <img src="{{ $qrcode_link }}" id="wechat_qrcode" data-signature="{{ $signature }}" class="h-300px" alt="">
</div>
