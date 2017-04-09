<?php
// 引用头部、底部、统计
include_once MOJIKIDS_TEMPLATES_ROOT.'wap/webcontrol/head.php';
include_once MOJIKIDS_TEMPLATES_ROOT.'wap/webcontrol/footer.php';
include_once MOJIKIDS_TEMPLATES_ROOT.'wap/webcontrol/tj.php';

// 读取模板
$tpl = getSmartyTemplate('index.tpl.htm', MOJIKIDS_TEMPLATES_ROOT.'wap/auth/',false, $clear_cache);

// 获取头、底部、统计结构
$wap_global_head = _get_wbc_head(array('use_vue'=>1));
$wap_global_footer = _get_wbc_footer(array('share_open'=>false));
$wap_global_tj = _get_wbc_tj();

// 输出数据
$tpl->assign('is_weixin',MALL_UA_IS_WEIXIN);
$tpl->assign('wap_global_head',$wap_global_head);
$tpl->assign('wap_global_footer',$wap_global_footer);
$tpl->assign('wap_global_tj',$wap_global_tj);


//页面逻辑
//获取回链
$r_url = urldecode(trim($_INPUT["r_url"]));
if(empty($r_url))
{
    $r_url= $MOJIKIDS_PAGE_ARR["index"];
}


//区分微信跟普通浏览器处理
if(MALL_UA_IS_WEIXIN)
{
    //微信处理
    //判断当前环境是否有unionid
    $pai_mall_yueshe_user_obj = POCO::singleton('pai_mall_yueshe_user_class');
    $weixin_unionid = $_COOKIE["mojikids_unionid"];
    if($weixin_unionid)
    {
        $weixin_bind_phone_ret = $pai_mall_yueshe_user_obj->check_weixin_is_bind_cellphone($weixin_unionid);
        //判断unionid是否有绑定了手机
        if($weixin_bind_phone_ret["result"]=="1")
        {
            //表示绑定了user_id，则获取对应的yue_login_id,并且登录跳转
            $user_id = $weixin_bind_phone_ret["user_id"];
            $pai_mall_yueshe_user_obj-> load_member($user_id);
            //登录后，回跳到来源页面
            header("Location:".$r_url);
        }
        else//微信号未绑过手机
        {
            //暂不做处理
        }


    }
    else
    {
        //暂时不做处理
    }

}
else//非微信状态
{
    if($yue_login_id)
    {
        //处理特殊情况，开发机，测试机切换，避免重定向
        $output_arr = mojikids_check_login_id_bind_phone(array("user_id"=>$yue_login_id));
        if(empty($output_arr["bind_phone"]))
        {
            //做退出处理
            $pai_mall_yueshe_user_obj = POCO::singleton('pai_mall_yueshe_user_class');
            $pai_mall_yueshe_user_obj->logout();

            //cookie
            $expire_time = time() - 24*3600;
            setcookie('mojikids_openid', '', $expire_time, '/', '.mojikids.com');
            setcookie('mojikids_unionid', '', $expire_time, '/', '.mojikids.com');
            setcookie('mojikids_code', '', $expire_time, '/', '.mojikids.com');
            setcookie('mojikids_scope', '', $expire_time, '/', '.mojikids.com');

            //再走登录注册流程
        }
        else
        {
            //已经有对应的手机号，回跳到来源页面
            header("Location:".$r_url);
        }

    }
    else
    {
        //不处理
        //没有登录ID，去绑定手机
    }
}

//echo $r_url;

//传递来源链接
$tpl->assign("r_url",$r_url);
$tpl->assign("count_sec",60);//控制倒数的时间
$tpl->assign("page_title","手机验证 - 莫吉照相馆");//控制倒数的时间
?>
