$(function () {

    //获取处理对象
    var permission_nodes_box = $("#kt_permission_nodes_box"),
        header_nav_box = $("body").find('.pros_header_menus_box[data-kt-menu="true"]'),
        aside_menu_box = $("#kt_aside").find('.menu[data-kt-menu="true"]'),
        ROUTE_NAME = $("meta[name='current_route_name']").attr('content'),
        permission_nodes = [],
        change_password = $("#kt-edit_admin_password"),
        change_password_modal = $("#kt-edit_admin_password_modal"),
        refresh_nodes = $("#kt-refresh_console_nodes");

    //处理权限节点
    if (typeof (permission_nodes_box) !== 'undefined' && permission_nodes_box.length > 0) {
        //获取节点内容
        permission_nodes = JSON.parse(permission_nodes_box.text().trim());
    }

    //判断顶部元素是否存在
    if (typeof header_nav_box !== 'undefined' && header_nav_box.length > 0) {
        //循环处理link
        header_nav_box.find('a.menu-link').each(function () {

            //获取信息
            var route_name = $(this).attr('data-route-name'), __permissions = $(this).attr('data-permission-nodes');

            //处理信息
            __permissions = typeof __permissions !== 'undefined' && __permissions.length > 0 ? JSON.parse(__permissions) : [];

            //判断当前是否选中
            if (ROUTE_NAME === route_name || (!$.isEmptyObject(__permissions) && $.inArray(ROUTE_NAME, __permissions) >= 0)) {

                //设置当前选中
                $(this).removeClass('active').addClass('active');

                //设置父级item选中
                $(this).parents('.menu-item').removeClass('here hover show').addClass('here hover show');

            } else {

                //判断是否存在权限
                var has_permission = false;

                //循环当前菜单拥有权限节点
                $.each(__permissions, function (i, item) {
                    //判断路由名是否存在
                    if ($.inArray(item, permission_nodes) >= 0) {
                        //设置有权限
                        has_permission = true;
                        //跳出循环
                        return false;
                    }
                });

                //判断是否存在权限
                if (!has_permission) {
                    //删除当前菜单
                    $(this).parents('.menu-item ').eq(0).remove();
                }

            }

        });

        //循环所有存在下拉菜单ITem
        header_nav_box.find('.menu-item.menu-lg-down-accordion').each(function () {
            //判断下级菜单是否存在内容
            if ($(this).find('.menu-sub .menu-item').length <= 0) {
                //删除当前item
                $(this).remove();
            }
        });
    }

    //删除权限节点
    if (typeof (permission_nodes_box) !== 'undefined' && permission_nodes_box.length > 0) {

        //删除权限节点box
        permission_nodes_box.remove();

    }

    //判断aside元素是否存在
    if (typeof aside_menu_box !== 'undefined' && aside_menu_box.length > 0) {
        //循环所有已处理头部菜单
        header_nav_box.find('.pros_header_menus_items.here.show .pros_header_menus_subs').each(function () {
            //追加到aside中
            aside_menu_box.append($(this).html());
        });
        //更改item指定样式
        aside_menu_box.find('.menu-item').each(function () {
            //判断是否存在下拉样式
            if ($(this).hasClass('menu-lg-down-accordion')) {
                //更换样式
                $(this).remove('menu-lg-down-accordion').addClass('menu-accordion');
            }
            //判断属性是否存在
            if (typeof $(this).attr('data-kt-menu-trigger') !== 'undefined') {
                //更改属性值
                $(this).attr('data-kt-menu-trigger', 'click');
            }
            //判断属性是否存在
            if (typeof $(this).attr('data-kt-menu-placement') !== 'undefined') {
                //移除属性
                $(this).removeAttr('data-kt-menu-placement');
            }
        });
        //更改sub指定样式
        aside_menu_box.find('.menu-sub').each(function () {
            //移除样式
            $(this).removeClass('menu-sub-lg-down-accordion').removeClass('menu-sub-lg-dropdown').removeClass('menu-rounded-0').removeClass('py-lg-4').removeClass('w-lg-225px').addClass('menu-sub-accordion');
        });
    }

    //判断是否存在刷新节点按钮
    if (typeof refresh_nodes !== 'undefined' && refresh_nodes.length > 0) {
        //点击监听
        refresh_nodes.on('click', function () {
            //提示确认
            confirmPopup('刷新节点预计需要1-2分钟，确认后请耐心等待，是否继续？', function (res) {
                //加载loading
                var loading = loadingStart(refresh_nodes, $('body')[0], '正在刷新节点...');
                //发起请求
                buildRequest(refresh_nodes.attr('data-query-url'), {}, 'post', true, function (res) {
                    //提示信息
                    alertToast('刷新成功', 2000, 'success');
                    //刷新页面
                    window.location.reload();
                }, function (res) {
                    //提示信息
                    alertToast(res.msg, 2000, 'error');
                }, function () {
                    //关闭loading
                    loadingStop(loading, refresh_nodes);
                })
            });
        });
    }

    //判断是否存在修改密码按钮
    if (typeof change_password !== 'undefined' && change_password.length > 0) {
        //实例modal
        var change_password_modal_object;
        //设置监听
        change_password.on('click', function () {
            //显示弹窗
            change_password_modal_object = new bootstrap.Modal(change_password_modal[0], {backdrop: 'static', keyboard: false});
            //显示弹窗
            change_password_modal_object.show();
        });
        //提交确认
        $("#kt-edit_admin_password_modal_confirm_button").on('click', function () {
            //获取参数
            var _this = $(this), password = $("#kt-edit_admin_password_modal_new_password"), password_confirmed = $("#kt-edit_admin_password_modal_new_password_confirmed"), password_value = password.val(), confirmed_password_value = password_confirmed.val();
            //判断信息
            if (typeof password_value === 'undefined' || password_value.length < 6) {
                //新增提示
                password.parents('.form_item').append('<div class="fs-7 fw-bold text-danger my-2 validator_tip">密码需设置至少6位，请更新此项内容后再试</span></div>');
                //设置延时关闭
                setTimeout(function () {
                    password.parents('.form_item').find('.validator_tip').remove();
                }, 5000);
                //跳出循环
                return false;
            }
            //判断信息
            if (typeof confirmed_password_value === 'undefined' || confirmed_password_value.length < 6) {
                //新增提示
                password_confirmed.parents('.form_item').append('<div class="fs-7 fw-bold text-danger my-2 validator_tip">密码需设置至少6位，请更新此项内容后再试</span></div>');
                //设置延时关闭
                setTimeout(function () {
                    password_confirmed.parents('.form_item').find('.validator_tip').remove();
                }, 5000);
                //跳出循环
                return false;
            }
            //判断值是否一致
            if (password_value !== confirmed_password_value) {
                //新增提示
                password_confirmed.parents('.form_item').append('<div class="fs-7 fw-bold text-danger my-2 validator_tip">前后密码不一致</span></div>');
                //设置延时关闭
                setTimeout(function () {
                    password_confirmed.parents('.form_item').find('.validator_tip').remove();
                }, 5000);
                //跳出循环
                return false;
            }
            //加载loading
            var loading = loadingStart(_this, change_password_modal[0], '正在修改...');
            //发起请求
            buildRequest(change_password_modal.attr('data-query-url'), {password:password_value, password_confirmed:confirmed_password_value}, 'post', true, function (res) {
                //提示信息
                alertToast('修改成功', 2000, 'success');
                //关闭弹窗
                change_password_modal_object.hide();
            }, function (res) {
                //提示信息
                alertToast(res.msg, 2000, 'error');
                //关闭弹窗
                change_password_modal_object.hide();
            }, function () {
                //关闭loading
                loadingStop(loading, _this);
            });
        });
    }

});

