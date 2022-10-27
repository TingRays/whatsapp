// 配置基本设置
var hostUrl = HOST_URL = $("meta[name='website-url']").attr('content'), ENABLE_ASIDE = $("meta[name='enable_aside']").attr('content'), ROUTE_NAME = $("meta[name='current_route_name']").attr('content'), AES_IV = $("meta[name='aes-iv']").attr('content'), AES_ENCRYPT_KEY = $("meta[name='aes-encrypt-key']").attr('content');
//初始化ajax请求
$.ajaxSetup({
    //设置默认头部
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
});

/**
 * 获取随机字符串
 * @param length
 * @returns {string}
 */
function randomString(length) {
    //设置字符集
    var str = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', result = '';
    //循环长度
    for (var i = length; i > 0; --i)
        //设置内容
        result += str[Math.floor(Math.random() * str.length)];
    //返回结果
    return result;
}

/**
 * container滑动至
 * @param o
 * @param container
 */
function scrollToObject(o, container)
{
    //判断container
    if (typeof container === 'undefined' || container.lenhth <= 0) {
        //设置container
        container = $('html , body');
    }
    //获取高度
    var of = o.offset().top;
    //滚动页面
    container.animate({scrollTop: parseInt(of > 200 ? (of - 160) : of)}, 1000)
}

/**
 * 开始加载loading
 * @param trigger 触发对象
 * @param target 目标对象
 * @param message 提示信息
 * @param theme 主题色
 * @returns {KTBlockUI}
 */
function loadingStart(trigger, target, message, theme)
{
    //判断对象信息
    if (typeof (trigger) === 'undefined' || trigger.length <= 0) {
        //设置主体对象
        trigger = $('body');
    }
    if (typeof (target) === 'undefined' || target.length <= 0) {
        //设置主体对象
        target = $('body');
    }
    //判断主题信息
    if (typeof (theme) === 'undefined' || theme.length <= 0) {
        //设置默认主题
        theme = 'secondary';
    }
    //判断提示信息
    if (typeof (message) === 'undefined' || message.length <= 0) {
        //设置默认提示信息
        message = '请稍后...';
    }
    //实例化处理对象
    var blockUI = new KTBlockUI(target, {
        overlayClass: "bg-"+theme+" bg-opacity-25",
        message: '<div class="blockui-message"><span class="spinner-border text-primary"></span>Loading '+message+'</div>'
    }), tagName = trigger[0].tagName;
    //判断状态
    if (!blockUI.isBlocked()) {
        //根据标签名操作
        switch (tagName) {
            case 'BUTTON':
                //判断是否存在progress
                if (trigger.find('.indicator-progress').length <= 0) {
                    //更改button内容
                    trigger.html('<span class="indicator-label">'+trigger.text()+'</span><span class="indicator-progress">'+message+'<span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>');
                }
                //设置动态
                trigger.attr("data-kt-indicator", "on");
                break;
        }
        //显示加载
        blockUI.block();
    }
    //返回实例对象
    return blockUI;
}

/**
 * 停止加载
 * @param blockUI 实例对象
 * @param trigger 触发对象
 * @returns {boolean}
 */
function loadingStop(blockUI, trigger) {
    //判断对象信息
    if (typeof (trigger) === 'undefined' || trigger.length <= 0) {
        //设置主体对象
        trigger = $('body');
    }
    //获取信息
    var tagName = trigger[0].tagName;
    //判断标签
    switch (tagName) {
        case 'BUTTON':
            //设置动态
            trigger.removeAttr("data-kt-indicator");
            break;
    }
    //判断状态
    if (blockUI.isBlocked()) {
        //释放block
        blockUI.release();
    }
    //销毁block
    blockUI.destroy();
    //返回成功
    return true;
}

/**
 * 加密表单数据
 * @param encrypt_params 加密参数
 * @returns {{}|{__encrypt__}}
 */
function encryptFormData(encrypt_params)
{
    //判断是否为对象
    if (typeof (encrypt_params) === 'object') {
        //判断是否为空
        if ($.isEmptyObject(encrypt_params)) {
            //添加默认参数
            encrypt_params['__RANDOM_STRING__'] = randomString(3);
        }
        //转义信息
        var encrypt_string = JSON.stringify(encrypt_params);
        //返回信息
        return {'__encrypt__': encrypt(encrypt_string, AES_ENCRYPT_KEY, AES_IV)};
    }
    //返回空
    return {};
}

