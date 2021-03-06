<?php
require(dirname(dirname(__FILE__)).'/common.inc.php');
set_time_limit(0);
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

$condition = array('button_where'=>$button_where, 'where'=>$where, 'g_p_where'=>$g_p_where, 'consult_type'=>$consult_type);

$page = $_INPUT['page'] ? (int)$_INPUT['page'] : 1;
$per_page = 10000;

$filename = '咨询按钮转化列表_' . date('Y_m_d_h_i_s');
header("Content-type: application/vnd.ms-excel; charset=gbk");
header("Content-type:text/csv");
header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
header('Expires:0');
header('Pragma:public');
header("Content-Disposition: attachment; filename=$filename.xls");

$headArr =array(
                '日期',
                '商品名称',
                '价格',
                '商品类型',
                '场景风格',
                '商家名字',
                '咨询次数',
                '提交次数',
                '转化',
                '来源',
                );

//表头
$data ='';
$data .= "<table border='1'>";
$data .= "<tr>";
foreach ($headArr as $key => $val)
{
    $data .= "<th>" . iconv("GB2312", "utf-8", $val) . "</th>";
}
$data .= "</tr>";

while(true)
{
    $start_num = ($page-1)*$per_page;
    $list = $consult_obj->get_consult_button_list(false, $condition, "$start_num,$per_page");
    if( ! $list )
    {
        break;
    }
    foreach($list as $key => $val)
    {
        $val['create_time'] = date("Y-m-d H:i:s",$val['create_time']);
        $val['ratio'] = round($val['consult_num'] / $val['consult_click_num'], 2);

        $data .= "<tr>";
            $data.="<td>".$val['create_time']."</td>";
            $data.="<td>".iconv("GB2312", "utf-8", $val['titles'])."</td>";
            $data.="<td>".$val['prices']."</td>";
            $data.="<td>".iconv("GB2312", "utf-8", $val['type_name'])."</td>";
            $data.="<td>".iconv("GB2312", "utf-8", $val['style_name'])."</td>";
            $data.="<td>".iconv("GB2312", "utf-8", $val['seller_name'])."</td>";
            $data.="<td>".$val['consult_click_num']."</td>";
            $data.="<td>".$val['consult_num']."</td>";
            $data.="<td>".$val['ratio'];
            $data.="<td>".$val['source'];
        $data .= "</tr>";
        
    }
    
    echo $data . "\t";
    unset($data);
    $page++;
}

exit;
?>