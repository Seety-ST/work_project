<?php

include_once('../../fe_include/common.inc.php');

$phone = strval($_INPUT['phone']);
$operate = strval($_INPUT['operate']);
$union_id = strval($_INPUT['union_id']);

$ret = get_api_result('common/send_code.php',array(
    'phone' => $phone,
	'operate' => 'bind',
	'union_id' => $union_id
));


mojikids_mobile_output($ret,false);
?>




