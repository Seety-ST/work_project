<?php
/**
 * @Desc:     ��ҳ
 * @User:     zrz<zhangrz@yueus.com>
 * @Date:     2016��10��24��
 * @Time:
 * @Version:
 */
include_once('../common.inc.php');

// ========================= ����pc��wapģ�������ݸ�ʽ���� start  =======================
if(MALL_UA_IS_PC == 1)
{

    //****************** pc�� ******************
    include_once './index-pc.php';
}
else
{
    //****************** wap�� ******************
    include_once './index-wap.php';

}
$tpl->display();
// ========================= ����ģ�����  =======================