<?php
/**
* @Desc:     商品咨询数编辑      
* @User:     wlq<wulq@yueus.com>
* @Date:     2016年10月18日
* @Time:     上午9:38:30
* @Version:  
*/
require(dirname(dirname(__FILE__)).'/common.inc.php');
$goods_obj = POCO::singleton('pai_paizhao_goods_class');
$goods_id = $_POST['goods_id'];
$type = $_POST['type'];
$num = $_POST['num'];
if ($goods_id && ($type=='add_num' || $type=='add_rand_num') && $num)
{
    $reg = '/^\d+-\d+$/';
    if (is_array($goods_id))
    {
        if (!preg_match_all($reg, $num))
        {
            echo json_encode(array('result'=>-1,'message'=>'Data illegal'));
            exit;
        }
        $num = explode('-', $num);
    }
    $update = $goods_obj->add_consult_num($goods_id, $type, $num);
    echo $update ? json_encode(array('result'=>1)) : json_encode(array('result'=>-1));
    exit;
}
else if (is_array($goods_id) && $type=='multiply')
{
    foreach ($goods_id as $k=>$v)
    {
        $goods_obj->add_consult_num($v, 'add_num', $num[$k]);
    }
    echo json_encode(array('result'=>1));
    exit;
}
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
    $where = is_numeric($condition) ? $where . " AND goods_id={$condition}" : $where . ' AND title like "%' . mysql_escape_string($condition) . '%"';
}
$user_id = $_INPUT['user_id'];
if ($user_id)
{
    $user_id = (int)$user_id;
    $where .= " AND user_id={$user_id}";
}
$is_show = isset($_INPUT['is_show']) ? $_INPUT['is_show'] : -1;
if ($is_show == 0 || $is_show == 1)
{
    $is_show = (int)$is_show;
    $where = $is_show == 1 ? "{$where} AND is_show=1" : "{$where} AND is_show!=1";
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
$total_count = $goods_obj->admin_get_goods_list(true, $where);
$page_obj = new show_page ();
$show_count = 40;
$page_obj->setvar(array(
    'consult_num'=>$consult_num ? $consult_num : 1,
    'condition'=>$condition,
    'action'=>'goods_consult_edit',
    'user_id'=>$user_id,
    'is_show'=>$is_show,
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
$list = $goods_obj->admin_get_goods_list(false, $where, $page_obj->limit(), $sort, 'user_id,titles,is_show,is_black,goods_id,consult_num,add_consult_num,pv,uv,images');
foreach ($list['data'] as $k=>$v)
{
    $list['data'][$k]['titles'] = iconv('utf-8', 'gbk', $v['titles']);
    $list['data'][$k]['is_show_str'] = $v['is_show'] == 1 ? '上架' : '下架';
    $list['data'][$k]['is_black_str'] = $v['is_black'] == 0 ? '否' : '是';
}
$tpl = new SmartTemplate ( TASK_TEMPLATES_ROOT."/goods/goods_consult_edit.tpl.htm" );
$tpl->assign('list', $list['data']);
$tpl->assign('page', $page_obj->output(1));
$tpl->assign('condition', $condition);
$tpl->assign('user_id', $user_id);
$tpl->assign('sort_type', $sort_type);
$tpl->assign('consult_num', $consult_num ? $consult_num : 1);
$tpl->assign('pv_sort', $pv_sort ? $pv_sort : 1);
$tpl->assign('uv_sort', $uv_sort ? $uv_sort : 1);
$tpl->assign('is_show', $is_show);
$tpl->assign('is_black', $is_black);
$tpl->assign('pv_start', $pv_start);
$tpl->assign('pv_end', $pv_end);
$tpl->assign('uv_start', $uv_start);
$tpl->assign('uv_end', $uv_end);
$tpl->output();
