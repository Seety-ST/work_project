<?php

include_once('../fe_include/common.inc.php');
// 引用头部、底部、统计
include_once MOJIKIDS_TEMPLATES_ROOT.'wap/webcontrol/head.php';
include_once MOJIKIDS_TEMPLATES_ROOT.'wap/webcontrol/footer.php';
include_once MOJIKIDS_TEMPLATES_ROOT.'wap/webcontrol/tj.php';

// 读取模板
$tpl = getSmartyTemplate('store_info.tpl.htm', MOJIKIDS_TEMPLATES_ROOT.'wap/about/',true, $clear_cache);

// 获取头、底部、统计结构
$wap_global_head = _get_wbc_head(array('use_zepto'=>0));
$wap_global_footer = _get_wbc_footer(array('share_open'=>false));
$wap_global_tj = _get_wbc_tj();

$store_id = trim($_INPUT['store_id']);
$show_map = trim($_INPUT['show_map']);
// 获取数据
$ret = get_api_result('services/store_info.php',array(
    'user_id' => $yue_login_id,
    'store_id' =>$store_id,
));

$ret['data']['image'] = mojikids_resize_act_img_url($ret['data']['image'],'640');
$json_data = mojikids_output_format_data($ret['data']);


// 转换数据为json
$tpl->assign('ret',$ret);
$tpl->assign('json_data',$json_data);
$tpl->assign('is_weixin',MALL_UA_IS_WEIXIN);
$tpl->assign('show_map',$show_map);
$tpl->assign('wap_global_head',$wap_global_head);
$tpl->assign('wap_global_footer',$wap_global_footer);
$tpl->assign('wap_global_tj',$wap_global_tj);


$tpl->display();

?>
