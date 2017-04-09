<?php
// 引用头部、底部、统计
include_once MOJIKIDS_TEMPLATES_ROOT.'wap/webcontrol/head.php';
include_once MOJIKIDS_TEMPLATES_ROOT.'wap/webcontrol/footer.php';
include_once MOJIKIDS_TEMPLATES_ROOT.'wap/webcontrol/tj.php';

// 读取模板
$tpl = getSmartyTemplate('index.tpl.htm', MOJIKIDS_TEMPLATES_ROOT.'wap/mine/',false, $clear_cache);

// 获取头、底部、统计结构
$wap_global_head = _get_wbc_head(array('use_zepto'=>0));
$wap_global_footer = _get_wbc_footer(array('share_open'=>false));
$wap_global_tj = _get_wbc_tj();

// 接收参数
$target_id = trim($_INPUT['target_id']);

// 获取数据
$ret = get_api_result('user/babycard_list.php',array(
	'user_id' => $yue_login_id,
	'target_id' => $target_id,
));

// 获取用户信息
$user_info = get_api_result('user/user_info.php',array(
    'user_id' => $yue_login_id
));




// 转换数据为json
$json_data = mojikids_output_format_data($ret['data']);
$json_user_info = mojikids_output_format_data($user_info);

$tpl->assign('ret',$ret);
$tpl->assign('json_user_info',$json_user_info);
$tpl->assign('json_data',$json_data);
$tpl->assign('is_weixin',MALL_UA_IS_WEIXIN);
$tpl->assign('wap_global_head',$wap_global_head);
$tpl->assign('wap_global_footer',$wap_global_footer);
$tpl->assign('wap_global_tj',$wap_global_tj);
$tpl->assign('currentKey','mine');
$tpl->assign('page_url',$MOJIKIDS_PAGE_ARR);
$tpl->assign('MOJIKIDS_PAGE_ARR',$MOJIKIDS_PAGE_ARR);


?>
