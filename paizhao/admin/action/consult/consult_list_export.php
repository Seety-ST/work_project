<?php
require(dirname(dirname(__FILE__)).'/common.inc.php');
set_time_limit(0);
$consult_obj = POCO::singleton('pai_paizhao_consult_class');
$where = '1';
$start_time = $_INPUT['start_time'];
if ($start_time)
{
    $s_time = strtotime($start_time);
    $where .= " AND c.`create_time` > {$s_time}";
}
$end_time = $_INPUT['end_time'];
if ($end_time)
{
    $e_time = strtotime($end_time) + 86400;
    $where .= " AND c.`create_time` < {$e_time}";
}
$mobile = $_INPUT['mobile'];
if ($mobile && is_numeric($mobile))
{
    $where .= " AND c.`mobile` LIKE '%{$mobile}%'";
}
$p_where = '';
$photographers = $_INPUT['photographers'];
if ($photographers)
{
    $p_where = '(p.user_id='.(int)$photographers . " OR p.seller_name LIKE '%{$photographers}%')";
    $where .= ' AND (c.user_id=' . (int)$photographers . " OR seller_name LIKE '%{$photographers}%')";
}
$order_id = $_INPUT['order_id'];
if ($order_id)
{
    $order_id = (int)$order_id;
    $where .= " AND c.`order_id` LIKE '%{$order_id}%'";
}
$status = $_INPUT['status'];
if ($status)
{
    $status = (int)$status;
    $where .= " AND c.`status_id` = {$status}";
}
$work_start_time = $_INPUT['work_start_time'];
if ($work_start_time)
{
    $work_s_time = strtotime($work_start_time);
    $where .= " AND c.`work_time` > {$work_s_time}";
}
$work_end_time = $_INPUT['work_end_time'];
if ($work_end_time)
{
    $work_e_time = strtotime($work_end_time) + 86400;
    $where .= " AND c.`work_time` < {$work_e_time}";
}

$status_list = $consult_obj->get_consult_status();
$status_arr = array();
foreach ($status_list as $k=>$v)
{
    $status_arr[$v['id']] = $v['status_desc'];
}

$page = $_INPUT['page'] ? (int)$_INPUT['page'] : 1;
$per_page = 10000;

$filename = '咨询列表_' . date('Y_m_d_h_i_s');
header("Content-type: application/vnd.ms-excel; charset=gbk");
header("Content-type:text/csv");
header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
header('Expires:0');
header('Pragma:public');
header("Content-Disposition: attachment; filename=$filename.xls");

$headArr =array(
                '序号',
                '咨询时间',
                '用户姓名',
                '用户联系方式',
                '咨询摄影师',
                '咨询套系',
                '价格',
                '跟进状态',
                '订单号',
                '拍摄时间',
                '拍摄地址',
                '备注',
                );

//表头
$data ='';
$data .= "<table border='1'>";
$data .= "<tr>";
foreach ($headArr as $key => $val)
{
    $data .= "<th>" . iconv("GB2312", "utf-8", $val) . "</th>";
}
$data .= "</tr>";

while(true)
{
    $start_num = ($page-1)*$per_page;
    $list = $consult_obj->get_list(false, $where, $p_where, "$start_num,$per_page");
    if( ! $list )
    {
        break;
    }
    foreach($list as $key => $val)
    {
        $val['create_time'] = date("Y-m-d H:i:s",$val['create_time']);
        $val['status_desc'] = $status_arr[$val['status_id']];
        $val['work_time'] = $val['work_time'] ? date("Y-m-d",$val['work_time']) : '';

        $data .= "<tr>";
            $data.="<td>".$val['id']."</td>";
            $data.="<td>".$val['create_time']."</td>";
            $data.="<td>".iconv("GB2312", "utf-8", $val['name'])."</td>";
            $data.="<td>".$val['mobile']."</td>";
            $data.="<td>".iconv("GB2312", "utf-8", $val['seller_name'])."</td>";
            $data.="<td>".iconv("GB2312", "utf-8", $val['package_info'])."</td>";
            $data.="<td>".$val['price']."</td>";
            $data.="<td>".iconv("GB2312", "utf-8", $val['status_desc'])."</td>";
            $data.="<td>".$val['order_id']."</td>";
            $data.="<td>".$val['work_time']."</td>";
            $data.="<td>".iconv("GB2312", "utf-8", $val['address'])."</td>";
            $data.="<td>".iconv("GB2312", "utf-8", $val['remark'])."</td>";
        $data .= "</tr>";
        
    }
    
    echo $data . "\t";
    unset($data);
    $page++;
}

exit;
?>