<?php

/**
 * �ύ����
 *
 * @author willike <chenwb@yueus.com>
 * @since 2017-01-07
 */
require(dirname(dirname(__FILE__)) . '/moji_input.inc.php');

$trial_code = $client_data['data']['param']['trial_code'];  // ������
$trial_version = $client_data['data']['param']['trial_version'];

$goods_id = $client_data['data']['param']['goods_id'];
if (empty($goods_id)) {
    $options['data'] = array(
        'result' => 0,
        'message' => '��ƷIDΪ��',
    );
    return $cp->output($options);
}
// ��Ʒ��Ϣ
$yueshe_goods_obj = POCO::singleton('pai_mall_yueshe_goods_class');
$goods_result = $yueshe_goods_obj->get_goods_info_for_yueshe($goods_id);
$goods_data = $goods_result['goods_data'];
if (empty($goods_data)) {
    $options['data'] = array(
        'result' => 0,
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
// ����ID (ѡ��)
$store_id_list = explode(',', $tmp_detail['store_id']);  // ����ID��
$store_id = $client_data['data']['param']['store_id'];
// �ж��Ƿ� ����Ʒ���ŵ��б���
if (!in_array($store_id, $store_id_list)) {
    $options['data'] = array(
        'result' => 0,
        'message' => '�ŵ�ID���Ϸ�',
    );
    return $cp->output($options);
}
// ���
$prices_type_id = $client_data['data']['param']['standard_id'];
if (empty($prices_type_id) || !is_numeric($prices_type_id)) {
    $options['data'] = array(
        'result' => -1,
        'message' => '���δѡ��',
    );
    return $cp->output($options);
}
$prices_data_list = $goods_result['prices_data_list'];
$standard = array();
foreach ($prices_data_list as $prices_) {
    $standard_id = $prices_['type_id'];
    $standard[] = $standard_id;  // ���ID�б�
}
if (!in_array($prices_type_id, $standard)) {
    $options['data'] = array(
        'result' => -1,
        'message' => '����Ʒû�д˹��',
    );
    return $cp->output($options);
}
// ��������
$quantity = trim($client_data['data']['param']['buy_num']);
if ($quantity < 1) {
    $options['data'] = array(
        'result' => -2,
        'message' => '����������������1',
    );
    return $cp->output($options);
}
// ����ʱ��
$service_time = $client_data['data']['param']['service_time'];
$service_time_stamp = strtotime($service_time);
if (empty($service_time_stamp)) {
    $options['data'] = array(
        'result' => -3,
        'message' => '����ʱ���ʽ����ȷ',
    );
    return $cp->output($options);
}
$service_time = date('Y-m-d', $service_time_stamp);
if (strcmp($service_time, date('Y-m-d')) < 0) {
    $options['data'] = array(
        'result' => -3,
        'message' => '����ʱ�䲻��ȷ',
    );
    return $cp->output($options);
}
// ����ʱ��ID
$schedule_time_id = $client_data['data']['param']['timezone_id'];
if (!is_numeric($schedule_time_id)) {
    $options['data'] = array(
        'result' => -3,
        'message' => '����ʱ���ID����ȷ',
    );
    return $cp->output($options);
}
$yueshe_schedule_obj = POCO::singleton('pai_mall_yueshe_schedule_class');
$schedule_time_re = $yueshe_schedule_obj->get_schedule_time_info($schedule_time_id);
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
//$request_goods = array(
//    array(
//        'goods_id' => $goods_data['goods_id'], //��ƷID
//        'prices_type_id' => $prices_type_id, //���ID
//        'quantity' => $quantity, //����
//        'service_time' => $service_time_stamp, //����ʱ�䣬ʱ���
//    )
//);
// �û���
$username = $client_data['data']['param']['username'];
if (empty($username)) {
    $options['data'] = array(
        'result' => -4,
        'message' => '��������Ϊ��',
    );
    return $cp->output($options);
}
if (mb_strlen($username, 'GBK') > 20) {
    $options['data'] = array(
        'result' => -4,
        'message' => '�������ܳ���20��',
    );
    return $cp->output($options);
}
// �û��ֻ���
$cellphone = $client_data['data']['param']['phone'];
if (empty($cellphone)) {
    $options['data'] = array(
        'result' => -4,
        'message' => '�ֻ��Ų���Ϊ��',
    );
    return $cp->output($options);
}
if (!preg_match('/^1[0-9]{10}$/', $cellphone)) {
    $options['data'] = array(
        'result' => -4,
        'message' => '�ֻ��Ų��Ϸ�',
    );
    return $cp->output($options);
}
// ��ȡ ������Ϣ
//$yueshe_store_obj = POCO::singleton('pai_mall_yueshe_store_class');
//$store_result = $yueshe_store_obj->get_store_info($store_id);
//$yueshe_info = $store_result['yueshe_info'];
//$request_consignee = array(
//    'consignee' => $username, //��ϵ������
//    'cellphone' => $cellphone, //��ϵ�绰
//    'location_id' => $yueshe_info['location_id'], //���̵�ַID
//    'address' => $store_result['address'], // ���̵�ַ
//);
// ������Դ
$referer = $client_data['data']['param']['order_from'];
if (!in_array($referer, array('app', 'pc', 'weixin', 'weixin_app', 'wap'))) {
    $options['data'] = array(
        'result' => -5,
        'message' => '������Դ���Ϸ�',
    );
    return $cp->output($options);
}
$description = $client_data['data']['param']['description'];  // ��ע
if (!empty($description)) {
    $description = moji_content_strip($description);
}
// ������Ϣ
$extra = array();
$email = trim($client_data['data']['param']['email']);
//if (!empty($email)) {
//    $extra[] = array('email' => $email);
//}
$baby_age = trim($client_data['data']['param']['baby_age']);
$baby_age_list = array();
if (!empty($baby_age)) {
    // ��������
    $str_reg = array(
        '|', '��', ':', '��', '-', '��', '.', '��',
        '/', '\\', '%', '*', '^', '@', '#', '$',
        ' ', '~', '��', '&', '+', '=', '!', '��',
        '?', '��', '��', ' ',
    );
    $baby_age = str_replace($str_reg, ',', $baby_age);
    $baby_age_list = explode(',', $baby_age);
}
if (empty($baby_age_list)) {
    $options['data'] = array(
        'result' => -6,
        'message' => '�������䲻��Ϊ��',
    );
    return $cp->output($options);
}
//if (!empty($baby_age_list)) {
//    $extra[] = array(
//        'baby_age' => $baby_age_list,
//    );
//}
//$more_info = array(
//    'store_id' => $store_id, //�ŵ�ID
//    'payment_pattern' => 'online', //֧����ʽ, online���ϣ�offline����
//    'referer' => $referer, //������Դ
//    'description' => $description, //������ע
//    'extra' => serialize($extra), //���������Ϣ�����л�
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
    'goods_id' => $goods_data['goods_id'], //   ��Ʒid
    'store_id' => $store_id, //����id
    'schedule_time_id' => $schedule_time_id, //  ʱ���id
    'prices_id' => $prices_type_id, //�۸�id
    'book_day' => date('Ymd', $service_time_stamp), //Ԥ������  20170101
    'quantity' => $quantity, //��������
    'name' => $username, //�û���
    'phone' => $cellphone, //�û��ֻ�
    'remark' => $description, //������ע
    'email' => $email, //ѡ��
    'baby_age' => implode(',', $baby_age_list), //��������
);
if ($location_id == 'test') {
    $options['data'] = $data;
    return $cp->output($options);
}
// �ύ����
if ($referer == 'weixin_app') {  // ΢��С����
    $yueshe_production_obj = POCO::singleton('pai_mall_yueshe_production_class');
    $submit_res = $yueshe_production_obj->xcx_add_order($data, $referer, 'online', $user_id);
    $options['data'] = $submit_res;
    return $cp->output($options);
}
if (!empty($trial_code) && !empty($trial_version)) {
    // ��������
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