<?php
/**
* @Desc:     公共变量
* @User:     wlq<wulq@yueus.com>
* @Date:     2016年10月11日
* @Time:     上午9:07:36
* @Version:
*/
define('MOJIKIDS_ROOT_URL', '/disk/data/htdocs232/poco/mojikids/');

require('/disk/data/htdocs232/poco/pai/poco_app_common.inc.php');
require('/disk/data/htdocs232/poco/mojikids/poco_app_common.inc.php');
include(MOJIKIDS_ROOT_URL.'fe_include/api_rest.php');
$MOJIKIDS_PAGE_ARR = include(MOJIKIDS_ROOT_URL.'config/page_url_config.inc.php');

define('MOJIKIDS_TEMPLATES_ROOT', MOJIKIDS_ROOT_URL.'templates/');
define('MOJIKIDS_SITE_URL', 'http://www.mojikids.com');
define('MOJIKIDS_PAGE_ARR', $MOJIKIDS_PAGE_ARR);
/**hudw 前端使用的define**/
$user_agent_arr = mall_get_user_agent_arr();
$ua_fe_arr = array();

$__is__pc = $user_agent_arr['is_pc'];
if($_INPUT['__moni_mobile_ua__'] == 1)
{
        $__is__pc = 0;
}

if($__is__pc == 1 )
{
    $ua_fe_arr['pc'] = 1;
    $ua_fe_arr['mobile'] = 0;
    $ua_fe_arr['weixin'] = 0;

}
else
{
    $ua_fe_arr['pc'] = 0;
    $ua_fe_arr['mobile'] = 1;
    $ua_fe_arr['weixin'] = 0;

    if($user_agent_arr['is_weixin'] == 1 )
    {
        $ua_fe_arr['weixin'] = 1;
    }
}

define('MALL_UA_IS_PC',$ua_fe_arr['pc']);
define('MALL_UA_IS_MOBILE',$ua_fe_arr['mobile']);
define('MALL_UA_IS_WEIXIN',$ua_fe_arr['weixin']);
/**hudw 前端使用的define**/

// 前端使用拍照占位图
define('MOJIKIDS_PLACEHOLER_IMG','//www.51snap.cn/static/pc/image/common/paizhao-ph-bgimg-500x500.png');

// 微信授权
if($user_agent_arr['is_weixin'] == 1  && MALL_WEIXIN_NEED_AUTHORIZE == 1 && empty($_COOKIE['mojikids_openid']))
{
    $url1 = $MOJIKIDS_PAGE_ARR['auth']; //注册页地址
    $url2 = 'http://'.$_SERVER['SERVER_NAME'].$_SERVER["REQUEST_URI"];
    $scope = 'snsapi_userinfo';
    $params = array(
        'url' => $url1,
        'url2' => $url2,
    );
    $weixin_pub_obj = POCO::singleton('pai_weixin_pub_class');
    $auth_url = $weixin_pub_obj->auth_get_authorize_url($params, $scope, '', '', 10);

    header("Location:{$auth_url}");
    exit();
}

/**
 * 使用api_rest.php
 */
if(!function_exists("get_api_result"))
{
        function get_api_result($path, $param, $decode = TRUE, $to_gbk = FALSE, $is_login = TRUE,$version = '1.0.0')
        {
            return __get_api_result($path, $param, $decode, $to_gbk, $is_login,$version);
        }
}

/**
 * 返回Smarty模板对象
 * @param string $template_file
 * @param string $template_dir
 * @return object
 */
