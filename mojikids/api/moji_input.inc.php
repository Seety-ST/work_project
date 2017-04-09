<?php

/**
 * ����Э���ļ� ( ��� )
 *
 * @author willike <chenwb@yueus.com>
 * @since 2015-8-5
 * @description ����Э�鴦������(include), �� ���ݱ��� ����Ϊ $post_data
 */
// ���� �Ƿ�����Э��
if (!defined('YUE_INVOCATION_PROTOCOL')) {
    define('YUE_INVOCATION_PROTOCOL', TRUE); // ����Э��
}
// �����Ƿ� ��֤access_token
if (!defined('YUE_INPUT_CHECK_TOKEN')) {
    define('YUE_INPUT_CHECK_TOKEN', TRUE);
}
// ����ȫ��DB��
//if (!isset($DB) || empty($DB)) {
//    global $DB;
//}
// ����Э��
$yue_protocol_path = str_replace('\\', '/', dirname(dirname(__FILE__))) . '/protocol/';
require($yue_protocol_path . 'moji_protocol.inc.php');
// ��ȡ�ͻ��˵�����
$cp = new yue_protocol_system();
// �ж��Ƿ� ʹ��Э��
if (YUE_INVOCATION_PROTOCOL === FALSE) {
    // ʹ�� include ���� ( for web )
    if (empty($post_data)) {
        $options['code'] = 204;
        return $cp->output($options);
    }
    $client_data = $cp->get_input_process($post_data, YUE_INPUT_CHECK_TOKEN, FALSE);
    if (!is_array($client_data)) {
        $client_data = strpos($client_data, '{"') === 0 ? json_decode($client_data, TRUE) : array();
    }
} else {
    // �������� ( for APP )
    $client_data = $cp->get_input(array('be_check_token' => YUE_INPUT_CHECK_TOKEN));
}
// ���� ���
require_once('/disk/data/htdocs232/poco/pai/poco_app_common.inc.php');
// ���� ��������
require_once('/disk/data/htdocs232/poco/pai/yue_admin/task/include/basics.fun.php');
// ���빫������
require_once('moji_interface.func.php');

// �ͻ������� ios/android/web
$os_type = $client_data['data']['os_type'];
// $version �汾�� $channel ������
$version_str = $client_data['data']['version'];  // �汾��
list($version, $channel) = explode('_', $version_str);
// λ��ID
$location_id = trim($client_data['data']['param']['location_id']);
// ͳһ���� �û�ID
$user_id = trim($client_data['data']['param']['user_id']);
if (!is_numeric($user_id)) {
    $user_id = 0;
}
if (empty($user_id) && YUE_INPUT_CHECK_TOKEN === TRUE) {
    $options['data'] = array(
        'result' => -100,
        'message' => '���ȵ�¼',
    );
    return $cp->output($options);
}
// Ŀ�����
$target_id = trim($client_data['data']['param']['target_id']);
$target_id = empty($target_id) ? $user_id : $target_id;

// ��������
$limit = trim($client_data['data']['param']['limit']);  // ��ֵ��: 0,20
if (empty($limit) || !preg_match('/^\d+,{1}\d+$/', $limit)) {
    $page = intval($client_data['data']['param']['page']);  // �ڼ�ҳ
    $rows = intval($client_data['data']['param']['rows']); // ÿҳ��������(5-100֮��)

    $page = $page < 1 ? 1 : $page;
    $lcount = $rows < 5 ? 5 : ($rows > 100 ? 100 : $rows);
    $lstart = ($page - 1) * $lcount;  // ��ʼλ��
    $limit_str = $lstart . ',' . $lcount;
} else {
    list($lstart, $lcount) = explode(',', $limit);
    $lstart = $lstart < 0 ? 0 : $lstart;
    $lcount = $lcount > 100 ? 100 : ($lcount < 1 ? 1 : $lcount);
    $limit_str = $lstart . ',' . $lcount;
}
// ����ƽ̨
$request_platform = $client_data['data']['param']['request_platform'];
if (!empty($request_platform)) {
    if (!in_array($request_platform, array('weixin', 'pc', 'weixin_app', 'app', 'api', 'web'))) {
        $options['data'] = array(
            'result' => 0,
            'message' => 'request platform invaild!',
        );
        return $cp->output($options);
    }
} else {
    $request_platform = 'app';
}