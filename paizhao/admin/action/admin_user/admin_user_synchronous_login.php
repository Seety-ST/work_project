<?php
require(dirname(dirname(__FILE__)).'/common.inc.php');
$task_admin_acl_user_obj = POCO::singleton('pai_mall_admin_user_class');
$user_id = (int)$_INPUT['user_id'];
if(!$user_id)
{
    return false;
}
$re = $task_admin_acl_user_obj->synchronous_user_login_session($user_id);
if($re)
{
    exit('<script>alert("ͬ���ɹ�");window.open("admin_user.php?action=admin_user_list","_self");</script>');
    //js_pop_msg('ͬ���ɹ�',false,"admin_acl_user.php");
}else
{
    exit('<script>alert("ͬ��ʧ��");window.open("admin_user.php?action=admin_user_list","_self");</script>');
    //js_pop_msg('ͬ��ʧ��',false,"admin_acl_user.php");
}
?>