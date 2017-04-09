<?php
include_once('../../fe_include/common.inc.php');


$order_sn = trim($_INPUT["order_sn"]);
$coupon_sn = trim($_INPUT["coupon_sn"]);
$redirect_url = trim($_INPUT["redirect_url"]);



if(MALL_UA_IS_WEIXIN)
{
    $pay_way = 'wxpay_pub';
}
else
{
    $pay_way = 'alipay_wap';
}


$param = array('user_id' => $yue_login_id,
    "order_sn"=>$order_sn,
    "balance"=>"余额",
    'use_balance'=>0,
    'pay_way'=>$pay_way,
    'coupon_sn'=>"",
    'redirect_url'=>$redirect_url//跳转的链接
);

$submit_param_arr = $param;

// 获取数据
$ret = get_api_result('trade/pay_order.php', $submit_param_arr);


$output_arr["data"] = $ret["data"];

mojikids_mobile_output($output_arr,false);
?>