<?php

include_once('../../fe_include/common.inc.php');

$user_id = strval($_INPUT['yue_login_id']);
$store_id = intval($_INPUT['store_id']);

$ret = get_api_result('services/store_info.php',array(
    'user_id' => $user_id,
	'store_id' => $store_id
));

mojikids_mobile_output($ret,false);
?>




