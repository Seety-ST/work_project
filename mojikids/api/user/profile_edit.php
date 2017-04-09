<?php

/**
 * 修改用户信息
 * 
 * @author willike <chenwb@yueus.com>
 * @since 2017-01-06
 */
//define('YUE_INPUT_CHECK_TOKEN', FALSE);
require(dirname(dirname(__FILE__)) . '/moji_input.inc.php');
if (empty($user_id)) {
    $res = array(
        'result' => 0,
        'message' => '请先登录',
    );
    $options['data'] = $res;
    return $cp->output($options);
}
$operate = $client_data['data']['param']['operate'];
$yueshe_user_obj = POCO::singleton('pai_mall_yueshe_user_class');
switch ($operate) {
    case 'nickname':  // 修改昵称
        $nickname = $client_data['data']['param']['nickname'];
        if (empty($nickname)) {
            $res = array(
                'result' => 0,
                'message' => '昵称为空',
            );
            $options['data'] = $res;
            return $cp->output($options);
        }
        if (mb_strlen($baby_name, 'GBK') > 20) {
            $res = array(
                'result' => 0,
                'message' => '昵称不能大于20个字',
            );
            $options['data'] = $res;
            return $cp->output($options);
        }
        $res = $yueshe_user_obj->update_member_nickname($nickname, $user_id);
        break;
    case 'bind': // 绑定手机号
        $password = $client_data['data']['param']['password'];
        if (empty($password)) {
            $res = array(
                'result' => 0,
                'message' => '密码为空',
            );
            $options['data'] = $res;
            return $cp->output($options);
        }
        $cellphone = $client_data['data']['param']['phone'];
        if (empty($cellphone)) {
            $res = array(
                'result' => 0,
                'message' => '手机号为空',
            );
            $options['data'] = $res;
            return $cp->output($options);
        }
        $code = $client_data['data']['param']['code'];
        if (empty($code)) {
            $res = array(
                'result' => 0,
                'message' => '验证码为空',
            );
            $options['data'] = $res;
            return $cp->output($options);
        }
        // 验证码验证
        $verify_res = $yueshe_user_obj->wx_check_verify_code($cellphone, 'bind', $code);
        if (empty($verify_res) || $verify_res['result'] != 1) {
            $res = array(
                'result' => 0,
                'message' => '验证码错误',
            );
            $options['data'] = $res;
            return $cp->output($options);
        }
        $res = $yueshe_user_obj->weixin_bind_cellphone_v2($user_id, $cellphone, $password, $code);
    case 'password':   // 修改密码
        $code = $client_data['data']['param']['code'];
        if (empty($code)) {
            $res = array(
                'result' => 0,
                'message' => '验证码为空',
            );
            $options['data'] = $res;
            return $cp->output($options);
        }
        $password = $client_data['data']['param']['password'];
        if (empty($password)) {
            $res = array(
                'result' => 0,
                'message' => '密码为空',
            );
            $options['data'] = $res;
            return $cp->output($options);
        }
        // 验证码验证
        $verify_res = $yueshe_user_obj->wx_check_verify_code($cellphone, 'passwd', $code);
        if (empty($verify_res) || $verify_res['result'] != 1) {
            $res = array(
                'result' => 0,
                'message' => '验证码错误',
            );
            $options['data'] = $res;
            return $cp->output($options);
        }
        $res = $yueshe_user_obj->update_member_password($password, $user_id);
    case 'changephone':  // 修改手机号
        $cellphone = $client_data['data']['param']['phone'];
        if (strlen($cellphone) != 11 || !preg_match('/^1[0-9]{10}$/', $cellphone)) {
            $res = array(
                'result' => 0,
                'message' => '手机号不正确',
            );
            $options['data'] = $res;
            return $cp->output($options);
        }
        $code = $client_data['data']['param']['code'];
        if (empty($code)) {
            $res = array(
                'result' => 0,
                'message' => '验证码为空',
            );
            $options['data'] = $res;
            return $cp->output($options);
        }
        $phone_type = $client_data['data']['param']['phone_type'];
        if (in_array($phone_type, array('new', 'old'))) {  // 返回 验证码校验 结果
            $verify_res = $yueshe_user_obj->check_verify_code($phone_type, $cellphone, $code, false);
            if (empty($verify_res) ||
                    (isset($verify_res['result']) && $verify_res['result'] != 1)) {
                $res = array(
                    'result' => -1,
                    'message' => '验证码不正确',
                );
                $options['data'] = $res;
                return $cp->output($options);
            }
            $options['data'] = array(
                'result' => 1,
                'message' => '验证成功',
            );
            return $cp->output($options);
        }
        $new_cellphone = $client_data['data']['param']['new_phone'];
        if (strlen($new_cellphone) != 11 || !preg_match('/^1[0-9]{10}$/', $new_cellphone)) {
            $res = array(
                'result' => 0,
                'message' => '新手机号不正确',
            );
            $options['data'] = $res;
            return $cp->output($options);
        }
        $new_code = $client_data['data']['param']['new_code'];
        if (empty($new_code)) {
            $res = array(
                'result' => 0,
                'message' => '验证码为空',
            );
            $options['data'] = $res;
            return $cp->output($options);
        }
//        $verify_res = $yueshe_user_obj->check_verify_code('new', $new_cellphone, $new_code, false);
//        if (empty($verify_res) || $verify_res['result'] != 1) {
//            $res = array(
//                'result' => -2,
//                'message' => '新手机验证码不正确',
//            );
//            $options['data'] = $res;
//            return $cp->output($options);
//        }
        $res = $yueshe_user_obj->change_cellphone_directly($cellphone, $code, $new_cellphone, $new_code, $user_id);
        break;
    default:
        $res = array(
            'result' => -1,
            'message' => '非法操作',
        );
        break;
}
$options['data'] = $res;
return $cp->output($options);
