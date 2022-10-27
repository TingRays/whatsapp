/**
 * abnermouke/pros 表格构建器
 * @type {{arrangeParams: (function(*, *): any), init: $.table_builder.init, setExportTrigger: $.table_builder.setExportTrigger, requestList: $.table_builder.requestList, setSortTrigger: $.table_builder.setSortTrigger, setFilterTrigger: $.table_builder.setFilterTrigger, doActionTrigger: $.table_builder.doActionTrigger, importModules: $.table_builder.importModules, doActionAfter: $.table_builder.doActionAfter, setPageTrigger: $.table_builder.setPageTrigger, setContentTrigger: $.table_builder.setContentTrigger, setButtonTrigger: $.table_builder.setButtonTrigger, clearSubContent: (function(*=, *=, *): boolean), setTabTrigger: $.table_builder.setTabTrigger, doRefreshTrigger: $.table_builder.doRefreshTrigger}}
 */
$.table_builder = {
    init: function (sign, callback) {
        //获取表格信息
        let table = $("#pros_table_"+sign);
        //引入必要组件
        this.importModules(table, sign);
        //设置筛选项触发
        this.setFilterTrigger(table, sign);
        //设置筛选项触发
        this.setTabTrigger(table, sign);
        //设置排序触发
        this.setSortTrigger(table, sign);
        //设置导出触发
        this.setExportTrigger(table, sign);
        //设置页码触发
        this.setPageTrigger(table, sign);
        //设置按钮触发
        this.setButtonTrigger(table, sign);
        //发起请求
        this.requestList(table, sign);
        //延迟设置成功
        setTimeout(function () {
            //设置构建完成
            table.attr('data-build', 1);
            //debug
            console.log('Pros Table [' + sign + '] build success!');
        }, 500);
        //判断是否存在处理完回调
        if (typeof callback == 'function') {
            //执行回调
            callback();
        }
    },
    importModules: function (table, sign) {
        //引入大图查看功能
        createExtraJs(table.attr('data-source-path')+'/fslightbox/fslightbox.bundle.js', window.fsLightbox);
    },
    doRefreshTrigger: function (table, sign, trigger_alias) {
        //获取对象
        var __this = this;
        //判断标识是否有效
        if (typeof trigger_alias !== 'undefined' && trigger_alias.length > 0) {
            //获取触发对象
            var table_append_trigger = table.find('#pros_table_'+sign+'_append_trigger_'+trigger_alias);
            //判断触发对象是否有效
            if (typeof table_append_trigger !== 'undefined' && table_append_trigger.length > 0) {
                //设置已打开
                table_append_trigger.attr('data-open', 0);
                //设置点击
                table_append_trigger.trigger('click');
            } else {
                //刷新列表
                __this.requestList(table, sign);
            }
        } else {
            //刷新列表
            __this.requestList(table, sign);
        }
    },
    doActionAfter: function (table, sign, after, _this) {
        //获取信息
        var __this = this, trigger_alias = _this.parents('tr').eq(0).attr('data-target-alias');
        //根据后置类型处理
        switch (after) {
            case 'refresh':
                //刷新列表
                __this.doRefreshTrigger(table, sign, trigger_alias);
                break;
            case 'reload':
                //刷新页面
                window.location.reload();
                break;
            case 'none':
                //不进行操作
                break;
            default:
                //判断长度
                if (after.length > 0) {
                    //跳转页面
                    window.location.href = after;
                }
                break;
        }
    },
    doActionTrigger: function (table, sign, _this) {
        //设置处理方案
        var type = _this.attr('data-type'), __this = this, params = _this.attr('data-params'), confirm_tip = _this.attr('data-confirm-tip'), bind_checkbox = _this.attr('data-bind-checkbox'), query_url = _this.attr('data-query-url'), trigger_alias = _this.parents('tr').eq(0).attr('data-target-alias'), after_func = function (after) {
            //执行操作后置方法
            __this.doActionAfter(table, sign, after, _this);
        }, func = function () {
            //初始化参数信息
            params = JSON.parse(params);
            //判断自定义请求链接是否有效
            if (typeof query_url !== 'undefined' && query_url.length > 0) {
                //设置自定义请求链接
                params['query_url'] = query_url;
            }
            //根据类型处理
            switch (type) {
                case 'form':
                    //整理模态框信息
                    var body = $('body'), bind_form_modal_target = 'pros_table_'+sign+'_modal_with_from_of_'+randomString(8), query_params = {}, loading = loadingStart(_this, table[0], '正在加载...');
                    //执行请求
                    buildRequest(params['query_url'], query_params, params['query_method'], true, function (res) {
                        //设置内容
                        var modal_html = '<div class="modal fade pros_table_'+sign+'_bind_form_of_modal" id="'+bind_form_modal_target+'" tabindex="-1"><div class="modal-dialog modal-dialog-centered modal-'+params['size']+'" role="document"><div class="modal-content"><div class="modal-header py-5"><h5 class="modal-title">'+params['guard_name']+'</h5><button type="button" class="btn btn-icon btn-sm btn-active-light-primary ms-2 pros_table_form_modal_close_icon" data-bs-dismiss="modal" aria-label="Close" id="'+bind_form_modal_target+'_close_icon"><i aria-hidden="true" class="fa fa-times"></i></button></div><div class="modal-body mh-700px overflow-auto" style="background-color: #f5f8fa">'+res.data['html']+'</div><div class="modal-footer" id="'+bind_form_modal_target+'_footer"></div></div></div></div>'
                        //添加内容
                        body.append(modal_html);
                        //获取对象
                        var modal_object = $("#"+bind_form_modal_target);
                        //显示弹窗
                        new bootstrap.Modal(modal_object[0], {backdrop: 'static', keyboard: false}).show();
                        //引入实例对象
                        createExtraJs(table.attr('data-source-path')+'/form-builder.js', $.form_builder, function () {
                            //获取表单标识
                            var form_sign = modal_object.find('.pros_form_builder').attr('data-sign');
                            //创建处理实例对象
                            $.form_builder.init(form_sign, function () {
                                //设置表单与modal结合
                                $.form_builder.containWithModal(form_sign, bind_form_modal_target);
                            });
                        });
                        //关闭弹窗事件触发
                        modal_object.on('hidden.bs.modal', function () {
                            //删除当前modal
                            $("#"+bind_form_modal_target).remove();
                            //删除遮罩
                            body.find('.modal-backdrop').remove();
                            //执行后置操作
                            after_func(params['after']);
                        });
                    }, function (res) {
                        //提示失败
                        alertToast(res.msg, 2000, 'error');
                    }, function () {
                        //关闭加载
                        loadingStop(loading, _this);
                    });
                    break;
                case 'modal':
                    //整理模态框信息
                    var body = $('body'), bind_modal_target = params['bind_modal_id'], query_params = {};
                    //判断模态框绑定ID是否有效
                    if (typeof bind_modal_target == 'undefined' || bind_modal_target.length <= 0) {
                        //生成模态框信息
                        bind_modal_target = 'pros_table_'+sign+'_modal_of_'+randomString(8);
                        //设置内容
                        var modal_html = '<div class="modal fade pros_table_'+sign+'_bind_modal" id="'+bind_modal_target+'" tabindex="-1"><div class="modal-dialog modal-dialog-centered modal-'+params['size']+'" role="document"><div class="modal-content"><div class="modal-header py-5"><h5 class="modal-title">'+params['guard_name']+'</h5><button type="button" class="btn btn-icon btn-sm btn-active-light-primary ms-2 pros_table_form_modal_close_icon" data-bs-dismiss="modal" aria-label="Close" id="'+bind_modal_target+'_close_icon"><i aria-hidden="true" class="fa fa-times"></i></button></div><div class="modal-body mh-700px overflow-auto"></div></div></div></div>'
                        //添加内容
                        body.append(modal_html);
                    }
                    //开始加载
                    var modal_object = $("#"+bind_modal_target), loading = loadingStart(_this, table[0], '正在加载...');
                    //执行请求
                    buildRequest(params['query_url'], query_params, params['query_method'], true, function (res) {
                        //设置内容
                        modal_object.find('.modal-body').empty().html(res.data['html']);
                        //显示弹窗
                        new bootstrap.Modal(modal_object[0], {backdrop: 'static', keyboard: false}).show();
                        //关闭弹窗事件触发
                        modal_object.on('hidden.bs.modal', function () {
                            //判断是否为动态新增modal
                            if (typeof params['bind_modal_id'] == 'undefined' || params['bind_modal_id'].length <= 0) {
                                //删除当前modal
                                $("#"+bind_modal_target).remove();
                                //删除遮罩
                                body.find('.modal-backdrop').remove();
                            }
                            //执行后置操作
                            after_func(params['after']);
                        });
                    }, function (res) {
                        //提示失败
                        alertToast(res.msg, 2000, 'error');
                    }, function () {
                        //关闭加载
                        loadingStop(loading, _this);
                    });
                    break;
                case 'ajax':
                    //整理请求参数
                    var query_params = {};
                    //判断是否绑定checkbox
                    if (typeof(bind_checkbox) !== 'undefined' && parseInt(bind_checkbox) === 1) {
                        //设置选中信息
                        var checkbox_values = [];
                        //循环所有checkbox
                        $("#pros_table_"+sign+"_tbody").find('input.pros_table_'+sign+'_select_item:checked').each(function () {
                            //添加value
                            checkbox_values.push($(this).val());
                        });
                        //查询长度
                        if (checkbox_values.length <= 0) {
                            //提示错误
                            alertToast('请至少选择一项后操作', 2000, 'warning');
                            //返回失败
                            return false;
                        }
                        //设置参数
                        query_params[$("#pros_table_"+sign+"_box").attr('data-checkbox-field')] = checkbox_values;
                    }
                    //开始加载
                    var loading = loadingStart(_this, table[0], '处理中...');
                    //执行请求
                    buildRequest(params['query_url'], query_params, params['query_method'], true, function (res) {
                        //执行后置操作
                        after_func(params['after']);
                    }, function (res) {
                        //提示失败
                        alertToast(res.msg, 2000, 'error');
                    }, function () {
                        //关闭加载
                        loadingStop(loading, _this);
                    });
                    break;
                default:
                    //判断是否绑定checkbox
                    if (typeof(bind_checkbox) !== 'undefined' && parseInt(bind_checkbox) === 1) {
                        //设置选中信息
                        var checkbox_values = [];
                        //循环所有checkbox
                        $("#pros_table_"+sign+"_tbody").find('input.pros_table_'+sign+'_select_item:checked').each(function () {
                            //添加value
                            checkbox_values.push($(this).val());
                        });
                        //查询长度
                        if (checkbox_values.length <= 0) {
                            //提示错误
                            alertToast('请至少选择一项后操作', 2000, 'warning');
                            //返回失败
                            return false;
                        }
                        //判断链接
                        if (params['query_url'].indexOf('?') === -1) {
                            //追加信息
                            params['query_url'] += '?' + $("#pros_table_"+sign+"_box").attr('data-checkbox-field') + '=' + checkbox_values.join('-');
                        } else {
                            //追加信息
                            params['query_url'] += '&' +  $("#pros_table_"+sign+"_box").attr('data-checkbox-field') + '=' + checkbox_values.join('-');
                        }
                    }
                    //判断是否新开页面
                    if (params['target']) {
                        //新开页面跳转
                        window.open(params['query_url']);
                    } else {
                        //当前页面刷新
                        window.location.href = params['query_url'];
                    }
                    break;
            }
        }
        //判断是否提示后触发
        if (typeof confirm_tip !== 'undefined' && confirm_tip.length > 0) {
            //弹窗触发
            confirmPopup(confirm_tip, function () {
                //执行按钮操作
                func();
            });
        } else {
            //执行按钮操作
            func();
        }
    },
    setButtonTrigger: function (table, sign) {
        //获取按钮触发信息
        var button_box = $("#pros_table_"+sign+"_buttons"), __this = this;
        //判断是否存在按钮
        if (typeof button_box !== 'undefined' && button_box.length > 0) {
            //设置触发
            button_box.find('.pros_table_button').on('click', function () {
                //设置统一触发
                __this.doActionTrigger(table, sign, $(this));
            });
        }
    },
    setPageTrigger: function (table, sign) {
        //获取页码选择组件
        var pagination_box = $("#pros_table_"+sign+"_pagination_box"), __this = this;
        //判断组件是否存在
        if (typeof pagination_box !== 'undefined' && pagination_box.length > 0) {
            //获取子组件
            var page_size_select = $("#pros_table_"+sign+"_pagination_of_page_size_select"), pagination = $("#pros_table_"+sign+"_pagination");
            //设置触发
            page_size_select.on('change', function () {
                //设置信息
                pagination_box.attr('data-page-size', parseInt($(this).val())).attr('data-page', 1);
                //刷新列表
                __this.requestList(table, sign, false);
            });
            //页码更改
            pagination.off('click').on('click', '.page-item', function () {
                //判断是否不可点击
                if (!$(this).hasClass('disabled')) {
                    //设置页码
                    pagination_box.attr('data-page', $(this).attr('data-page'));
                    //刷新列表
                    __this.requestList(table, sign, false);
                }
            });
        }
    },
    setExportTrigger: function (table, sign) {
        //获取导出触发按钮
        var export_btn = $("#pros_table_"+sign+"_export");
        //判断导出按钮是否存在
        if (typeof export_btn !== 'undefined' && export_btn.length > 0) {
            //引入实例对象
            createExtraJs(table.attr('data-source-path')+'/table2excel/jquery.table2excel.min.js', $.table2excel, function () {
                //设置导出触发
                export_btn.on('click', function () {
                    //获取元素信息
                    var table_box = $("#pros_table_"+sign+"_box");
                    //导出表格数据
                    table_box.table2excel({
                        exclude: ".export_ignore",
                        name: "ProsExport",
                        filename: sign,
                        preserveColors: true,
                        exclude_img: true
                    });
                    //提示信息
                    alertToast('导出当前列表成功，EXCEL如有空白请留意背景颜色是否与字体颜色一致！', 3500, 'info', '表格内容导出')
                });
            });
        }
    },
    setSortTrigger: function (table, sign) {
        //获取表头元素
        var thead_box = $("#pros_table_"+sign+"_thead"), __this = this;
        //判断表头是否存在
        if (typeof thead_box !== 'undefined' && thead_box.length > 0) {
            //判断点击
            thead_box.find('th[data-pros-tool="sorting"]').on('click', function () {
                //获取当前排序规则
                var sort_rule = $(this).attr('data-sort');
                //判断排序规则
                switch (sort_rule) {
                    case 'desc':
                        //设置正序
                        $(this).attr('data-sort', 'asc');
                        //设置图标
                        $(this).find('i.fa').removeClass('fa-sort').removeClass('fa-sort-down').removeClass('fa-sort-up').removeClass('text-primary').addClass('fa-sort-up').addClass('text-primary');
                        break;
                    case 'asc':
                        //设置不排序
                        $(this).attr('data-sort', 'default');
                        //设置图标
                        $(this).find('i.fa').removeClass('fa-sort').removeClass('fa-sort-down').removeClass('fa-sort-up').removeClass('text-primary').addClass('fa-sort');
                        break;
                    default:
                        //设置倒序
                        $(this).attr('data-sort', 'desc');
                        //设置图标
                        $(this).find('i.fa').removeClass('fa-sort').removeClass('fa-sort-down').removeClass('fa-sort-up').removeClass('text-primary').addClass('fa-sort-down').addClass('text-primary');
                        break;
                }
                //刷新列表
                __this.requestList(table, sign, true);
            });
        }
    },
    setTabTrigger: function (table, sign) {
        //获取选项卡对象信息
        var tab_box = $("#pros_table_"+sign+"_tabs"), button_box = $("#pros_table_"+sign+"_buttons"), filter_box = $("#pros_table_"+sign+"_filters"), __this = this;
        //判断选项卡是否存在
        if (typeof (tab_box) !== 'undefined' && tab_box.length > 0) {
            //判断选项卡点击
            tab_box.find('.nav-link').on('click', function () {
                //获取选项卡信息
                var bind_button_suffixes = $(this).attr('data-bind-button-suffixes'), alias = $(this).attr('data-alias');
                //判断是否存在按钮
                if (typeof button_box !== 'undefined' && button_box.length > 0) {
                    //隐藏所有按钮
                    button_box.find('.pros_table_button').removeClass('d-none').addClass('d-none');
                    //判断是否绑定按钮
                    if (typeof (bind_button_suffixes) !== 'undefined' && bind_button_suffixes.length > 0) {
                        //解析按钮
                        bind_button_suffixes = JSON.parse(bind_button_suffixes);
                        //判断是否为空
                        if (bind_button_suffixes.length > 0) {
                            //循环按钮
                            button_box.find('.pros_table_button[data-bind-checkbox="0"]').each(function () {
                                //判断标识是否存在
                                if ($.inArray($(this).attr('data-did-suffix'), bind_button_suffixes) > -1) {
                                    //显示当前按钮
                                    $(this).removeClass('d-none');
                                }
                            });
                        } else {
                            //显示所有按钮
                            button_box.find('.pros_table_button[data-bind-checkbox="0"]').removeClass('d-none');
                        }
                    } else {
                        //隐藏所有按钮
                        button_box.find('.pros_table_button').removeClass('d-none').addClass('d-none');
                    }
                }
                //判断筛选条件框是否存在
                if (typeof (filter_box) !== 'undefined' && filter_box.length > 0) {
                    //循环筛选项
                    filter_box.find('.pros_table_filter_item').each(function () {
                        //过去绑定tab
                        var filter_bind_tabs = $(this).attr('data-bind-tabs');
                        //判断信息
                        if (typeof filter_bind_tabs !== 'undefined' && filter_bind_tabs.length > 0) {
                            //解析信息
                            filter_bind_tabs = JSON.parse(filter_bind_tabs);
                            //判断是否单独设置
                            if (filter_bind_tabs.length > 0) {
                                //判断是否存在当前标识
                                if ($.inArray(alias, filter_bind_tabs) > -1) {
                                    //显示筛选项
                                    $(this).removeClass('d-none');
                                } else {
                                    //隐藏筛选项
                                    $(this).removeClass('d-none').addClass('d-none');
                                }
                            } else {
                                //显示筛选项
                                $(this).removeClass('d-none');
                            }
                        }
                    });
                }
                 //设置全部不选中
                tab_box.find('.nav-link').removeClass('fs-6 fw-bold active').removeClass('text-primary').addClass('text-dark');
                //设置当前选项卡
                $(this).addClass('fs-6 fw-bold active').removeClass('text-dark').addClass('text-primary');
                //设置请求信息
                table.attr('data-query-url', $(this).attr('data-query-url'));
                table.attr('data-query-method', $(this).attr('data-query-method'));
                //判断表格构建成功直接请求
                if (parseInt(table.attr('data-build')) === 1) {
                    //刷新列表
                    __this.requestList(table, sign, true);
                }
            });
            //触发点击
            tab_box.find('.nav-link').eq(0).trigger('click');
        }
    },
    setFilterTrigger: function (table, sign) {
        //获取筛选对象信息
        var filter_box = $("#pros_table_"+sign+"_filters"), __this = this;
        //判断筛选条件框是否存在
        if (typeof (filter_box) !== 'undefined' && filter_box.length > 0) {
            //获取请求参数
            var submit_btn = $("#pros_table_"+sign+"_filter_to_submit"), reset_btn = $("#pros_table_"+sign+"_filter_to_reset"), filter_items = filter_box.find('.pros_table_filter_item');
            //判断筛选对象是否存在
            if (typeof filter_items !== 'undefined' && filter_items.length > 0) {
                //循环筛选对象
                filter_items.each(function () {
                    //获取基本参数
                    let filter_type = $(this).attr('data-type'),  filter_target = $(this).attr('data-target');
                    //根据类型实例化对象
                    switch (filter_type) {
                        case 'date':
                        case 'time_range':
                            //引入实例对象
                            createExtraJs(table.attr('data-source-path')+'/flatpickr/langs/zh-cn.js', window.flatpickr.l10ns.zh, function () {
                                //获取显示格式
                                var filter_date_format = $(filter_target).attr('data-format'), filter_date_params = {
                                    locale: "zh",
                                    altFormat: filter_date_format,
                                    dateFormat: filter_date_format,
                                };
                                //判断是否需要显示时间
                                if (filter_date_format.indexOf('H') >= 0 || filter_date_format.indexOf('h') >= 0 || filter_date_format.indexOf('i') >= 0 || filter_date_format.indexOf('s') >= 0) {
                                    //添加小时支持
                                    filter_date_params['enableTime'] = filter_date_params['time_24hr'] = true;
                                }
                                //判断是否为range
                                if (filter_type === 'time_range') {
                                    //添加范围支持
                                    filter_date_params['mode'] = 'range';
                                }
                                //实例化日期
                                $(filter_target).flatpickr(filter_date_params);

                            });
                            break;
                        case 'tags':
                            //实例化标签
                            new Tagify(document.querySelector(filter_target));
                            break;
                    }
                });
            }
            //判断重置按钮触发
            if (typeof reset_btn !== 'undefined' && reset_btn.length > 0) {
                //设置点击触发
                reset_btn.on('click', function () {
                    //判断筛选对象是否存在
                    if (typeof filter_items !== 'undefined' && filter_items.length > 0) {
                        //循环筛选对象
                        filter_items.each(function () {
                            //获取基本参数
                            let filter_type = $(this).attr('data-type'), filter_target = $(this).attr('data-target');
                            //根据类型实例化对象
                            switch (filter_type) {
                                case 'select':
                                    //设置全部不选中
                                    $(filter_target).find('option:selected').removeAttr('selected');
                                    //设置为空
                                    $(filter_target).val('').change();
                                    break;
                                case 'group':
                                case 'switch':
                                    //设置全部不选中
                                    $(filter_target).prop('checked', false).change();
                                    break;
                                default:
                                    //清空输入框
                                    $(filter_target).val('').change();
                                    break;
                            }
                        });
                    }
                });
            }
            //判断搜索按钮触发
            if (typeof submit_btn !== 'undefined' && submit_btn.length > 0) {
                //设置点击触发
                submit_btn.on('click', function () {
                   //刷新列表
                   __this.requestList(table, sign, true);
                });
            }
        }
    },
    clearSubContent: function (table, sign, target)
    {
        //获取处理对象
        var table_body = $("#pros_table_"+sign+"_tbody"), trigger_objects = table_body.find('.'+target), __this = this;
        //判断对象是否存在
        if (typeof trigger_objects !== "undefined" && trigger_objects.length > 0) {
            //循环触发对象
            $.each(trigger_objects, function () {
               //查询是否存在下级触发对象
                var sub_triggers = $(this).find('.pros_table_'+sign+'_append_trigger');
                //判断对象是否存在
                if (typeof sub_triggers !== 'undefined' && sub_triggers.length > 0) {
                    //继续清除
                    __this.clearSubContent(table, sign, sub_triggers.attr('data-target'));
                }
            });
            //移除数据
            trigger_objects.remove();
        }
        //返回成功
        return true;
    },
    setContentTrigger: function (table, sign)
    {
        //获取处理对象
        var thead_box = $("#pros_table_"+sign+"_thead"), table_wrapper = $("#pros_table_"+sign+"_wrapper"), table_body = $("#pros_table_"+sign+"_tbody"), button_box = $("#pros_table_"+sign+"_buttons"), __this = this;
        //判断是否存在表头
        if (typeof thead_box !== 'undefined' && thead_box.length > 0) {
            //获取选中按钮
            var checkbox_all = $("#pros_table_"+sign+"_select_all"), checkbox_item = table_body.find('input.pros_table_'+sign+'_select_item');
            //判断是否存在全部选中按钮
            if (typeof checkbox_all !== 'undefined' && checkbox_all.length > 0 && typeof checkbox_item !== 'undefined' && checkbox_item.length > 0) {
                //整理点击触发
                var trigger_func = function () {
                    //判断按钮项是否存在
                    if (typeof button_box !== 'undefined' && button_box.length > 0) {
                        //获取当前已选中checkbox个数
                        var checked_count = table_body.find('input.pros_table_'+sign+'_select_item:checked').length;
                        //判断选中个数
                        if (parseInt(checked_count) > 0) {
                            //设置关联按钮显示
                            button_box.find('.pros_table_button[data-bind-checkbox="1"]').removeClass('d-none');
                        } else {
                            //设置关联按钮不显示
                            button_box.find('.pros_table_button[data-bind-checkbox="1"]').removeClass('d-none').addClass('d-none');
                        }
                    }
                };
                //设置点击触发
                checkbox_all.on('click', function () {
                    //设置列表选中状态
                    checkbox_item.prop('checked', !!checkbox_all.is(':checked'));
                    //触发点击后操作
                    trigger_func();
                });
                //设置触发
                checkbox_item.on('click', function () {
                    //判断数量
                    if (table_body.find('input.pros_table_'+sign+'_select_item:checked').length < checkbox_item.length) {
                        //设置全选不选中
                        checkbox_all.prop('checked', false);
                    } else {
                        //设置全选选中
                        checkbox_all.prop('checked', true);
                    }
                    //触发点击后操作
                    trigger_func();
                });
                //取消全选
                checkbox_all.prop('checked', false).change();
                //触发点击后操作
                trigger_func();
            }
        }
        //设置下级表格触发
        table_body.find('.pros_table_'+sign+'_append_trigger').off('click').on('click', function () {
            //判断触发方式
            switch ($(this).attr('data-method')) {
                case 'more':
                    //判断是否打开
                    if (parseInt($(this).attr('data-open')) === 0) {
                        //设置显示下级
                        $('.'+$(this).attr('data-target')).removeClass('d-none');
                        //更改图标
                        $(this).find('i').removeClass('fa-angle-right').removeClass('fa-angle-down').addClass('fa-angle-down');
                        //设置已打开
                        $(this).attr('data-open', 1);
                        //设置选中
                        $(this).removeClass('btn-light-primary').addClass('btn-light-primary');
                    } else {
                        //设置显示下级
                        $('.'+$(this).attr('data-target')).removeClass('d-none').addClass('d-none');
                        //更改图标
                        $(this).find('i').removeClass('fa-angle-right').removeClass('fa-angle-down').addClass('fa-angle-right');
                        //设置已打开
                        $(this).attr('data-open', 0);
                        //设置选中
                        $(this).removeClass('btn-light-primary');
                    }
                    break;
                default:
                    //设置基本对象
                    var this_append = $(this), parent_tr = this_append.parents('tr');
                    //判断是否打开
                    if (parseInt(this_append.attr('data-open')) === 0) {
                        //删除显示下级
                        $('.'+this_append.attr('data-target')).remove();
                        //开始加载
                        var loading = loadingStart(parent_tr, parent_tr[0], '正在查询...');
                        //执行请求
                        buildRequest(this_append.attr('data-trigger'), {target: this_append.attr('data-target'), column_count: parseInt(this_append.attr('data-column-count')), sign: sign, trigger_alias: this_append.attr('data-trigger-alias')}, this_append.attr('data-method'), true, function (res) {
                            //设置信息
                            parent_tr.after(res.data);
                            //更改图标
                            this_append.find('i').removeClass('fa-angle-right').removeClass('fa-angle-down').addClass('fa-angle-down');
                            //设置已打开
                            this_append.attr('data-open', 1);
                            //设置选中
                            this_append.removeClass('btn-light-primary').addClass('btn-light-primary');
                            //再次设置内容触发（子集自身构建内容）
                            __this.setContentTrigger(table, sign);
                        }, function (res) {
                            //提示失败
                            alertToast(res.msg, 2000, 'error');
                        }, function () {
                            //关闭加载
                            loadingStop(loading, parent_tr);
                        });
                    } else {
                        //清除下级
                        __this.clearSubContent(table, sign, this_append.attr('data-target'));
                        //更改图标
                        this_append.find('i').removeClass('fa-angle-right').removeClass('fa-angle-down').addClass('fa-angle-right');
                        //设置已打开
                        this_append.attr('data-open', 0);
                        //设置选中
                        this_append.removeClass('btn-light-primary');
                    }
                    break;
            }
        });
        //设置action触发
        table_body.find('td.pros_table_content_actions').off('click').on('click', '.pros_table_content_action', function () {
            //设置统一触发
            __this.doActionTrigger(table, sign, $(this));
        });
        //设置switch触发
        table_body.find('td.pros_'+sign+'_table_tbody_td[data-type="switch"]').on('change', 'input:checkbox', function () {
            //整理请求参数
            var query_url = '', query_method = '', query_params = {}, on = JSON.parse($(this).attr('data-on-params')), off = JSON.parse($(this).attr('data-off-params')), parent_tr = $(this).parents('tr'), this_switch = $(this), after_action = this_switch.attr('data-after'), loading = loadingStart(parent_tr, parent_tr[0], '正在设置...');
            //判断当前是否选中
            if (this_switch.is(':checked')) {
                //整理信息
                query_url = this_switch.attr('data-on-query-url');
                query_method = on['query_method'];
                query_params = {value: checkNumberOrString(on['value']), text: on['text']};
                //判断链接是否可用
                if (typeof query_url == 'undefined' || query_url.length <= 0) {
                    //设置为默认地址
                    query_url = on['query_url'];
                }
            } else {
                //整理信息
                query_url = this_switch.attr('data-off-query-url');
                query_method = off['query_method'];
                query_params = {value: checkNumberOrString(off['value']), text: off['text']};
                //判断链接是否可用
                if (typeof query_url == 'undefined' || query_url.length <= 0) {
                    //设置为默认地址
                    query_url = off['query_url'];
                }
            }
            //执行请求
            buildRequest(query_url, query_params, query_method, true, function (res) {
                //判断选中状态
                if (this_switch.is(':checked')) {
                    //设置on提示
                    this_switch.siblings('.form-check-label').text(on['text']);
                } else {
                    //设置off提示
                    this_switch.siblings('.form-check-label').text(off['text']);
                }
                //执行后置操作
                __this.doActionAfter(table, sign, after_action, this_switch);
            }, function (res) {
                //判断选中状态
                if (this_switch.is(':checked')) {
                    //恢复off
                    this_switch.prop('checked', false);
                    this_switch.siblings('.form-check-label').text(off['text']);
                } else {
                    //恢复on
                    this_switch.prop('checked', true);
                    this_switch.siblings('.form-check-label').text(on['text']);
                }
                //提示失败
                alertToast(res.msg, 2000, 'error');
            }, function () {
                //关闭加载
                loadingStop(loading, parent_tr);
            });
        });
        //延迟刷新灯箱渲染
        setTimeout(function () {
            //刷新灯箱操作
            refreshFsLightbox();
        }, 1000);
    },
    requestList: function (table, sign, beforeClear = false)
    {
        //获取处理对象
        var __this = this, table_wrapper = $("#pros_table_"+sign+"_wrapper"), tab_box = $("#pros_table_"+sign+"_tabs"), table_body = $("#pros_table_"+sign+"_tbody"), pagination_box = $("#pros_table_"+sign+"_pagination"), query_url = table.attr('data-query-url'), query_method = table.attr('data-query-method'), loading = loadingStart(table_wrapper, table_body[0], '正在查询最新内容...');
        //判断是否提前清理当前分页信息
        if (beforeClear && typeof pagination_box !== 'undefined' && pagination_box.length > 0) {
            //设置页码
            $("#pros_table_"+sign+"_pagination_box").attr('data-page', 1);
        }
        //执行请求
        buildRequest(query_url, __this.arrangeParams(table, sign), query_method, true, function (res) {
            //设置内容
            table_body.empty().html(res.data['content']);
            //判断是否存在分页信息
            if (typeof pagination_box !== 'undefined' && pagination_box.length > 0) {
                //设置分页信息
                pagination_box.empty().html(res.data['pagination']);
            }
            //判断表格构建成功直接请求
            if (parseInt(table.attr('data-build')) === 1) {
                //滑动到列表顶端
                scrollToObject(table_wrapper);
            }
            //判断选项卡是否存在
            if (typeof (tab_box) !== 'undefined' && tab_box.length > 0) {
                //设置选中项数量
                tab_box.find('.nav-link.active').find('.number').text('（'+res.data['total_match_count']+'）');
            }
            //设置内容触发
            __this.setContentTrigger(table, sign);
        }, function (res) {
            //提示失败
            alertToast(res.msg, 2000, 'error');
        }, function () {
            //关闭加载
            loadingStop(loading, table_wrapper);
        });
    },
    arrangeParams: function (table, sign)
    {
        //获取现设参数
        var params = JSON.parse(table.attr('data-query-params')), filter_box = $("#pros_table_"+sign+"_filters"), thead_box = $("#pros_table_"+sign+"_thead"), pagination_box = $("#pros_table_"+sign+"_pagination_box"), __this = this;
        //判断筛选条件框是否存在
        if (typeof (filter_box) !== 'undefined' && filter_box.length > 0) {
            //获取筛选项信息
            var filter_items = filter_box.find('.pros_table_filter_item'), filter_params = {};
            //判断筛选对象是否存在
            if (typeof filter_items !== 'undefined' && filter_items.length > 0) {
                //循环筛选对象
                filter_items.each(function () {
                    //获取基本参数
                    let filter_type = $(this).attr('data-type'), filter_field = $(this).attr('data-field'), filter_target = $(this).attr('data-target'), filter_value = $(filter_target).val();
                    //根据类型实例化对象
                    switch (filter_type) {
                        case 'tags':
                            //判断值内容
                            if (typeof (filter_value) !== 'undefined' && filter_value.length > 0) {
                                //设置条件
                                filter_params[filter_field] = [];
                                //循环内容
                                $.each(JSON.parse(filter_value), function (i, item) {
                                    //新增字段
                                    filter_params[filter_field].push(item.value);
                                });
                            }
                            break;
                        case 'time_range':
                            //判断值内容
                            if (typeof (filter_value) !== 'undefined' && filter_value.length > 0) {
                                //设置条件
                                filter_params[filter_field] = filter_value.split(' 至 ');
                            }
                            break;
                        case 'select':
                            //判断值内容
                            if (typeof (filter_value) !== 'undefined' && filter_value && filter_value.length > 0 && filter_value !== '__WITHOUT_ANY_VALUE__') {
                                //设置条件
                                filter_params[filter_field] = filter_value;
                            }
                            break;
                        case 'group':
                            //重置值
                            filter_value = $(this).find(filter_target + ":checked").val();
                            //判断值内容
                            if (typeof (filter_value) !== 'undefined' && filter_value.length > 0) {
                                //设置条件
                                filter_params[filter_field] = filter_value;
                            }
                            break;
                        case 'dialer':
                            //判断值内容
                            if (typeof (filter_value) !== 'undefined' && filter_value.length > 0) {
                                //设置条件
                                filter_params[filter_field] = filter_value;
                            }
                            break;
                        case 'switch':
                            //判断是否选中
                            if ($(filter_target).is(':checked')) {
                                //设置筛选条件
                                filter_params[filter_field] = $(filter_target).attr('data-on-value');
                            }
                            break;
                        default:
                            //判断值内容
                            if (typeof (filter_value) !== 'undefined' && filter_value.trim().length > 0) {
                                //设置条件
                                filter_params[filter_field] = filter_value;
                            }
                            break;
                    }
                });
            }
            //判断不为空
            if (!$.isEmptyObject(filter_params)) {
                //设置筛选条件
                params.filters = filter_params;
            }
        }
        //判断是否存在表头
        if (typeof thead_box !== 'undefined' && thead_box.length > 0) {
            //获取排序项信息
            var sort_th = thead_box.find('.pros_table_sorting_th'), sort_rules = {};
            //判断是否存在排序项
            if (typeof sort_th !== 'undefined' && sort_th.length > 0) {
                //循环排序项
                sort_th.each(function () {
                   //判断排序规则
                   if ($(this).attr('data-sort') !== 'default') {
                       //设置排序规则
                       sort_rules[$(this).attr('data-field')] = $(this).attr('data-sort');
                   }
                });
                //判断不为空
                if (!$.isEmptyObject(sort_rules)) {
                    //设置排序方式
                    params.sorts = sort_rules;
                }
            }
        }
        //判断是否存在分页信息
        if (typeof pagination_box !== 'undefined' && pagination_box.length > 0) {
            //设置分页信息
            params.page = parseInt(pagination_box.attr('data-page'));
            params.page_size = parseInt(pagination_box.attr('data-page-size'))
        }
        //返回请求参数
        return params;
    }
};
