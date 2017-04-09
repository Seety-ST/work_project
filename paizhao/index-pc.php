<?php


// 引用头部、底部、统计
include_once PAIZHAO_TEMPLATES_ROOT.'pc/webcontrol/head.php';
include_once PAIZHAO_TEMPLATES_ROOT.'pc/webcontrol/footer.php';
include_once PAIZHAO_TEMPLATES_ROOT.'pc/webcontrol/tj.php';

// 公共头部和底部
include_once PAIZHAO_TEMPLATES_ROOT.'pc/webcontrol/global-footer-bar.php';
include_once PAIZHAO_TEMPLATES_ROOT.'pc/webcontrol/global-top-bar.php';

// 读取模板
$tpl = getSmartyTemplate('index.tpl.htm', PAIZHAO_TEMPLATES_ROOT.'pc/index/');   

// 获取头、底部、统计结构
$pc_global_head = _get_wbc_head(array('use_vue'=>1));
$pc_global_footer = _get_wbc_footer();
$pc_global_tj = _get_wbc_tj();

$pc_global_footer_bar = _get_wbc_footer_bar();
$pc_global_top_bar = _get_wbc_global_top_bar(array('nav'=>"index"));

$paizhao_goods_obj = POCO::singleton('pai_paizhao_goods_class');
$paizhao_goods_info = $paizhao_goods_obj->get_index_data();






// 推荐列表图片规格设置
foreach ($paizhao_goods_info['custom_recommend'] as $key => $value) {
	if ($key%3==0) {
	   $paizhao_goods_info['custom_recommend'][$key]['img_width'] = "w480-img" ;
	}
	else{
		$paizhao_goods_info['custom_recommend'][$key]['img_width'] = "w700-img" ;
	}
  
}
// 热门拍摄模块图片规则设置
foreach ($paizhao_goods_info['hot_goods'] as $k => $val) {
   if ($k ==0) 
   {
	  $paizhao_goods_info['hot_goods'][$k]['img_width'] = "max-size";

   }
   if ($k>=1&$k<=8) 
   {
	   $paizhao_goods_info['hot_goods'][$k]['img_width'] = "min-size";
   }
   if ($k>=9&$k<=14) 
   {
	  $paizhao_goods_info['hot_goods'][$k]['img_width'] = "middle-size";
   }
   if ($k>=15) 
   {
	  $paizhao_goods_info['hot_goods'][$k]['img_width'] = "min-size";
   }
}



// 输出数据
$tpl->assign('paizhao_goods_info',$paizhao_goods_info);
$tpl->assign('pc_global_head',$pc_global_head);
$tpl->assign('pc_global_footer',$pc_global_footer);
$tpl->assign('pc_global_tj',$pc_global_tj);
$tpl->assign('pc_global_footer_bar',$pc_global_footer_bar);
$tpl->assign('pc_global_top_bar',$pc_global_top_bar);


?>