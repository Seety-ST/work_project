<?php

include_once('../../fe_include/common.inc.php');

$user_id = strval($_INPUT['yue_login_id']);
$order_sn = strval($_INPUT['order_sn']);

$ret = get_api_result('trade/order_info.php',array(
    'user_id' => $user_id,
	'order_sn' => $order_sn
));

mojikids_mobile_output($ret,false);
?>




