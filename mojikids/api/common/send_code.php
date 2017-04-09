<?php

/**
 * ������֤��
 * 
 * @author willike <chenwb@yueus.com>
 * @since 2017-01-06
 */
define('YUE_INPUT_CHECK_TOKEN', FALSE);
require(dirname(dirname(__FILE__)) . '/moji_input.inc.php');

$cellphone = $client_data['data']['param']['phone'];
if (empty($cellphone)) {
    $res = array(
        'result' => -1,
        'message' => '�ֻ���Ϊ��',
    );
    $options['data'] = $res;
    return $cp->output($options);
}
if (strlen($cellphone) != 11 || !preg_match('/^1[0-9]{10}$/', $cellphone)) {
    $res = array(
        'result' => -1,
        'message' => '�ֻ��Ų���ȷ',
    );
    $options['data'] = $res;
    return $cp->output($options);
}
$operate = $client_data['data']['param']['operate'];
switch ($operate) {
    case 'bind':  // ��
        $union_id = $client_data['data']['param']['union_id'];
        $yueshe_user_obj = POCO::singleton('pai_mall_yueshe_user_class');
        // ��֤ �ֻ���
        $judge_res = $yueshe_user_obj->judge_weixin_bind_cellphone_send_code($union_id, $cellphone);
        if (empty($judge_res) ||
                (isset($judge_res['result']) && $judge_res['result'] != 1)) {
            $options['data'] = $judge_res;
            return $cp->output($options);
        }
        $res = $yueshe_user_obj->wx_send_verify_code($cellphone, 'bind');
        break;
    case 'changephone':  // �޸��ֻ���
        $phone_type = $client_data['data']['param']['phone_type'];
        if (!in_array($phone_type, array('new', 'old'))) {
            $options['data'] = array(
                'result' => -1,
                'message' => '�ֻ����Ͳ���Ϊ��',
            );
            return $cp->output($options);
        }
        $yueshe_user_obj = POCO::singleton('pai_mall_yueshe_user_class');
        $res = $yueshe_user_obj->send_verify_code($phone_type, $cellphone);
        break;
    case 'resetpwd':  // �޸�����
        $yueshe_user_obj = POCO::singleton('pai_mall_yueshe_user_class');
        $res = $yueshe_user_obj->wx_send_verify_code($cellphone, 'passwd');
        break;
    case 'login':  // ��¼
        $yueshe_user_obj = POCO::singleton('pai_mall_yueshe_user_class');
        $res = $yueshe_user_obj->wx_send_verify_code($cellphone, 'login');
        break;
    default:
        $res = array(
            'result' => -1,
            'message' => '�Ƿ�����',
        );
        break;
}
$options['data'] = $res;
return $cp->output($options);
