<?php

include_once('../../fe_include/common.inc.php');

$user_id = strval($_INPUT['yue_login_id']);
$goods_id = strval($_INPUT['goods_id']);
$store_id = strval($_INPUT['store_id']);
$standard_id = strval($_INPUT['standard_id']);
$timezone_id = strval($_INPUT['timezone_id']);
$buy_num = strval($_INPUT['buy_num']);
$username = strval($_INPUT['username']);
$phone = strval($_INPUT['phone']);
$email = strval($_INPUT['email']);
$baby_age = strval($_INPUT['baby_age']);
$service_time = strval($_INPUT['service_time']);
$order_from = strval($_INPUT['order_from']);
$description = strval($_INPUT['description']);
$baby_age = strval($_INPUT['baby_age']);
$trial_code = strval($_INPUT['trial_code']);
$trial_version = strval($_INPUT['trial_version']);

$ret = get_api_result('trade/submit_order.php',array(
    'user_id' => $user_id,
	'goods_id' => $goods_id,
    'store_id' => $store_id,
	'standard_id'=> $standard_id,
	'buy_num'=> $buy_num,
	'timezone_id'=>$timezone_id,
	'service_time'=>$service_time,
	'username' =>$username,
	'phone' => $phone,
	'email' => $email,
	'baby_age' =>$baby_age,
	'order_from' => 'weixin_app',
	'description' =>$description,
	'trial_code' =>$trial_code,
	'trial_version' =>$trial_version
));

mojikids_mobile_output($ret,false);
?>




