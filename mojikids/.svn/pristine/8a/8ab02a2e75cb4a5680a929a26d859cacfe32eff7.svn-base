<?php
include_once('../../fe_include/common.inc.php');


$phone = intval($_INPUT['phone']);
$phone_type = strval($_INPUT['phone_type']);

$ret = get_api_result('common/send_code.php',array(
    'phone' => $phone,
    'operate'=> 'changephone',
    'phone_type' => $phone_type
));



$output_arr['data'] = $ret["data"];

mojikids_mobile_output($output_arr,false);




?>