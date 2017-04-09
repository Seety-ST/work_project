<?php
/**
 * 微信授权页面
 * 
 * @author Henry
 * @copyright 2016-12-22
 */

include_once('/disk/data/htdocs232/poco/pai/poco_app_common.inc.php');
include_once('/disk/data/htdocs232/poco/member/poco_app_common.inc.php');
include_once('/disk/data/htdocs232/poco/pai/mobile/poco_pai_common.inc.php');

$weixin_pub_obj = POCO::singleton('pai_weixin_pub_class');
$user_obj = POCO::singleton('pai_user_class');
$bind_id = 10;

$code = trim($_GET['code']);
$url = trim($_GET['url']);
$url2 = trim($_GET['url2']);
$mode = trim($_GET['mode']);
if( strlen($mode)<1 ) $mode = trim($_GET['link_mode']);
$route = trim($_GET['route']);

if( strlen($code)<1 )
{
	$yueus_openid = trim($_COOKIE['mojikids_openid']);
	$yueus_code = trim($_COOKIE['mojikids_code']);
	$yueus_scope = trim($_COOKIE['mojikids_scope']);
	
	//若没授权，则跳去授权
	if( strlen($yueus_openid)<1 || strlen($yueus_code)<1 || strlen($yueus_scope)<1 )
	{
		$params = array();
		if( strlen($url)>0 ) $params['url'] = $url;
		if( strlen($url2)>0 ) $params['url2'] = $url2;
		if( strlen($mode)>0 ) $params['mode'] = $mode;
		if( strlen($route)>0 ) $params['route'] = $route;
		
		$auth_url = $weixin_pub_obj->auth_get_authorize_url($params, 'snsapi_userinfo', '', '', $bind_id);
		header('Location:' . $auth_url);
		exit();
	}
}
else
{
	//授权成功回来了
	$access_info = $weixin_pub_obj->auth_get_access_info($code, $bind_id);
	if( empty($access_info) )
	{
		die('weixin authorization failed!');
	}

	//获取绑定信息
	$bind_info = $weixin_pub_obj->get_bind_info($bind_id);
	if( empty($bind_info) )
	{
		die('weixin bind_info empty!');
	}

	$user_info = $weixin_pub_obj->auth_get_user_info($access_info['openid'], $access_info['access_token']);
	if( !is_array($user_info) ) $user_info = array();

    //日志
    $data_log = array();
    $data_log['cookie'] = $_COOKIE;
    $data_log['access_info'] = $access_info;
    $data_log['user_info'] = $user_info;
    $data_log['bind_info'] = $bind_info;
    $payment_obj = POCO::singleton('pai_payment_class');
    ecpay_log_class::add_log($data_log, 'code', 'pai_weixin_auth');

	//入库
	$user_info['openid'] = $access_info['openid'];
	$user_info['code'] = $code;
	$user_info['access_token'] = $access_info['access_token'];
	$user_info['expires_in'] = $access_info['expires_in'];
	$user_info['refresh_token'] = $access_info['refresh_token'];
	$user_info['scope'] = $access_info['scope'];
	$weixin_pub_obj->save_weixin_user($user_info, $bind_info['app_id']);

	if( $yue_login_id ) //已经有登录
	{
		$user_obj->logout(); //先退出，以便用户下次重新进来

		//退出授权
		setcookie('mojikids_openid', '', null, '/', '.mojikids.com');
        setcookie('mojikids_unionid', '', null, '/', '.mojikids.com');
		setcookie('mojikids_code', '', null, '/', '.mojikids.com');
		setcookie('mojikids_scope', '', null, '/', '.mojikids.com');
		setcookie('mojikids_url2', '', null, '/', '.mojikids.com'); //已授权、未绑定、未登录，将url2传递给前端，以便前端注册、登录完跳转
		echo '<meta http-equiv="Content-Type" content="text/html; charset=gbk" /><script>alert("'.$yue_login_id.'很抱歉，登录状态错误；请先关闭此窗口，再重新授权。");</script>';
		exit();
	}


	if( strlen($url2)>0 ) $url = $url2; //已授权、已绑定、已登录的跳转
	setcookie('mojikids_url2', $url2, null, '/', '.mojikids.com'); //已授权、未绑定、未登录，将url2传递给前端，以便前端注册、登录完跳转

	//cookie
	$expire_time = time() + 2147483646;
	setcookie('mojikids_openid', $access_info['openid'], $expire_time, '/', '.mojikids.com');
    setcookie('mojikids_unionid', $access_info['unionid'], $expire_time, '/', '.mojikids.com');
	setcookie('mojikids_code', $code, $expire_time, '/', '.mojikids.com');
	setcookie('mojikids_scope', $access_info['scope'], $expire_time, '/', '.mojikids.com');
}

if( strlen($url)<1 )
{
	if( strlen($mode)<1 ) $mode = 'wx';
	
	$version_control = include('/disk/data/htdocs232/poco/pai/m/config/version_control.conf.php');
	$url = trim($version_control[$mode]['page_url']);
	if( strlen($url)>0 && strlen($route)>0 )
	{
		$url .= '#' . $route;
	}
}

//临时日志
$_GET['href_url'] = $url;
$payment_obj = POCO::singleton('pai_payment_class');
ecpay_log_class::add_log($_GET, __FILE__ , 'pai_weixin_auth');

if( strlen($url)>0 )
{
	$url = str_replace("'", "\'", $url);
	echo "<script type=\"text/javascript\">location.href='{$url}';</script>";
}
