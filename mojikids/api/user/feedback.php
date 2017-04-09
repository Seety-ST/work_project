<?php

/**
 * 用户反馈
 * 
 * @author willike <chenwb@yueus.com>
 * @since 2017-01-06
 */
//define('YUE_INPUT_CHECK_TOKEN', FALSE);
require(dirname(dirname(__FILE__)) . '/moji_input.inc.php');

$advice = $client_data['data']['param']['content'];
if (empty($advice)) {
    $options['data'] = array(
        'result' => 0,
        'message' => '反馈内容不能为空',
    );
    return $cp->output($options);
}
$yueshe_advice_obj = POCO::singleton('pai_mall_yueshe_advice_class');
$advice_data = array(
    'user_id' => $user_id,
    'advice' => moji_content_strip($advice, 1000),
);
$advice_res = $yueshe_advice_obj->add_advice($advice_data);
if ($advice_res['result'] == 1) {
    $advice_res['message'] = '谢谢您的反馈~';
}
$options['data'] = $advice_res;
return $cp->output($options);
