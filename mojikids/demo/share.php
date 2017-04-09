<?php
/**
 * DEMO
 */

include_once('../fe_include/common.inc.php');

// 引用头部、底部、统计
include_once MOJIKIDS_TEMPLATES_ROOT.'wap/webcontrol/head.php';
include_once MOJIKIDS_TEMPLATES_ROOT.'wap/webcontrol/footer.php';
include_once MOJIKIDS_TEMPLATES_ROOT.'wap/webcontrol/tj.php';

// 读取模板
$tpl = getSmartyTemplate('share.tpl.htm', MOJIKIDS_TEMPLATES_ROOT.'wap/demo/',false, $clear_cache);

// 获取头、底部、统计结构
$wap_global_head = _get_wbc_head(array('use_zepto'=>1));
$wap_global_footer = _get_wbc_footer(array('share_open'=>false));
$wap_global_tj = _get_wbc_tj();



// ====== 分享字段 ======
$title = '六六六666';
$content = '啦啦啦啦';
$sina_content = $content;
$share_url = 'http://yp.yueus.com/yue_ui/share/share.php';
$share_url_v3 = $share_url;
$share_img = 'http://image19-d.yueus.com/yueyue/20161008/20161008165508_996548_10002_178346.png?100x100_130';

$share_text['title'] = $title;
$share_text['content'] = $content;
$share_text['sina_content'] = $sina_content.' '.$share_url;
$share_text['sinacontent'] = $sina_content.' '.$share_url;//修复安卓BUG
$share_text['remark'] = '';
$share_text['url'] = $share_url;
$share_text['url_v3'] = $share_url_v3;
$share_text['img'] = $share_img;
$share_text['pic'] = $share_img;
$share_text['user_id'] = '';
$share_text['qrcodeurl'] = $share_url;

$share_text = mojikids_output_format_data($share_text);
// ====== 分享字段 ======


$tpl->assign('is_wap',!MALL_UA_IS_WEIXIN);
$tpl->assign('upload_params',$upload_params);
$tpl->assign('server_url',$server_url);
$tpl->assign('ret',$ret);
$tpl->assign('share_text',$share_text);
$tpl->assign('token_arr',$token_arr);
$tpl->assign('is_weixin',MALL_UA_IS_WEIXIN);
$tpl->assign('wap_global_head',$wap_global_head);
$tpl->assign('wap_global_footer',$wap_global_footer);
$tpl->assign('wap_global_tj',$wap_global_tj);


// ========================= 最终模板输出  =======================
$tpl->display();
?>
