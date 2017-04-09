<?php
// 引用头部、底部、统计
include_once MOJIKIDS_TEMPLATES_ROOT.'wap/webcontrol/head.php';
include_once MOJIKIDS_TEMPLATES_ROOT.'wap/webcontrol/footer.php';
include_once MOJIKIDS_TEMPLATES_ROOT.'wap/webcontrol/tj.php';

// 读取模板
$tpl = getSmartyTemplate('index.tpl.htm', MOJIKIDS_TEMPLATES_ROOT.'wap/pay/',false, $clear_cache);

// 获取头、底部、统计结构
$wap_global_head = _get_wbc_head(array('use_vue'=>1));
$wap_global_footer = _get_wbc_footer(array('share_open'=>false));
$wap_global_tj = _get_wbc_tj();

// 输出数据

$tpl->assign('ret',$ret);
$tpl->assign('is_weixin',MALL_UA_IS_WEIXIN);
$tpl->assign('wap_global_head',$wap_global_head);
$tpl->assign('wap_global_footer',$wap_global_footer);
$tpl->assign('wap_global_tj',$wap_global_tj);


$order_sn = trim($_INPUT["order_sn"]);//订单sn
$coupon_sn = trim($_INPUT["coupon_sn"]);//优惠券



//查订单详情
$order_info_ret = get_api_result('trade/order_info.php',array(
    "user_id"=>$yue_login_id,
    "order_sn"=>$order_sn
));

/******订单号容错处理******/
if(!$order_info_ret["data"]["order_sn"])
{
    header("Location:".$MOJIKIDS_PAGE_ARR["index"]);
    exit;
}
/******订单号容错处理******/
if(MALL_UA_IS_WEIXIN)
{
    $btn_html = "微信支付";
}
else
{
    $btn_html = "支付宝支付";
}



if($_INPUT["print"]==1)
{
    print_r($order_info_ret);

}

//计算倒数基准时间，目前跟距下单时间进行计算
/*$order_add_time = strtotime($order_info_ret["data"]["add_time"]);//订单添加时间
$data_end_time = $order_add_time+1800;//档期释放时间，暂定30分钟

if($_INPUT["print"]==1)
{
    echo $order_add_time;
    echo $data_end_time;
    exit;
}*/



//$now_time = time();//目前时间
//$left_time = $data_end_time-$now_time;//倒数还剩下的秒数
$left_time = $order_info_ret["data"]["pay_time_left"];
/*print_r($order_info_ret);
exit;*/


if($left_time>0)
{
    $date_exit = true;
}
else
{
    $date_exit = false;//档期已经不保留
    //构造跳回订单详情页面
    //to do
    $order_detail_link = $MOJIKIDS_PAGE_ARR["order"]."detail.php?order_sn=".$order_sn;
    header("Location:".$order_detail_link);
}

//处理页面html结构
$order_sn_html_title = "订单号：";
$order_sn = $order_info_ret["data"]["order_sn"];
$goods_info_html_title = "商品信息：";
$goods_info_title = $order_info_ret["data"]["goods"][0]["title"];
$order_pay_html_title = "支付金额：";
$order_pay = $order_info_ret["data"]["price"];

//展示信息的数组
$pay_info_array = array(
    array("title"=>$order_sn_html_title,"value"=>$order_sn,"font_color"=>"color-666"),
    array("title"=>$goods_info_html_title,"value"=>$goods_info_title,"font_color"=>"color-666"),
    array("title"=>$order_pay_html_title,"value"=>$order_pay,"font_color"=>"main-color"),
);

$tpl->assign("pay_info_array",$pay_info_array);
$tpl->assign("date_exit",$date_exit);
$tpl->assign("left_time",$left_time);
$tpl->assign("order_sn",$order_sn);
$tpl->assign("btn_html",$btn_html);
$tpl->assign("page_title","支付订单 - 莫吉照相馆");


?>
