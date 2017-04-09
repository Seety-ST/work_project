<?php
// 引用头部、底部、统计
include_once MOJIKIDS_TEMPLATES_ROOT.'wap/webcontrol/head.php';
include_once MOJIKIDS_TEMPLATES_ROOT.'wap/webcontrol/footer.php';
include_once MOJIKIDS_TEMPLATES_ROOT.'wap/webcontrol/tj.php';

// 读取模板
$tpl = getSmartyTemplate('index.tpl.htm', MOJIKIDS_TEMPLATES_ROOT.'wap/bb/list/',false, $clear_cache);

// 获取头、底部、统计结构
$wap_global_head = _get_wbc_head(array('use_zepto'=>0));
$wap_global_footer = _get_wbc_footer(array('share_open'=>false));
$wap_global_tj = _get_wbc_tj();

// 接收参数
// $goods_id = trim($_INPUT['goods_id']);


//检查用户的权限
$input_arr["user_id"] = $yue_login_id;
mojikids_check_permissions($input_arr);
//检查用户的权限end



// 获取数据
$ret = get_api_result('user/babycard_list.php',array(
	'user_id' => $yue_login_id,
    'target_id' => "",
    'limit' => "0,99"
));

if ($_INPUT['print']  == 1)
{
    print_r($ret);
}

// 转换数据为json
$json_data = mojikids_output_format_data($ret);


$tpl->assign('ret',$ret);
$tpl->assign('json_data',$json_data);
$tpl->assign('is_weixin',MALL_UA_IS_WEIXIN);
$tpl->assign('wap_global_head',$wap_global_head);
$tpl->assign('wap_global_footer',$wap_global_footer);
$tpl->assign('wap_global_tj',$wap_global_tj);




?>
