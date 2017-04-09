<?php
/**
* @Desc:     咨询列表      
* @User:     wlq<wulq@yueus.com>
* @Date:     2016年10月12日
* @Time:     上午9:38:30
* @Version:  
*/
//error_reporting(E_ALL ^ E_NOTICE);
require(dirname(dirname(__FILE__)).'/common.inc.php');
$consult_obj = POCO::singleton('pai_paizhao_consult_class');
var_dump($consult_obj->send_message());