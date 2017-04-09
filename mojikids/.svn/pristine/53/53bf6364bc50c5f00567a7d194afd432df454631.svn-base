<?php
// 引用头部、底部、统计
include_once MOJIKIDS_TEMPLATES_ROOT.'wap/webcontrol/head.php';
include_once MOJIKIDS_TEMPLATES_ROOT.'wap/webcontrol/footer.php';
include_once MOJIKIDS_TEMPLATES_ROOT.'wap/webcontrol/tj.php';

// 读取模板
$tpl = getSmartyTemplate('index.tpl.htm', MOJIKIDS_TEMPLATES_ROOT.'wap/topic/open_store/',false, $clear_cache);

// 获取头、底部、统计结构
$wap_global_head = _get_wbc_head(array('use_zepto'=>1));
$wap_global_footer = _get_wbc_footer(array('share_open'=>false));
$wap_global_tj = _get_wbc_tj();



// ====== 分享字段 ======
$share_text = array();
$title = "MOJIKIDS  莫吉照相馆--莫急，好时光";
$content = "莫吉照相馆，给你最美的亲子时光，新店开业大狂欢，买一送一";
$page_url = "";
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


// 整个页面数据整合
$total_data =  array (
	"baby_list"  => array(
		array(
			"img" => "//image19.yueus.com/yueyue/20170223/20170223092357_293343_100002_10415_260.png?230x230_130", 
			"txt" => "四宫格-Emoji"
		),
		array(

			"img" => "//image19.yueus.com/yueyue/20170223/20170223092359_738012_100002_10420_260.jpg?231x230_120", 
			"txt" => "四宫格-Fun"
		),
		array("img" => "//image19.yueus.com/yueyue/20170223/20170223092400_274341_100002_10421_260.jpg?560x560_120", 
			"txt" => "四宫格-Smile"
		)
	),
	"buy_list"  => array(
		
		array(
			"img" => "//image19.yueus.com/yueyue/20170223/20170223092358_271292_100002_10417_260.jpg?562x561_120", 
			"txt" => "Fun套系-趣味儿童大头照",
			"unit"=>"¥",
			"money" => "699",
			"style" =>"img-item-style-1",
			"url" =>  $MOJIKIDS_PAGE_ARR['goods']."?goods_id=2160327",
		),
		array(
			"img" => "//image19.yueus.com/yueyue/20170223/20170223092359_80016_100002_10418_260.jpg?559x559_120", 
			"txt" => "Smile套系-一样的U and Mi",
			"unit"=>"¥",
			"money" => "699",
			"style" =>"img-item-style-2",
			"url" =>$MOJIKIDS_PAGE_ARR['goods']."?goods_id=2160328",
		),
	),
	"send_list"  => array(
		array(
			"img" => "//image19.yueus.com/yueyue/20170223/20170223092359_538490_100002_10419_260.jpg?230x231_120", 
			"url" =>$MOJIKIDS_PAGE_ARR['goods']."?goods_id=2160069"
		)

	),
);

$json_data = mojikids_output_format_data($total_data);
$tpl->assign('wap_global_head',$wap_global_head);
$tpl->assign('wap_global_footer',$wap_global_footer);
$tpl->assign('wap_global_tj',$wap_global_tj);
$tpl->assign('json_data',$json_data);






?>
