<?php
//include_once 'config.php';
include_once('/disk/data/htdocs232/poco/pai/mobile/poco_pai_common.inc.php');
include_once('/disk/data/htdocs232/poco/pai/mall/user/include/output_function.php');
include_once '../api_rest.php';


$weixin_obj = POCO::singleton('pai_weixin_pub_class');
$user_obj = POCO::singleton('pai_user_class');
$member_obj = POCO::singleton('member_index_class');

$code = trim($_INPUT['code']);
$bind_id = 11;


if($code)
{
    /**
     * 
     * @param string $code
     * @param int $bind_id
     * @return string
     */
    $session_id = $weixin_obj->auth_get_session_key($code, $bind_id);

    //return $session_id;
	
	$output_arr['status'] = '1';
	
	$output_arr['session_id'] = $session_id;
	
	mall_mobile_output($output_arr);

}
else
{
	$encrypted_data = $_INPUT['encryptedData'];
	$iv = $_INPUT['iv'];
    $session_id = trim($_INPUT['session_id']);

    /**
     * mojikids微信小程序登录验证
     * @param $session_id
     * @param $encrypted_data
     * @param $iv
     * @param $bind_id
     * @return $result['result'] = -1  异常情况
     *         $result['result'] = 1   未绑手机，未注册
     *         $result['result'] = 2   未绑手机，已注册
     *         $result['result'] = 3   已绑手机，已注册
     */
    $login_verify = $weixin_obj->mojikids_small_app_login_verify($session_id,$encrypted_data,$iv,$bind_id);

    if($login_verify['result']>0)
    {
		$output_arr['result_data'] = $login_verify;
		$output_arr['status'] = $login_verify['result'];

		mall_mobile_output($output_arr);
    }
	else{
		$output_arr['status'] = '3'; 

		mall_mobile_output($output_arr);
	}
}



?>