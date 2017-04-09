<?php
include_once ('/disk/data/htdocs232/poco/pai/yue_admin/audit/include/Classes/PHPExcel.php');
require('common.inc.php');
switch($action)
{
    case "goods_auto_list":
	     mall_load_action('goods_list');
         exit;
    break;
    default:
        mall_load_action($action);
        exit;
	break;
}
?>