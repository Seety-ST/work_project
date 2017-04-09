<?php
/**
* @Desc:     商品列表      
* @User:     wlq<wulq@yueus.com>
* @Date:     2016年10月11日
* @Time:     上午9:34:45
* @Version:  
*/
include_once('common.inc.php');
$topic_obj = POCO::singleton('pai_topic_class');
$ret = $topic_obj->get_topic_info(10000050);
var_dump($ret);