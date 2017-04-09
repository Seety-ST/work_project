<?php
/**
 * ΢����Ȩҳ��
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
	
	//��û��Ȩ������ȥ��Ȩ
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
	//��Ȩ�ɹ�������
	$access_info = $weixin_pub_obj->auth_get_access_info($code, $bind_id);
	if( empty($access_info) )
	{
		die('weixin authorization failed!');
	}

	//��ȡ����Ϣ
	$bind_info = $weixin_pub_obj->get_bind_info($bind_id);
	if( empty($bind_info) )
	{
		die('weixin bind_info empty!');
	}

	$user_info = $weixin_pub_obj->auth_get_user_info($access_info['openid'], $access_info['access_token']);
	if( !is_array($user_info) ) $user_info = array();

    //��־
    $data_log = array();
    $data_log['cookie'] = $_COOKIE;
    $data_log['access_info'] = $access_info;
    $data_log['user_info'] = $user_info;
    $data_log['bind_info'] = $bind_info;
    $payment_obj = POCO::singleton('pai_payment_class');
    ecpay_log_class::add_log($data_log, 'code', 'pai_weixin_auth');

	//���
	$user_info['openid'] = $access_info['openid'];
	$user_info['code'] = $code;
	$user_info['access_token'] = $access_info['access_token'];
	$user_info['expires_in'] = $access_info['expires_in'];
	$user_info['refresh_token'] = $access_info['refresh_token'];
	$user_info['scope'] = $access_info['scope'];
	$weixin_pub_obj->save_weixin_user($user_info, $bind_info['app_id']);

	if( $yue_login_id ) //�Ѿ��е�¼
	{
		$user_obj->logout(); //���˳����Ա��û��´����½���

		//�˳���Ȩ
		setcookie('mojikids_openid', '', null, '/', '.mojikids.com');
        setcookie('mojikids_unionid', '', null, '/', '.mojikids.com');
		setcookie('mojikids_code', '', null, '/', '.mojikids.com');
		setcookie('mojikids_scope', '', null, '/', '.mojikids.com');
		setcookie('mojikids_url2', '', null, '/', '.mojikids.com'); //����Ȩ��δ�󶨡�δ��¼����url2���ݸ�ǰ�ˣ��Ա�ǰ��ע�ᡢ��¼����ת
		echo '<meta http-equiv="Content-Type" content="text/html; charset=gbk" /><script>alert("'.$yue_login_id.'�ܱ�Ǹ����¼״̬�������ȹرմ˴��ڣ���������Ȩ��");</script>';
		exit();
	}


	if( strlen($url2)>0 ) $url = $url2; //����Ȩ���Ѱ󶨡��ѵ�¼����ת
	setcookie('mojikids_url2', $url2, null, '/', '.mojikids.com'); //����Ȩ��δ�󶨡�δ��¼����url2���ݸ�ǰ�ˣ��Ա�ǰ��ע�ᡢ��¼����ת

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

//��ʱ��־
$_GET['href_url'] = $url;
$payment_obj = POCO::singleton('pai_payment_class');
ecpay_log_class::add_log($_GET, __FILE__ , 'pai_weixin_auth');

if( strlen($url)>0 )
{
	$url = str_replace("'", "\'", $url);
	echo "<script type=\"text/javascript\">location.href='{$url}';</script>";
}