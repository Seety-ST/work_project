<?php

/**
 * 提交订单
 *
 * @author willike <chenwb@yueus.com>
 * @since 2017-01-07
 */
require(dirname(dirname(__FILE__)) . '/moji_input.inc.php');

$trial_code = $client_data['data']['param']['trial_code'];  // 邀请码
$trial_version = $client_data['data']['param']['trial_version'];

$goods_id = $client_data['data']['param']['goods_id'];
if (empty($goods_id)) {
    $options['data'] = array(
        'result' => 0,
        'message' => '商品ID为空',
    );
    return $cp->output($options);
}
// 商品信息
$yueshe_goods_obj = POCO::singleton('pai_mall_yueshe_goods_class');
$goods_result = $yueshe_goods_obj->get_goods_info_for_yueshe($goods_id);
$goods_data = $goods_result['goods_data'];
if (empty($goods_data)) {
    $options['data'] = array(
        'result' => 0,
        'message' => '没有该商品',
    );
    return $cp->output($options);
}
$seller_user_id = $goods_data['user_id'];  // 商家ID
// 店铺
$yueshe_detail = $goods_result['yueshe_detail'];
$tmp_detail = array();
foreach ($yueshe_detail as $value) {
    $detail_key = $value['detail_key'];
    $tmp_detail[$detail_key] = $value['detail_val'];
}
// 店铺ID (选填)
$store_id_list = explode(',', $tmp_detail['store_id']);  // 店铺ID集
$store_id = $client_data['data']['param']['store_id'];
// 判断是否 在商品的门店列表中
if (!in_array($store_id, $store_id_list)) {
    $options['data'] = array(
        'result' => 0,
        'message' => '门店ID不合法',
    );
    return $cp->output($options);
}
// 规格
$prices_type_id = $client_data['data']['param']['standard_id'];
if (empty($prices_type_id) || !is_numeric($prices_type_id)) {
    $options['data'] = array(
        'result' => -1,
        'message' => '规格未选择',
    );
    return $cp->output($options);
}
$prices_data_list = $goods_result['prices_data_list'];
$standard = array();
foreach ($prices_data_list as $prices_) {
    $standard_id = $prices_['type_id'];
    $standard[] = $standard_id;  // 规格ID列表
}
if (!in_array($prices_type_id, $standard)) {
    $options['data'] = array(
        'result' => -1,
        'message' => '该商品没有此规格',
    );
    return $cp->output($options);
}
// 购买数量
$quantity = trim($client_data['data']['param']['buy_num']);
if ($quantity < 1) {
    $options['data'] = array(
        'result' => -2,
        'message' => '购买数量不能少于1',
    );
    return $cp->output($options);
}
// 服务时间
$service_time = $client_data['data']['param']['service_time'];
$service_time_stamp = strtotime($service_time);
if (empty($service_time_stamp)) {
    $options['data'] = array(
        'result' => -3,
        'message' => '服务时间格式不正确',
    );
    return $cp->output($options);
}
$service_time = date('Y-m-d', $service_time_stamp);
if (strcmp($service_time, date('Y-m-d')) < 0) {
    $options['data'] = array(
        'result' => -3,
        'message' => '服务时间不正确',
    );
    return $cp->output($options);
}
// 档期时间ID
$schedule_time_id = $client_data['data']['param']['timezone_id'];
if (!is_numeric($schedule_time_id)) {
    $options['data'] = array(
        'result' => -3,
        'message' => '档期时间点ID不正确',
    );
    return $cp->output($options);
}
$yueshe_schedule_obj = POCO::singleton('pai_mall_yueshe_schedule_class');
$schedule_time_re = $yueshe_schedule_obj->get_schedule_time_info($schedule_time_id);
if (empty($schedule_time_re)) {
    $options['data'] = array(
        'result' => -3,
        'message' => '没有该档期时间点',
    );
    return $cp->output($options);
}
if ($quantity > $schedule_time_re['stock_total']) {
    $options['data'] = array(
        'result' => -3,
        'message' => '该档期时间点库存不足',
    );
    return $cp->output($options);
}
$time_name = $schedule_time_re['time_name'];  // 时间点
if ($service_time == date('Y-m-d')) {  // 当天
    list($h, $m) = explode(':', $time_name);
    if (is_numeric($h) && is_numeric($m)) {
        list($h_now, $m_now) = explode(':', date('G:i'));  // 当前时分
        if ($h < $h_now) {
            $options['data'] = array(
                'result' => -3,
                'message' => '该档期时间点已过期',
            );
            return $cp->output($options);
        } else if ($h == $h_now && $m < $m_now) {
            $options['data'] = array(
                'result' => -3,
                'message' => '该档期时间点已过期了',
            );
            return $cp->output($options);
        }
    }
}
//$request_goods = array(
//    array(
//        'goods_id' => $goods_data['goods_id'], //商品ID
//        'prices_type_id' => $prices_type_id, //规格ID
//        'quantity' => $quantity, //数量
//        'service_time' => $service_time_stamp, //拍摄时间，时间戳
//    )
//);
// 用户名
$username = $client_data['data']['param']['username'];
if (empty($username)) {
    $options['data'] = array(
        'result' => -4,
        'message' => '姓名不能为空',
    );
    return $cp->output($options);
}
if (mb_strlen($username, 'GBK') > 20) {
    $options['data'] = array(
        'result' => -4,
        'message' => '姓名不能超过20字',
    );
    return $cp->output($options);
}
// 用户手机号
$cellphone = $client_data['data']['param']['phone'];
if (empty($cellphone)) {
    $options['data'] = array(
        'result' => -4,
        'message' => '手机号不能为空',
    );
    return $cp->output($options);
}
if (!preg_match('/^1[0-9]{10}$/', $cellphone)) {
    $options['data'] = array(
        'result' => -4,
        'message' => '手机号不合法',
    );
    return $cp->output($options);
}
// 获取 店铺信息
//$yueshe_store_obj = POCO::singleton('pai_mall_yueshe_store_class');
//$store_result = $yueshe_store_obj->get_store_info($store_id);
//$yueshe_info = $store_result['yueshe_info'];
//$request_consignee = array(
//    'consignee' => $username, //联系人姓名
//    'cellphone' => $cellphone, //联系电话
//    'location_id' => $yueshe_info['location_id'], //店铺地址ID
//    'address' => $store_result['address'], // 店铺地址
//);
// 订单来源
$referer = $client_data['data']['param']['order_from'];
if (!in_array($referer, array('app', 'pc', 'weixin', 'weixin_app', 'wap'))) {
    $options['data'] = array(
        'result' => -5,
        'message' => '订单来源不合法',
    );
    return $cp->output($options);
}
$description = $client_data['data']['param']['description'];  // 备注
if (!empty($description)) {
    $description = moji_content_strip($description);
}
// 工单信息
$extra = array();
$email = trim($client_data['data']['param']['email']);
//if (!empty($email)) {
//    $extra[] = array('email' => $email);
//}
$baby_age = trim($client_data['data']['param']['baby_age']);
$baby_age_list = array();
if (!empty($baby_age)) {
    // 宝宝年龄
    $str_reg = array(
        '|', '、', ':', '：', '-', '—', '.', '。',
        '/', '\\', '%', '*', '^', '@', '#', '$',
        ' ', '~', '～', '&', '+', '=', '!', '！',
        '?', '？', '，', ' ',
    );
    $baby_age = str_replace($str_reg, ',', $baby_age);
    $baby_age_list = explode(',', $baby_age);
}
if (empty($baby_age_list)) {
    $options['data'] = array(
        'result' => -6,
        'message' => '宝宝年龄不能为空',
    );
    return $cp->output($options);
}
//if (!empty($baby_age_list)) {
//    $extra[] = array(
//        'baby_age' => $baby_age_list,
//    );
//}
//$more_info = array(
//    'store_id' => $store_id, //门店ID
//    'payment_pattern' => 'online', //支付方式, online线上，offline线下
//    'referer' => $referer, //订单来源
//    'description' => $description, //订单备注
//    'extra' => serialize($extra), //工单相关信息，序列化
//);
//if ($location_id == 'test') {
//    $options['data'] = array(
//        '$user_id' => $user_id,
//        '$seller_user_id' => $seller_user_id,
//        '$request_goods' => $request_goods,
//        '$request_consignee' => $request_consignee,
//        '$more_info' => $more_info,
//    );
//    return $cp->output($options);
//}
$data = array(
    'goods_id' => $goods_data['goods_id'], //   商品id
    'store_id' => $store_id, //店铺id
    'schedule_time_id' => $schedule_time_id, //  时间段id
    'prices_id' => $prices_type_id, //价格id
    'book_day' => date('Ymd', $service_time_stamp), //预订日期  20170101
    'quantity' => $quantity, //订单数量
    'name' => $username, //用户名
    'phone' => $cellphone, //用户手机
    'remark' => $description, //订单备注
    'email' => $email, //选填
    'baby_age' => implode(',', $baby_age_list), //宝贝年龄
);
if ($location_id == 'test') {
    $options['data'] = $data;
    return $cp->output($options);
}
// 提交订单
if ($referer == 'weixin_app') {  // 微信小程序
    $yueshe_production_obj = POCO::singleton('pai_mall_yueshe_production_class');
    $submit_res = $yueshe_production_obj->xcx_add_order($data, $referer, 'online', $user_id);
    $options['data'] = $submit_res;
    return $cp->output($options);
}
if (!empty($trial_code) && !empty($trial_version)) {
    // 有邀请码
    $yueshe_invitation_code_obj = POCO::singleton('pai_mall_yueshe_invitation_code_class');
    $submit_res = $yueshe_invitation_code_obj->add_and_pay_order($data, $referer, 'online', $user_id, $trial_code, $trial_version);
} else {
    $yueshe_production_obj = POCO::singleton('pai_mall_yueshe_production_class');
    $submit_res = $yueshe_production_obj->add_order($data, $referer, 'online', $user_id);
}
//$mall_order_api_obj = POCO::singleton('snap_mall_order_api_class');
//$submit_res = $mall_order_api_obj->submit_order($user_id, $seller_user_id, $request_goods, $request_consignee, $more_info);
$options['data'] = $submit_res;
return $cp->output($options);
