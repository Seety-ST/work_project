<?php


// 引用头部、底部、统计
include_once PAIZHAO_TEMPLATES_ROOT.'pc/webcontrol/head.php';
include_once PAIZHAO_TEMPLATES_ROOT.'pc/webcontrol/footer.php';
include_once PAIZHAO_TEMPLATES_ROOT.'pc/webcontrol/tj.php';

// 公共头部和底部
include_once PAIZHAO_TEMPLATES_ROOT.'pc/webcontrol/global-footer-bar.php';
include_once PAIZHAO_TEMPLATES_ROOT.'pc/webcontrol/global-top-bar.php';
include_once PAIZHAO_TEMPLATES_ROOT.'pc/webcontrol/goods_and_photographers_list_control.php';


include('/disk/data/htdocs232/poco/pai/action/area_v2.conf.php');



// 获取头、底部、统计结构
$pc_global_head = _get_wbc_head(array('use_vue'=>1));
$pc_global_footer = _get_wbc_footer();
$pc_global_tj = _get_wbc_tj();

$pc_global_footer_bar = _get_wbc_footer_bar();




$tp = trim($_INPUT["tp"]);
$tpl_php = "";
if($tp=="list" || empty($tp))
{
    $tpl_php = "list";
    $pc_global_top_bar = _get_wbc_global_top_bar();
}
else
{
    $tpl_php = "photographers";
    $pc_global_top_bar = _get_wbc_global_top_bar(array('nav'=>"camera"));
}

// 读取模板
$tpl = getSmartyTemplate($tpl_php.'.tpl.htm', PAIZHAO_TEMPLATES_ROOT.'pc/search/');

//对象

/*******************公共代码***********************/
//配置的数组
//地址数组
//$goods_location_array_config = require('../config/paizhao_goods_location_config.php');
/*******************公共代码***********************/



//根据类型选择不同的代码段
$include_once_url = "./index-pc-".$tpl_php.".php";
//不同分类的数据处理
if(file_exists($include_once_url))
{

    include_once $include_once_url;
    //echo "文件存在";
    //exit;
}
else
{
    echo "文件不存在";
    exit();
}


// 输出数据
$tpl->assign('pc_global_head',$pc_global_head);
$tpl->assign('pc_global_footer',$pc_global_footer);
$tpl->assign('pc_global_tj',$pc_global_tj);
$tpl->assign('pc_global_footer_bar',$pc_global_footer_bar);
$tpl->assign('pc_global_top_bar',$pc_global_top_bar);
//公共地址
$location_arr['province'] = $area['province'];
$location_arr['city'] = $area['city'];
$location_arr['district'] = $area['district'];
$location_arr_json = paizhao_output_format_data($location_arr);
//echo $location_arr_json;
$tpl->assign('location_arr_json',$location_arr_json);

?>