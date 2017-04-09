<?php
// 引用头部、底部、统计
include_once MOJIKIDS_TEMPLATES_ROOT.'wap/webcontrol/head.php';
include_once MOJIKIDS_TEMPLATES_ROOT.'wap/webcontrol/footer.php';
include_once MOJIKIDS_TEMPLATES_ROOT.'wap/webcontrol/tj.php';

// 读取模板
$tpl = getSmartyTemplate('index.tpl.htm', MOJIKIDS_TEMPLATES_ROOT.'wap/bb/',false, $clear_cache);

// 获取头、底部、统计结构
$wap_global_head = _get_wbc_head(array('use_zepto'=>1));
$wap_global_footer = _get_wbc_footer(array('share_open'=>false));
$wap_global_tj = _get_wbc_tj();

// 接收参数
$sex_key = intval($_INPUT['sex_key']);
$img = urldecode(strval($_INPUT['img']));
$image_ids = strval($_INPUT['image_ids']);


$car_type = strval($_INPUT['car_type']);


switch ($car_type)
{

    // 宝宝卡片
    case 'bb_car':
        $is_show = '1';
        $car_type_str = '宝宝卡片';
    break;


    // 海报
    case 'hb':
        $is_show = '0';
        $car_type_str = '我的海报';
    break;

}

/**
 * 图片组合
 * @param array $data
 * @param int $type 组合类型1男孩 2女孩 3相片墙
 * @param array $text_array 文字描述
 */
$obj = POCO::singleton('pai_mall_yueshe_fix_photo_class');

$data = array();
$name = trim($_INPUT['name']);
$age = trim($_INPUT['age']);
$age = mb_convert_encoding($age, "GBK" , "UTF-8");
$name = mb_convert_encoding($name, "GBK" , "UTF-8");
$text_array = array();
if ($image_ids){
    $img_arr = explode(',', $image_ids);
    foreach ($img_arr as $key => $value)
    {
        $rs = $obj->get_photo_info($value);

        $rs['img_url'] = mojikids_resize_act_img_url($rs['img_url']);

        array_push($data, $rs['img_url']);

    }

    $sex_key = 3;
}
else
{
    $img = mojikids_resize_act_img_url($img);
    array_push($data, $img);
    $sex_name = mb_convert_encoding(($sex_key == 1 ? '小王子' : '小公主'), "GBK" , "UTF-8");
    $name = $name.''.$sex_name.' '.$age;
	$text_array = array($name);
}
$ret = $obj->composite_photo($data, $sex_key ,$text_array);

if ($_INPUT['print']  == 1)
{
    print_r($ret);
}

// 转换数据为json
$json_data = mojikids_output_format_data($ret);


$tpl->assign('ret',$ret);
$tpl->assign('json_data',$json_data);
$tpl->assign('is_weixin',MALL_UA_IS_WEIXIN);
$tpl->assign('wap_global_head',$wap_global_head);
$tpl->assign('wap_global_footer',$wap_global_footer);
$tpl->assign('wap_global_tj',$wap_global_tj);
$tpl->assign('page_url',$MOJIKIDS_PAGE_ARR);

$tpl->assign('car_type_str',$car_type_str);
$tpl->assign('is_show',$is_show);
?>