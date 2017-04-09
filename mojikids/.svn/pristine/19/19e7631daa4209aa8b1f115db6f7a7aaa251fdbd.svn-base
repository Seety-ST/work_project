<?php
/**
 * DEMO
 */

include_once('../fe_include/common.inc.php');

mojikids_check_permissions(array('user_id'=>$yue_login_id));

// ========================= 区分pc，wap模板与数据格式整理 start  =======================
if(MALL_UA_IS_PC == 1)
{

    //****************** pc版 ******************
    include_once './photos-wap.php';
}
else
{
    //****************** wap版 ******************
    include_once './photos-wap.php';

}




// ========================= 最终模板输出  =======================
$tpl->display();
