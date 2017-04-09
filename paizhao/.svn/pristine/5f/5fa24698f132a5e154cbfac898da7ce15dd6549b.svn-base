<?php
require(dirname(dirname(__FILE__)).'/common.inc.php');

$sms_obj = POCO::singleton('pai_sms_class');
$user_acl_obj = POCO::singleton('pai_paizhao_admin_user_class');
$mall_user_obj = POCO::singleton('pai_user_class');
$user_phone = $mall_user_obj->get_phone_by_user_id($yue_login_id);
$user_info = $user_acl_obj->get_user_info($yue_login_id);
//print_r($user_info);
//$session_id = session_id();
$session_id = $_COOKIE['yue_g_session_id'].'|'.date('Y-m-d');
$phone = $user_info['phone'];
$group_key = 'G_YUEUS_ADMIN_TASK_USER_LOGIN_VERIFY';
$send_sms = false;

if($_GET['send_sms'])
{
	$send_sms = true;
	$show_message = "¶ÌĞÅÒÑ·¢ËÍ,Çë×¢Òâ²éÊÕ";
}
elseif($_POST)
{
	$send_sms = false;
	if($_POST['code'])
	{
		$re = $sms_obj->check_verify_code($phone, $group_key, $_POST['code'], $yue_login_id,false);
		if($re)
		{
			$password = $_POST['password'];
			$user_login = $mall_user_obj->user_login_for_check($user_phone, $password);
			if($user_login)
			{
				$user_acl_obj->update_user_session($yue_login_id,$session_id);
				$sms_obj->del_verify_code($phone, $group_key);
				$r_url = $_INPUT['r_url']?urldecode($_INPUT['r_url']):"/yue_admin/task/index.php";
				header("Location:http://www.yueus.com".$r_url);
				exit;
			}
			else
			{
				$show_message = "ÃÜÂë´íÎó";
			}
		}
		else
		{
			$show_message = "ÑéÖ¤Âë´íÎó";
		}
	}
}
$re = $send_sms?$sms_obj->send_verify_code($phone, $group_key, '', $yue_login_id):false;
$tpl = new SmartTemplate ( TASK_TEMPLATES_ROOT."/verification/send_verification.tpl.htm" );
$tpl->assign('send_code',$re);
$tpl->assign('phone',$phone);
$tpl->assign('user_id',$yue_login_id);
$tpl->assign('r_url',$_INPUT['r_url']);
$tpl->assign('code',$_INPUT['code']);
$tpl->assign('password',$_INPUT['password']);
$tpl->assign('show_message',$show_message);
$tpl->output();
