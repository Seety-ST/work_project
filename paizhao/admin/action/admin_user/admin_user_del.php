<?php
require(dirname(dirname(__FILE__)).'/common.inc.php');
$task_admin_acl_user_obj = POCO::singleton('pai_mall_admin_user_class');
$id = (int)$_INPUT['id'];
if( ! $id )
{
	mall_show_message('id����Ϊ��','admin_user.php?action=admin_user_list','_self');
    //js_pop_msg('id����Ϊ��',false,"admin_user.php?action=admin_user_list");
}
if($_INPUT['id'] == 2)
{
	mall_show_message('ɾ���ɹ�','admin_user.php?action=admin_user_list','_self');
    //js_pop_msg('ɾ���ɹ�',false,"admin_user.php?action=admin_user_list");
}
$rs = $task_admin_acl_user_obj->del_one($id);
if($rs)
{
    $task_log_obj = POCO::singleton('pai_task_admin_log_class');
    $task_log_obj->add_log($yue_login_id,8003,8,$_INPUT,'',$id);
	mall_show_message('ɾ���ɹ�','admin_user.php?action=admin_user_list','_self');
    //js_pop_msg('ɾ���ɹ�',false,"admin_user.php?action=admin_user_list");
}else
{
	mall_show_message('ɾ��ʧ��','admin_user.php?action=admin_user_list','_self');
    //js_pop_msg('ɾ��ʧ��',false,"admin_user.php?action=admin_user_list");
}
?>