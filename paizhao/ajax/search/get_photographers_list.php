<?php
/*
 * //��ȡ��Ӱʦ�б�
 *
 * //author ����
 *
 * 2016-11-21
*/

include_once('../../common.inc.php');
$photographers = POCO::singleton('pai_paizhao_photographers_class');

$lid = (int)$_INPUT["lid"];
$pt = (int)$_INPUT["pt"];
$keyword = urldecode(trim($_INPUT["kw"]));
$sort = trim($_INPUT["sort"]);
$order = trim($_INPUT["order"]);
// ���ղ���
$page = intval($_INPUT['page']);

if(empty($page))
{
    $page = 1;
}



//Ĭ�Ͻ���
$order = $order == '1' ? $order : '2';

$submit_array  = array();
$submit_array["kw"] = $keyword;
$submit_array["sr"] = $sort;
$submit_array["o"] = $order;
$submit_array["l"] = $lid;
$submit_array["pt"] = $pt;

// ��ҳʹ�õ�page_count
$page_count = 11;

if($page > 1)
{
    $limit_start = ($page - 1)*($page_count - 1);
}
else
{
    $limit_start = ($page - 1)*$page_count;
}

$limit_str = "{$limit_start},{$page_count}";

//��ȡ����
$ret = $photographers->get_photographers_list(false,$submit_array, $limit_str,"WAP");

//���ǰ���й������һ�����ݣ�������ʵ���
$rel_page_count = 10;


//�ж��Ƿ��и���ҳ��
$has_next_page = (count($ret)>$rel_page_count);

if($has_next_page)
{
    array_pop($ret);
}

$output_arr['page'] = $page;
$output_arr['has_next_page'] = $has_next_page;
$output_arr['list'] = $ret;

paizhao_mobile_output($output_arr,false);

?>