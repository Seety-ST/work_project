<?php

include_once('../../fe_include/common.inc.php');

$order_sn = strval($_INPUT['order_sn']);

// 获取数据
$ret = get_api_result('trade/order_info.php',array(
    'user_id' => $yue_login_id,
    'order_sn' => $order_sn
));


mojikids_mobile_output($ret,false);


?>