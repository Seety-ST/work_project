<?php

/**
 * �������� �б�
 * 
 * @author willike <chenwb@yueus.com>
 * @since 2017-01-06
 */
//define('YUE_INPUT_CHECK_TOKEN', FALSE);
require(dirname(dirname(__FILE__)) . '/moji_input.inc.php');

$yueshe_baby_info_obj = POCO::singleton('pai_mall_yueshe_baby_info_class');
$baby_result = $yueshe_baby_info_obj->get_my_baby_list($target_id, $limit_str);
if ($location_id == 'test') {
    $options['data'] = array(
        '$user_id' => $user_id,
        '$baby_result' => $baby_result,
    );
    return $cp->output($options);
}
$baby_list = array();
foreach ($baby_result as $baby_) {
    $baby_birth_diff = time() - $baby_['baby_birth'];
    $baby_diff = 0;
    if ($baby_birth_diff > 0) {
        $baby_diff = floor($baby_birth_diff / 31536000);
    }
    $baby_list[] = array(
        'baby_id' => $baby_['id'],
        'baby_image' => $baby_['baby_head_img'],
        'baby_name' => $baby_['baby_name'],
        'baby_sex' => $baby_['baby_sex'] == 1 ? 'С����' : 'С����',
        'baby_birth' => ($baby_diff < 1 ? 1 : $baby_diff) . '��',
    );
}
$options['data'] = array(
    'title' => '�ҵı�������',
    'target_id' => $target_id,
    'list' => $baby_list,
);
return $cp->output($options);
