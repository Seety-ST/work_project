<?php
/**
 * @author zhangrz <zhangrz@yueus.com>
 * 2016.10.27
 * ������Ʒ�б��������̼��б�����
 */

include_once PAIZHAO_ROOT_URL.'common.inc.php';

//��ȡ�б�
/*
 * @param
 * array $data_list //��������
 * str $list_type //�������ͣ�Ŀǰ֧��goods_list,photographers_list����Ʒ�б����̼��б��������ö�Ӧģ��
 *
*/

function _get_wbc_data_list($list_data,$list_type="goods_list")
{
    /*print_r($list_data);
        echo $list_type;*/


    $tpl = getSmartyTemplate($list_type.'_control.tpl.htm', PAIZHAO_TEMPLATES_ROOT.'pc/webcontrol/', $caching = false, $clear_cache);
    $tpl ->assign("list_data",$list_data);
    $tpl_html = $tpl->fetch();

    return $tpl_html;
}


?>