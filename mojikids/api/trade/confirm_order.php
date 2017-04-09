<?php

/**
 * ȷ�϶���
 *
 * @author willike <chenwb@yueus.com>
 * @since 2017-01-12
 */
require(dirname(dirname(__FILE__)) . '/moji_input.inc.php');

$goods_id = $client_data['data']['param']['goods_id'];
$store_id = $client_data['data']['param']['store_id'];
$standard_id = $client_data['data']['param']['standard_id'];
$quantity = trim($client_data['data']['param']['buy_num']);
$service_time = $client_data['data']['param']['service_time'];
$timezone_id = $client_data['data']['param']['timezone_id']; // ����ʱ��ID
$trial_code = $client_data['data']['param']['trial_code'];  // ������
$trial_version = $client_data['data']['param']['trial_version'];
if (empty($goods_id) || empty($standard_id) || empty($service_time)) {
    $options['data'] = array(
        'result' => 0,
        'message' => '������Ϣ������',
    );
    return $cp->output($options);
}
if ($quantity < 1) {
    $options['data'] = array(
        'result' => -1,
        'message' => '������������С��1',
        'buy_num' => $quantity,
    );
    return $cp->output($options);
}
$service_time_stamp = strtotime($service_time);
if (empty($service_time_stamp)) {
    $options['data'] = array(
        'result' => -2,
        'message' => '����ʱ���ʽ����ȷ',
    );
    return $cp->output($options);
}
$service_time = date('Y-m-d', $service_time_stamp);
if (strcmp($service_time, date('Y-m-d')) < 0) {
    $options['data'] = array(
        'result' => -2,
        'message' => '����ʱ�䲻��ȷ',
    );
    return $cp->output($options);
}
if (!is_numeric($timezone_id)) {
    $options['data'] = array(
        'result' => -3,
        'message' => '����ʱ���ID����ȷ',
    );
    return $cp->output($options);
}
$yueshe_schedule_obj = POCO::singleton('pai_mall_yueshe_schedule_class');
$schedule_time_re = $yueshe_schedule_obj->get_schedule_time_info($timezone_id);
if (empty($schedule_time_re)) {
    $options['data'] = array(
        'result' => -3,
        'message' => 'û�иõ���ʱ���',
    );
    return $cp->output($options);
}
if ($quantity > $schedule_time_re['stock_total']) {
    $options['data'] = array(
        'result' => -3,
        'message' => '�õ���ʱ����治��',
    );
    return $cp->output($options);
}
$time_name = $schedule_time_re['time_name'];  // ʱ���
if ($service_time == date('Y-m-d')) {  // ����
    list($h, $m) = explode(':', $time_name);
    if (is_numeric($h) && is_numeric($m)) {
        list($h_now, $m_now) = explode(':', date('G:i'));  // ��ǰʱ��
        if ($h < $h_now) {
            $options['data'] = array(
                'result' => -3,
                'message' => '�õ���ʱ����ѹ���',
            );
            return $cp->output($options);
        } else if ($h == $h_now && $m < $m_now) {
            $options['data'] = array(
                'result' => -3,
                'message' => '�õ���ʱ����ѹ�����',
            );
            return $cp->output($options);
        }
    }
}
// ��Ʒ��Ϣ
$yueshe_goods_obj = POCO::singleton('pai_mall_yueshe_goods_class');
$goods_result = $yueshe_goods_obj->get_goods_info_for_yueshe($goods_id);
$goods_data = $goods_result['goods_data'];
if (empty($goods_data)) {
    $options['data'] = array(
        'result' => -4,
        'message' => 'û�и���Ʒ',
    );
    return $cp->output($options);
}
$seller_user_id = $goods_data['user_id'];  // �̼�ID
// ����
$yueshe_detail = $goods_result['yueshe_detail'];
$tmp_detail = array();
foreach ($yueshe_detail as $value) {
    $detail_key = $value['detail_key'];
    $tmp_detail[$detail_key] = $value['detail_val'];
}
$store_id_list = explode(',', $tmp_detail['store_id']);  // ����ID��
if (empty($store_id)) {
    // û���ŵ�, Ĭ��ȡ ��һ��
    $store_id = $store_id_list[0];
} else {
    // �ж��Ƿ� ����Ʒ���ŵ��б���
    if (!in_array($store_id, $store_id_list)) {
        $options['data'] = array(
            'result' => -5,
            'message' => '�ŵ�ID���Ϸ�',
        );
        return $cp->output($options);
    }
}
// ������Ϣ
$yueshe_store_obj = POCO::singleton('pai_mall_yueshe_store_class');
$store_result = $yueshe_store_obj->get_store_info($store_id);
if (empty($store_result)) {
    $options['data'] = array(
        'result' => -5,
        'message' => 'û�иõ���',
    );
    return $cp->output($options);
}
// ����
$property = array(
    array('title' => '����ʱ��:', 'value' => $service_time . ' ' . $time_name),
    array('title' => '�����ŵ�:', 'value' => $store_result['name']),
);
// ����ͼ
$cover = array();
foreach ($goods_data['img'] as $goods_data_img) {
    $cover[] = array(
        'value' => $goods_data_img['img_url'],
    );
}
// ���
$prices_data_list = $goods_result['prices_data_list'];
$standard = array();
$min_price = $max_price = 0;
foreach ($prices_data_list as $prices_) {
    $price_num = $prices_['value'];  // �۸�
    $prices_standard_id = $prices_['type_id'];
    $standard[$prices_standard_id] = array(
        'standard_id' => $prices_standard_id,
        'name' => $prices_['name_val'], // �������
        'price' => $price_num,
        'unit' => '',
        'stock_num' => $prices_['stock_num'],
    );
}
if (!isset($standard[$standard_id])) {
    $options['data'] = array(
        'result' => -6,
        'message' => 'û�иù��',
    );
    return $cp->output($options);
}
$standard_info = $standard[$standard_id];  // �����Ϣ
// ����ж�
if ($quantity > $standard_info['stock_num']) {
    $options['data'] = array(
        'result' => -1,
        'message' => '�������������ù������',
    );
    return $cp->output($options);
}
$goods = array(
    'goods_id' => $goods_data['goods_id'],
    'title' => $goods_data['titles'],
    'cover' => $cover[0],
    'standard_id' => $standard_info['standard_id'],
    'standard_name' => $standard_info['name'],
    'price' => '��' . $standard_info['price'],
);
// �ϼƽ��
$goods_data_list = array(
    array(
        'goods_id' => $goods_data['goods_id'],
        'stage_id' => 0,
        'prices_type_id' => $standard_id,
        'quantity' => $quantity,
        'goods_promotion_id' => 0,
    )
);
$order_api_obj = POCO::singleton('snap_mall_order_api_class');
$cal_result = $order_api_obj->cal_shopping_cart($goods_data_list, $user_id, 'weixin');
if ($cal_result['result'] != 20000) {
    $options['data'] = array(
        'result' => -7,
        'message' => $cal_result['message'],
    );
    return $cp->output($options);
}
$response_data = $cal_result['response_data'];
if ($location_id == 'test') {
    $options['data'] = array(
        '$response_data' => $response_data,
    );
    return $cp->output($options);
}
$total_amount = $response_data['total_amount'];  // �������
$pending_amount = $response_data['pending_amount'];  // �ϼƽ��
$bill = array(
    array('title' => '������', 'value' => '��' . $total_amount),
    array('title' => '�ϼƽ�', 'value' => '��' . $pending_amount),
);
// ���� ������ ���Ż�
$yueshe_invitation_code_obj = POCO::singleton('pai_mall_yueshe_invitation_code_class');
$invitation_code_rs = $yueshe_invitation_code_obj->get_invitation_code_info($trial_code, $trial_version);
if (!empty($invitation_code_rs)) {
    $code_price = $invitation_code_rs['price'];
    $last_amount = bcsub($pending_amount, $code_price, 2);  // ������� - ��������
    $bill = array(
        array('title' => '������', 'value' => '��' . $total_amount),
        array('title' => '�ۿ۽�', 'value' => '-��' . $code_price),
        array('title' => '�ϼƽ�', 'value' => '��' . $last_amount),
    );
}
// �û���Ϣ
$recently_ret = $order_api_obj->get_recently_order_info($user_id, 'buyer');
$username = $phone = $baby_age = $email = '';
if (!empty($recently_ret)) {
    $username = $recently_ret['consignee'];
    $phone = $recently_ret['cellphone'];
    $baby_age = $recently_ret['baby_age'];
    $email = $recently_ret['email'];
}
if (empty($phone)) { // ʹ���û��󶨵��ֻ���
    $yueshe_user_obj = POCO::singleton('pai_mall_yueshe_user_class');
    $user_result = $yueshe_user_obj->get_member_info($user_id);
    $phone = $user_result['cellphone'];  // �ֻ���
}
$order_info = array(
    'result' => 1,
    'title' => 'ȷ�϶���',
    'goods_id' => $goods_data['goods_id'],
    'store_id' => $store_result['store_id'],
    'standard_id' => $standard_id,
    'buy_num' => $quantity,
    'timezone_id' => $timezone_id,
    'service_time' => $service_time,
    'price' => '��' . $pending_amount,
    'property' => $property,
    'goods' => $goods,
    'bill' => $bill,
    'username' => $username,
    'phone' => $phone,
    'baby_age' => $baby_age,
    'email' => $email,
);
$options['data'] = $order_info;
return $cp->output($options);