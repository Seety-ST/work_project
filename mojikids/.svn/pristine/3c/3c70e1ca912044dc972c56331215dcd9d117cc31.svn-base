<?php

include_once('../../fe_include/common.inc.php');

$user_id = strval($_INPUT['yue_login_id']);
$schedule_id = intval($_INPUT['schedule_id']);
$show_date = strval($_INPUT['show_date']);
if(strlen($show_date) == 5){
	$show_date = substr_replace($show_date,'0',4,0);
}
$ret = get_api_result('services/schedule_info.php',array(
    'user_id' => $user_id,
	'schedule_id' => $schedule_id,
    'show_date' => $show_date 
));

mojikids_mobile_output($ret,false);
?>




