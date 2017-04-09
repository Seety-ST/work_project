<?php
/**
 * 档期选择
 */
define('MALL_WEIXIN_NEED_AUTHORIZE',1);//微信则授权
include_once('../fe_include/common.inc.php');

// ========================= 区分pc，wap模板与数据格式整理 start  =======================
if(MALL_UA_IS_PC == 1)
{

    //****************** pc版 ******************
    include_once './date-wap.php';
}
else
{
    //****************** wap版 ******************
    include_once './date-wap.php';

}




// ========================= 最终模板输出  =======================
$tpl->display();
