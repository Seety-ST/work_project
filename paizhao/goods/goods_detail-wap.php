<?php


// 引用头部、底部、统计
include_once PAIZHAO_TEMPLATES_ROOT.'wap/webcontrol/head.php';
include_once PAIZHAO_TEMPLATES_ROOT.'wap/webcontrol/footer.php';
include_once PAIZHAO_TEMPLATES_ROOT.'wap/webcontrol/tj.php';




// 读取模板
$tpl = getSmartyTemplate('index.tpl.htm', PAIZHAO_TEMPLATES_ROOT.'wap/goods/',false, $clear_cache);   

// 获取头、底部、统计结构
$wap_global_head = _get_wbc_head(array('use_vue'=>1));
$wap_global_footer = _get_wbc_footer(array('share_open'=>false));
$wap_global_tj = _get_wbc_tj();

// 获取链接
$paizhao_page_url = pai_mall_paizhao_load_config('page_url');

$goods_id = trim($_INPUT['goods_id']);

$paizhao_goods_obj = POCO::singleton('pai_paizhao_goods_class');
$ret = $paizhao_goods_obj->get_goods_info($goods_id,true,true,'WAP');

$ret['goods_info']['goods_data']['content'] = mall_replace_lazyload_img($ret['goods_info']['goods_data']['content'],'img','src','data-lazyload-url',PAIZHAO_PLACEHOLER_IMG);
if ($ret['result']==-1) {
    header('Location:http://www.51snap.cn/');
    die();
}



// 输出数据

$tpl->assign('ret',$ret);
$tpl->assign('is_weixin',MALL_UA_IS_WEIXIN);
$tpl->assign('wap_global_head',$wap_global_head);
$tpl->assign('wap_global_footer',$wap_global_footer);
$tpl->assign('wap_global_tj',$wap_global_tj);




?>