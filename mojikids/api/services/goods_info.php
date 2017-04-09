<?php

/**
 * 商品详情
 * 
 * @author willike <chenwb@yueus.com>
 * @since 2017-01-04
 */
define('YUE_INPUT_CHECK_TOKEN', FALSE);
require(dirname(dirname(__FILE__)) . '/moji_input.inc.php');

$goods_id = $client_data['data']['param']['goods_id'];

$yueshe_goods_obj = POCO::singleton('pai_mall_yueshe_goods_class');
$goods_result = $yueshe_goods_obj->get_goods_info_for_yueshe($goods_id);
if ($location_id == 'test') {
    $options['data'] = array(
        '$goods_id' => $goods_id,
        '$goods_result' => $goods_result,
    );
    return $cp->output($options);
}
$goods_data = $goods_result['goods_data'];
if (empty($goods_data)) {
    $options['data'] = array(
        'result' => 0,
        'message' => '没有该商品',
    );
    return $cp->output($options);
}
// 封面图
$cover = array();
foreach ($goods_data['img'] as $goods_data_img) {
    $cover[] = array(
        'value' => $goods_data_img['img_url'],
    );
}
// 店铺
$yueshe_detail = $goods_result['yueshe_detail'];
$tmp_detail = array();
foreach ($yueshe_detail as $value) {
    $detail_key = $value['detail_key'];
    $tmp_detail[$detail_key] = $value['detail_val'];
}
$description = $tmp_detail['desc']; //描述
$store_info = array();
$store_id_list = explode(',', $tmp_detail['store_id']);  // 店铺ID集
$yueshe_store_obj = POCO::singleton('pai_mall_yueshe_store_class');
foreach ($store_id_list as $store_id) {
    if (empty($store_id)) {
        continue;
    }
    $store_result = $yueshe_store_obj->get_store_info($store_id);
    if ($location_id == 'test1') {
        $options['data'] = array(
            '$goods_id' => $goods_id,
            '$store_id' => $store_id,
            '$store_result' => $store_result,
        );
        return $cp->output($options);
    }
    if (empty($store_result)) {
        continue;
    }
    $yueshe_info = $store_result['yueshe_info'];
    $store_location_id = $yueshe_info['location_id'];  // 位置ID
    $address = $store_result['address'];  // 地理位置
    $store_location_name = get_poco_location_name_by_location_id($store_location_id);
    if (!empty($store_location_name)) {
        $address = explode(' ', $store_location_name)[2] . ' ' . $address;
    }
    $store = array(
        array(
            'image' => 'http://image19-d.yueus.com/yueyue/20170107/20170107105339_623663_10002_53511.png?32x32_130',
            'title' => '', 'value' => $store_result['name']
        ),
        array(
            'image' => 'http://image19-d.yueus.com/yueyue/20170107/20170107105230_754041_10002_53509.png?32x32_130',
            'title' => '', 'value' => $address,
            'lng_lat' => str_replace('-', ',', $yueshe_info['lng_lat']),
        ),
        array(
            'image' => 'http://image19-d.yueus.com/yueyue/20170107/20170107105324_210681_10002_53510.png?32x32_130',
            'title' => '', 'value' => $store_result['tel']
        ),
    );
    $schedule_id = $yueshe_info['schedule_id'];
    $store_info[] = array(
        'store_id' => $store_id,
        'schedule_id' => $schedule_id,
        'property' => $store,
    );
}
// 规格
$prices_data_list = $goods_result['prices_data_list'];
$standard = array();
$min_price = $max_price = 0;
foreach ($prices_data_list as $prices_) {
    $price_num = $prices_['value'];  // 价格
    if ($price_num == '' || bccomp($price_num, 0.01, 2) < 0) {  // 价格为空
        continue;
    }
    if ($min_price == 0 || bccomp($price_num, $min_price, 2) <= 0) {
        $min_price = $price_num;  // 最小价格
    }
    if ($max_price == 0 || bccomp($price_num, $max_price, 2) >= 0) {
        $max_price = $price_num;  // 最大价格
    }
    $standard_id = $prices_['type_id'];
    $standard[$standard_id] = array(
        'standard_id' => $standard_id,
        'name' => $prices_['name_val'], // 规格名称
        'price' => '￥' . $price_num,
        'unit' => '',
        'stock_num' => $prices_['stock_num'],
    );
}
$unit = '';
if (bccomp($min_price, $max_price, 2) != 0) {
    $unit = '起';
}
// 约摄 规格属性
$detail_keymap = $yueshe_goods_obj->get_format_prices_detail_key();
if ($location_id == 'test2') {
    $options['data'] = array(
        '$detail_keymap' => $detail_keymap,
    );
    return $cp->output($options);
}
$standard_package = array();  // 套餐内容
$yueshe_prices = $goods_result['yueshe_prices_format'];
foreach ($yueshe_prices as $standard_id => $yueshe_standard) {
    if (!isset($standard[$standard_id])) {  // 没有该价格
        continue;
    }
    $standard_property = $package_property = array();   // 规格属性
    foreach ($yueshe_standard as $property_info) {
        $detail_number = $property_info['number'];  // 套餐数量
        $prices_upgrade = $property_info['prices_upgrade'];  // 升级条件
        if ($detail_number < 1 && empty($prices_upgrade)) {
            continue;
        }
        $detail_key = $property_info['detail_key'];  // 套餐属性key
        $detail_key_name = $detail_keymap[$detail_key]['name'];  // 属性名称
        if (strpos($detail_key_name, '宫格') !== false) {
            // 去掉 四宫格/九宫格 2017-02-23
            continue;
        }
        $detail_key_unit = $detail_keymap[$detail_key]['message'];  // 属性单位
        $property_message = moji_html_decode($property_info['message']);  // // 属性备注
        $standard_property_info = array(
            'title' => $detail_key_name,
            'value' => $detail_number,
            'unit' => $detail_key_unit, // 单位
        );
        if (in_array($detail_key, array('make_up'))) { // 化妆
            // 图片显示
            $standard_property_info['image'] = 'http://image19-d.yueus.com/yueyue/20170107/20170107103533_683953_10002_53497.png?52x34_130';
            $detail_number = $detail_number == 1 ? '有' : '无';
        }
        if (!empty($property_message)) {  // 使用 逗号
            $property_message = '，' . $property_message;
        }
        // 套餐内容 正常属性
        $package_property[] = array(
            'title' => $detail_key_name . ':',
            'value' => $detail_number . $detail_key_unit . $property_message,
        );
        // 套餐升级, 不显示 2017-01-23
//        if (!empty($prices_upgrade)) {
//            foreach ($prices_upgrade as $upgrade_res) {
//                $prices_options_detail = array(
//                    'title' => $detail_key_name . '(' . $upgrade_res['number'] . $detail_key_unit . ')',
//                    'value' => '￥' . $upgrade_res['prices'],
//                );
////                $prices_options[] = $prices_options_detail;
//                // 套餐内容 升级属性
//                $prices_options_detail['title'] .= ':';
//                $package_property[] = $prices_options_detail;
//            }
////            $standard_property_info['options'] = $prices_options;
//        }
        $standard_property[] = $standard_property_info;
    }
    // 属性内容
    $package_info = $standard[$standard_id];
    $standard_package[] = array(
        'name' => $package_info['name'],
        'price' => $package_info['price'],
        'package' => $package_property,
    );
    // 规格内容, 获取前6个 2017-01-23
    $standard_property = array_slice($standard_property, 0, 6);
    $standard[$standard_id]['property'] = $standard_property;
}
$options['data'] = array(
    'result' => 1,
    'goods_id' => $goods_data['goods_id'],
    'cover' => $cover,
    'title' => $goods_data['titles'], // . ' - MOJIKIDS莫吉照相馆',
    'description' => $description,
    'price' => '￥' . $min_price,
    'unit' => $unit,
    'store' => $store_info,
    'exhibition' => $goods_data['content'],
    'package' => $standard_package,
    'standard' => array_values($standard),
);
return $cp->output($options);
