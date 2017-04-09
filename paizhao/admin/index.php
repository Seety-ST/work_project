<?php
require('common.inc.php');
$list = mall_get_admin_menu_v2();
$f_index = '';
$i = 0;
foreach($list as $key => $val)
{
	//$list[$key]['index_key'] = $key+1;
	if($val['is_show'] == 1)
	{
		$list[$key]['index_key'] = $i+1;
		$i++;
	}	
	if(!$f_index)
	{
            $f_index = $val['list'][0]['children_list'][0]['url']?$val['list'][0]['children_list'][0]['url']:$val['list'][0]['url'];
	}       
}
include('top.php');

$tpl = new SmartTemplate ( TASK_TEMPLATES_ROOT."index.tpl.htm" );


$tpl->assign ( 'YUE_ADMIN_TOP', $_POCO_STAT_YUE_ADMIN_REPORT_HEADER );
$tpl->assign ( 'list', $list );
$tpl->assign ( 'f_index', $f_index );
$tpl->output ();
?>