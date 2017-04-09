<?php

/**
 * ���� ����
 *
 * @author willike <chenwb@yueus.com>
 * @since 2017-01-10
 */
require(dirname(dirname(__FILE__)) . '/moji_input.inc.php');

$order_sn = $client_data['data']['param']['order_sn'];
if (empty($order_sn)) {
    $options['data'] = array(
        'result' => 0,
        'message' => '������Ϊ��',
    );
    return $cp->output($options);
}
$mall_order_api_obj = POCO::singleton('snap_mall_order_api_class');
$order_result = $mall_order_api_obj->get_order_full_info($order_sn, $user_id);
if ($location_id == 'test') {
    $options['data'] = array(
        '$user_id' => $user_id,
        '$order_sn' => $order_sn,
        '$order_result' => $order_result,
    );
    return $cp->output($options);
}
if (empty($order_result)) {
    $options['data'] = array(
        'result' => 0,
        'message' => 'û�иö���',
    );
    return $cp->output($options);
}
if ($order_result['buyer_user_id'] != $user_id &&
        $order_result['seller_user_id'] != $user_id) {
    $options['data'] = array(
        'result' => -1,
        'message' => '�Ǳ��˶���,�޷��鿴',
    );
    return $cp->output($options);
}
// ״̬
$status = $order_result['status'];
$status_str = moji_get_config('order_status', $status);
$pay_time_left_str = 0;
// ������ 0, ������ 1, ����� 2, �ѹر� 7, ����� 8
if ($status == 0) {  // ��֧��
    $pay_time_left_str = $order_result['pay_time_left'];  // ʣ��֧��ʱ��
    $time_left = $pay_time_left_str;   // ʣ��ʱ��
    $status_description = '����ʱ�伴����ֹ���뾡�����֧�������򶩵�����ȡ����';
    $order_tips = $order_result['tips'];  // ������ʾ
    if ($time_left > 0) {
        // ��
        $minute = floor($time_left / 60);
        if ($minute > 0) {
            $new_time_left = $minute . '��';
        }
        $second = $time_left % 60;
        if ($second > 0) {
            $new_time_left .= $second . '��';
        }
//        if (empty($new_time_left)) {  // �����޶�ʱ��
//            $new_time_left = '����';
//        } else {
//            $new_time_left .= '��';
//        }
        if ($minute >= 1) {  // ����1���ӵ�, ��ʾ����ʱ
            if ($os_type == 'web') {  // web ������ɫ
                $new_time_left = '<label id="djs" style="color:#fff;font-weight: bold;">' . $new_time_left . '</label>';
            }
            $status_description = str_replace('{pay_time_left}��', $new_time_left, $order_tips);
        }
    }
} elseif ($status == 1) {  // ������
    $details_info = $order_result['details'][0];
    $scheduled_time_str = $details_info['scheduled_time_str'];
    if (empty($scheduled_time_str)) {
        $scheduled_time_str = date('Y-m-d', $details_info['service_time']);
    }
    $time_left = $scheduled_time_str;   // ����ʱ��
    if ($os_type == 'web') {  // web ������ɫ
        $new_time_left = '<label style="color:#fff;font-weight: bold;">' . $new_time_left . '</label>';
    }
    $status_description = str_replace('{schedule_time}', $scheduled_time_str, $order_result['tips']);
} else {
    $status_description = $order_result['tips'];
}
// ����
$consignee = $order_result['consignee'];
//$store_location_id = $consignee['location_id'];  // �ŵ����ڵ���ID
//$store_location_name = get_poco_location_name_by_location_id($store_location_id);
$lng_lat = str_replace('-', ',', $order_result['properties']['lng_lat']);
$store = array(
    array(
        'image' => 'http://image19-d.yueus.com/yueyue/20170107/20170107105339_623663_10002_53511.png?32x32_130',
        'title' => '', 'value' => $order_result['properties']['store_name'],
        'lng_lat' => $lng_lat,
    ),
    array(
        'image' => 'http://image19-d.yueus.com/yueyue/20170107/20170107105230_754041_10002_53509.png?32x32_130',
        'title' => '', 'value' => $consignee['address'],
        'lng_lat' => $lng_lat,
    ),
    array(
        'image' => 'http://image19-d.yueus.com/yueyue/20170107/20170107105324_210681_10002_53510.png?32x32_130',
        'title' => '', 'value' => $order_result['hotline']
    ),
);
// ��Ʒ��Ϣ
$order_details = $order_result['details'];
$scheduled_time = '';  // ԤԼʱ��
$goods = array();
foreach ($order_details as $value) {
    // ����ʱ��
    $scheduled_time .= $value['scheduled_time_str'] . ',';
    // ��Ʒ
    $goods[] = array(
        'goods_id' => $value['goods_id'],
        'title' => $value['goods_name'],
        'cover' => $value['goods_images'],
        'standard_id' => $value['prices_type_id'],
        'standard_name' => $value['prices_spec'],
        'price' => '��' . $value['original_prices'],
    );
}
// ��������
$property = array(
    array('title' => 'ԤԼ��:', 'value' => $consignee['consignee'] . ' ' . $consignee['cellphone']),
    array('title' => '����ʱ��:', 'value' => trim($scheduled_time, ',')),
);
// ��������
$order_trade = array(
    array('title' => '�����ţ�', 'value' => $order_result['order_sn']),
    array('title' => '�µ�ʱ�䣺', 'value' => $order_result['add_time_str']),
);
// ����ʱ��
if (!empty($order_result['pay_time'])) {
    $pay_time = empty($order_result['pay_time']) ? '--' : date('Y-m-d H:i:s', $order_result['pay_time']);
    $order_trade[] = array('title' => '����ʱ�䣺', 'value' => $pay_time,);
}
// ������ 0, ������ 1, ����� 2, �ѹر� 7, ����� 8
if ($status == 7) {
    $order_trade[] = array('title' => '�ر�ʱ�䣺', 'value' => $order_result['close_time_str'],);
} else if ($status == 2 || $status == 8) {
    $order_trade[] = array('title' => '����ʱ�䣺', 'value' => $order_result['accept_time_str'],);
    if ($status == 8) {
        $order_trade[] = array('title' => '���ʱ�䣺', 'value' => date('Y-m-d H:i:s', $order_result['sign_time']),);
    }
}
$order_info = array(
    'title' => '��������',
    'order_sn' => $order_result['order_sn'],
    'status' => $status,
    'status_str' => $status_str,
    'status_icon' => moji_get_config('order_info_icon', $status),
    'pay_time_left' => $pay_time_left_str,
    'status_description' => $status_description,
    'price' => '��' . $order_result['pending_amount'], // �ϼ�
    'store' => $store,
    'property' => $property,
    'goods' => $goods,
    'bill' => array(
        array('title' => '������', 'value' => '��' . $order_result['total_amount']),
//        array('title' => '�Ż�ȯ', 'value' => '-��' . $order_result['discount_amount']),
        array('title' => '�ϼƽ�', 'value' => '��' . $order_result['pending_amount']),
    ),
    'trade' => $order_trade,
    'add_time' => $order_result['add_time_str'], // �µ�ʱ��
    'button' => moji_trade_button($status, array('from' => 'order_info')),
);
// �Ƿ�ļ�
if ($order_result['is_change_price'] == 1) {
    // original_subtotal��ԭʼ�۸�pending_amountʵ��֧���۸�discount_amount�Ż�ȯ�۸�total_amount�ܽ������ļۺ�Ľ�,original_amount������Ķ����������ļ�ǰ�Ľ�
    $order_info['promo_title'] = '�ļ���Ϣ';
    $order_info['promo'] = array(
        array('title' => '����ԭ�ۣ�', 'value' => '��' . $order_result['original_amount']),
//        array('title' => '�ļ�ԭ��', 'value' => $order_result['change_price_reason']),
    );
}
$options['data'] = $order_info;
return $cp->output($options);