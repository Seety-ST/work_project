<?php
require(dirname(dirname(__FILE__)).'/common.inc.php');


$task_admin_group_obj = POCO::singleton('pai_mall_admin_group_class');
$tpl = new SmartTemplate ( TASK_TEMPLATES_ROOT."/admin_group/admin_group_list.tpl.htm" );
$where = '1';

$status = (int)$_INPUT['status'];
if($status > 0)
{
    $where .= " and status='{$status}'";
}
$keywords = mall_simple_filter($_INPUT['keywords']);
if( ! empty($keywords) )
{
    $where .= " and name like '%{$keywords}%'";
}

$total_count = $task_admin_group_obj->get_admin_group_list(true, $where);		
$page_obj = new show_page ();
$show_count = 20;
$page_obj->setvar ( array (
    'action'=>'admin_group_list',
    'status'=>$_INPUT['status'],
    'keywords'=>$_INPUT['keywords'],
) );
$page_obj->set ( $show_count, $total_count );		

$list = $task_admin_group_obj->get_admin_group_list(false, $where, "id desc", $page_obj->limit());
$user_acl_obj = POCO::singleton('pai_mall_admin_user_class');
foreach($list as $k => $v)
{
    $user_acl = $user_acl_obj->get_acl_user_cache($v['user_id']);
    $red_str = '';
    if($user_acl['status'] == 2)
    {
        $red_str = "&nbsp;&nbsp;<span style='color:red'>(已失效)</span>";
    }
    $list[$k]['user_name'] = $user_acl['name'].$red_str;
    if($v['status'] == 1)
    {
        $list[$k]['status_name'] = '有效';
    }else if($v['status'] == 2)
    {
        $list[$k]['status_name'] = '无效';
    }
    $list[$k]['player_num'] = $task_admin_group_obj->get_group_player_num($v['id']);
}

$status_html = get_status_list_for_admin_html($_INPUT['status']);
$keywords_html = get_keywords_input_for_admin_html(array('keywords'=>$_INPUT['keywords']));

$tpl->assign('status_html',$status_html);
$tpl->assign('keywords_html',$keywords_html);
$tpl->assign ( 'page', $page_obj->output ( 1 ) );
$tpl->assign ( 'list', $list );
$tpl->output ();
?>