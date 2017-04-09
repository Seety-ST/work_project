<?php
include_once('../../fe_include/common.inc.php');

$new_phone = intval($_INPUT['new_phone']);
$new_code = intval($_INPUT['new_code']);

$phone = intval($_INPUT['phone']);
$code = $_INPUT['code'];
$phone_type = strval($_INPUT['phone_type']);



$ret = get_api_result('user/profile_edit.php',array(
    'user_id' => $yue_login_id,
    
    'operate'=> 'changephone', 
    'phone_type' => $phone_type, //如果是修改，不传

    'phone' => $phone, //旧手机号码
    'code' => $code, //旧手机验证码

    'new_phone' => $new_phone, //新手机号码
    'new_code' => $new_code //新手机验证码

));



$output_arr['data'] = $ret["data"];

mojikids_mobile_output($output_arr,false);




?>