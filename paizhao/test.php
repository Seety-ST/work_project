<?php
/**
* @Desc:     商品列表      
* @User:     wlq<wulq@yueus.com>
* @Date:     2016年10月11日
* @Time:     上午9:34:45
* @Version:  
*/
include_once('common.inc.php');
$goods_class = POCO::singleton('pai_paizhao_goods_class');
$type = $_INPUT['type'];
$data = $goods_class->get_index_data($type);
var_dump($data);