/**
 * 加密
 * @param str 加密串
 * @param key esc_keys
 * @param iv esc_iv
 * @returns {*}
 */
function encrypt(str, key, iv) {
    // //密钥16位
    key = CryptoJS.enc.Utf8.parse(key);
    // //加密向量16位
    iv = CryptoJS.enc.Utf8.parse(iv);
    //加密信息
    return CryptoJS.AES.encrypt(str, key, {
        iv: iv,
        mode: CryptoJS.mode.CBC,
        padding: CryptoJS.pad.ZeroPadding
    }).toString();
}

/**
 * 提示信息
 * @param msg string 提示文案
 * @param timeout int >0 显示时长，<=0 不自动关闭
 * @param theme string 显示主题颜色：success|info|error|warning
 * @param title string 提示标题
 * @param position string 显示为止
 * @returns {*}
 */
function alertToast(msg, timeout, theme, title, position) {
    //整理基础配置
    toastr.options = {
        "closeButton": false,
        "debug": false,
        "newestOnTop": false,
        "progressBar": true,
        "positionClass": "toastr-top-center",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };
    //初始化信息
    if (typeof (timeout) === 'undefined') {
        //设置默认值
        timeout = 0;
    }
    if (typeof (theme) === 'undefined') {
        //设置默认值
        theme = 'warning';
    }
    if (typeof (position) === 'undefined') {
        //设置默认值
        toastr.options.positionClass = 'toastr-top-center';
    }
    //判断是否持续提示
    if (parseInt(timeout) <= 0) {
        //设置关闭按钮
        toastr.options.progressBar = false;
        toastr.options.extendedTimeOut = 0;
    } else {
        //取消移上停止操作
        toastr.options.tapToDismiss = true;
        //设置超时时间
        toastr.options.extendedTimeOut = parseInt(timeout) + 500;
    }
    //设置超时时间
    toastr.options.timeOut = parseInt(timeout);
    //根据主题提示
    switch (theme) {
        case 'info':
            toastr.info(msg, title);
            break;
        case 'success':
            toastr.success(msg, title);
            break;
        case 'error':
            toastr.error(msg, title);
            break;
        case 'warning':
            toastr.warning(msg, title);
            break;
        default:
            toastr.warning(msg, title);
            break;
    }
    //返回成功
    return true;
}

/**
 *
 * 序列化表单数据
 * @param data
 * @returns {{}}
 */
function serializeFormData(data)
{
    //初始化参数
    var form_data = {};
    //循环表单数据
    $.each(data, function() {
        //设置参数
        form_data[this.name] = this.value;
    });
    //返回数据
    return form_data;
}

/**
 * 确认弹窗
 * @param tip
 * @param success
 * @param fail
 * @param timeout
 * @returns {boolean}
 */
function confirmPopup(tip, success, fail, timeout) {
    //判断延时信息
    if (typeof (timeout) === 'undefined') {
        //设置默认10
        timeout = 10000;
    }
    //提示信息
    Swal.fire({
        text: tip,
        icon: "info",
        buttonsStyling: false,
        position: 'center',
        timer: timeout,
        timerProgressBar: true,
        showCancelButton: true,
        confirmButtonText: "确定",
        cancelButtonText: '取消',
        allowOutsideClick: false,
        customClass: {
            confirmButton: "btn btn-primary btn-sm",
            cancelButton: 'btn btn-danger btn-sm'
        },
    }).then(function(isConfirmed) {
        //判断是否确认
        if (isConfirmed.isConfirmed) {
            return typeof (success) !== 'undefined' && success ? success() : true;
        } else {
            return typeof (fail) !== 'undefined' && fail ? fail() : true;
        }
    });
    //返回成功
    return true;
}

/**
 * 创建外部引入CSS文件
 * @param href
 * @param callback
 */
