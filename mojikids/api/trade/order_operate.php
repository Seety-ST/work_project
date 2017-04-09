<?php

/**
 * 订单 操作
 *
 * @author willike <chenwb@yueus.com>
 * @since 2017-01-10
 */
require(dirname(dirname(__FILE__)) . '/moji_input.inc.php');

$operate = $client_data['data']['param']['operate'];
switch ($operate) {
    case 'uphold':  // 图片上墙
        $ids_ary = $client_data['data']['param']['image_ids'];
        if (empty($ids_ary)) {
            $res = array(
                'result' => 0,
                'message' => '请选择照片',
            );
            $options['data'] = $res;
            return $cp->output($options);
        }
        $ids_ary_arr = explode(',', $ids_ary);
        $images_ids = array();
        foreach ($ids_ary_arr as $img_id) {
            if (empty($img_id) || !is_numeric($img_id)) {
                continue;
            }
            $images_ids[] = $img_id;
        }
        if (empty($images_ids)) {
            $res = array(
                'result' => 0,
                'message' => '请选择需要上墙的照片',
            );
            $options['data'] = $res;
            return $cp->output($options);
        }
        $yueshe_fix_photo_obj = POCO::singleton('pai_mall_yueshe_fix_photo_class');
        $res = $yueshe_fix_photo_obj->batch_update_img_is_up($images_ids, 1);
        break;
    case 'dismiss': // 下墙
        $ids_ary = $client_data['data']['param']['image_ids'];
        if (empty($ids_ary)) {
            $res = array(
                'result' => 0,
                'message' => '请选择照片',
            );
            $options['data'] = $res;
            return $cp->output($options);
        }
        $ids_ary_arr = explode(',', $ids_ary);
        $images_ids = array();
        foreach ($ids_ary_arr as $img_id) {
            if (empty($img_id) || !is_numeric($img_id)) {
                continue;
            }
            $images_ids[] = $img_id;
        }
        if (empty($images_ids)) {
            $res = array(
                'result' => 0,
                'message' => '请选择需要下墙的照片',
            );
            $options['data'] = $res;
            return $cp->output($options);
        }
        $yueshe_fix_photo_obj = POCO::singleton('pai_mall_yueshe_fix_photo_class');
        $res = $yueshe_fix_photo_obj->batch_update_img_is_up($images_ids, 0);
    default:
        $res = array(
            'result' => -1,
            'message' => '非法操作',
        );
        break;
}
$options['data'] = $res;
return $cp->output($options);


