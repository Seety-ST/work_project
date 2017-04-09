<?php
/**
 * 检查用户信息
 */
include_once('../../fe_include/common.inc.php');

$ret = mojikids_check_login_id_bind_phone(array('user_id'=>$yue_login_id));

$output_arr['code'] = 1;
$output_arr['message'] = '';
$output_arr['data'] = $ret;

mojikids_mobile_output($output_arr,false);




?>
