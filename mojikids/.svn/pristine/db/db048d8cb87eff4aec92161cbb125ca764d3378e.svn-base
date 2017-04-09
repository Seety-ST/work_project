<?php
include_once('../../fe_include/common.inc.php');

$date = (int)$_INPUT["date"];
$schedule_id = (int)$_INPUT["schedule_id"];
//推广业务处理
$c_code = trim($_INPUT["c"]);//邀请码
$v_code = trim($_INPUT["v"]);//版本号
//推广业务处理
if(empty($date))
{
    $date = date("Ym",time());
}


// 获取数据
$ret = get_api_result('services/schedule_info.php',array(
    'user_id' => $yue_login_id,//用户ID
    'schedule_id' => $schedule_id,//日程ID
    'show_date' => $date,
    'trial_code'=>$c_code,
    'trial_version'=>$v_code
));


$list = $ret["data"]["list"];

//print_r($list);
//筛选出不能选
$i=0;
foreach($list as $key => $value)
{
    if(!empty($value["date"]))
    {
        if($value["canbook"] == 1)
        {
            $result_list[$i]["date"] = $value["date"];
            foreach($value["timezone"] as $k => $v)
            {
                $value["timezone"][$k]["click_cur"] = "";
            }
            $result_list[$i]["timezone"] = $value["timezone"];
            $i++;
        }
    }


}
/*print_r($result_list);
exit;*/
if(empty($result_list))
{
    $result_list = array();
}

$output_arr["choose_date"] = $date;
$output_arr["list"] = $result_list;


mojikids_mobile_output($output_arr,false);

?>