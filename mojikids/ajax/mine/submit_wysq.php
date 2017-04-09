<?php
/**
 * 我要上墙接口
 * @author hudw
 */

include_once('../../fe_include/common.inc.php');

$image_ids = trim($_INPUT['image_ids']);
$ret = array();

// 提交数据
$ret = get_api_result('trade/order_operate.php',array(
    'operate' => 'uphold',
    'user_id' => $yue_login_id,
    'image_ids' => $image_ids
));
mojikids_mobile_output($ret,false);
?>
