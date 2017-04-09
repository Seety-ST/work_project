<?php

/**
 * 用户信息
 * 
 * @author willike <chenwb@yueus.com>
 * @since 2017-01-07
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
$yueshe_user_obj = POCO::singleton('pai_mall_yueshe_user_class');
$user_result = $yueshe_user_obj->get_member_info($user_id);
if ($location_id == 'test') {
    $options['data'] = array(
        '$baby_id' => $baby_id,
        '$user_result' => $user_result,
    );
    return $cp->output($options);
}
if (empty($user_result['nickname'])) {
    $options['data'] = array(
        'result' => 0,
        'message' => '没有该用户信息'
    );
    return $cp->output($options);
}
$cellphone = $user_result['cellphone'];  // 手机号
$options['data'] = array(
    'result' => 1,
    'user_id' => $user_id,
    'nickname' => $user_result['nickname'],
    'avatar' => $user_result['avatar'],
    'phone' => substr($cellphone, 0, 3) . '****' . substr($cellphone, -4),
);
return $cp->output($options);