function getSmartyTemplate($template_file, $template_dir='', $caching = false, $clear_cache = false)
{
    $smarty = new Smarty();
    $template_dir = trim($template_dir);
    if( strlen($template_dir)>0 ) $smarty->setTemplateDir($template_dir);
    $smarty->setCompileDir('./_smarty_templates_c/');
    $smarty->setCompileCheck(true);
    $smarty->setForceCompile(false);
    $smarty->setCacheDir('./_smarty_cache/');
    if ($caching)
    {
        $smarty->setCaching(Smarty::CACHING_LIFETIME_CURRENT);
        $smarty->setCacheLifetime(3600);
    }
    else if(!$caching)
    {
        $smarty->setCacheLifetime(0);
    }
    if ($clear_cache)
    {
        $smarty->clearAllCache();
    }
    $smarty->setLeftDelimiter('{%');
    $smarty->setRightDelimiter('%}');
    $smarty->setDebugging(false);
    return $smarty->createTemplate($template_file);
}

/**
 * 获取微信签名
 * @return unknown
 */
function mall_paizhao_wx_get_js_api_sign_package()
{
    $app_id = 'wxb93f7fe974be1cc2';	//约摄服务号
    // ======== 当前链接判断 ========
    $_PROTOCOL = 'http';
    if(!empty($_SERVER['HTTPS']))
    {
        $_PROTOCOL = 'https';
    }
    $url = "{$_PROTOCOL}://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";

    $weixin_helper_obj = POCO::singleton('pai_weixin_helper_class');
    $ret = $weixin_helper_obj->wx_get_js_api_sign_package_by_app_id($app_id, $url);
    return $ret;
}


/**
 * 转换图片
 * @param  [type] $img_url [description]
 * @param  string $size    [description]
 * @return [type]          [description]
 */
function mojikids_resize_act_img_url($img_url, $size = '')
{
    if (in_array ( $size, array (32,64,86,100,145,165,260,320,440,468,640 ) ))
    {
        $size_str = '_' . $size;
    }
    else
    {
        $size_str = '';
    }


    if (preg_match ( "/_(32|64|86|100|145|165|260|320|440|468|640).(jpg|png|jpeg|gif|bmp)/i", $img_url ))
    {
        $img_url = preg_replace ( "/_(32|64|86|100|145|165|260|320|440|468|640).(jpg|png|jpeg|gif|bmp)/i", "{$size_str}.$2", $img_url );
    }
    else
    {
        $img_url = preg_replace ( '/.(jpg|png|jpeg|gif|bmp)/i', "{$size_str}.$1", $img_url );
    }


    return $img_url;
}

/**
 * 转换输出json
 * @param  [type]  $output_arr   [description]
 * @param  boolean $include_stat [description]
 * @param  boolean $output_bol   [description]
 * @return [type]                [description]
 */
function mojikids_mobile_output($output_arr,$include_stat = true,$output_bol = false)
{

    $poco_services_api_obj = POCO::singleton('poco_services_api_class');
    $poco_services_api_obj->output_bol = $output_bol;

    // 将图片换成bgp线路
    define('POCO_CONTENT_OUPUT_BGP_PARSER_LINK', 1);

    if($include_stat)
    {
        if (class_exists("poco_run_time_class") && !defined("IGNORE_TPL_RUN_TIME_SCRIPT"))
        {
            $page_totalrun_time = poco_run_time_class::end();
        }

        $real_output_arr['page_run_time'] = $page_totalrun_time;

    }

    $real_output_arr['res'] = $output_arr;

    $page_type = $output_arr['page_type'];

    //cdn链接替换

    /*$output_str =  $poco_services_api_obj->output($real_output_arr,$format="json",true,"",array('strip_tags' => false , 'entity' => false,'special_handle' => array('&lt;'=>1,'&gt;'=>1) ));*/


    $output_str = POCO::execute('common.content_output_cdn_parser',$output_str);


    header("Access-Control-Allow-Origin:*");
    header("Content-Type:application/json;");

    echo json_encode($real_output_arr,false);

    if(extension_loaded("zlib"))
    {
        ob_end_flush(); //输出buffer中的内容
    }
}

