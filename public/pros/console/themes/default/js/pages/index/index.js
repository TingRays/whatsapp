$(function () {
    //初始化订单统计
    initKtOrderChats();
    //初始化用户统计
    initKtUserChats();
});

/**
 * 初始化用户统计
 */
function initKtUserChats()
{
    //获取元素
    var element = document.getElementById("user_chats");
    //指定参数
    var height = parseInt(KTUtil.css(element, 'height')), labelColor = '#A1A5B7', borderColor = '#E4E6EF', baseprimaryColor = '#009ef7', lightprimaryColor = '#009ef7', basesuccessColor = '#50cd89', lightsuccessColor = '#50cd89', basedangerColor = '#F1416C', lightdangerColor = '#F1416C', basewarningColor = '#FFC700', lightwarningColor = '#FFC700', baseinfoColor = '#5014D0', lightinfoColor = '#5014D0';
    //获取统计数据
    var jquery_element = $("#user_chats"), statistics_date = JSON.parse(jquery_element.attr('data-dates')), statistics_user_data = JSON.parse(jquery_element.attr('data-users'));
    //实例化统计
    var options = {
        series: [{
            name: '新增用户数',
            data: statistics_user_data['insert']
        }, {
            name: '访客数',
            data: statistics_user_data['browser']
        }, {
            name: '成交用户数',
            data: statistics_user_data['paid']
        }, {
            name: '充值用户',
            data: statistics_user_data['charge']
        }, {
            name: '新增付费用户数',
            data: statistics_user_data['insert_paid']
        }],
        chart: {
            fontFamily: 'inherit',
            type: 'area',
            height: height,
            toolbar: {
                show: false
            }
        },
        fill: {
            type: "gradient",
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.4,
                opacityTo: 0.2,
                stops: [15, 120, 100]
            }
        },
        stroke: {
            curve: 'smooth',
            show: true,
            width: 3,
            colors: [baseprimaryColor, basesuccessColor, basedangerColor, baseinfoColor, basewarningColor]
        },
        xaxis: {
            categories: statistics_date,
            tickAmount: 6,
            labels: {
                rotate: 0,
                rotateAlways: true,
                style: {
                    colors: labelColor,
                    fontSize: '12px'
                }
            },
            crosshairs: {
                position: 'front',
                stroke: {
                    color: [baseprimaryColor, basesuccessColor, basedangerColor, baseinfoColor, basewarningColor],
                    width: 1,
                    dashArray: 3
                }
            },
            tooltip: {
                enabled: true,
                formatter: undefined,
                offsetY: 0,
                style: {
                    fontSize: '12px'
                }
            }
        },
        yaxis: {
            max: parseInt(jquery_element.attr('data-user-max')),
            min: 30,
            tickAmount: 6,
            labels: {
                style: {
                    colors: labelColor,
                    fontSize: '12px'
                },
                formatter: function(val) {
                    if (window.isNaN(val) || Math.floor(val) != val) {
                        return val;
                    }
                    try{
                        return val.toFixed(0);
                    } catch(e){
                        return val;
                    }
                }
            }
        },
        tooltip: {
            style: {
                fontSize: '12px'
            }
        },
        colors: [lightprimaryColor, lightsuccessColor, lightdangerColor, lightinfoColor, lightwarningColor],
        grid: {
            borderColor: borderColor,
            strokeDashArray: 4,
            yaxis: {
                lines: {
                    show: true
                }
            }
        },
        markers: {
            strokeColor: [baseprimaryColor, basesuccessColor],
            strokeWidth: 3
        }
    };

    var chart = new ApexCharts(element, options);
    chart.render();
}

/**
 * 初始化订单统计模块
 */
function initKtOrderChats()
{
    //获取元素
    var element = document.getElementById('order_chats');
    //指定参数
    var height = parseInt(KTUtil.css(element, 'height')), labelColor = '#A1A5B7', borderColor = '#eff2f5', baseColor = '#009ef7', baseLightColor = '#50cd89', secondaryColor = '#7239EC';
    //获取统计数据
    var jquery_element = $("#order_chats"), statistics_date = JSON.parse(jquery_element.attr('data-dates')), statistics_order_data = JSON.parse(jquery_element.attr('data-orders'));
    //实例化统计
    var chart = new ApexCharts(element, {
        series: [{
            name: '营业额',
            type: 'bar',
            stacked: true,
            data: statistics_order_data['pay']
        },{
            name: '订单金额',
            type: 'bar',
            stacked: true,
            data: statistics_order_data['total']
        }, {
            name: '充值金额',
            type: 'area',
            data: statistics_order_data['charge']
        }],
        chart: {
            fontFamily: 'inherit',
            stacked: true,
            height: height,
            toolbar: {
                show: false
            }
        },
        stroke: {
            curve: 'smooth',
            show: false,
            width: 2,
            colors: ['transparent']
        },
        xaxis: {
            categories: statistics_date,
            axisBorder: {
                show: true,
            },
            axisTicks: {
                show: true
            },
            labels: {
                style: {
                    colors: labelColor,
                    fontSize: '12px'
                }
            }
        },
        yaxis: {
            max: parseInt(jquery_element.attr('data-order-max')),
            labels: {
                style: {
                    colors: labelColor,
                    fontSize: '12px'
                }
            }
        },
        tooltip: {
            style: {
                fontSize: '12px'
            },
            y: {
                formatter: function (val) {
                    return '共 ' + val + ' 元'
                }
            },
        },
        colors: [baseColor, secondaryColor, baseLightColor],
        grid: {
            borderColor: borderColor,
            yaxis: {
                lines: {
                    show: false
                }
            },
        }
    });
    //渲染统计模块
    chart.render();
}
