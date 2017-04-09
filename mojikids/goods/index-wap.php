<?php
// 引用头部、底部、统计
include_once MOJIKIDS_TEMPLATES_ROOT.'wap/webcontrol/head.php';
include_once MOJIKIDS_TEMPLATES_ROOT.'wap/webcontrol/footer.php';
include_once MOJIKIDS_TEMPLATES_ROOT.'wap/webcontrol/tj.php';

// 读取模板
$tpl = getSmartyTemplate('index.tpl.htm', MOJIKIDS_TEMPLATES_ROOT.'wap/goods/',false, $clear_cache);

// 获取头、底部、统计结构
$wap_global_head = _get_wbc_head(array('use_zepto'=>0));
$wap_global_footer = _get_wbc_footer(array('share_open'=>false));
$wap_global_tj = _get_wbc_tj();

// ======== 当前链接判断 ========
$_PROTOCOL = 'http';
if(!empty($_SERVER['HTTPS']))
{
	$_PROTOCOL = 'https';
}
$page_url = "{$_PROTOCOL}://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
// ======== 当前链接判断 ========

// 接收参数
$goods_id = trim($_INPUT['goods_id']);
$standard_id = trim($_INPUT['standard_id']);
// 获取数据
$ret = get_api_result('services/goods_info.php',array(
	'user_id' => $yue_login_id,
	'goods_id' => $goods_id,
));

// 判断如果商品不存在跳回首页
if ($ret['data']['result']<1) {
    header('Location:'.$MOJIKIDS_PAGE_ARR['index']);
}

// 替换lazyload标记
$ret['data']['exhibition'] = mojikids_replace_lazyload_img($ret['data']['exhibition'],'img','src','v-lazy.container','@click="preview"');

// 转图片大小
foreach ($ret['data']['cover'] as $key => $value) {
    $ret['data']['cover'][$key]['value'] = mojikids_resize_act_img_url($ret['data']['cover'][$key]['value'],'640');
}
// ====== 分享字段 ======
$share_text = array();
$title = $ret['data']['title']."- MOJIKIDS莫吉照相馆";
$content = $ret['data']['description'];
$sina_content = $content;
$share_url = $page_url;
$share_url_v3 = $share_url;
$share_img = str_replace('-d','',$ret['data']['cover'][0]['value']);

$share_text['title'] = $title;
$share_text['content'] = $content;
$share_text['sina_content'] = $sina_content.' '.$share_url;
$share_text['sinacontent'] = $sina_content.' '.$share_url;//修复安卓BUG
$share_text['remark'] = '';
$share_text['url'] = $share_url;
$share_text['url_v3'] = $share_url_v3;
$share_text['img'] = mojikids_resize_act_img_url($share_img,'165');
$share_text['user_id'] = '';
$share_text['qrcodeurl'] = $share_url;
$share_text = mojikids_output_format_data($share_text);
$tpl->assign('share_text',$share_text);
// ====== 分享字段 ======

// 转换数据为json
$json_data = mojikids_output_format_data($ret['data']);
$tpl->assign('ret',$ret);
$tpl->assign('standard_id',$standard_id);
$tpl->assign('json_data',$json_data);
$tpl->assign('is_weixin',MALL_UA_IS_WEIXIN);
$tpl->assign('wap_global_head',$wap_global_head);
$tpl->assign('wap_global_footer',$wap_global_footer);
$tpl->assign('wap_global_tj',$wap_global_tj);




?>
