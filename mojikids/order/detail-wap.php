<?php
// 引用头部、底部、统计
include_once MOJIKIDS_TEMPLATES_ROOT.'wap/webcontrol/head.php';
include_once MOJIKIDS_TEMPLATES_ROOT.'wap/webcontrol/footer.php';
include_once MOJIKIDS_TEMPLATES_ROOT.'wap/webcontrol/tj.php';

// 读取模板
$tpl = getSmartyTemplate('detail.tpl.htm', MOJIKIDS_TEMPLATES_ROOT.'wap/order/',false, $clear_cache);

// 获取头、底部、统计结构
$wap_global_head = _get_wbc_head(array('use_vue'=>1));
$wap_global_footer = _get_wbc_footer(array('share_open'=>false));
$wap_global_tj = _get_wbc_tj();

//检查用户的权限
$input_arr["user_id"] = $yue_login_id;
mojikids_check_permissions($input_arr);
//检查用户的权限end


// 接收参数
$order_sn = trim($_INPUT['order_sn']);
// $order_sn = 96240308 ;


// 获取数据
$ret = get_api_result('trade/order_info.php',array(
    'user_id' => $yue_login_id,
    'order_sn' => $order_sn
));

if ($_INPUT['print']  == 1)
{
    print_r($ret);
}

// 如果没有该订单号，跳去列表
if (isset($ret['data']['result']))
{
    header("location: ./list.php"); //php跳转
}

$out_data = mojikids_output_format_data($ret['data']);


if (G_PHP_COMMON_ENVIRONMENT_TYPE=='pro') 
{
    $store_id = '231449';
}
else
{
    $store_id = '210300';
}


// 输出数据
$tpl->assign('ret',$out_data);
$tpl->assign('MOJIKIDS_PAGE_ARR',$MOJIKIDS_PAGE_ARR);
$tpl->assign('is_weixin',MALL_UA_IS_WEIXIN);
$tpl->assign('wap_global_head',$wap_global_head);
$tpl->assign('wap_global_footer',$wap_global_footer);
$tpl->assign('wap_global_tj',$wap_global_tj);
$tpl->assign('order_sn',$order_sn);

$tpl->assign('pay_time_left',$ret['data']['pay_time_left']);

$tpl->assign('store_id',$store_id);


?>
