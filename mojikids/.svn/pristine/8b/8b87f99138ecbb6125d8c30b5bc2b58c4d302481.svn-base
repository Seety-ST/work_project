<?php

/**
 * 应用框架项目公共文件
 */
//应用项目对象声明
global $my_app_mojikids;
//引入应用项目配置信息
define('G_MOJIKIDS_ROOT_PATH', realpath(dirname(__FILE__)));
define('G_MOJIKIDS_CONF_PATH', realpath(G_MOJIKIDS_ROOT_PATH . '/../../conf/mojikids'));
define('G_MOJIKIDS_DATA_PATH', realpath(G_MOJIKIDS_ROOT_PATH . '/../../data/mojikids'));
define('G_MOJIKIDS_LOGS_PATH', realpath(G_MOJIKIDS_ROOT_PATH . '/../../logs/mojikids'));
$app_mojikids_config = require(G_MOJIKIDS_CONF_PATH . '/app_config.php');
//引入应用框架主文件

require $app_mojikids_config['POCO_APP_DIR'] . '/poco.php';

//引入应用项目程序初始化类
require G_MOJIKIDS_ROOT_PATH . '/include/poco_app_mojikids.inc.php';

//启动应用程序并返回应用程序对象唯一实例
$my_app_mojikids = POCO_APP_MOJIKIDS::instance($app_mojikids_config);

