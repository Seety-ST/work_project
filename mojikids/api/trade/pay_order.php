<?php

/**
 * ���� ֧��
 *
 * @author willike <chenwb@yueus.com>
 * @since 2017-01-11
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
$redirect_url = $client_data['data']['param']['redirect_url'];   // ��ת����(���û�ʹ�����ȫ��֧��ʱ��Ϊ��)
if (!empty($redirect_url)) {
    if (!filter_var($redirect_url, FILTER_VALIDATE_URL)) {
        $options['data'] = array(
            'result' => 0,
            'message' => '��ת���Ӳ��Ϸ�',
        );
        return $cp->output($options);
    }
    // TODO:: �ж� ��ת���ӵ� ����
}
$available_balance = $client_data['data']['param']['balance'];  // ҳ�浱ǰ���
$is_available_balance = $client_data['data']['param']['use_balance'];  // �Ƿ�ʹ����0�� 1��
$third_code = $client_data['data']['param']['pay_way'];  // ֧����ʽ
if (!in_array($third_code, array('balance', 'alipay_wap', 'wxpay_pub', 'wxpay_small'))) {
    $options['data'] = array(
        'result' => 0,
        'message' => '֧����ʽ��֧��',
    );
    return $cp->output($options);
}
$third_code = $third_code == 'balance' ? '' : $third_code;  // ���û�ʹ�����ȫ��֧��ʱ��Ϊ��

$coupon_sn = $client_data['data']['param']['coupon_sn'];  // �Ż���(ѡ��)
$more_info = array();
$mall_order_api_obj = POCO::singleton('snap_mall_order_api_class');
$pay_res = $mall_order_api_obj->submit_pay_order($order_sn, $user_id, $available_balance, $is_available_balance, $third_code, $redirect_url, '', $coupon_sn, $more_info);
$options['data'] = $pay_res;
return $cp->output($options);