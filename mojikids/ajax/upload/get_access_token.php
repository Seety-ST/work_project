<?php
include_once('../../fe_include/common.inc.php');

$user_id = 10000;

if($yue_login_id)
{
    $user_id = $yue_login_id;
}

$upload_type = trim($_INPUT['upload_type']);
if(!$upload_type)
{
    $upload_type = 'pics';
}

// ****** 获取传图token ******
$poco_yun_obj = POCO::singleton('pai_poco_yun_service_class');

if($upload_type == 'pics')
{
    $token_arr = $poco_yun_obj->get_kids_upload_access_token($user_id,'jpg');
}
else if($upload_type == 'icon')
{
    $token_arr = $poco_yun_obj->get_kids_icon_access_token($user_id,'jpg');
}


// ****** 获取传图token ******

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

	$server_url = "http://os-upload.poco.cn:8080/poco/upload?identify=".$token_info['identify']."&access_key=".$token_info['access_key']."&access_token=".$token_info['access_token']."&expire=".$token_info['expire_in'];
}

$output_arr['token_info'] = $token_info;
$output_arr['server_url'] = $server_url;
$output_arr['upload_params'] = $upload_params;
$output_arr['img_url'] = $token_info["file_url"];

mojikids_mobile_output($output_arr);
?>
