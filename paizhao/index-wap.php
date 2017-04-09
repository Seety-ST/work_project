<?php


// 引用头部、底部、统计
include_once PAIZHAO_TEMPLATES_ROOT.'wap/webcontrol/head.php';
include_once PAIZHAO_TEMPLATES_ROOT.'wap/webcontrol/footer.php';
include_once PAIZHAO_TEMPLATES_ROOT.'wap/webcontrol/tj.php';



// 读取模板
$tpl = getSmartyTemplate('index.tpl.htm', PAIZHAO_TEMPLATES_ROOT.'wap/index/');   

// 获取头、底部、统计结构
$wap_global_head = _get_wbc_head(array('use_vue'=>1));
$wap_global_footer = _get_wbc_footer();
$wap_global_tj = _get_wbc_tj();

$paizhao_goods_obj = POCO::singleton('pai_paizhao_goods_class');
$paizhao_goods_info = $paizhao_goods_obj->get_index_data('WAP');  


// 商品分类风格 样式处理
foreach ($paizhao_goods_info['styles'] as $key => $value) {
	if ($key==3) {
		$paizhao_goods_info['styles'][$key]['all_list_style'] = 'all-list-style';
	}
}

// 分类图标单双样式处理
foreach ($paizhao_goods_info['package_type'] as $k => $val) {
	if (count($paizhao_goods_info['package_type'])%2==0) {
		if (count($paizhao_goods_info['package_type'])==6) {
			$paizhao_goods_info['package_type'][$k]['even_or_odd'] ='odd';
		}
		else{
			$paizhao_goods_info['package_type'][$k]['even_or_odd'] ='even';
		}
	}
	else{
		$paizhao_goods_info['package_type'][$k]['even_or_odd'] ='odd';
	}
}

// 热门商品图片尺寸处理
foreach ($paizhao_goods_info['hot_goods'] as $key => $value) {
	if ($key==0) {
		$paizhao_goods_info['hot_goods'][$key]['img_size'] = "max-size";
	}
	else{
		$paizhao_goods_info['hot_goods'][$key]['img_size'] = "min-size";
	}
}
 
if ($_INPUT['print']) {
	print_r($paizhao_goods_info);
}

// 输出数据
$tpl->assign('paizhao_goods_info',$paizhao_goods_info);
$tpl->assign('wap_global_head',$wap_global_head);
$tpl->assign('wap_global_footer',$wap_global_footer);
$tpl->assign('wap_global_tj',$wap_global_tj);



?>