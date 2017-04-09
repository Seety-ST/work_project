<?php
/*
 * //下单操作页面
 * author //星星
 *
*/

include_once('../../fe_include/common.inc.php');

//确认订单页面输入的内容
$phone = (int)$_INPUT["phone"];
$username = trim($_INPUT["username"]);
$baby_age = trim($_INPUT["baby_age"]);
$email = trim($_INPUT["email"]);
$description = trim($_INPUT["description"]);
//相关内容提交
$service_time = trim($_INPUT["service_time"]);
$goods_id = (int)$_INPUT["goods_id"];
$standard_id = (int)$_INPUT["standard_id"];
$store_id = (int)$_INPUT["store_id"];
$buy_num = (int)$_INPUT["buy_num"];
$timezone_id = (int)$_INPUT["timezone_id"];
//推广参数
$c_code = trim($_INPUT["c"]);
$v_code = trim($_INPUT["v"]);



//入库前形式处理


$param = array('user_id' => $yue_login_id,
    'goods_id' =>$goods_id,
    'store_id' => $store_id,
    'standard_id' => $standard_id,
    'buy_num' =>$buy_num,
    'service_time'=>date("Y-m-d",$service_time),
    "timezone_id"=>$timezone_id,
    'username'=>$username,
    'phone'=>$phone,
    'description'=>$description,
    'email'=>$email,
    'baby_age'=> $baby_age,
    'trial_code'=>$c_code,
    'trial_version'=>$v_code

);

if(MALL_UA_IS_WEIXIN)
{
    $param["order_from"] = "weixin";
}
else
{
    $param["order_from"] = "wap";
}

//默认推广成功
$code_ret["result"] = "1";
$code_ret["message"] = "成功使用邀请码";
//推广处理
if(!empty($c_code) && !empty($v_code))
{
    $code_obj = POCO::singleton('pai_mall_yueshe_invitation_code_class');
    $code_ret = $code_obj->consumer_invitation_code($c_code, $v_code,$goods_id,$standard_id, $yue_login_id, $phone);
}
//推广处理

if($code_ret["result"]=="1")
{

    //推广成功，正常处理提交订单
    $submit_param_array = $param;
    //print_r($submit_param_array);

    // 获取数据
    $ret = get_api_result('trade/submit_order.php',$submit_param_array);

    $output_arr["data"] = $ret["data"];
    /*print_r($output_arr);
    exit;*/
}
else
{
    $code_ret["message"] = iconv("GBK","UTF-8",$code_ret["message"]);
    $output_arr["data"] = $code_ret;
    /*print_r($output_arr);
    exit;*/
}


mojikids_mobile_output($output_arr,false);
?>