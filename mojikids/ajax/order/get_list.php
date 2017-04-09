<?php

include_once('../../fe_include/common.inc.php');

$page = intval($_INPUT['page']);
$trade_type = strval($_INPUT['trade_type']);


if(empty($page))
{
    $page = 1;
}

// 分页使用的page_count
$page_count = 6;

if($page > 1)
{
    $limit_start = ($page - 1)*($page_count - 1);
}
else
{
    $limit_start = ($page - 1)*$page_count;
}

$limit = "{$limit_start},{$page_count}";

$ret = get_api_result('trade/order_list.php',array(
    'user_id' => $yue_login_id,
    'trade_type' => $trade_type,
    'limit' =>  $limit 
));

// print_r($ret);

// 输出前进行过滤最后一个数据，用于真实输出
$rel_page_count = 5;

$has_next_page = (count($ret['data']['list']) > $rel_page_count);


if($has_next_page)
{
    array_pop($ret['data']['list']); 
}

$output_arr['list'] = $ret['data']['list'];

$output_arr['page'] = $page;

$output_arr['has_next_page'] = $has_next_page;


mojikids_mobile_output($output_arr,false);


?>