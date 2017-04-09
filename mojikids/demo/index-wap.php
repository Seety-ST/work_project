<?php
// 引用头部、底部、统计
include_once MOJIKIDS_TEMPLATES_ROOT.'wap/webcontrol/head.php';
include_once MOJIKIDS_TEMPLATES_ROOT.'wap/webcontrol/footer.php';
include_once MOJIKIDS_TEMPLATES_ROOT.'wap/webcontrol/tj.php';

// 读取模板
$tpl = getSmartyTemplate('demo.tpl.htm', MOJIKIDS_TEMPLATES_ROOT.'wap/demo/',false, $clear_cache);

// 获取头、底部、统计结构
$wap_global_head = _get_wbc_head(array('use_zepto'=>1));
$wap_global_footer = _get_wbc_footer(array('share_open'=>false));
$wap_global_tj = _get_wbc_tj();


// ****** 获取传图token ******
$user_id = 10000;

if($yue_login_id)
{
    $user_id = $yue_login_id;
}
$poco_yun_obj = POCO::singleton('pai_poco_yun_service_class');
$token_arr = $poco_yun_obj->get_kids_upload_access_token($user_id,'jpg');

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

	$tpl->assign("img_url",$token_info["file_url"]);

	$server_url = "http://os-upload.poco.cn:8080/poco/upload?identify=".$token_info['identify']."&access_key=".$token_info['access_key']."&access_token=".$token_info['access_token']."&expire=".$token_info['expire_in'];
}

$upload_params = json_encode($upload_params);


// ====== 分享字段 ======
$title = '六六六666';
$content = '啦啦啦啦';
$sina_content = $content;
$share_url = 'http://yp.yueus.com/yue_ui/share/share.php';
$share_url_v3 = $share_url;
$share_img = 'http://image19-d.yueus.com/yueyue/20161008/20161008165508_996548_10002_178346.png?100x100_130';

$share_text['title'] = $title;
$share_text['content'] = $content;
$share_text['sina_content'] = $sina_content.' '.$share_url;
$share_text['sinacontent'] = $sina_content.' '.$share_url;//修复安卓BUG
$share_text['remark'] = '';
$share_text['url'] = $share_url;
$share_text['url_v3'] = $share_url_v3;
$share_text['img'] = $share_img;
$share_text['pic'] = $share_img;
$share_text['user_id'] = '';
$share_text['qrcodeurl'] = $share_url;

$share_text = mojikids_output_format_data($share_text);
// ====== 分享字段 ======


$tpl->assign('is_wap',!MALL_UA_IS_WEIXIN);
$tpl->assign('upload_params',$upload_params);
$tpl->assign('server_url',$server_url);
$tpl->assign('ret',$ret);
$tpl->assign('share_text',$share_text);
$tpl->assign('token_arr',$token_arr);
$tpl->assign('is_weixin',MALL_UA_IS_WEIXIN);
$tpl->assign('wap_global_head',$wap_global_head);
$tpl->assign('wap_global_footer',$wap_global_footer);
$tpl->assign('wap_global_tj',$wap_global_tj);
?>
