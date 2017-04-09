<?php
// 引用头部、底部、统计
include_once MOJIKIDS_TEMPLATES_ROOT.'wap/webcontrol/head.php';
include_once MOJIKIDS_TEMPLATES_ROOT.'wap/webcontrol/footer.php';
include_once MOJIKIDS_TEMPLATES_ROOT.'wap/webcontrol/tj.php';

// 读取模板
$tpl = getSmartyTemplate('index.tpl.htm', MOJIKIDS_TEMPLATES_ROOT.'wap/order/',false, $clear_cache);

// 获取头、底部、统计结构
$wap_global_head = _get_wbc_head(array('use_vue'=>1));
$wap_global_footer = _get_wbc_footer(array('share_open'=>false));
$wap_global_tj = _get_wbc_tj();

// 输出数据


$tpl->assign('is_weixin',MALL_UA_IS_WEIXIN);
$tpl->assign('wap_global_head',$wap_global_head);
$tpl->assign('wap_global_footer',$wap_global_footer);
$tpl->assign('wap_global_tj',$wap_global_tj);

//获取相关的参数
$goods_id = (int)$_INPUT["goods_id"];//商品ID
$service_time = (int)$_INPUT["service_time"];//时间戳的形式,需要转换
$standard_id = (int)$_INPUT["standard_id"];//规格ID
$buy_num = (int)$_INPUT["buy_num"];//购买数量
$store_id = (int)$_INPUT["store_id"];//店铺ID
$timezone_id = (int)$_INPUT["timezone_id"];//时分精确时间的ID值

//推广业务处理
$c_code = trim($_INPUT["c"]);//邀请码
$v_code = trim($_INPUT["v"]);//版本号
//推广业务处理

//检查用户的权限
$input_arr["user_id"] = $yue_login_id;
mojikids_check_permissions($input_arr);
//检查用户的权限end




//预留，通过参数控制下单填充数据
$username = trim(urldecode($_INPUT["username"]));
$baby_age = trim($_INPUT["baby_age"]);
$phone = trim($_INPUT["phone"]);
$email = trim(urldecode($_INPUT["email"]));
$description  = trim(urldecode($_INPUT["description"]));

//数据结构的处理

//判断如果缺漏参数，则另做处理
//to do


//查询选中的商品详情
$confirm_order_ret = get_api_result('trade/confirm_order.php',array(
    "user_id"=>$yue_login_id,
    "goods_id"=>$goods_id,
    "store_id"=>$store_id,
    "standard_id"=>$standard_id,
    "buy_num"=>$buy_num,
    'timezone_id'=>$timezone_id,
    "service_time"=>date("Y-m-d",$service_time),//2017-1-1 15:00形式
    "trial_code"=>$c_code,
    "trial_version"=>$v_code
));

if($_INPUT["print"]==1)
{
    print_r($confirm_order_ret);
}

$page_show_message = "";
$page_show_result = "1";
$page_refer_link = $MOJIKIDS_PAGE_ARR["index"];
//检查传递的内容跟信息是否符合规定内容
if($confirm_order_ret["data"]["result"]<1)
{
    $page_show_message = $confirm_order_ret["data"]["message"];
    $page_show_result = "0";
    $page_refer_link = $_SERVER['HTTP_REFERER'];
    if(empty($page_refer_link))
    {
        //如果没有来源
        $page_refer_link = $MOJIKIDS_PAGE_ARR["index"];
    }

}
$tpl->assign("page_show_message",$page_show_message);
$tpl->assign("page_show_result",$page_show_result);
$tpl->assign("page_refer_link",$page_refer_link);
//检查传递的内容跟信息是否符合规定内容end



//如果不指定手机号下单
if(empty($username))
{
    //获取用户手机号
    $username = $confirm_order_ret["data"]["username"];
}
if(empty($baby_age))
{
    //获取用户手机号
    $baby_age = $confirm_order_ret["data"]["baby_age"];
}
if(empty($phone))
{
    //获取用户手机号
    $phone = $confirm_order_ret["data"]["phone"];
}
if(empty($email))
{
    //获取用户手机号
    $email = $confirm_order_ret["data"]["email"];
}



$tpl->assign("username",$username);
$tpl->assign("baby_age",$baby_age);
$tpl->assign("phone",$phone);
$tpl->assign("email",$email);
$tpl->assign("description",$description);
//预留，通过参数控制下单填充数据



//显示相关信息
$goods_info = $confirm_order_ret["data"]["goods"];
$property_arr = $confirm_order_ret["data"]["property"];
$bill_arr = $confirm_order_ret["data"]["bill"];

$property_arr_count = count($property_arr);
foreach($property_arr as $k => $v)
{
    if($k==($property_arr_count-1))
    {
        $v["border_class"] = "";
    }
    else
    {
        $v["border_class"] = "ui-border-b";
    }
    $property_arr[$k] = $v;
}

$bill_arr_count = count($bill_arr);
foreach($bill_arr as $k => $v)
{
    if($k==($bill_arr_count-1))
    {
        $v["total_class"] = "order-total-price";
        $v["total_font_color"] = "main-color";

    }
    else
    {
        $v["total_class"] = "";
    }
    $bill_arr[$k] = $v;
}


$tpl->assign($goods_info);
if($_INPUT["print"]==1)
{
    print_r($property_arr);
    print_r($bill_arr);
}

$tpl->assign("property_arr",$property_arr);
$tpl->assign("bill_arr",$bill_arr);







/************配置数据*************/
//标题数组
$title_array["order_booker_title"] = "预约人：";
$title_array["order_baby_age_title"] = "宝宝年龄：";
$title_array["order_phone_title"] = "联系方式：";
$title_array["order_email_title"] = "电子邮箱：";
$title_array["order_more_text_title"] = "备注：";

$title_array["order_booker_title_placeholder"] = "请输入您的姓名";
$title_array["order_baby_age_title_placeholder"] = "如要填写多个年龄，请用“,”隔开";
$title_array["order_phone_title_placeholder"] = "请输入正确的联系方式";
$title_array["order_email_title_placeholder"] = "(选填)请输入您的邮箱地址";
$title_array["order_more_text_title_placeholder"] = "(选填)请填写您对拍摄的一些要求";

//占位符数组
$tpl->assign($title_array);

//传递的数据
$tpl->assign("service_time",$service_time);
$tpl->assign("goods_id",$goods_id);
$tpl->assign("standard_id",$standard_id);
$tpl->assign("store_id",$store_id);
$tpl->assign("buy_num",$buy_num);
$tpl->assign("timezone_id",$timezone_id);
$tpl->assign("page_title","确认订单 - 莫吉照相馆");
$tpl->assign("c_code",$c_code);
$tpl->assign("v_code",$v_code);




?>

