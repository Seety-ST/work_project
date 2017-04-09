<?php
require(dirname(dirname(__FILE__)).'/common.inc.php');
$task_admin_group_obj = POCO::singleton('pai_mall_admin_group_class');
//单个操作的
if($_INPUT['do_option'] == 1)
{
    $rs = $task_admin_group_obj->batch_do_option_for_group_player(array(
        'do'=>$_INPUT['do'],
        'group_id'=>$_INPUT['group_id'],
        'user_id'=>array($_INPUT['user_id']),
    ));
    if($rs['result'] == 1 && $_INPUT['do'] == 'add') //添加成员
    {
        mall_add_admin_log(10004,100,$_INPUT['group_id'],"{$_INPUT['user_id']}添加");
    }else if($rs['result'] == 1 && $_INPUT['do'] == 'reduce')
    {
        mall_add_admin_log(10005,100,$_INPUT['group_id'],"{$_INPUT['user_id']}删除"); //删除成员
    }
    $rs['message'] = iconv('gbk','utf-8',$rs['message']);
    echo json_encode($rs);
    exit;
}

//批量操作的
if($_INPUT['batch_option'] == 1)
{
    $rs = $task_admin_group_obj->batch_do_option_for_group_player(array(
        'do'=>$_INPUT['do'],
        'group_id'=>$_INPUT['group_id'],
        'user_id'=>explode(',',$_INPUT['users_id_str']),
    ));
    if($rs['result'] == 1 && $_INPUT['do'] == 'add') //批量添加成员
    {
        mall_add_admin_log(10006,100,$_INPUT['group_id'],"{$_INPUT['users_id_str']}批量添加");
    }
    $rs['message'] = iconv('gbk','utf-8',$rs['message']);
    echo json_encode($rs);
    exit;
}

$id = (int)$_INPUT['id'];
$do = trim($_INPUT['do']);
$tpl = new SmartTemplate ( TASK_TEMPLATES_ROOT."/admin_group/admin_group_player_list.tpl.htm" );
$list = $task_admin_group_obj->get_admin_group_player_list(array('do'=>$do,'group_id'=>$id));

$tpl->assign('id',$id);
$tpl->assign('do',$do);
$tpl->assign ( 'list', $list );
$tpl->output ();
?>