<?php

include_once('../../fe_include/common.inc.php');

$phone = strval($_INPUT['phone']);
$code = strval($_INPUT['code']);
$union_id = strval($_INPUT['union_id']);

$ret = get_api_result('user/wx_login.php',array(
    'phone' => $phone,
	'code' => $code,
	'union_id' => $union_id,
	'reg_from' => 'weixin',
	'app_id' => 'wx13e9d419c2a3a786'
));


mojikids_mobile_output($ret,false);
?>




