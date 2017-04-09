<?php

/**
 * �û�ע��
 * 
 * @author willike <chenwb@yueus.com>
 * @since 2017-01-06
 */
define('YUE_INPUT_CHECK_TOKEN', FALSE);
require(dirname(dirname(__FILE__)) . '/moji_input.inc.php');

$union_id = $client_data['data']['param']['union_id'];
$app_id = $client_data['data']['param']['app_id'];

if(empty($app_id)){
    $app_id = 'wx65d9c265d66c268c';
}

if (empty($union_id)) {
    $options['data'] = array(
        'result' => 0,
        'message' => '΢����Ȩ��ʶ����',
    );
    return $cp->output($options);
}
$yueshe_user_obj = POCO::singleton('pai_mall_yueshe_user_class');
// �ж� ΢���Ƿ�󶨹��ֻ�
$check_res = $yueshe_user_obj->check_weixin_is_bind_cellphone($union_id);
/*if ($check_res['result'] == 1) {
    // �Ѱ�
    $options['data'] = array(
        'result' => 1,
        'message' => '��¼�ɹ�',
        'user_id' => $check_res['user_id'],
    );
    return $cp->output($options);
}*/
/*if ($check_res['result'] != -1) {
    // ΢��δ���ֻ���΢����ע��
    $options['data'] = $check_res;
    return $cp->output($options);
}*/
$cellphone = $client_data['data']['param']['phone'];
$reg_from = $client_data['data']['param']['reg_from'];
$code = $client_data['data']['param']['code'];
if (empty($cellphone)) {
    // ΢��δ���ֻ���΢��δע��, ��Ҫ��
    $options['data'] = $check_res;
    return $cp->output($options);
}
if (!preg_match('/^1[0-9]{10}$/', $cellphone)) {
    $options['data'] = array(
        'result' => 0,
        'message' => '�ֻ��Ų��Ϸ�',
    );
    return $cp->output($options);
}
// ��֤ �ֻ���
$judge_res = $yueshe_user_obj->judge_weixin_bind_cellphone_send_code($union_id, $cellphone);
if (empty($judge_res) ||
        (isset($judge_res['result']) && $judge_res['result'] != 1)) {
    $options['data'] = $judge_res;
    return $cp->output($options);
}
if (empty($code)) {
    $res = array(
        'result' => -11,
        'message' => '��֤��Ϊ��',
    );
    $options['data'] = $res;
    return $cp->output($options);
}
// ��֤����֤
$verify_res = $yueshe_user_obj->wx_check_verify_code($cellphone, 'bind', $code);
if (empty($verify_res) ||
        (isset($verify_res['result']) && $verify_res['result'] != 1)) {
    $res = array(
        'result' => 0,
        'message' => '��֤�����',
    );
    $options['data'] = $res;
    return $cp->output($options);
}
if (!in_array($reg_from, array('app', 'pc', 'weixin', 'wap', 'weixin_app'))) {
    $options['data'] = array(
        'result' => 0,
        'message' => 'ע����Դ���Ϸ�',
    );
    return $cp->output($options);
}
$bind_data = array(
    'cellphone' => $cellphone,
    'union_id' => $union_id,
    'reg_from' => $reg_from,
    'app_id' => $app_id,
);
$bind_res = $yueshe_user_obj->weixin_bind_cellphone_v3($bind_data);
if ($bind_res['result'] == 1) {
    $user_id = $yueshe_user_obj->get_user_id_by_cellphone($cellphone);
    $bind_res['user_id'] = $user_id;
}
$options['data'] = $bind_res;
return $cp->output($options);
