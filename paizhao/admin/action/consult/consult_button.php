<?php
/**
* @Desc:     咨询按钮转化报表      
* @User:     wlq<wulq@yueus.com>
* @Date:     2016年10月13日
* @Time:     上午13:58:30
* @Version:  
*/
//error_reporting(E_ALL ^ E_NOTICE);
require(dirname(dirname(__FILE__)).'/common.inc.php');
$action = $_INPUT['action'];
if ($action == 'consult_button_export')
{
    mall_load_action($action);
    exit;
}
$consult_obj = POCO::singleton('pai_paizhao_consult_class');
$button_where = '1';
$where = '1';
$start_time = $_INPUT['start_time'];
if ($start_time)
{
    $s_time = strtotime($start_time);
    $where .= " AND create_time > {$s_time}";
    $button_where .= " AND mall_consult_button_tbl.create_time > {$s_time}";
}
$end_time = $_INPUT['end_time'];
if ($end_time)
{
    $e_time = strtotime($end_time) + 86400;
    $where .= " AND create_time < {$e_time}";
    $button_where .= " AND mall_consult_button_tbl.create_time < {$e_time}";
}

$consult_type = $_INPUT['consult_type'] == 'seller' ? 'seller' : 'goods';
$where .= " AND consult_type='{$consult_type}'";
$button_where .= " AND mall_consult_button_tbl.consult_type='{$consult_type}'";
$search_word = $_INPUT['search_word'];
if ($search_word)
{
    $g_p_where = '(mall_photographers_tbl.user_id=' . (int)$search_word . " OR mall_photographers_tbl.seller_name LIKE '%{$search_word}%')";
}
if ($consult_type == 'goods')
{
    if ($search_word)
    {
        $g_p_where = '('. $g_p_where .' OR (mall_goods_tbl.goods_id=' . (int)$search_word . " OR mall_goods_tbl.titles LIKE '%{$search_word}%'))";
    }
    $goods_type_id = $_INPUT['goods_type_id'];
    if ($goods_type_id)
    {
        $goods_type_id = (int)$goods_type_id;
        $g_p_where = $g_p_where ? " {$g_p_where} AND `type_id` = {$goods_type_id}" : " `type_id` = {$goods_type_id} ";
    }
    $goods_style_id = $_INPUT['goods_style_id'];
    if ($goods_style_id)
    {
        $goods_style_id = (int)$goods_style_id;
        $g_p_where = $g_p_where ? " {$g_p_where} AND `style_id` = {$goods_style_id}" : " `style_id` = {$goods_style_id} ";
    }
}
$source = $_INPUT['source'];
if ($source == 'PC' || $source == 'WAP')
{
    $where .= " AND source='{$source}'";
    $button_where .= " AND mall_consult_button_tbl.source='{$source}'";
}

$goods_type_config = pai_mall_paizhao_load_config('paizhao_goods_type');
$goods_type_arr = array();
foreach ($goods_type_config as $k=>$v)
{
    $selected = $goods_type_id == $k ? 1 : 0;
    $goods_type_arr[] = array('id'=>$k, 'name'=>iconv('utf-8', 'gbk', $v['name']), 'selected'=>$selected);
}
$goods_style_config = pai_mall_paizhao_load_config('paizhao_goods_style');
$goods_style_arr = array();
foreach ($goods_style_config as $k=>$v)
{
    $selected = $goods_style_id == $k ? 1 : 0;
    $goods_style_arr[] = array('id'=>$k, 'name'=>iconv('utf-8', 'gbk', $v['name']), 'selected'=>$selected);
}
$condition = array('button_where'=>$button_where, 'where'=>$where, 'g_p_where'=>$g_p_where, 'consult_type'=>$consult_type);
$total_count = $consult_obj->get_consult_button_list(true, $condition);
// var_dump($total_count);
$page_obj = new show_page ();
$show_count = 100;
$page_obj->setvar ( array (
    'start_time'=>$start_time,
    'end_time'=>$end_time,
    'goods_type_id'=>$goods_type_id,
    'goods_style_id'=>$goods_style_id,
    'consult_type'=>$consult_type,
    'action'=>$action,
) );
$page_obj->set($show_count, $total_count);
$list = $consult_obj->get_consult_button_list(false, $condition, $page_obj->limit());
// var_dump($list);
$page_url_config = pai_mall_paizhao_load_config('page_url');
foreach ($list as $k=>$v)
{
    $list[$k]['goods_link_url'] = $v['goods_id'] ? "{$page_url_config['goods_detail']}?goods_id={$v['goods_id']}" : '#';
    $list[$k]['seller_link_url'] = "{$page_url_config['photographer_detail']}?user_id={$v['user_id']}";
    $list[$k]['create_time'] = date('Y-m-d H:i:s', $v['create_time']);
    $list[$k]['ratio'] = round($v['consult_num'] / $v['consult_click_num'], 2);
}
$tpl = new SmartTemplate ( TASK_TEMPLATES_ROOT."/consult/consult_button.tpl.htm" );
$tpl->assign('list', $list);
$tpl->assign('page', $page_obj->output(1));
$tpl->assign('goods_type_arr', $goods_type_arr);
$tpl->assign('start_time', $start_time);
$tpl->assign('end_time', $end_time);
$tpl->assign('goods_style_arr', $goods_style_arr);
$tpl->assign('consult_type', $consult_type);
$tpl->assign('source', $source);
$tpl->assign('search_word', $search_word);
$tpl->output();