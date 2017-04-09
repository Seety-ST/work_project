<?php

/**
 * ���� ����
 * 
 * @author willike <chenwb@yueus.com>
 * @since 2017-01-06
 */
define('YUE_INPUT_CHECK_TOKEN', FALSE);
require(dirname(dirname(__FILE__)) . '/moji_input.inc.php');

$schedule_id = $client_data['data']['param']['schedule_id'];
$show_date = $client_data['data']['param']['show_date'];  // ѡ�е����� ��: 201701
if (empty($schedule_id)) {
    $options['data'] = array(
        'result' => 0,
        'message' => '����IDΪ��',
    );
    return $cp->output($options);
}
if (!empty($show_date)) {
    if (!is_numeric($show_date) || strpos($show_date, '20') !== 0 || strlen($show_date) != 6) {
        $options['data'] = array(
            'result' => 0,
            'message' => '����ʱ�䲻��ȷ',
        );
        return $cp->output($options);
    }
} else {
    $show_date = date('Ym');
}
$trial_code = $client_data['data']['param']['trial_code'];  // ������
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
            'message' => '��Ч��������',
        );
        return $cp->output($options);
    }
    $trial_start_time = $invitation_code_rs['start_time'];  // ��ʼʱ��
    $trial_end_time = $invitation_code_rs['end_time'];  // ����ʱ��
}
$yueshe_schedule_obj = POCO::singleton('pai_mall_yueshe_schedule_class');
$show_day = $show_date . '01';  // ��ʾ ��������
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
        'message' => 'û�иõ�����Ϣ',
    );
    return $cp->output($options);
}
$todaystamp = strtotime(date('Y-m-d'));   // ����ʱ���
$week_keymap = array(
    1 => '��һ',
    2 => '�ܶ�',
    3 => '����',
    4 => '����',
    5 => '����',
    6 => '����',
    7 => '����',
);
$schedule_list = array();
// ����1�� ���ܼ�
$week_first = date('N', strtotime($show_day));
$week_diff = $week_first == 7 ? 0 : $week_first;  // ���
if ($week_diff > 0) {
    // ��������
    $schedule_list = array_pad($schedule_list, $week_diff, array('day' => ''));
}
list($h_now, $m_now) = explode(':', date('G:i'));  // ��ǰʱ��
foreach ($schedule_result as $schedule_) {
    $return_day = $schedule_['day'];  // 1��

    $day = str_pad($return_day, 2, '0', STR_PAD_LEFT);  // �������� 0ǰ׺
    $timestamp = strtotime($show_date . $day);  // ���� ʱ���
    $week = date('N', $timestamp);  // �ܼ�
    $expire = 0;
    $can_book = $schedule_['can_book']; // �ܷ�ԤԼ
    if ($timestamp < $todaystamp) {
        $expire = 1;
        $can_book = 0;  // ����,����ԤԼ
    }
    $book_full = $schedule_['book_full'];  // �Ƿ�Լ��
    if ($book_full == 1) {
        // Լ��, ����ԤԼ
        $can_book = 0;
    }
    if ($can_book == 1) {
        if ($trial_start_time > 0 && $timestamp < $trial_start_time) {  // ������ ��ʼʱ��
            // ��û��ʼ
            $can_book = 0;
        }
        if ($trial_end_time > 0 && $timestamp > $trial_end_time) {  // ������ ����ʱ��
            // �Ѿ�����
            $can_book = 0;
        }
    }
    $this_day = date('Y-m-d', $timestamp);  // ����
    $schedule_info = array(
        'day' => $return_day,
        'date' => $this_day,
        'expire' => $expire, // �Ƿ����
        'canbook' => intval($can_book),
        'show' => date('Y��m��d��', $timestamp) . '��' . $week_keymap[$week] . '��',
    );
    if ($can_book == 1) {  // ��Ԥ��
        $time_list = $schedule_['time_list'];  // ��ѡʱ��
        $timezone = array();
        foreach ($time_list as $time_) {
            $stock_num = $time_['stock'];
            if ($stock_num < 1) {  // û�п��
                continue;
            }
            $time_name = trim($time_['time_name']);
            if (empty($time_name)) {
                continue;
            }
            if ($timestamp == $todaystamp) {  // ��ǰ���� ����(����)
                list($h, $m) = explode(':', $time_name);
                if (is_numeric($h) && is_numeric($m)) {
                    if ($h < $h_now) {  // �ж� �����Ƿ����
                        continue;
                    } else if ($h == $h_now && $m < $m_now) {
                        continue;
                    }
                }
            }
            if ($trial_start_time > 0 || $trial_end_time > 0) {
                // �������� ��Ч��
                if (preg_match('/^[0-9]{1,2}:[0-9]{1,2}$/', $time_name)) {
                    // ������ʱ���, ��: 10:00
                    $this_timestamp = strtotime($this_day . ' ' . $time_name . ':00');
                    if ($trial_start_time > 0 && $this_timestamp < $trial_start_time) {  // ������ ��ʼʱ��
                        // ��û��ʼ
                        continue;
                    }
                    if ($trial_end_time > 0 && $this_timestamp > $trial_end_time) {  // ������ ����ʱ��
                        // �Ѿ�����
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
    'title' => 'ѡ����',
    'description' => '* ��ɫΪ��Լ������ɫΪ��ԤԼ',
    'list' => $schedule_list,
);
return $cp->output($options);