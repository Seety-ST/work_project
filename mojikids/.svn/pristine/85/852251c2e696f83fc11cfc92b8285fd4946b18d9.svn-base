<?php

/**
 * 编辑宝宝档案
 * 
 * @author willike <chenwb@yueus.com>
 * @since 2017-01-05
 */
//define('YUE_INPUT_CHECK_TOKEN', FALSE);
require(dirname(dirname(__FILE__)) . '/moji_input.inc.php');
if (empty($user_id)) {
    $options['data'] = array(
        'result' => -10,
        'message' => '请先登录',
    );
    return $cp->output($options);
}
$baby_id = $client_data['data']['param']['baby_id'];
$baby_image = $client_data['data']['param']['baby_image'];
$baby_name = $client_data['data']['param']['baby_name'];
$baby_sex = $client_data['data']['param']['baby_sex'];  // 1男 2女
$baby_birth = $client_data['data']['param']['baby_birth'];
if (empty($baby_name)) {
    $options['data'] = array(
        'result' => 0,
        'message' => '请填写宝宝小名',
    );
    return $cp->output($options);
}
if (!filter_var($baby_image, FILTER_VALIDATE_URL)) {
    $options['data'] = array(
        'result' => 0,
        'message' => '请选择宝宝图片',
    );
    return $cp->output($options);
}
if (empty($baby_sex) || !in_array($baby_sex, array('男', '女'))) {
    $options['data'] = array(
        'result' => 0,
        'message' => '请选择宝宝性别',
    );
    return $cp->output($options);
}
if (empty($baby_birth) || strtotime($baby_birth) === FALSE) {
    $options['data'] = array(
        'result' => 0,
        'message' => '请选择宝宝生日',
    );
    return $cp->output($options);
}
if (mb_strlen($baby_name, 'GBK') > 20) {
    $options['data'] = array(
        'result' => 0,
        'message' => '宝宝小名不能大于20个字',
    );
    return $cp->output($options);
}
// 卡片信息
$card_info = array(
    'user_id' => $user_id,
    'baby_head_img' => $baby_image,
    'baby_name' => $baby_name,
    'baby_sex' => $baby_sex == '男' ? 1 : 2,
    'baby_birth' => $baby_birth,
);
if ($location_id == 'test') {
    $options['data'] = array(
        '$card_info' => $card_info,
    );
    return $cp->output($options);
}
$yueshe_baby_info_obj = POCO::singleton('pai_mall_yueshe_baby_info_class');
if (empty($baby_id)) {
    // 没有 baby_id, 则添加
    $res = $yueshe_baby_info_obj->add_baby($card_info);
    if ($res['result'] == 1) {
        $res['message'] = '宝宝档案添加成功';
    }
} else {
    $res = $yueshe_baby_info_obj->update_baby($card_info, $baby_id);
}
$options['data'] = $res;
return $cp->output($options);
