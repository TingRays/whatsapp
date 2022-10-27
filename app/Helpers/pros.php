<?php

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

if (!function_exists('pros_decode_template')) {
    /**
     * 解析表格构建器显示模版信息
     * @Author Abnermouke <abnermouke@outlook.com>
     * @Originate in Abnermouke's MBP
     * @Time 2022-03-21 02:13:51
     * @param $template string 显示模版
     * @param $row array 列表信息
     * @param string $default 默认信息
     * @return string
     */
    function pros_decode_template($template, $row, $default = '')
    {
        //判断数据信息
        if ($row && $template && preg_match_all('~\{(.*)\}~Uuis', $template, $matched) >= 1) {
            //判断匹配信息
            if ($matched[1] && !empty($matched[1])) {
                //循环字段信息
                foreach ($matched[1] as $field) {
                    //判断字段是否存在
                    if (isset($row[strtolower($field)])) {
                        //判断是否为数组
                        $row[strtolower($field)] = is_array($row[strtolower($field)]) ? implode(', ', $row[strtolower($field)]) : $row[strtolower($field)];
                        //设置链接数据
                        $template = str_replace('{' . $field . '}', $row[strtolower($field)], $template);
                    }
                }
            }
            //设置返回信息
            $default = $template;
        }
        //返回信息
        return trim($template) ? trim($template) : trim($default);
    }
}


if (!function_exists('abbr_pros_template')) {
    /**
     * 获取模版大写首字母
     * @Author Abnermouke <abnermouke@outlook.com>
     * @Originate in Abnermouke's MBP
     * @Time 2022-06-27 11:30:40
     * @param $template
     * @param $row
     * @param $default
     * @return mixed
     */
    function abbr_pros_template($template, $row, $default)
    {
        //获取模版内容
        $template = pros_decode_template($template, $row, $default);
        //获取首字母
        return abbr_pros_string($template);
    }
}

if (!function_exists('abbr_pros_string')) {
    /**
     * 获取文本大写首字母
     * @Author Abnermouke <abnermouke@outlook.com>
     * @Originate in Abnermouke's MBP
     * @Time 2022-06-27 11:53:36
     * @param $string
     * @return string
     * @throws Exception
     */
    function abbr_pros_string($string)
    {
        //获取第一位
        $first = mb_substr($string, 0, 1);
        //判断是否为中文
        if (\Abnermouke\EasyBuilder\Library\Currency\ValidateLibrary::onlyZh($first)) {
            //转为英文
            $first = pinyin_abbr($first);
        }
        //返回大写首字母
        return strtoupper($first);
    }
}

if (!function_exists('pros_decode_link')) {
    /**
     * 解析表格构建器请求链接信息
     * @Author Abnermouke <abnermouke@outlook.com>
     * @Originate in Abnermouke's MBP
     * @Time 2022-07-13 18:09:48
     * @param $link
     * @param $row
     * @param string $default_link
     * @return mixed|string
     */
    function pros_decode_link($link, $row, $default_link = 'javascript:;')
    {
        //判断链接
        if ($link && strstr($link, '__')) {
            //初始化链接信息
            $link = urldecode($link);
            //匹配信息
            if ($row && preg_match_all('~__(.*)__~Uuis', $link, $matched) >= 1) {
                //判断匹配信息
                if ($matched[1] && !empty($matched[1])) {
                    //循环字段信息
                    foreach ($matched[1] as $field) {
                        //设置链接数据
                        $link = str_replace('__'.$field.'__', data_get($row, strtolower($field), ''), $link);
                    }
                }
            }
            //初始化链接
            $link = trim($link);
        }
        //返回链接
        return $link ? $link : $default_link;
    }
}

if (!function_exists('init_request_params')) {
    /**
     * 初始化请求参数（替换不规则字段）
     * @Author Abnermouke <abnermouke@outlook.com>
     * @Originate in Abnermouke's MBP
     * @Time 2022-07-21 16:07:01
     * @param $params
     * @return mixed
     */
    function init_request_params($params) {
        //判断信息
        if ($params) {
            //循环参数信息
            foreach ($params as $k => $param) {
                //判断信息
                if (is_array($param)) {
                    //整理信息
                    $param = !empty($param) ? Arr::query($param) : '[]';
                }
                //判断信息
                $param = Str::length($param) > 200 ? ('__LONG_TEXT__:'.Str::length($param)) : $param;
                //设置参数信息
                $params[$k] = $param;
            }
        }
        //返回参数信息
        return $params;
    }
}

if (!function_exists('pros_console_has_permission')) {
    /**
     * 验证是否有权限
     * @Author Abnermouke <abnermouke@gmail.com>
     * @Originate in Abnermouke's MBP
     * @Time 2021-04-28 14:30:49
     * @param $route_name
     * @param string $method
     * @return bool
     * @throws Exception
     */
    function pros_console_has_permission($route_name, $method = 'post')
    {
        //验证信息
        return (new \App\Handler\Cache\Data\Pros\Console\RoleCacheHandler(current_auth('role_id', config('pros.session_prefix', 'abnermouke:pros:console:auth'))))->hasPermission(strtolower($method), strtolower($route_name));
    }
}

if (!function_exists('pros_console_abort_error'))
{
    /**
     * 渲染错误信息
     * @Author Abnermouke <abnermouke@gmail.com>
     * @Originate in Abnermouke's MBP
     * @Time 2021-04-28 15:33:24
     * @param $code
     * @param string $message
     * @param false $redirect_uri
     * @return \Illuminate\Http\Response
     */
    function pros_console_abort_error($code, $message = '页面找不到了', $redirect_uri = false)
    {
        //配置消息信息
        $message = !empty($message) ? $message : '页面找不到了！';
        //渲染页面
        return response()->view('vendor.pros.console.errors', compact('code', 'message', 'redirect_uri'));
    }
}

if (!function_exists('pros_console_action_condition')) {
    /**
     * 设置构建器Action显示条件
     * @Author Abnermouke <abnermouke@outlook.com>
     * @Originate in Abnermouke's MBP
     * @Time 2022-07-25 23:20:38
     * @param $conditions
     * @param $row
     * @return bool
     */
    function pros_console_action_condition($conditions, $row)
    {
        //初始化是否限制
        $show = true;
        //判断是否存在条件
        if ($conditions) {
            //循环条件
            foreach ($conditions as $filed => $values) {
                //整理信息
                $value = data_get($row, $filed, false);
                //判断值是否存在信息
                if ((is_bool($value) && !$value) || !in_array($value, object_2_array($values))) {
                    //设置限制显示
                    $show = false;
                }
            }
        }
        //返回是否限制
        return $show;
    }
}
