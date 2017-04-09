<?php
//include_once 'config.php';
include_once('/disk/data/htdocs232/poco/pai/mobile/poco_pai_common.inc.php');
include_once('/disk/data/htdocs232/poco/pai/mall/user/include/output_function.php');
include_once '../api_rest.php';


$weixin_obj = POCO::singleton('pai_weixin_pub_class');
$user_obj = POCO::singleton('pai_user_class');
$member_obj = POCO::singleton('member_index_class');

$app_session = $_INPUT['app_session'];

$output_arr = $weixin_obj->get_small_app_login_status_info($app_session);

mall_mobile_output($output_arr); 


?>