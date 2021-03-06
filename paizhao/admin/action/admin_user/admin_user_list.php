<?php
require(dirname(dirname(__FILE__)).'/common.inc.php');
$task_admin_acl_user_obj = POCO::singleton('pai_mall_admin_user_class');
$task_admin_acl_obj = POCO::singleton('pai_mall_admin_acl_class');
$task_admin_type_obj = POCO::singleton('pai_mall_admin_type_class');

$status = isset($_INPUT['status']) && $_INPUT['status']!=='' ? (int)$_INPUT ['status'] : '';
$keyword = $_INPUT['keyword'];
$acl = (int)$_INPUT['acl'];
$type_id = (int)$_INPUT['type_id'];
$tpl = new SmartTemplate ( TASK_TEMPLATES_ROOT."/admin_user/admin_user_list.tpl.htm" );

//where 条件
$where = '1';

if($acl)
{
    $where .= " and FIND_IN_SET({$acl},acl)";
}

if( $keyword )
{
    $where .= " and user_id = "."'".(int)$keyword."' or name like '%".pai_mall_change_str_in($keyword)."%' or phone like '%".pai_mall_change_str_in($keyword)."%'";
}

if( $status !=='')
{
    $where .= " AND status='{$status}'";
}

//$get_type_id_all_childs = $task_admin_type_obj->get_all_childs($type_id);
//加上自身
//if( ! empty($get_type_id_all_childs) )
//{
//    $get_type_id_all_childs[] = $type_id;
//}
//
//if($get_type_id_all_childs)
//{
//    $type_id_where_str = implode(',',$get_type_id_all_childs);
//    $where .= $type_id==2?" AND type_id in (1,2)":" AND type_id in ($type_id_where_str)";
//
//}else if(empty($get_type_id_all_childs) && $type_id > 0)
//{
//    $where .= $type_id==2?" AND type_id in (1,2)":" AND type_id='$type_id'";
//}

if($type_id > 0)
{
	$get_type_id_all_childs = $task_admin_type_obj->get_all_childs($type_id);
	$type_id == 2?$get_type_id_all_childs[]=1:"";
	if($get_type_id_all_childs)	
	{
		$get_type_id_all_childs[] = $type_id;//加上自身		
		$type_id_where_str = implode(',',$get_type_id_all_childs);
		$where .= " AND type_id in ($type_id_where_str)";
	}
	else
	{
		$where .= " AND type_id='$type_id'";
	}
}
$total_count = $task_admin_acl_user_obj->get_admin_list(true, $where);		
$page_obj = new show_page ();
$show_count = 20;
$page_obj->setvar ( array (
    "status" => $status,
    'keyword'=>$keyword,
    'acl'=>$acl,
    'type_id'=>$type_id,
    'action'=>'admin_user_list',
) );
$page_obj->set ( $show_count, $total_count );		

$list = $task_admin_acl_user_obj->get_admin_list(false, $where, "id desc", $page_obj->limit());

$add_time_color = '';
$user_obj =  POCO::singleton('pai_user_class');
foreach($list as $key => $val)
{
    if($val['status'] == 0)
    {
        $list[$key]['status_name'] = '无效';
    }else if($val['status'] == 1)
    {
        $list[$key]['status_name'] = '有效';
    }

    if($val['n_s_id'] and $val['s_id'] != $val['n_s_id'])
    {
        $list[$key]['show_login'] = 1;
    }

    $list[$key]['user_name'] = get_user_nickname_by_user_id($val['user_id']);
    //$list[$key]['phone'] = $user_obj->get_phone_by_user_id($val['user_id']);

    $id_ary = explode(",",$val['acl']);
    if( ! empty($id_ary) )
    {
        $can_do_list = '';
        $can_do_ary = array();
        foreach($id_ary as $ik => $iv)
        {
            $acl_info = $task_admin_acl_obj->get_admin_acl_info($iv);
            if( ! empty($acl_info) )
            {
               $can_do_ary[] = $acl_info['name'];
            }

        }
    }

    $list[$key]['can_do_list'] = implode(',',$can_do_ary);
    if($val['type_id'])
    {
        $admin_type_info = $task_admin_type_obj->get_admin_type_info($val['type_id']);
        //是超管的情况
        if( ! empty($admin_type_info) && in_array($admin_type_info['id'],array(1,2)) )
        {
            $list[$key]['type_name'] = $admin_type_info['name'];
        }else
        {
            $parent_list_name = $task_admin_type_obj->get_parent_list_name($admin_type_info['parents_list']);
            $list[$key]['type_name'] = $parent_list_name.'->'.$admin_type_info['name'];
        }
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
         if($acl)
         {
            if($acl == $v['id'])
            {
                $acl_list[$k]['is_selected'] = 1;
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
        if($type_id == $v['id'])
        {
            $admin_type_list[$k]['is_selected'] = 1;
        }
    }

}

$tpl->assign('admin_type_list',$admin_type_list);
$tpl->assign('keyword',$keyword);
$tpl->assign('status',$status);
$tpl->assign ( 'page', $page_obj->output ( 1 ) );
$tpl->assign('acl_list',$acl_list);
$tpl->assign ( 'list', $list );

$tpl->output ();
?>