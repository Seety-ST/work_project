<?php

include_once('../../../fe_include/common.inc.php');

$c = $_INPUT['c'];
$v = $_INPUT['v'];


/**
* 检查验证码
* @param $code string 邀请码
* @param $version string 版本号
* @return array
*/

$code_obj = POCO::singleton('pai_mall_yueshe_invitation_code_class');
$rs = $code_obj->check_invitation_code($c, $v);

$rs['message']=iconv('gbk','utf-8',$rs['message']);

mojikids_mobile_output($rs ,false);

?>