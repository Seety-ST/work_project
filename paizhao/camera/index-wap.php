<?php


// 引用头部、底部、统计
include_once PAIZHAO_TEMPLATES_ROOT.'wap/webcontrol/head.php';
include_once PAIZHAO_TEMPLATES_ROOT.'wap/webcontrol/footer.php';
include_once PAIZHAO_TEMPLATES_ROOT.'wap/webcontrol/tj.php';




// 读取模板
$tpl = getSmartyTemplate('index.tpl.htm', PAIZHAO_TEMPLATES_ROOT.'wap/camera/',false, $clear_cache);   

// 获取头、底部、统计结构
$wap_global_head = _get_wbc_head(array('use_vue'=>1));
$wap_global_footer = _get_wbc_footer(array('share_open'=>false));
$wap_global_tj = _get_wbc_tj();

// 获取链接
$paizhao_page_url = pai_mall_paizhao_load_config('page_url');

$user_id = trim($_INPUT['user_id']);
// 商家信息获取
$photographers_class = POCO::singleton('pai_paizhao_photographers_class');
$photographers_info = $photographers_class->get_photographers_info($user_id);
if ($photographers_info['result']==-1) {
    header('Location: http://www.51snap.cn/');
    die();
}
// 获取刷选列表项
$paizhao_goods_type = $photographers_info['un_data']['type_ids'];


 

// 输出数据
$tpl->assign('is_weixin',MALL_UA_IS_WEIXIN);
$tpl->assign('paizhao_goods_type',$paizhao_goods_type);
$tpl->assign('photographers_info',$photographers_info);
$tpl->assign('user_id',$user_id);
$tpl->assign('wap_global_head',$wap_global_head);
$tpl->assign('wap_global_footer',$wap_global_footer);
$tpl->assign('wap_global_tj',$wap_global_tj);



?>