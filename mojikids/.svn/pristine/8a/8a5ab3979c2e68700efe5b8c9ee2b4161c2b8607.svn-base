<?php
include_once('/disk/data/htdocs232/poco/pai/mobile/poco_pai_common.inc.php');
include_once ('/disk/data/htdocs232/poco/member/poco_app_common.inc.php');



$user_id = $_INPUT['user_id'];

$obj = POCO::singleton('member_index_class');
$user_obj = POCO::singleton('pai_user_class');

$user_info = $obj->get_member_info($user_id);

if(!$user_info)
{
    die('查无此人');
}else
{
    $user_obj->load_member($user_id);
    echo $yue_login_id;
    echo "登录成功 昵称：".$user_info["nickname"]." 角色：".$user_info["role"];
}


?>