<?php

/**
 * 用户登录
 * 
 * @author willike <chenwb@yueus.com>
 * @since 2017-01-06
 */
define('YUE_INPUT_CHECK_TOKEN', FALSE);
require(dirname(dirname(__FILE__)) . '/moji_input.inc.php');

$cellphone = $client_data['data']['param']['phone'];
$code = $client_data['data']['param']['code'];
$pwd = $client_data['data']['param']['password'];
$union_id = $client_data['data']['param']['union_id'];
$reg_from = $client_data['data']['param']['reg_from'];
if (empty($cellphone)) {
    $options['data'] = array(
        'result' => 0,
        'message' => '手机号不能为空',
    );
    return $cp->output($options);
}
if (!preg_match('/^1[0-9]{10}$/', $cellphone)) {
    $options['data'] = array(
        'result' => 0,
        'message' => '手机号不合法',
    );
    return $cp->output($options);
}
$yueshe_user_obj = POCO::singleton('pai_mall_yueshe_user_class');
if (empty($code)) {
    // 密码登录
    if (empty($pwd)) {
        $options['data'] = array(
            'result' => -1,
            'message' => '密码不能为空',
        );
        return $cp->output($options);
    }
    $res = $yueshe_user_obj->user_login_by_cellphone($cellphone, $pwd, $union_id);
    $options['data'] = $res;
    return $cp->output($options);
}
$verify_res = $yueshe_user_obj->wx_check_verify_code($cellphone, 'login', $code);
if (empty($verify_res) || $verify_res['result'] != 1) {
    $res = array(
        'result' => -2,
        'message' => '验证码错误',
    );
    $options['data'] = $res;
    return $cp->output($options);
}
$user_id = $yueshe_user_obj->get_user_id_by_cellphone($cellphone);
if (empty($user_id)) {
    // 没有该用户, 直接注册
    $reg_from = empty($reg_from) ? 'weixin' : $reg_from;
    $user_data = array(
        'cellphone' => $cellphone,
        'nickname' => $nickname = '手机用户_' . substr($cellphone, -4),
        'pwd' => '',
        'union_id' => $union_id,
        'reg_from' => $reg_from,
        'cellphone_not_verify' => TRUE, // 手机号未验证
    );
    $res = $yueshe_user_obj->create_member_by_cellphone($user_data);
    $options['data'] = $res;
    return $cp->output($options);
}
$options['data'] = array(
    'result' => 1,
    'message' => '登录成功',
    'user_id' => $user_id,
);
return $cp->output($options);
