<?php
/**
* @authors huangst
* @date    2016-10-26 15:35:39
* 获取私人订制信息
*/
include_once('../../common.inc.php');
$name = trim($_INPUT['name']);
$mobile = intval($_INPUT['mobile']);
$user_id = intval($_INPUT['user_id']);
$consult_cookie = trim($_INPUT['consult_cookie']);
$source = strval($_INPUT['source'])?strval($_INPUT['source']) : 'PC';

$consult_class = POCO::singleton('pai_paizhao_consult_class');
$data = array(
    'package_info'=>'-',
    'name'=> $name,
    'mobile'=>$mobile,
    'goods_id'=>0,
    'user_id'=>$user_id,
    'source'=>$source,
    'consult_type'=> 'seller',
    'consult_cookie'=>$consult_cookie
);

$contact_info = $consult_class->add_consult($data);
paizhao_mobile_output($contact_info);