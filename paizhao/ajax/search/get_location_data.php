<?php
/*
 * //异步获取地区数据
 *
 *
 * */

include_once('../../common.inc.php');
include('/disk/data/htdocs232/poco/pai/action/area_v2.conf.php');

//公共地址，两层数据，省市
$location_arr['version'] = "1.0.2";//自定义版本号，如果地区数据有改动，调整版本号-2016-11-28
$location_arr['province'] = $area['province'];
$location_arr['city'] = $area['city'];
paizhao_mobile_output($location_arr,false);

?>