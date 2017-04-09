<?php

/**
 * 档期 详情
 * 
 * @author willike <chenwb@yueus.com>
 * @since 2017-01-06
 */
define('YUE_INPUT_CHECK_TOKEN', FALSE);
require(dirname(dirname(__FILE__)) . '/moji_input.inc.php');

$schedule_id = $client_data['data']['param']['schedule_id'];
$show_date = $client_data['data']['param']['show_date'];  // 选中的年月 例: 201701
if (empty($schedule_id)) {
    $options['data'] = array(
        'result' => 0,
        'message' => '档期ID为空',
    );
    return $cp->output($options);
}
if (!empty($show_date)) {
    if (!is_numeric($show_date) || strpos($show_date, '20') !== 0 || strlen($show_date) != 6) {
        $options['data'] = array(
            'result' => 0,
            'message' => '档期时间不正确',
        );
        return $cp->output($options);
    }
} else {
    $show_date = date('Ym');
}
$trial_code = $client_data['data']['param']['trial_code'];  // 邀请码
$trial_version = $client_data['data']['param']['trial_version'];
$trial_start_time = $trial_end_time = 0;
if (!empty($trial_code) && !empty($trial_version)) {
    $yueshe_invitation_code_obj = POCO::singleton('pai_mall_yueshe_invitation_code_class');
    $invitation_code_rs = $yueshe_invitation_code_obj->get_invitation_code_info($trial_code, $trial_version);
    if ($location_id == 'test1') {
        $options['data'] = array(
            '$trial_code' => $trial_code,
            '$trial_version' => $trial_version,
            '$invitation_code_rs' => $invitation_code_rs,
        );
        return $cp->output($options);
    }
    if (empty($invitation_code_rs)) {
        $options['data'] = array(
            'result' => -1,
            'message' => '无效的邀请码',
        );
        return $cp->output($options);
    }
    $trial_start_time = $invitation_code_rs['start_time'];  // 开始时间
    $trial_end_time = $invitation_code_rs['end_time'];  // 结束时间
}
$yueshe_schedule_obj = POCO::singleton('pai_mall_yueshe_schedule_class');
$show_day = $show_date . '01';  // 显示 的年月日
$schedule_result = $yueshe_schedule_obj->get_schedule_calendar($schedule_id, $show_day);
if ($location_id == 'test') {
    $options['data'] = array(
        '$schedule_id' => $schedule_id,
        '$schedule_result' => $schedule_result,
    );
    return $cp->output($options);
}
if (empty($schedule_result)) {
    $options['data'] = array(
        'result' => 0,
        'message' => '没有该档期信息',
    );
    return $cp->output($options);
}
$todaystamp = strtotime(date('Y-m-d'));   // 今天时间戳
$week_keymap = array(
    1 => '周一',
    2 => '周二',
    3 => '周三',
    4 => '周四',
    5 => '周五',
    6 => '周六',
    7 => '周日',
);
$schedule_list = array();
// 当月1日 是周几
$week_first = date('N', strtotime($show_day));
$week_diff = $week_first == 7 ? 0 : $week_first;  // 差几个
if ($week_diff > 0) {
    // 填充空数组
    $schedule_list = array_pad($schedule_list, $week_diff, array('day' => ''));
}
list($h_now, $m_now) = explode(':', date('G:i'));  // 当前时分
foreach ($schedule_result as $schedule_) {
    $return_day = $schedule_['day'];  // 1日

    $day = str_pad($return_day, 2, '0', STR_PAD_LEFT);  // 日期添加 0前缀
    $timestamp = strtotime($show_date . $day);  // 日期 时间戳
    $week = date('N', $timestamp);  // 周几
    $expire = 0;
    $can_book = $schedule_['can_book']; // 能否预约
    if ($timestamp < $todaystamp) {
        $expire = 1;
        $can_book = 0;  // 过期,不能预约
    }
    $book_full = $schedule_['book_full'];  // 是否约满
    if ($book_full == 1) {
        // 约满, 不能预约
        $can_book = 0;
    }
    if ($can_book == 1) {
        if ($trial_start_time > 0 && $timestamp < $trial_start_time) {  // 邀请码 开始时间
            // 还没开始
            $can_book = 0;
        }
        if ($trial_end_time > 0 && $timestamp > $trial_end_time) {  // 邀请码 结束时间
            // 已经结束
            $can_book = 0;
        }
    }
    $this_day = date('Y-m-d', $timestamp);  // 该日
    $schedule_info = array(
        'day' => $return_day,
        'date' => $this_day,
        'expire' => $expire, // 是否过期
        'canbook' => intval($can_book),
        'show' => date('Y年m月d日', $timestamp) . '（' . $week_keymap[$week] . '）',
    );
    if ($can_book == 1) {  // 可预定
        $time_list = $schedule_['time_list'];  // 可选时间
        $timezone = array();
        foreach ($time_list as $time_) {
            $stock_num = $time_['stock'];
            if ($stock_num < 1) {  // 没有库存
                continue;
            }
            $time_name = trim($time_['time_name']);
            if (empty($time_name)) {
                continue;
            }
            if ($timestamp == $todaystamp) {  // 当前日期 当天(今天)
                list($h, $m) = explode(':', $time_name);
                if (is_numeric($h) && is_numeric($m)) {
                    if ($h < $h_now) {  // 判断 日期是否过期
                        continue;
                    } else if ($h == $h_now && $m < $m_now) {
                        continue;
                    }
                }
            }
            if ($trial_start_time > 0 || $trial_end_time > 0) {
                // 有邀请码 有效期
                if (preg_match('/^[0-9]{1,2}:[0-9]{1,2}$/', $time_name)) {
                    // 正常的时间点, 如: 10:00
                    $this_timestamp = strtotime($this_day . ' ' . $time_name . ':00');
                    if ($trial_start_time > 0 && $this_timestamp < $trial_start_time) {  // 邀请码 开始时间
                        // 还没开始
                        continue;
                    }
                    if ($trial_end_time > 0 && $this_timestamp > $trial_end_time) {  // 邀请码 结束时间
                        // 已经结束
                        continue;
                    }
                }
            }
            $timezone[] = array(
                'title' => $time_name,
                'value' => $time_['id'],
                'stock_num' => $stock_num,
            );
        }
        if(empty($timezone)){  // 
            $schedule_info['canbook'] = 0;
        }
        $schedule_info['timezone'] = $timezone;
    }
    $schedule_list[] = $schedule_info;
}
$options['data'] = array(
    'title' => '选择档期',
    'description' => '* 灰色为已约满，青色为可预约',
    'list' => $schedule_list,
);
return $cp->output($options);
