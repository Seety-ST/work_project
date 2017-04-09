<?php
/**
* @Desc:     咨询操作类      
* @User:     wlq<wulq@yueus.com>
* @Date:     2016年10月13日
* @Time:     上午9:59:58
* @Version:  
*/
$id = $_POST['id'];
$status_id = $_POST['status_id'];
$consult_obj = POCO::singleton('pai_paizhao_consult_class');
if ($id && ($status_id == 5 || $status_id==6 || $status_id==2 || $status_id==3))
{
    $remark = $_POST['remark'];
    if (!$remark)
    {
        echo json_encode(array('result'=>-1,'message'=>'信息请填写完整!'));
        exit;
    }
    $data = array(
        'remark' => iconv('utf-8', 'gbk', $remark),
    );
}
else if ($id && $status_id == 4)
{
    $price = (float)$_POST['price'];
    $address = $_POST['address'];
    $work_time = strtotime($_POST['work_time']);
    $order_id = (int)$_POST['order_id'];
    if (!$price || !$address || !$work_time || !$order_id)
    {
        echo json_encode(array('result'=>-1,'message'=>'信息请填写完整!'));
        exit;
    }
    $data = array(
        'work_time' => $work_time,
        'price' => $price,
        'address' => iconv('utf-8', 'gbk', $address),
        'order_id' => $order_id,
    );
}
else if ($id && !$status_id)
{
    $consult = $consult_obj->get_consult($id);
    if ($consult)
    {
        $consult['work_time'] = $consult['work_time'] ? date('Y-m-d', $consult['work_time']) : '';
    }
    echo $consult ? json_encode(array('result'=>1, 'data'=>$consult)) : json_encode(array('result'=>-1));
    exit;
}
else
{
    exit;
}
$data['update_time'] = time();
$data['status_id'] = $status_id;
$update = $consult_obj->update_consult($data, $id);
echo $update ? json_encode(array('result'=>1, 'message'=>'更新成功!')) : json_encode(array('result'=>2, 'message'=>'更新失败!'));
exit;