<?php

/**
 * Ӧ�ÿ����Ŀ�����ļ�
 */
//Ӧ����Ŀ��������
global $my_app_mojikids;
//����Ӧ����Ŀ������Ϣ
define('G_MOJIKIDS_ROOT_PATH', realpath(dirname(__FILE__)));
define('G_MOJIKIDS_CONF_PATH', realpath(G_MOJIKIDS_ROOT_PATH . '/../../conf/mojikids'));
define('G_MOJIKIDS_DATA_PATH', realpath(G_MOJIKIDS_ROOT_PATH . '/../../data/mojikids'));
define('G_MOJIKIDS_LOGS_PATH', realpath(G_MOJIKIDS_ROOT_PATH . '/../../logs/mojikids'));
$app_mojikids_config = require(G_MOJIKIDS_CONF_PATH . '/app_config.php');
//����Ӧ�ÿ�����ļ�

require $app_mojikids_config['POCO_APP_DIR'] . '/poco.php';

//����Ӧ����Ŀ�����ʼ����
require G_MOJIKIDS_ROOT_PATH . '/include/poco_app_mojikids.inc.php';

//����Ӧ�ó��򲢷���Ӧ�ó������Ψһʵ��
$my_app_mojikids = POCO_APP_MOJIKIDS::instance($app_mojikids_config);

