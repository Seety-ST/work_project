<?php

/**
 * �û�ע��
 * 
 * @author willike <chenwb@yueus.com>
 * @since 2017-01-06
 */
define('YUE_INPUT_CHECK_TOKEN', FALSE);
require(dirname(dirname(__FILE__)) . '/moji_input.inc.php');

$cellphone = $client_data['data']['param']['phone'];
$nickname = $client_data['data']['param']['nickname'];
$pwd = $client_data['data']['param']['password'];
$union_id = $client_data['data']['param']['union_id'];
$reg_from = $client_data['data']['param']['reg_from'];
if (empty($pwd) || empty($cellphone)) {
    $options['data'] = array(
        'result' => 0,
        'message' => '�ֻ��ź����벻��Ϊ��',
    );
    return $cp->output($options);
}
if (!preg_match('/^1[0-9]{10}$/', $cellphone)) {
    $options['data'] = array(
        'result' => 0,
        'message' => '�ֻ��Ų��Ϸ�',
    );
    return $cp->output($options);
}
if (!in_array($reg_from, array('app', 'pc', 'weixin', 'wap'))) {
    $options['data'] = array(
        'result' => 0,
        'message' => 'ע����Դ���Ϸ�',
    );
    return $cp->output($options);
}
if (!empty($nickname)) {
    if (mb_strlen($nickname, 'GBK') > 20) {
        $options['data'] = array(
            'result' => 0,
            'message' => '�ǳƲ��ܴ���20����',
        );
        return $cp->output($options);
    }
} else {
    $nickname = '�ֻ��û�_' . substr($cellphone, -4);
}
$yueshe_user_obj = POCO::singleton('pai_mall_yueshe_user_class');
$user_data = array(
    'cellphone' => $cellphone,
    'nickname' => $nickname,
    'pwd' => $pwd,
    'union_id' => $union_id,
    'reg_from' => $reg_from,
    'cellphone_not_verify' => TRUE, // �ֻ���δ��֤
);
$res = $yueshe_user_obj->create_member_by_cellphone($user_data);
$options['data'] = $res;
return $cp->output($options);
