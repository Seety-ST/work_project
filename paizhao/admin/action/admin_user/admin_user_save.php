<?php
require(dirname(dirname(__FILE__)).'/common.inc.php');
$task_admin_acl_user_obj = POCO::singleton('pai_mall_admin_user_class');
$task_admin_acl_obj = POCO::singleton('pai_mall_admin_acl_class');
$task_admin_type_obj = POCO::singleton('pai_mall_admin_type_class');

//ljl_dump($_INPUT['type_id']);
//角色id
$type_id = $_INPUT['type_id'];
if($_POST)
{
    $_INPUT['ip'] = str_replace("\n",",",$_INPUT['ip']);
    $_INPUT['acl'] = implode(",",$_INPUT['acl']);
    $_INPUT['type_id'] = $_INPUT['id']==2?1:$_INPUT['type_id'];
    $_INPUT['status'] = $_INPUT['id']==2?1:$_INPUT['status'];			
    $rs = $task_admin_acl_user_obj->do_save($_INPUT);
    if($rs['result'] == 1)
    {
       if( ! empty($_POST['id']) )
       {
            $task_log_obj = POCO::singleton('pai_task_admin_log_class');
            $task_log_obj->add_log($yue_login_id,8002,8,$_INPUT,'',$_POST['id']);
       }else
       {
            $task_log_obj = POCO::singleton('pai_task_admin_log_class');
            $task_log_obj->add_log($yue_login_id,8001,8,$_INPUT,'',$rs['id']);
       }
       js_pop_msg('操作成功',false,"admin_user.php?action=admin_user_list");
    }else
    {
       $message = $rs['message']; 
       js_pop_msg("$message",false,"admin_user.php?action=admin_user_list");
    }
}
$id = (int)$_INPUT['id'];
$tpl = new SmartTemplate ( TASK_TEMPLATES_ROOT."/admin_user/admin_user_save.tpl.htm" );

if($id)
{
    $task_log_obj = POCO::singleton('pai_task_admin_log_class');
    $log_list = $task_log_obj->get_log_by_type(array('action_type'=>8,'action_id'=>$id));
    if($log_list)
    {
        foreach($log_list as $key => $val)
        {
            $log_list[$key]['add_time'] = date('Y-m-d H:i:s',$val['add_time']);
            $log_list[$key]['user_name'] = mall_get_admin_nickname_by_user_id($val['admin_id']);
        }
    }
}
$user_info = $task_admin_acl_user_obj->get_one($id);
if($user_info['ip'])
{
    $ip_ary = explode(',',$user_info['ip']);
    if($ip_ary)
    {
        $user_info['ip'] = implode("\n",$ip_ary);
    }
}

//取无限级分类
$acl_list = array();
$acl_list = $task_admin_acl_obj->get_acl_cate();
if( ! empty($acl_list) )
{
    foreach($acl_list as $k => $v)
    {
         $acl_list[$k]['shuo_jing'] = str_repeat("&nbsp;",$v['level']*8);
         if($type_id)
         {
             $admin_type_info = $task_admin_type_obj->get_admin_type_info($type_id);
             $acl_ary = explode(",",$admin_type_info['acl']);
             if( ! empty($acl_ary))
             {
                 if(in_array($v['id'],$acl_ary))
                 {
                     $acl_list[$k]['is_selected'] = 1;
                 }
             }   

         }else if($user_info)
         {
             $acl_ary = explode(",",$user_info['acl']);
             if( ! empty($acl_ary) )
             {
                if(in_array($v['id'],$acl_ary))
                {
                    $acl_list[$k]['is_selected'] = 1;
                }
             }   

         }    
    }
}

$admin_type_list = array();
$admin_type_list = $task_admin_type_obj->get_admin_type_cate();
if( ! empty($admin_type_list) )
{
    //缩进
    foreach($admin_type_list as $k => $v)
    {
        $admin_type_list[$k]['shuo_jing'] = str_repeat("&nbsp;",$v['level']*8);
        unset($admin_type_list[$k]['is_selected']);
    }


    //回选
    foreach($admin_type_list as $k => $v)
    {
        if($type_id == $v['id'])
        {
            $admin_type_list[$k]['is_selected'] = 1;
        }
        if( ! $type_id )
        {
            if($user_info['type_id'])
            {
                if($v['id'] == $user_info['type_id'])
                {
                    $admin_type_list[$k]['is_selected'] = 1;

                }
            }
        } 
    }
}

$tpl->assign('log_list',$log_list);
$tpl->assign('admin_type_list',$admin_type_list);
$tpl->assign('user_info',$user_info);
$tpl->assign('acl_admin',in_array($yue_login_id,array(109650,115203,100002,100293,214374)) ? 1 : 0 );
$tpl->assign('acl_list',$acl_list);
$tpl->output();
?>