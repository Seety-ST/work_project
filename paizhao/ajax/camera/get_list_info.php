<?php
/**
* @authors huangst
* @date    2016-11-24 17:30:21
* 获取摄影师主页列表
*/
include_once('../../common.inc.php');

$type_id = trim($_INPUT['type_id']);
$sort = trim($_INPUT['sort']) ;
$user_id = trim($_INPUT['user_id']);
$order = trim($_INPUT['order']) ;
$page  = intval($_INPUT['page']) ;

if(empty($page))
{
    $page = 1;
}

// 分页使用的page_count
$page_count = 7;

if($page > 1)
{
    $limit_start = ($page - 1)*($page_count - 1);
}
else
{
    $limit_start = ($page - 1)*$page_count;
}

$limit = "{$limit_start},{$page_count}";

// 输出前进行过滤最后一个数据，用于真实输出


$goods_class = POCO::singleton('pai_paizhao_goods_class');
$total = $goods_class->get_goods_list(true,array('t'=>$type_id, 'sr'=>$sort, 'u'=>$user_id, 'o'=>$order ));
$goods_info = $goods_class->get_goods_list(false,array('t'=>$type_id, 'sr'=>$sort, 'u'=>$user_id, 'o'=>$order ) ,$limit);
$rel_page_count = 6;

$has_next_page = (count($goods_info['data'])>$rel_page_count);

if($has_next_page)
{
    array_pop($goods_info['data']);
}




$output_arr['list'] = $goods_info['data'];
$output_arr['page'] = $page;
$output_arr['has_next_page'] = $has_next_page;
paizhao_mobile_output($output_arr,false);