<?php
// 引用头部、底部、统计
include_once MOJIKIDS_TEMPLATES_ROOT.'wap/webcontrol/head.php';
include_once MOJIKIDS_TEMPLATES_ROOT.'wap/webcontrol/footer.php';
include_once MOJIKIDS_TEMPLATES_ROOT.'wap/webcontrol/tj.php';

// 读取模板
$tpl = getSmartyTemplate('index.tpl.htm', MOJIKIDS_TEMPLATES_ROOT.'wap/topic/t_27/',false, $clear_cache);

// 获取头、底部、统计结构
$wap_global_head = _get_wbc_head(array('use_zepto'=>1));
$wap_global_footer = _get_wbc_footer(array('share_open'=>false));
$wap_global_tj = _get_wbc_tj();

// 接收参数
$c = $_INPUT['c'];
$v = $_INPUT['v'];
$g = $_INPUT['g'];
$p = $_INPUT['p'];



/**
 * 获取店铺id和档期id
 * @param $goods_id int 商品id
 * @return array
 */
$obj = POCO::singleton('pai_mall_yueshe_invitation_code_class');
$result = $obj->get_store_and_schedule($g);


$store_id = $result[0]['store_id'];
$schedule_id = $result[0]['schedule_id'];


// ====== 分享字段 ======
$share_text = array();
$title = "萌娃Emoji表情包创意摄影，免费来袭";
$content = "MOJIKIDS内测名额，抢先体验！";
$page_url = $MOJIKIDS_PAGE_ARR['order']."date.php?goods_id=$g&standard_id=$p&c=$c&v=$v&buy_num=1&store_id=$store_id&schedule_id=$schedule_id";
$share_img = "http://image19.yueus.com/yueyue/20170217/20170217160818_755372_100002_20367.jpg?200x200_120";


$sina_content = $content;
$share_url = $page_url;
$share_url_v3 = $share_url;
// $share_img = str_replace('-d','',$ret['data']['cover'][0]['value']);

$share_text['title'] = $title;
$share_text['content'] = $content;
$share_text['sina_content'] = $sina_content.' '.$share_url;
$share_text['sinacontent'] = $sina_content.' '.$share_url;//修复安卓BUG
$share_text['remark'] = '';
$share_text['url'] = $share_url;
$share_text['url_v3'] = $share_url_v3;
$share_text['img'] = $share_img;
$share_text['user_id'] = '';
$share_text['qrcodeurl'] = $share_url;
$share_text = mojikids_output_format_data($share_text);
$tpl->assign('share_text',$share_text);
// ====== 分享字段 ======


// 转换数据为json
$json_data = mojikids_output_format_data($ret);

$tpl->assign('ret',$ret);
$tpl->assign('json_data',$json_data);
$tpl->assign('is_weixin',MALL_UA_IS_WEIXIN);
$tpl->assign('wap_global_head',$wap_global_head);
$tpl->assign('wap_global_footer',$wap_global_footer);
$tpl->assign('wap_global_tj',$wap_global_tj);
$tpl->assign('page_url',$MOJIKIDS_PAGE_ARR);


$tpl->assign('c',$c);
$tpl->assign('v',$v);
$tpl->assign('g',$g);
$tpl->assign('p',$p);

$tpl->assign('store_id',$store_id);
$tpl->assign('schedule_id',$schedule_id);



?>
