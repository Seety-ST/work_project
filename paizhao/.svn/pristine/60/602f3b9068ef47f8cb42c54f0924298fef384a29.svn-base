<?php
/**
* @Desc:     摄影师咨询、pv、uv 
* @User:     wlq<wulq@yueus.com>
* @Date:     2016年11月10日
* @Time:     上午10:39:58
* @Version:  
*/
require(dirname(dirname(__FILE__)).'/common.inc.php');
$photographers_obj = POCO::singleton('pai_paizhao_photographers_class');

$sort = '';
$sort_type = $_INPUT['sort_type'];
if ($sort_type == 'consult')
{
    $consult_num = $_INPUT['consult_num'] == 1 ? 2 : 1;
    $sort = $consult_num == 2 ? 'consult_num DESC' : 'consult_num ASC';
}
if ($sort_type == 'pv')
{
    $pv_sort = $_INPUT['pv_sort'] == 1 ? 2 : 1;
    $sort = $pv_sort == 2 ? 'pv DESC' : 'pv ASC';
}
if ($sort_type == 'uv')
{
    $uv_sort = $_INPUT['uv_sort'] == 1 ? 2 : 1;
    $sort = $uv_sort == 2 ? 'uv DESC' : 'uv ASC';
}

$where = '1';
$condition = $_INPUT['condition'];
if ($condition)
{
    $where .= " AND (user_id={$condition} OR seller_name like '%" . mysql_escape_string($condition) . "%')";
}

$status = isset($_INPUT['status']) ? $_INPUT['status'] : -1;
if ($status == 0 || $status == 1)
{
    $status = (int)$status;
    $where = $status == 1 ? "{$where} AND status=1" : "{$where} AND status!=1";
}
$is_black = isset($_INPUT['is_black']) ? $_INPUT['is_black'] : -1;
if ($is_black == 0 || $is_black == 1)
{
    $is_black = (int)$is_black;
    $where .= " AND is_black={$is_black}";
}

$pv_start = $_INPUT['pv_start'];
if ($pv_start)
{
    $pv_start = (int)$pv_start;
    $where .= " AND pv > {$pv_start} ";
}
$pv_end = $_INPUT['pv_end'];
if ($pv_end)
{
    $pv_end = (int)$pv_end;
    $where .= " AND pv < {$pv_end} ";
}
$uv_start = $_INPUT['uv_start'];
if ($uv_start)
{
    $uv_start = (int)$uv_start;
    $where .= " AND uv > {$uv_start} ";
}
$uv_end = $_INPUT['uv_end'];
if ($uv_end)
{
    $uv_end = (int)$uv_end;
    $where .= " AND uv < {$uv_end} ";
}
$total_count = $photographers_obj->get_photographers_list_count($where);
$page_obj = new show_page ();
$show_count = 40;
$page_obj->setvar(array(
    'consult_num'=>$consult_num ? $consult_num : 1,
    'condition'=>$condition,
    'action'=>'photographers_list',
    'status'=>$status,
    'is_black'=>$is_black,
    'pv_start'=>$pv_start,
    'pv_end'=>$pv_end,
    'uv_start'=>$uv_start,
    'uv_end'=>$uv_end,
    'sort_type'=>$sort_type,
    'pv_sort'=>$pv_sort ? $pv_sort : 1,
    'uv_sort'=>$uv_sort ? $uv_sort : 1,
));
$page_obj->set($show_count, $total_count);
$list = $photographers_obj->admin_get_photographers_list($where, $page_obj->limit(), $sort);
foreach ($list as $k=>$v)
{
    $list[$k]['status_str'] = $v['status'] == 1 ? '已通过审核' : '未通过审核状态';
    $list[$k]['is_black_str'] = $v['is_black'] == 0 ? '否' : '是';
}
$tpl = new SmartTemplate ( TASK_TEMPLATES_ROOT."/photographers/photographers_list.tpl.htm" );
$tpl->assign('list', $list);
$tpl->assign('page', $page_obj->output(1));
$tpl->assign('condition', $condition);
$tpl->assign('sort_type', $sort_type);
$tpl->assign('consult_num', $consult_num ? $consult_num : 1);
$tpl->assign('pv_sort', $pv_sort ? $pv_sort : 1);
$tpl->assign('uv_sort', $uv_sort ? $uv_sort : 1);
$tpl->assign('status', $status);
$tpl->assign('is_black', $is_black);
$tpl->assign('pv_start', $pv_start);
$tpl->assign('pv_end', $pv_end);
$tpl->assign('uv_start', $uv_start);
$tpl->assign('uv_end', $uv_end);
$tpl->output();
