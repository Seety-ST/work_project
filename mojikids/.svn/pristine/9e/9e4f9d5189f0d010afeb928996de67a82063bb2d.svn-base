<?php

/**
 * 宝宝档案
 * 
 * @author willike <chenwb@yueus.com>
 * @since 2017-01-06
 */
//define('YUE_INPUT_CHECK_TOKEN', FALSE);
require(dirname(dirname(__FILE__)) . '/moji_input.inc.php');

$baby_id = $client_data['data']['param']['baby_id'];

$yueshe_baby_info_obj = POCO::singleton('pai_mall_yueshe_baby_info_class');
$baby_result = $yueshe_baby_info_obj->get_baby_info($baby_id);
if ($location_id == 'test') {
    $options['data'] = array(
        '$baby_id' => $baby_id,
        '$baby_result' => $baby_result,
    );
    return $cp->output($options);
}
if (empty($baby_result)) {
    $options['data'] = array(
        'result' => 0,
        'message' => '没有该宝宝档案'
    );
    return $cp->output($options);
}
$baby_birth_diff = time() - $baby_result['baby_birth'];
$baby_diff = 0;
if ($baby_birth_diff > 0) {
    $baby_diff = floor($baby_birth_diff / 31536000);
}
$baby_info = array(
    'headline' => '宝宝卡片',
    'baby_id' => $baby_result['id'],
    'baby_image' => $baby_result['baby_head_img'],
    'baby_name' => $baby_result['baby_name'],
    'baby_sex' => $baby_result['baby_sex'] == 1 ? '小王子' : '小公主',
    'baby_gender' => $baby_result['baby_sex'] == 1 ? '男' : '女',
    'baby_birth' => date('Y-m-d', $baby_result['baby_birth']),
    'baby_age' => ($baby_diff < 1 ? 1 : $baby_diff) . '岁',
    'description' => '亲爱的宝贝，你的微笑就是全世界的快乐',
);
$options['data'] = $baby_info;
return $cp->output($options);