function createExtraCss(href, callback)
{
    //判断是否已存在css文件
    if ($("head").find("link[href='"+href+"']").length <= 0) {
        //引入外部文件
        $("<link>").attr({rel: "stylesheet", type: "text/css", href: href}).appendTo("head");
        //执行回调
        callCustomerFunc(callback);
    } else {
        //执行回调
        callCustomerFunc(callback);
    }
}

/**
 * 创建外部引入JS文件
 * @param file
 * @param object
 * @param callback
 */
function createExtraJs(file, object, callback)
{
    //判断js是否已实例化
    if (typeof (object) == 'undefined' || object === 'undefined') {
        //引入外部文件
        $.getScript(file, function () {
            //执行回调
            callCustomerFunc(callback);
        });
    } else {
        //执行回调
        callCustomerFunc(callback);
    }
}

/**
 * 调用自定义方法
 * @param func
 */
function callCustomerFunc(func)
{
    //判断为方法
    if (typeof (func) == 'function') {
        //自定义方法调用
        return func();
    }
    //返回失败
    return false;
}

/**
 * 创建请求方法
 * @param query_url 请求链接
 * @param params 请求参数
 * @param method 请求方式
 * @param is_ajax 是否使用ajax请求
 * @param callback 成功时回调
 * @param fail_callback 失败时回调
 * @param after_ajax_callback 处理之后回调
 * @returns {boolean}
 */
function buildRequest(query_url, params, method, is_ajax, callback, fail_callback, after_ajax_callback) {
    //判断链接信息
    if (typeof (query_url) !== 'undefined' && query_url.length > 0) {
        //判断是否为ajax请求
        if (is_ajax) {
            //整理基础方法
            var func = function (res) {
                //设置ajax同步请求
                $.ajaxSettings.async = false;
                //判断回调信息
                if (typeof (after_ajax_callback) == 'function') {
                    //自定义方法调用
                    after_ajax_callback();
                }
                //判断处理状态
                if (res.state) {
                    //恢复ajax异步请求
                    $.ajaxSettings.async = true;
                    //判断回调信息
                    if (typeof (callback) == 'function') {
                        //自定义方法调用
                        return callback(res);
                    }
                } else {
                    //恢复ajax异步请求
                    $.ajaxSettings.async = true;
                    //判断回调信息
                    if (typeof (fail_callback) == 'function') {
                        //自定义方法调用
                        return fail_callback(res);
                    }
                }
            }
            //判断请求方式
            if (method.toUpperCase() === 'POST') {
                //触发post请求
                $.post(query_url, encryptFormData(params), function (res) {
                    //执行方法
                    return func(res);
                }, 'json');
            } else {
                //触发get请求
                $.get(query_url, encryptFormData(params), function (res) {
                    //执行方法
                    return func(res);
                }, 'json');
            }
        } else {
            //判断是否存在参数
            if (typeof (params) !== 'undefined' && !$.isEmptyObject(params)) {
                //整理链接参数
                var url_params = [];
                //循环参数配置信息
                $.each(params, function (i, item) {
                    //设置链接参数
                    url_params.push(i + '=' + item);
                })
                //设置链接信息
                query_url += (query_url.indexOf('?') >= 0 ? '' : '?') + url_params.join('&');
            }
            //判断回调信息
            if (typeof (callback) == 'function') {
                //自定义方法调用
                return callback(query_url);
            } else {
                //发起跳转
                window.location.href = query_url;
            }
        }
    }
    //返回失败
    return false;
}

/**
 * 检测字符串为数字还是字符串
 * @param $value
 * @returns {string|number}
 */
function checkNumberOrString($value)
{
    //判断是否为数字
    if ($.isNumeric($value) && $value !== 0 && $value.length > 0 && $value.length <= 10 && $value.substr(0, 1) !== '0') {
        //返回value数值型
        return Number($value);
    }
    //返回value字符串型
    return String($value);
}

/**
 * 获取笛卡尔乘集
 * @param arr
 * @returns {[[]]}
 */
function crossJoin(arr = [])
{
    //获取数组乘积
    const result = arr.reduce((accArr, currentArr) => {
        let result = []
        currentArr.forEach(c => {
            if (accArr.length) {
                accArr.forEach(a => {
                    result.push(a.concat(c))
                })
            } else {
                result.push([c])
            }
        })
        return result
    }, [])
    return result;
}
