<?php


// 引用头部、底部、统计
include_once PAIZHAO_TEMPLATES_ROOT.'wap/webcontrol/head.php';
include_once PAIZHAO_TEMPLATES_ROOT.'wap/webcontrol/tj.php';
include_once PAIZHAO_TEMPLATES_ROOT.'wap/webcontrol/footer.php';

// 公共头部和底部
include_once PAIZHAO_TEMPLATES_ROOT.'wap/webcontrol/global-top-bar.php';

//include('/disk/data/htdocs232/poco/pai/action/area_v2.conf.php');

// 获取头、底部、统计结构
$wap_global_head = _get_wbc_head(array('use_vue'=>1));
$wap_global_footer = _get_wbc_footer();
$wap_global_tj = _get_wbc_tj();




$tp = trim($_INPUT["tp"]);
$tpl_php = "";
if($tp=="list" || empty($tp))
{
    $tpl_php = "list";
}
else
{
    $tpl_php = "photographers";
}

// 读取模板
$tpl = getSmartyTemplate($tpl_php.'.tpl.htm', PAIZHAO_TEMPLATES_ROOT.'wap/search/');

//根据类型选择不同的代码段
$include_once_url = "./index-wap-".$tpl_php.".php";
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


$tpl->assign('wap_global_head',$wap_global_head);
$tpl->assign('wap_global_tj',$wap_global_tj);
$tpl->assign('wap_global_footer',$wap_global_footer);
//公共地址
//注释同步获取数据
/*$location_arr['province'] = $area['province'];
$location_arr['city'] = $area['city'];
$location_arr_json = paizhao_output_format_data($location_arr);
$tpl->assign('location_arr_json',$location_arr_json);*/
//设置地址版本号
$version = "1.0.2";//控制地址数据版本
$tpl->assign('version',$version);
?>