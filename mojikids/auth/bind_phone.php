<?php
/**
 * DEMO
 */

include_once('../fe_include/common.inc.php');

// ========================= 区分pc，wap模板与数据格式整理 start  =======================
if(MALL_UA_IS_PC == 1)
{

    //****************** pc版 ******************
    include_once './bind-phone-wap.php';
}
else
{
    //****************** wap版 ******************
    include_once './bind-phone-wap.php';

}




// ========================= 最终模板输出  =======================
$tpl->display();
