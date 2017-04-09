<?php
include_once('../../fe_include/common.inc.php');




$user_id = trim($_INPUT["user_id"]);
$content = trim($_INPUT["content"]);

$ret = get_api_result('user/feedback.php',array(
        'user_id'=>$user_id,
        'content' => $content,
));





mojikids_mobile_output($ret,false);



?>