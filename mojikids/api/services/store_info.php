<?php

/**
 * 门店详情
 * 
 * @author willike <chenwb@yueus.com>
 * @since 2017-01-04
 */
define('YUE_INPUT_CHECK_TOKEN', FALSE);
require(dirname(dirname(__FILE__)) . '/moji_input.inc.php');

$store_id = $client_data['data']['param']['store_id'];

$yueshe_store_obj = POCO::singleton('pai_mall_yueshe_store_class');
$store_result = $yueshe_store_obj->get_store_info($store_id);
if ($location_id == 'test') {
    $options['data'] = array(
        '$goods_id' => $goods_id,
        '$store_id' => $store_id,
        '$store_result' => $store_result,
    );
    return $cp->output($options);
}
if (empty($store_result)) {
    $options['data'] = array(
        'result' => 0,
        'message' => '没有该店铺',
    );
    return $cp->output($options);
}
$yueshe_info = $store_result['yueshe_info'];
if (empty($yueshe_info)) {
    $options['data'] = array(
        'result' => -1,
        'message' => '没有该店铺',
    );
    return $cp->output($options);
}
$store = array(
    array(
        'image' => 'http://image19-d.yueus.com/yueyue/20170107/20170107105339_623663_10002_53511.png?32x32_130',
        'title' => '', 'value' => $store_result['name']
    ),
    array(
        'image' => 'http://image19-d.yueus.com/yueyue/20170107/20170107105230_754041_10002_53509.png?32x32_130',
        'title' => '', 'value' => $store_result['address'],
        'lng_lat' => str_replace('-', ',', $yueshe_info['lng_lat']),
    ),
    array(
        'image' => 'http://image19-d.yueus.com/yueyue/20170107/20170107105324_210681_10002_53510.png?32x32_130',
        'title' => '', 'value' => $store_result['tel']
    ),
);
$property = array(
    array('title' => '交通线路:', 'value' => $yueshe_info['traffic_route']),
    array('title' => '门店介绍:', 'value' => $store_result['introduce']),
);
$options['data'] = array(
    'title' => '门店信息',
    'store_id' => $store_result['store_id'],
    'image' => $yueshe_info['images'],
    'store' => $store,
    'property' => $property,
);
return $cp->output($options);
