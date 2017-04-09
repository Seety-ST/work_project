<?php


// 引用头部、底部、统计
include_once PAIZHAO_TEMPLATES_ROOT.'pc/webcontrol/head.php';
include_once PAIZHAO_TEMPLATES_ROOT.'pc/webcontrol/footer.php';
include_once PAIZHAO_TEMPLATES_ROOT.'pc/webcontrol/tj.php';

// 公共头部和底部
include_once PAIZHAO_TEMPLATES_ROOT.'pc/webcontrol/global-footer-bar.php';
include_once PAIZHAO_TEMPLATES_ROOT.'pc/webcontrol/global-top-bar.php';

// 读取模板
$tpl = getSmartyTemplate('index.tpl.htm', PAIZHAO_TEMPLATES_ROOT.'pc/camera/');   

// 获取头、底部、统计结构
$pc_global_head = _get_wbc_head(array('use_vue'=>1));
$pc_global_footer = _get_wbc_footer();
$pc_global_tj = _get_wbc_tj();

$pc_global_footer_bar = _get_wbc_footer_bar();
$pc_global_top_bar = _get_wbc_global_top_bar(array('nav'=>"camera"));


$sort = trim($_INPUT['sort']) ;
$order = trim($_INPUT['order']) ;
if (empty($order)) {
	$order =1;
}



$type_id = trim($_INPUT['type_id']);
$user_id = trim($_INPUT['user_id']);





//通过条件获取数据，并且做分页处理
$show_count  = 16;  //每页显示数
$p = (int)$_INPUT['p'];
if($p<=0)
{
	$p = 1;
}
$limit = ($p-1)*$show_count;
$limit_str = "{$limit},{$show_count}";

// 筛选列表内容

$goods_class = POCO::singleton('pai_paizhao_goods_class');
// $goods_info = $goods_class->get_goods_list(false, array('t'=>$type_id, 'sr'=>$sort, 'u'=>$user_id, 'o'=>$order) ,$limit_str);




/**********分页处理**********/
$page_obj =POCO::singleton('pai_paizhao_page_class');
$page_obj->file = "index.php?";
$page_obj->setvar (array('type_id'=>$type_id, 'sort'=>$sort, 'user_id'=>$user_id, 'order'=>$order ));

$total_count = $goods_class->get_goods_list(true,array('t'=>$type_id, 'sr'=>$sort, 'u'=>$user_id, 'o'=>$order ) ,$list_limit);

$page_obj->set($show_count, $total_count,false,5);
$list_limit = $page_obj->limit ();
$goods_info = $goods_class->get_goods_list(false,array('t'=>$type_id, 'sr'=>$sort, 'u'=>$user_id, 'o'=>$order ) ,$list_limit);

if ($show_count > $total_count) 
{
    $page_show = '';
}
else
{
    $page_show = $page_obj->output ( 1 ) ;
}

$page_show = str_replace('font-size:9pt',' ',$page_show);

$tpl->assign ( "page", $page_show );

/**********分页处理 end **********/


if ($_INPUT['print'])
 {
	print_r($goods_info);
}

// 商家信息获取
$photographers_class = POCO::singleton('pai_paizhao_photographers_class');
$photographers_info = $photographers_class->get_photographers_info($user_id);

$photographers_info_introduce =  strip_tags(htmlspecialchars_decode($photographers_info['un_data']['profile'][0]['introduce']));

$introduce_len = mb_strlen($photographers_info_introduce);
$introduce_limit = 110;
$beishu = ceil($introduce_len / $introduce_limit);
$introduce_arr = array();
for ($i=0; $i < $beishu; $i++) { 
	$introduce_arr[] = mb_substr($photographers_info_introduce, $i*$introduce_limit, $introduce_limit);
}

$photographers_info_introduce = $introduce_arr  ?  implode('<br />', $introduce_arr) : $photographers_info_introduce;
// 获取刷选列表项
$paizhao_goods_type = $photographers_info['un_data']['type_ids'];



// $paizhao_goods_type[0] = array('name'=>'所有分类');

