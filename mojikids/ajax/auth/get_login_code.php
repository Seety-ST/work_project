<?php
include_once('../../fe_include/common.inc.php');


    $phone = (int)$_INPUT["phone"];

    //检查手机号相关信息，是否发短信(根据不同客户端传入不同的值)
    if(MALL_UA_IS_WEIXIN)
    {
        //微信客户端
        $mojikids_unionid = $_COOKIE["mojikids_unionid"];
        $operate = "bind";
        $ret = get_api_result('common/send_code.php',array(
            'phone' => $phone,
            'operate' => $operate,
            'union_id' => $mojikids_unionid
        ));
    }
    else
    {
        //非微信
        $operate = "login";
        $ret = get_api_result('common/send_code.php',array(
            'phone' => $phone,
            'operate' => $operate
        ));
    }


/*$ret["data"]["phone"] = $phone;
$ret["data"]["union_id"] = $mojikids_unionid;*/
$output_arr['data'] = $ret["data"];


mojikids_mobile_output($output_arr,false);




?>