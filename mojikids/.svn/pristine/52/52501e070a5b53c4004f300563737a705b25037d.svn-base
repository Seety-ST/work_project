<?php
// 引用头部、底部、统计
include_once MOJIKIDS_TEMPLATES_ROOT.'wap/webcontrol/head.php';
include_once MOJIKIDS_TEMPLATES_ROOT.'wap/webcontrol/footer.php';
include_once MOJIKIDS_TEMPLATES_ROOT.'wap/webcontrol/tj.php';

// 读取模板
$tpl = getSmartyTemplate('index.tpl.htm', MOJIKIDS_TEMPLATES_ROOT.'wap/bb/file/',false, $clear_cache);

// 获取头、底部、统计结构
$wap_global_head = _get_wbc_head(array('use_zepto'=>1));
$wap_global_footer = _get_wbc_footer(array('share_open'=>false));
$wap_global_tj = _get_wbc_tj();

// 接收参数
$baby_id = intval($_INPUT['baby_id']);

$type = strval($_INPUT['type']);

if ( $type == 'edit')
{
	$type_str = '修改';
}
else
{
	$type_str = '新建';
}



// 获取数据
$ret = get_api_result('user/babycard_info.php',array(
	'user_id' => $yue_login_id,
    'baby_id' => $baby_id
));

if ($_INPUT['print']  == 1)
{
    print_r($ret);
}


$is_wap = !MALL_UA_IS_WEIXIN;

// ************ 阿里云传图配置，必须 ************
$user_id = $yue_login_id ? $yue_login_id : 10000;

$poco_yun_obj = POCO::singleton('pai_poco_yun_service_class');
$token_arr = $poco_yun_obj->get_kids_upload_access_token($user_id,'jpg');

// 转换数据为json
$server_url = '';
$upload_params = array();
if($token_arr['code'] == 0)
{
	$token_info = $token_arr['data'];
	//构造签名
	$params['key'] = $token_info["file_name"];
	$json_params = str_replace('\\',"",json_encode($params));
	$func_name = "/poco/upload";
	$now_time = time();
	//echo 'object_store' . $func_name . $json_params . $now_time . 'bodyauth';
	$sign = SHA1('object_store' . $func_name . $json_params . $now_time . 'bodyauth');
	//echo "sign为".$sign;

	//构造json
	$upload_params = array("time"=>$now_time,
	    "sign"=>$sign,
	    "params"=>array("key"=>$token_info["file_name"])
	);

	$tpl->assign("img_url",$token_info["file_url"]);

	$server_url = "http://os-upload.poco.cn:8080/poco/upload?identify=".$token_info['identify']."&access_key=".$token_info['access_key']."&access_token=".$token_info['access_token']."&expire=".$token_info['expire_in'];
}

$upload_params = json_encode($upload_params);
// ************ 阿里云传图配置，必须 ************

// 转换数据为json
$json_data = mojikids_output_format_data($ret);





$tpl->assign('user_id',$user_id);
$tpl->assign('is_wap',$is_wap);
$tpl->assign('upload_params',$upload_params);
$tpl->assign('server_url',$server_url);
$tpl->assign('type_str',$type_str);
$tpl->assign('baby_id',$baby_id);
$tpl->assign('ret',$ret);
$tpl->assign('json_data',$json_data);
$tpl->assign('is_weixin',MALL_UA_IS_WEIXIN);
$tpl->assign('wap_global_head',$wap_global_head);
$tpl->assign('wap_global_footer',$wap_global_footer);
$tpl->assign('wap_global_tj',$wap_global_tj);


?>
