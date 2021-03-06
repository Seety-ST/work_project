<?php
include_once('/disk/data/htdocs232/poco/pai/mobile/poco_pai_common.inc.php');

// 文件类型
header('Content-Type: application/json');

$upload_type = $_INPUT['upload_type'];
$callback = trim($_INPUT['callback']);
$obj_list = $_INPUT['obj_list'];
$platform = trim($_INPUT['platform']);
$user_id = trim($_INPUT['user_id']);


if(!$user_id)
{
	$user_id = $yue_login_id;
}

$weixin_helper_obj = POCO::singleton('pai_weixin_helper_class');

$upload_obj = POCO::singleton('pai_pic_class');

$token_str = $weixin_helper_obj->wx_get_access_token(10);

$poco_yun_obj = POCO::singleton('pai_poco_yun_service_class');

// ******** 存放到阿里云图片处理 ********
if($platform == 'aliyun')
{

	if($upload_type == 'icon')
	{
		$token_arr = $poco_yun_obj->get_kids_icon_access_token($user_id);

	}
	else
	{
		$token_arr = $poco_yun_obj->get_kids_upload_access_token($user_id,'jpg');
	}

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
}

// ******** 存放到阿里云图片处理 ********

for($i=0;$i<count($obj_list);$i++)
{
	$temp = 'http://file.api.weixin.qq.com/cgi-bin/media/get?access_token='.$token_str.'&media_id='.$obj_list[$i]['media_id'];

	$obj_list[$i]['wx_url'] = $temp;

	if($upload_type == 'pics')
	{
		$ret = $upload_obj->upload_works_pic($temp,$user_id);

		$ret_arr = json_decode($ret,true);

		$obj_list[$i]['server_url'] = $ret_arr['url'];
	}
	else if($upload_type == 'icon')
	{

		$param['file_name'] = $token_arr['data']['file_name'];
		$param['file_url'] = $temp;
		$param['access_data'] = $token_arr['data'];

		$ret = $poco_yun_obj->upload_mojikids_icon($param);

		$obj_list[$i]['server_url'] = $param['access_data']['file_url'];

		$obj_list[$i]['server_url'] = $obj_list[$i]['server_url'] . '?time='.time();
	}


	if($obj_list[$i]['server_url'] == null)
	{
		$obj_list[$i]['server_url'] = '';
	}
}

$output_arr['obj_list'] = $obj_list;


// 构造JS格式的对象变量
if ($callback)
{
    echo $callback."(".json_encode($output_arr).");";
} else
{
    echo json_encode($output_arr);
}

?>
