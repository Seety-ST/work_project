<?php
/**
 * ȷ���µ�
 */
define('MALL_WEIXIN_NEED_AUTHORIZE',1);//΢������Ȩ
include_once('../fe_include/common.inc.php');

// ========================= ����pc��wapģ�������ݸ�ʽ���� start  =======================
if(MALL_UA_IS_PC == 1)
{

    //****************** pc�� ******************
    include_once './index-wap.php';
}
else
{
    //****************** wap�� ******************
    include_once './index-wap.php';

}




// ========================= ����ģ�����  =======================
$tpl->display();
