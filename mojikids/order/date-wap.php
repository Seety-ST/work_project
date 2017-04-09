<?php
// 引用头部、底部、统计
include_once MOJIKIDS_TEMPLATES_ROOT.'wap/webcontrol/head.php';
include_once MOJIKIDS_TEMPLATES_ROOT.'wap/webcontrol/footer.php';
include_once MOJIKIDS_TEMPLATES_ROOT.'wap/webcontrol/tj.php';

// 读取模板
$tpl = getSmartyTemplate('date.tpl.htm', MOJIKIDS_TEMPLATES_ROOT.'wap/order/',false, $clear_cache);

// 获取头、底部、统计结构
$wap_global_head = _get_wbc_head(array('use_vue'=>1));
$wap_global_footer = _get_wbc_footer(array('share_open'=>false));
$wap_global_tj = _get_wbc_tj();

// 输出数据


$tpl->assign('is_weixin',MALL_UA_IS_WEIXIN);
$tpl->assign('wap_global_head',$wap_global_head);
$tpl->assign('wap_global_footer',$wap_global_footer);
$tpl->assign('wap_global_tj',$wap_global_tj);


//检查用户的权限
$input_arr["user_id"] = $yue_login_id;
mojikids_check_permissions($input_arr);
//检查用户的权限end




//详情页下单的值
$goods_id = (int)$_INPUT["goods_id"];
$standard_id = (int)$_INPUT["standard_id"];//规格ID
$buy_num = (int)$_INPUT["buy_num"];//购买数量
$store_id = (int)$_INPUT["store_id"];//店铺ID
$schedule_id = (int)$_INPUT["schedule_id"];//档期ID
//推广业务处理
$c_code = trim($_INPUT["c"]);//邀请码
$v_code = trim($_INPUT["v"]);//版本号
//推广业务处理

//构造模板上需要拼接参数
//拼接链接必要的参数及处理
$joint_link_key_need_array = array("goods_id","standard_id","buy_num","store_id");
//额外服务参数
$joint_link_key_third_array = array("c","v");

$param_link = "";
foreach($joint_link_key_need_array as $key => $value)
{
    $param_link .= $value."=".(int)$_INPUT[$value]."&";
}

foreach($joint_link_key_third_array as $key => $value)
{
    if(!empty($_INPUT[$value]))
    {
        $param_link .= $value."=".trim($_INPUT[$value])."&";
    }
}
$tpl->assign("param_link",$param_link);

//拼接链接必要的参数及处理end

// 获取数据
$ret = get_api_result('services/schedule_info.php',array(
    'user_id' => $yue_login_id,//测试ID
    'schedule_id' => $schedule_id,//测试ID
    'trial_code'=>$c_code,
    'trial_version'=>$v_code
));


$list = $ret["data"]["list"];
//筛选出当月可预约的列表
$i=0;
foreach($list as $key => $value)
{
    if(!empty($value["date"]))
    {
        if($value["canbook"] == 1)
        {
            $result_list[$i]["date"] = $value["date"];
            foreach($value["timezone"] as $k => $v)
            {
                $value["timezone"][$k]["click_cur"] = "";
            }
            $result_list[$i]["timezone"] = $value["timezone"];
            $i++;
        }

    }

}


//获取商品对应的门店ID的地址
$goods_info_ret = get_api_result('services/goods_info.php',array(
    'user_id' => $yue_login_id,//测试ID
    'location_id' => "",
    'goods_id' => $goods_id//测试ID
));



foreach($goods_info_ret["data"]["store"] as $key => $value)
{
    if($store_id==$value["store_id"])
    {
        $address_value = $value["property"][0]["value"];
    }
}



$result_list = mojikids_output_format_data($result_list);

$tpl->assign("result_list",$result_list);//可以预定的档期
$tpl->assign("address_value",$address_value);//门店地址

$tpl->assign("goods_id",$goods_id);
$tpl->assign("standard_id",$standard_id);
$tpl->assign("buy_num",$buy_num);
$tpl->assign("store_id",$store_id);
$tpl->assign("schedule_id",$schedule_id);
$tpl->assign("page_title","选择档期 - 莫吉照相馆");
$tpl->assign("c_code",$c_code);
$tpl->assign("v_code",$v_code);




?>
