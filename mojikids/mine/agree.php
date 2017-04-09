<?php

include_once('../fe_include/common.inc.php');
// 引用头部、底部、统计
include_once MOJIKIDS_TEMPLATES_ROOT.'wap/webcontrol/head.php';
include_once MOJIKIDS_TEMPLATES_ROOT.'wap/webcontrol/footer.php';
include_once MOJIKIDS_TEMPLATES_ROOT.'wap/webcontrol/tj.php';

// 读取模板
$tpl = getSmartyTemplate('agree.tpl.htm', MOJIKIDS_TEMPLATES_ROOT.'wap/mine/',false, $clear_cache);

// 获取头、底部、统计结构
$wap_global_head = _get_wbc_head(array('use_zepto'=>0));
$wap_global_footer = _get_wbc_footer(array('share_open'=>false));
$wap_global_tj = _get_wbc_tj();







// 转换数据为json

$tpl->assign('is_weixin',MALL_UA_IS_WEIXIN);
$tpl->assign('wap_global_head',$wap_global_head);
$tpl->assign('wap_global_footer',$wap_global_footer);
$tpl->assign('wap_global_tj',$wap_global_tj);


$tpl->display();

?>
