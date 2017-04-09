<?php

include_once('../../fe_include/common.inc.php');

$user_id = strval($_INPUT['yue_login_id']);
$location_id = intval($_INPUT['location_id']);
$goods_id = intval($_INPUT['goods_id']);

$ret = get_api_result('services/goods_info.php',array(
    'user_id' => $user_id,
	'location_id' => '',
    'goods_id' => $goods_id 
));
mojikids_mobile_output($ret,false);
?>