// 排序列表项
$sort_item =  array(
	array('name' => '综合', 'sort_type' => '0'),
	array('name' => '人气', 'sort_type' => '1','has_arrow'=>'yes','defalut_arrow'=>'down_arrow'),
	array('name' => '最新', 'sort_type' => '2'),
	array('name' => '价格', 'sort_type' => '3','has_arrow'=>'yes','defalut_arrow'=>'up_arrow')
);   

// 筛选链接拼接
function paizhao_camera_link_url($user_id,$type_id,$sort,$order){
	$link_url = $_SERVER['SCRIPT_URI'];
	$return_url = $link_url.'?user_id='.$user_id.'&type_id='.$type_id.'&sort='.$sort.'&order='.$order;
	return $return_url;
  
}

//params change start
// if($yue_login_id == 100001)
// {
	
// 	$_get = $_INPUT;
// 	unset($_get['IP_ADDRESS']);
// 	unset($_get['IP_ADDRESS1']);
// 	unset($_get['request_method']);
// 	unset($_get['s']);

// 	$url='//'.$_SERVER['SERVER_NAME'].$_SERVER["REQUEST_URI"]; 
// 	$url_arr = parse_url($url);
// 	$query = $url_arr['query'];
// 	parse_str($query,$query_arr);
// 	array_merge($query_arr,$_get);

// 	var_dump(http_build_query($query_arr));
// }
//params change end

foreach($paizhao_goods_type as $key => $value)
{
	if (empty($type_id)) {
		$paizhao_goods_type[0]['cur_name'] = "li-cur" ;
	}
	else{
		if ($paizhao_goods_type[$key]['type_id'] == $type_id) {
			$paizhao_goods_type[$key]['cur_name'] = "li-cur" ;
		}

	}
	$paizhao_goods_type[$key]["link"] = paizhao_camera_link_url($user_id,$paizhao_goods_type[$key]['type_id'],$sort,$goods_info[o]);
}
foreach ($sort_item as $key => $value) {
	if (empty($sort)) {
		$sort_item[0]['cur_name'] = "li-cur" ;
	}
	else{
		if ($sort_item[$key]['sort_type'] == $sort) {
		   $sort_item[$key]['cur_name'] = "li-cur" ;
		}
	}
	


	if ($sort==3) {

		
		if(empty($order) || $order==2)
		{
		    //降序序，icon也为降序
		   
		    
			
		   $sort_item[3]["sort_arrow_class"] = "sort-down-arrow";

		}
		else
		{
		    //升序，icon为升序
		    
		    
		    $sort_item[3]["sort_arrow_class"] = "sort-up-arrow";
		    
		    
		}
	}
	if ($sort==1) {
		$sort_item[1]["sort_arrow_class"] = 'sort-down-arrow';
	}

	$sort_item[$key]["link"] = paizhao_camera_link_url($user_id,$type_id,$sort_item[$key]['sort_type'],$goods_info[o]);
}

//默认降序
// 获取链接的参数
$url='//'.$_SERVER['SERVER_NAME'].$_SERVER["REQUEST_URI"]; 
$url_arr = parse_url($url);
$query = $url_arr['query'];
parse_str($query,$url_query_arr);
$tpl->assign('url_query_arr',$url_query_arr);


// 输出数据
$tpl->assign('goods_info',$goods_info);
$tpl->assign('photographers_info',$photographers_info);
$tpl->assign('paizhao_goods_type',$paizhao_goods_type);
$tpl->assign('sort_item',$sort_item);
$tpl->assign('user_id',$user_id);
$tpl->assign('photographers_info_introduce',$photographers_info_introduce);
$tpl->assign('pc_global_head',$pc_global_head);
$tpl->assign('pc_global_footer',$pc_global_footer);
$tpl->assign('pc_global_tj',$pc_global_tj);
$tpl->assign('pc_global_footer_bar',$pc_global_footer_bar);
$tpl->assign('pc_global_top_bar',$pc_global_top_bar);


?>