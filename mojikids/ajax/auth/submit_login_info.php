<?php
include_once('../../fe_include/common.inc.php');

$phone= (int)$_INPUT["phone"];
$code = trim($_INPUT["code"]);
$bb_file = $_INPUT["bb_file"];


if(MALL_UA_IS_WEIXIN)
{
    $reg_from = "weixin";
    $mojikids_unionid = $_COOKIE["mojikids_unionid"];
    // 获取数据
    $ret = get_api_result('user/wx_login.php',array(
        'union_id'=>$mojikids_unionid,
        'phone' => $phone,
        'code' => $code,
        'reg_from'=>$reg_from
    ));
}
else
{
    $reg_from = "wap";
    // 获取数据
    $ret = get_api_result('user/login.php',array(
        'phone'=>$phone,
        'code'=>$code,
        'reg_from'=>$reg_from
    ));

}


$output_arr['data'] = $ret["data"];

//返回yue_login_id,前端拿回来设置本地缓存
$output_arr["data"]["bb_file_add_result"] = "0";
if($ret["data"]["result"]=="1")
{
    $user_id = $ret["data"]["user_id"];//通过接口返回的登录成功或者注册成功的ID值
    //如果有宝宝卡片资料，则传递过来，提交入库
    if(!empty($bb_file) && is_array($bb_file))
    {
        foreach($bb_file as $key => $value)
        {
            $bb_file[$key] = trim($value);
        }

        $bb_file["user_id"] = $user_id;
        $bb_file_submit_array = $bb_file;

        //将宝宝文档入库
        $bb_ret = get_api_result('user/babycard_edit.php',$bb_file_submit_array);

        if($bb_ret["data"]["result"]=="1")
        {
            //表示宝宝卡片添加成功了，
            $output_arr["data"]["bb_file_add_result"] = "1";
        }
    }
}


//设置登录状态
if($ret["data"]["result"]=="1")
{
    $user_id = $ret["data"]["user_id"];//通过接口返回的登录成功或者注册成功的ID值
    $pai_mall_yueshe_user_obj = POCO::singleton('pai_mall_yueshe_user_class');
    $pai_mall_yueshe_user_obj-> load_member($user_id);
}



mojikids_mobile_output($output_arr,false);



?>