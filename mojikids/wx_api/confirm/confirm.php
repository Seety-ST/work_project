<?php

include_once('../../fe_include/common.inc.php');

$user_id = strval($_INPUT['yue_login_id']);
$goods_id = strval($_INPUT['goods_id']);
$store_id = strval($_INPUT['store_id']);
$standard_id = strval($_INPUT['standard_id']);
$buy_num = strval($_INPUT['buy_num']);
$timezone_id = strval($_INPUT['timezone_id']);
$service_time = strval($_INPUT['service_time']);

$ret = get_api_result('trade/confirm_order.php',array(
    'user_id' => $user_id,
	'goods_id' => $goods_id,
    'store_id' => $store_id,
	'standard_id'=> $standard_id,
	'buy_num'=> $buy_num,
	'timezone_id'=>$timezone_id,
	'service_time'=>$service_time
));

mojikids_mobile_output($ret,false);
?>




