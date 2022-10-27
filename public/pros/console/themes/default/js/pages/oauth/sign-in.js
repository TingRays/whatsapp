$(function () {

    //获取信息
    var wechat_button = $("#sign-in_wechat");

    // 微信授权登录
    if (typeof (wechat_button) !== 'undefined' && wechat_button.length > 0) {
        //获取信息
        var timer = window.setInterval(checkBinding, 2500), wechat_qr_code = $("#wechat_qrcode"), wechat_qrcode_modal = $("#modal_wechat_qr");
        //设置触发
        wechat_button.on('click', function () {
            //隐藏弹窗
            wechat_qrcode_modal.modal('hide');
            //加载loading
            var loading = loadingStart(wechat_button, $("#sign-in_box")[0], '正在获取微信授权二维码');
            //发起请求
            buildRequest(wechat_button.attr('data-query-signature'), {}, 'POST', true, function (res) {
                //设置二维码
                wechat_qr_code.attr('src', res.data['qrcode_link']);
                //设置检测链接
                wechat_button.attr('data-query-check', res.data['check_link']);
                //显示弹窗
                wechat_qrcode_modal.modal('show');
            }, function (res) {
                //提示失败
                alertToast(res.msg, 2000, 'error');
            }, function () {
                //关闭加载
                loadingStop(loading, wechat_button);
            });
        });
        //modal关闭监听
        wechat_qrcode_modal.on('hidden.bs.modal', function () {
            //设置二维码
            wechat_qr_code.attr('src', '');
            //设置检测链接
            wechat_button.attr('data-query-check', '');
        });

        /**
         * 检测绑定状态
         * @returns {boolean}
         */
        function checkBinding()
        {
            //获取检测链接
            var check_url = wechat_button.attr('data-query-check');
            //判断检测链接有效性
            if (typeof (check_url) !== 'undefined' && check_url.length > 0) {
                //发起检测请求
                buildRequest(check_url, {}, 'POST', true, function (res) {
                    //提示成功
                    alertToast('授权登录成功', 0, 'success');
                    //跳转指定页面
                    window.location.href = $("#sign-in_form").attr('data-redirect-uri');
                });
            }
            //返回成功
            return true;
        }
    }


});
/**
 * 登录账户
 * @param _this_form
 * @returns {boolean}
 */
function autoLogin(_this_form)
{
    //获取信息
    var query_url = _this_form.attr('data-query-login'), redirect_uri = _this_form.attr('data-redirect-uri'), trigger = _this_form.find('#sign-in_submit');
    //加载loading
    var loading = loadingStart(trigger, $("#sign-in_box")[0]);
    //创建请求
    buildRequest(query_url, serializeFormData(_this_form.serializeArray()), 'POST', true, function (res) {
        //提示成功
        alertToast('登录成功', 0, 'success');
        //跳转指定页面
        window.location.href = redirect_uri;
    }, function (res) {
        //提示失败
        alertToast(res.msg, 2000, 'error');
    }, function () {
        //关闭加载
        loadingStop(loading, trigger);
    });
    return false;
}
