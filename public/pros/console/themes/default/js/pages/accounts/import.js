//准备就绪
$(function () {

    //初始化信息
    var body = $('body'), trigger = $("#import_trigger"), uploader = $("#import_file_input"), query_url = uploader.attr('data-query-url');

    //设置触发监听
    trigger.on('click', function () {
        //清空值
        uploader.val('');
        //设置触发上传
        uploader.trigger('click');
    });

    //监听上传更改
    uploader.on('change', function (file) {
        //整理信息
        var upload_files = file.target.files;
        //判断文件信息
        if (typeof (upload_files) !== 'undefined' && !$.isEmptyObject(upload_files)) {
            //加载loading
            var loading = loadingStart(trigger, body[0], '正在上传文件...');
            //整理上传信息
            var uploadData = new FormData();
            //整理信息
            uploadData.append('file', upload_files[0]);
            uploadData.append('file_type', 'binary');
            uploadData.append('dictionary', 'accounts/posts/imports');
            uploadData.append('origin_name', file.target.files[0]['name']);
            //开始请求上传
            $.ajax({
                type: 'post',
                url: query_url,
                data: uploadData,
                processData: false,
                contentType: false,
                success: function (res) {
                    //关闭弹窗
                    loadingStop(loading, trigger);
                    //判断上传状态
                    if (res.state) {
                        //提示信息
                        alertToast(res.data['msg'], 5000, 'success');
                        //判断是否存在链接
                        if (typeof (res.data['link']) !== 'undefined' && res.data['link'].length > 0) {
                            //跳转打开文件
                            window.open(res.data['link']);
                        }
                    } else {
                        //提示信息
                        alertToast(res.msg, 2000, 'error', '文件上传');
                    }
                },
                error: function (res) { //关闭弹窗
                    loadingStop(loading, trigger);
                    //提示信息
                    alertToast('网络错误，请稍后再试', 2000, 'error', '文件上传');
                }
            });
        }
    });

});
