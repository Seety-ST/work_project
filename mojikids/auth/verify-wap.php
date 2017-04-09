<?php
// 引用头部、底部、统计
include_once MOJIKIDS_TEMPLATES_ROOT.'wap/webcontrol/head.php';
include_once MOJIKIDS_TEMPLATES_ROOT.'wap/webcontrol/footer.php';
include_once MOJIKIDS_TEMPLATES_ROOT.'wap/webcontrol/tj.php';

// 读取模板
$tpl = getSmartyTemplate('verify.tpl.htm', MOJIKIDS_TEMPLATES_ROOT.'wap/auth/',true, $clear_cache);

// 获取头、底部、统计结构
$wap_global_head = _get_wbc_head(array('use_zepto'=>1));
$wap_global_footer = _get_wbc_footer(array('share_open'=>false));
$wap_global_tj = _get_wbc_tj();

// 接收参数


//获取用户手机号
$output_arr = mojikids_check_login_id_bind_phone(array("user_id"=>$yue_login_id));
$user_phone = $output_arr["bind_phone"];


//检查用户的权限
$input_arr["user_id"] = $yue_login_id;
mojikids_check_permissions($input_arr);
//检查用户的权限end

$count_down_time = '10';
switch(G_PHP_COMMON_ENVIRONMENT_TYPE)
{
	case 'dev':	
		break;
	case 'test':
	case 'pro':
		$count_down_time = '60';
		break;
}


// 转换数据为json
$json_data = mojikids_output_format_data($ret);
$tpl->assign('ret',$ret);
$tpl->assign('json_data',$json_data);
$tpl->assign('is_weixin',MALL_UA_IS_WEIXIN);
$tpl->assign('wap_global_head',$wap_global_head);
$tpl->assign('wap_global_footer',$wap_global_footer);
$tpl->assign('wap_global_tj',$wap_global_tj);
$tpl->assign('user_phone',$user_phone);
$tpl->assign('count_down_time',$count_down_time);

?>
