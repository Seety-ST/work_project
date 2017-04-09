<?php

/**
 * 应用框架项目公共文件
 */
//应用项目对象声明
global $my_app_paizhao;
//引入应用项目配置信息
define('G_PAIZHAO_ROOT_PATH', realpath(dirname(__FILE__)));
define('G_PAIZHAO_CONF_PATH', realpath(G_PAIZHAO_ROOT_PATH . '/../../conf/paizhao'));
define('G_PAIZHAO_DATA_PATH', realpath(G_PAIZHAO_ROOT_PATH . '/../../data/paizhao'));
define('G_PAIZHAO_LOGS_PATH', realpath(G_PAIZHAO_ROOT_PATH . '/../../logs/paizhao'));
$app_paizhao_config = require(G_PAIZHAO_CONF_PATH . '/app_config.php');
//引入应用框架主文件
require $app_paizhao_config['POCO_APP_DIR'] . '/poco.php';
//引入应用项目程序初始化类
require G_PAIZHAO_ROOT_PATH . '/include/poco_app_paizhao.inc.php';
//启动应用程序并返回应用程序对象唯一实例
$my_app_paizhao = POCO_APP_PAIZHAO::instance($app_paizhao_config);

if(!function_exists("pai_mall_load_config"))
{
	function pai_mall_load_config($name)//获取配置文件
	{
		$return =array();
		$file = '/disk/data/htdocs232/poco/pai/yue_admin/task/config/'.$name.'_config.php';
		if(is_file($file))
		{
			$return = include($file);
		}
		return $return;
	}
}

if(!function_exists("pai_mall_paizhao_load_config"))
{
	function pai_mall_paizhao_load_config($name)//获取配置文件
	{
		$return =array();
		$file = '/disk/data/htdocs232/poco/paizhao/config/'.$name.'_config.php';
		if(is_file($file))
		{
			$return = include($file);
		}
		return $return;
	}
}


/**
 * 返回Smarty模板对象
 * @param string $template_file
 * @param string $template_dir
 * @return object
 */
function getSmartyTemplate($template_file, $template_dir='', $caching = false, $clear_cache = false)
{
    $smarty = new Smarty();
    $template_dir = trim($template_dir);
    if( strlen($template_dir)>0 ) $smarty->setTemplateDir($template_dir);
    $smarty->setCompileDir('./_smarty_templates_c/');
    $smarty->setCompileCheck(true);
    $smarty->setForceCompile(false);
    $smarty->setCacheDir('./_smarty_cache/');
    if ($caching)
    {
        $smarty->setCaching(Smarty::CACHING_LIFETIME_CURRENT);
        $smarty->setCacheLifetime(3600);
    }
    else if(!$caching)
    {
        $smarty->setCacheLifetime(0);
    }
    if ($clear_cache)
    {
        $smarty->clearAllCache();
    }
    $smarty->setLeftDelimiter('{%');
    $smarty->setRightDelimiter('%}');
    $smarty->setDebugging(false);
    return $smarty->createTemplate($template_file);
}

/**
 * 获取微信签名
 * @return unknown
 */
function mall_paizhao_wx_get_js_api_sign_package()
{
    $app_id = 'wxb93f7fe974be1cc2';	//约摄服务号
    // ======== 当前链接判断 ========
    $_PROTOCOL = 'http';
    if(!empty($_SERVER['HTTPS']))
    {
        $_PROTOCOL = 'https';
    }
    $url = "{$_PROTOCOL}://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";

    $weixin_helper_obj = POCO::singleton('pai_weixin_helper_class');
    $ret = $weixin_helper_obj->wx_get_js_api_sign_package_by_app_id($app_id, $url);
    return $ret;
}
