<?php

include_once('../../fe_include/common.inc.php');

$user_id = strval($_INPUT['yue_login_id']);
$location_id = intval($_INPUT['location_id']);
$page = intval($_INPUT['page']);

if(empty($page))
{
	$page = 1;
}

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

$limit = "{$limit_start},{$page_count}";

$ret_1 = get_api_result('common/homepage_index.php',array(
    'user_id' => $user_id,
    'location_id' => "",
    'limit' => $limit
));

$rel_page_count = 10;

$has_next_page = (count($ret_1['data']['goods'])>$rel_page_count);

if($has_next_page)
{
	array_pop($ret_1['data']['goods']);
}

$ret['index'] = $ret_1;
$ret['index']['has_next_page'] = $has_next_page;
mojikids_mobile_output($ret,false);


//$baby_sex = strval($_INPUT['baby_sex']);
//$baby_birth = $_INPUT['baby_birth'];
//$baby_id = intval($_INPUT['baby_id']);



/* 获取数据
$ret = get_api_result('user/babycard_edit.php',array(
    'user_id' => $yue_login_id,
    'baby_image'=> $baby_image,
    'baby_name' => $baby_name ,
    'baby_sex' =>  $baby_sex, 
    'baby_birth' =>  $baby_birth, 
    'baby_id' => $baby_id
));
*/
?>




