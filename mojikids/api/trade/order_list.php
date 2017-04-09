<?php

/**
 * ���� �б�
 *
 * @author willike <chenwb@yueus.com>
 * @since 2017-01-10
 */
require(dirname(dirname(__FILE__)) . '/moji_input.inc.php');

$goods_id = $client_data['data']['param']['goods_id'];
// ȫ�� all ������ unpaid ��ȷ�� tbc ��ǩ�� checkin �����  completed �ѹر� closed
$trade_type = $client_data['data']['param']['trade_type'];   // ��������
// ������ 0, ������ 1, ����� 2, �ѹر� 7, ����� 8, ���� -1
switch ($trade_type) {
    case 'unpaid':  // ������
        $status = 0;
        break;
    case 'tbc':  // ��ȷ��(������)
        $status = 1;
        break;
    case 'checkin':  // ��ǩ��
        $status = 2;
        break;
    case 'completed':  // �����
        $status = 8;
        break;
    case 'closed':  // �ѹر�
        $status = 7;
        break;
    default:  // ����
        $status = -1;
        break;
}
$mall_order_api_obj = POCO::singleton('snap_mall_order_api_class');
$order_result = $mall_order_api_obj->get_order_full_list($user_id, 'buyer', $status, false, '', '', $limit_str);
if ($location_id == 'test') {
    $options['data'] = array(
        '$user_id' => $user_id,
        '$status' => $status,
        '$limit_str' => $limit_str,
        '$order_result' => $order_result,
    );
    return $cp->output($options);
}
$order_list = array();
$order_status_keymap = moji_get_config('order_status');
$order_desc_keymap = [
    0 => '�ö�����δ֧��',
    1 => '�ö�����֧���ɹ�',
    2 => '�ö�����֧���ɹ�',
    7 => '�ö����ѹر�',
    8 => '�ö��������'
];
foreach ($order_result as $order_) {
    // 0��֧����1��ȷ�ϣ�2��ǩ����7�ѹرգ�8�����
    $status = $order_['status'];
    $status_str = $order_status_keymap[$status];
    $goods_details = $order_['details'][0];  // ��Ʒ����
    $consignee = $order_['consignee'];  // ��������
    $properties = $order_['properties'];  // ����
    $lng_lat = str_replace('-', ',', $properties['lng_lat']);  // ��γ��
    $more_info = array(
        'lng_lat' => $lng_lat,
        'hotline' => $order_['hotline'],
        'from' => $request_platform, // for С���� 2017-02-15
    );
    $order_property = array(
        array('title' => '����ʱ��:', 'value' => $goods_details['scheduled_time_str']),
        array(
            'title' => '�����ŵ�:',
            'value' => $properties['store_name'],
            'lng_lat' => $lng_lat,
        ),
        array('title' => '�ŵ��ַ:', 'value' => $consignee['address']),
        array('title' => '�ŵ�绰:', 'value' => $order_['hotline']),
    );
    $button = moji_trade_button($status, $more_info);  // ��ť
    $order_info = array(
        'order_sn' => $order_['order_sn'],
        'status' => $status,
        'status_str' => $status_str,
        'status_desc' => $order_desc_keymap[$status],
        'title' => $goods_details['goods_name'],
        'goods_id' => $goods_details['goods_id'],
        'property' => $order_property,
        'bill' => array(
            array('title' => '�ϼƽ�', 'value' => '��' . $order_['pending_amount']),
        ),
        'button' => $button,
    );
    $order_list[] = $order_info;
}
$options['data'] = array(
    'title' => '�ҵĶ���',
    'list' => $order_list,
);
return $cp->output($options);