<?php
/*
 * //获取商品列表
 *
 * //author 星星
 *
 * 2016-11-21
*/

include_once('../../common.inc.php');
$goods_class = POCO::singleton('pai_paizhao_goods_class');

//排序参数
$sort = trim($_INPUT['sort']);
$order = trim($_INPUT['order']);
$keyword = urldecode(trim($_INPUT['kw']));
//条件参数
$type_id_str = trim($_INPUT["type_id_str"]);//套系类型
$style_id_str = trim($_INPUT["style_id_str"]);//风格类型
$photographers_type_id = (int)$_INPUT["photographers_type_id"];//摄影师类型
$lid = (int)$_INPUT["lid"];//地址参数

$type_id_array = array();
$style_id_array = array();
if(!empty($type_id_str))
{
    $type_id_array = explode("||",$type_id_str);
}
if(!empty($style_id_str))
{
    $style_id_array = explode("||",$style_id_str);
}


//页面获取参数
$page = intval($_INPUT['page']);

if(empty($page))
{
    $page = 1;
}



//默认降序
$order = $order == '1' ? $order : '2';

$submit_array  = array();
$submit_array["t"] = $type_id_array;
$submit_array["s"] = $style_id_array;
$submit_array["kw"] = $keyword;
$submit_array["sr"] = $sort;
$submit_array["o"] = $order;
$submit_array["l"] = $lid;
$submit_array["pt"] = $photographers_type_id;

// 分页使用的page_count
$page_count = 11;

if($page > 1)
{
    $limit_start = ($page - 1)*($page_count - 1);
}
else
{
    $limit_start = ($page - 1)*$page_count;
}

$limit_str = "{$limit_start},{$page_count}";

//获取数据
$ret = $goods_class->get_goods_list_with_style(false,$submit_array, $limit_str);

//输出前进行过滤最后一个数据，用于真实输出
$rel_page_count = 10;


//判断是否还有更多页码
$has_next_page = (count($ret)>$rel_page_count);

if($has_next_page)
{
    array_pop($ret);
}

$output_arr['page'] = $page;
$output_arr['has_next_page'] = $has_next_page;
$output_arr['list'] = $ret;

paizhao_mobile_output($output_arr,false);

?>