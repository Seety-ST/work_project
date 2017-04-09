<?php

// 引用头部、底部、统计
include_once MOJIKIDS_TEMPLATES_ROOT.'wap/webcontrol/head.php';
include_once MOJIKIDS_TEMPLATES_ROOT.'wap/webcontrol/footer.php';
include_once MOJIKIDS_TEMPLATES_ROOT.'wap/webcontrol/tj.php';


// 读取模板
$tpl = getSmartyTemplate('index.tpl.htm', MOJIKIDS_TEMPLATES_ROOT.'wap/index/',true, $clear_cache);

// 获取头、底部、统计结构
$wap_global_head = _get_wbc_head(array('use_zepto'=>0));
$wap_global_footer = _get_wbc_footer(array('share_open'=>false));
$wap_global_tj = _get_wbc_tj();
// 接收参数


if ($yue_login_id)
{
   $is_login ='is_login';
}
else
{
    $is_login ='';
}

$location_name = "广州";

// 获取数据
$ret = get_api_result('common/homepage_index.php',array(
    'user_id' => $yue_login_id,
    'location_id' => "",
    'limit' => ""
));

foreach ($ret['data']['goods'] as $key => $value) {
    foreach ($ret['data']['goods'][$key]["images"] as $k => $val) {
        if ($k%4==0) {
            $ret['data']['goods'][$key]["images"][$k]['maximg'] = true;
            $ret['data']['goods'][$key]["images"][$k]['url'] = mojikids_resize_act_img_url($ret['data']['goods'][$key]["images"][$k]['url'],'640');
        }
        else{
            $ret['data']['goods'][$key]["images"][$k]['maximg'] = false;
            $ret['data']['goods'][$key]["images"][$k]['url'] = mojikids_resize_act_img_url($ret['data']['goods'][$key]["images"][$k]['url'],'260');
        }
    }
}

foreach ($ret['data']['banner'] as $key => $value) {
    $ret['data']['banner'][$key]['image'] = str_replace('-c','',$value['image']);
}

// 获取宝宝卡片
$baby_card = get_api_result('user/babycard_list.php',array(
    'user_id' => $yue_login_id,
    'target_id' => $target_id,
    'limit' => "0,5"
));
$baby_card_data = mojikids_output_format_data($baby_card['data']);
// 转换数据为json
$json_data = mojikids_output_format_data($ret['data']);
$tpl->assign('is_login',$is_login);
$tpl->assign('baby_card_data',$baby_card_data);
$tpl->assign('location_name',$location_name);
$tpl->assign('ret',$ret);
$tpl->assign('json_data',$json_data);
$tpl->assign('is_weixin',MALL_UA_IS_WEIXIN);
$tpl->assign('wap_global_head',$wap_global_head);
$tpl->assign('wap_global_footer',$wap_global_footer);
$tpl->assign('wap_global_tj',$wap_global_tj);
$tpl->assign('currentKey','index');
$tpl->assign('page_url',$MOJIKIDS_PAGE_ARR);
$tpl->display();

?>