// 生成JSON
function mojikids_output_format_data($output_arr)
{

    $poco_services_api_obj = POCO::singleton('poco_services_api_class');
    $poco_services_api_obj->output_bol = $output_bol;

    // 将图片换成bgp线路
    define('POCO_CONTENT_OUPUT_BGP_PARSER_LINK', 1);



    if($include_stat)
    {
        if (class_exists("poco_run_time_class") && !defined("IGNORE_TPL_RUN_TIME_SCRIPT"))
        {
            $page_totalrun_time = poco_run_time_class::end();
        }

        $real_output_arr['page_run_time'] = $page_totalrun_time;

    }

    $real_output_arr['res'] = $output_arr;

    $page_type = $output_arr['page_type'];

    $json_data = json_encode($real_output_arr,false);

    if(empty($json_data))
    {
        $json_data = "{}";
    }

    return $json_data;


}

//检查当前登录用户ID是否绑了手机及返回相关信息（BY-星星）
/*
 * @param $input_arr //条件数组，user_id表示要检查的登录ID
 *
 * return array
 * int bind_phone //表示绑定了的手机
 *
*/
function mojikids_check_login_id_bind_phone($input_arr)
{
    $pai_mall_yueshe_user_obj = POCO::singleton('pai_mall_yueshe_user_class');
    $user_info = $pai_mall_yueshe_user_obj->get_member_info($input_arr["user_id"]);
    if(!empty($user_info["cellphone"]))
    {
        $output_arr["bind_phone"] = $user_info["cellphone"];
    }
    else
    {
        $output_arr["bind_phone"] = "";
    }
    return $output_arr;
}


//用于检查是否需要绑定手机，根据传递的参数做相关跳转处理（BY-星星）
/*
 * @param $input_arr //条件数组，相关字段
 *  int user_id:表示要检查的登录ID，不传表示非登录状态，理应传入全局值 $yue_login_id
 *  string r_url:回跳链接，不传入则使用当前的链接;
 * */

function mojikids_check_permissions($input_arr)
{
    $page_arr = include(MOJIKIDS_ROOT_URL.'config/page_url_config.inc.php');
    //判断是否传有指定回跳链接
    if(!$input_arr["r_url"])
    {
        //当前链接
        $_PROTOCOL = 'http';
        if(!empty($_SERVER['HTTPS']))
        {
            $_PROTOCOL = 'https';
        }

        $input_arr["r_url"] = "{$_PROTOCOL}://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
        if($input_arr["r_url"]==$page_arr["auth"] || $input_arr["r_url"]==($page_arr["auth"]."index.php"))
        {
            $input_arr["r_url"] = $page_arr["index"];
        }
    }
    $encode_r_url = urlencode($input_arr["r_url"]);
    $bind_phone_link = $page_arr["auth"]."index.php?r_url=".$encode_r_url;//绑手机的跳转链接

    if($input_arr["user_id"])
    {
        //判断是否绑定了手机号
        $output_arr = mojikids_check_login_id_bind_phone($input_arr);
        if(!$output_arr["bind_phone"])
        {
            header("Location:".$bind_phone_link);
            exit;
        }
    }
    else
    {
        header("Location:".$bind_phone_link);
        exit;
    }
}

/**
 * 替换HTML格式图片
 * @param string $content
 * @param string $html_tag 替换的HTML标签
 * @param string $search_param 搜索要替换的属性
 * @param string $replace_param 要替换成的属性
 * @param string $replace_param_value 搜索出的参数要替换成的值
 * @param bool $is_copy 是否复制$search_param的值，不复制则替换成$replace_param_value
 * @return bool|mixed
 */
function mojikids_replace_lazyload_img($content="",$html_tag='img',$search_param='',$replace_attr="",$custom_attr="")
{
    if(empty($html_tag))
    {
        return false;
    }

    return preg_replace("#<{$html_tag}(.*?){$search_param}=(\"|')(.*?)(\"|')#i","<$html_tag$1 {$replace_attr}='{src:\"$3\"}' $custom_attr ",$content);
}
