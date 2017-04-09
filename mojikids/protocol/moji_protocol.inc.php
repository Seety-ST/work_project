<?php

/**
 * ԼԼ ͨ�� Э��
 *
 * @author willike <chenwb@yueus.com>
 * @since 2015-7-29
 * @version 1.0 Beta
 */
// ����Э���Ŀ¼
define('YUE_PROTOCOL_ROOT', str_replace('\\', '/', dirname(__FILE__)) . '/');

// Э�鴦��ʼʱ�� ( ȫ�� )
$GLOBALS['mojikids_protocol_start_time'] = microtime(TRUE);

// ���� ͨ�÷���
require_once YUE_PROTOCOL_ROOT . 'common/function.php';

$protocol_version = protocol_api_conf('PROTOCOL_VERSION', 'config');   // Э��汾
// �ļ��б�
$protocol_file_list = array(
    'yue_protocol_request.cls.php',
    'yue_protocol_response.cls.php',
    'yue_protocol_oauth.cls.php',
    'yue_protocol_log.cls.php',
    'yue_protocol_cache.cls.php',
    'yue_protocol_system.cls.php',
);
foreach ($protocol_file_list as $file_) {
    $protocol_file = YUE_PROTOCOL_ROOT . 'include/' . $protocol_version . '/' . $file_;
    if (file_exists($protocol_file)) {
        require_once($protocol_file);
    }
}
