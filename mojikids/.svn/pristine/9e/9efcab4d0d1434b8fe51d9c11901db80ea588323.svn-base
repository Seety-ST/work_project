<?php

/**
 * 订单 我的照片
 *
 * @author willike <chenwb@yueus.com>
 * @since 2017-01-10
 */
require(dirname(dirname(__FILE__)) . '/moji_input.inc.php');

$order_sn = $client_data['data']['param']['order_sn'];
if (empty($order_sn)) {
    $options['data'] = array(
        'result' => 0,
        'message' => '订单号为空',
    );
    return $cp->output($options);
}
$mall_order_api_obj = POCO::singleton('snap_mall_order_api_class');
$order_result = $mall_order_api_obj->get_order_info($order_sn, $user_id);
if (empty($order_result)) {
    $options['data'] = array(
        'result' => 0,
        'message' => '没有该订单',
    );
    return $cp->output($options);
}
if ($order_result['buyer_user_id'] != $user_id &&
        $order_result['seller_user_id'] != $user_id) {
    $options['data'] = array(
        'result' => -1,
        'user_id' => $user_id,
        'message' => '非本人订单,无法查看',
    );
    return $cp->output($options);
}
$yueshe_fix_photo_obj = POCO::singleton('pai_mall_yueshe_fix_photo_class');
$images_result = $yueshe_fix_photo_obj->get_my_img_by_order_sn($order_sn, $limit_str);
if ($location_id == 'test') {
    $options['data'] = array(
        '$user_id' => $user_id,
        '$order_sn' => $order_sn,
        '$images_result' => $images_result,
    );
    return $cp->output($options);
}
$images_list = array();
$timestamp = 0; // 图片添加时间
foreach ($images_result as $images_) {
    $add_time = $images_['add_time'];
    if ($timestamp == 0) {
        $timestamp = $add_time;
    }
    $images_list[] = array(
        'image_id' => $images_['id'],
        'url' => $images_['img_url'],
        'is_up' => $images_['is_up'], // 是否上墙
    );
}
// 过期时间 ( 90天 )
if ($timestamp < 1) {
    $description = 'MOJIKIDS温馨提示：抱歉，您的照片已失效';
} else {
    $remain_day = strtotime('+90 day', $timestamp);
    $description = 'MOJIKIDS温馨提示：照片将于' . date('Y-m-d', $remain_day) . '失效';
}
$options['data'] = array(
    'result' => 1,
    'title' => '我的照片',
    'description' => $description,
    'list' => $images_list,
);
return $cp->output($options);
