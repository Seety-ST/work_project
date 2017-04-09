<?php

include_once('../../fe_include/common.inc.php');

$baby_image = strval($_INPUT['baby_image']);
$baby_name = strval($_INPUT['baby_name']);
$baby_sex = strval($_INPUT['baby_sex']);
$baby_birth = $_INPUT['baby_birth'];
$baby_id = intval($_INPUT['baby_id']);



// 获取数据
$ret = get_api_result('user/babycard_edit.php',array(
    'user_id' => $yue_login_id,
    'baby_image'=> $baby_image,
    'baby_name' => $baby_name ,
    'baby_sex' =>  $baby_sex, 
    'baby_birth' =>  $baby_birth, 
    'baby_id' => $baby_id
));

mojikids_mobile_output($ret,false);


?>