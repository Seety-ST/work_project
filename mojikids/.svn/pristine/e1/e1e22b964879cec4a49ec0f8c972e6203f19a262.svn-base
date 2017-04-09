<?php
/**
 * 退出登录
 */

include_once('./fe_include/common.inc.php');


$pai_mall_yueshe_user_obj = POCO::singleton('pai_mall_yueshe_user_class');
$pai_mall_yueshe_user_obj->logout();

//cookie
$expire_time = time() - 24*3600;
setcookie('mojikids_openid', '', $expire_time, '/', '.mojikids.com');
setcookie('mojikids_unionid', '', $expire_time, '/', '.mojikids.com');
setcookie('mojikids_code', '', $expire_time, '/', '.mojikids.com');
setcookie('mojikids_scope', '', $expire_time, '/', '.mojikids.com');

echo "退出成功";

?>