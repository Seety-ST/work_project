<?php
/**
* @Desc:     商品详情      
* @User:     wlq<wulq@yueus.com>
* @Date:     2016年10月11日
* @Time:     上午9:35:24
* @Version:  
*/
// error_reporting(-1);
header("Content-type: text/html; charset=utf-8");
include_once('../common.inc.php');



// ========================= 区分pc，wap模板与数据格式整理 start  =======================
if(MALL_UA_IS_PC == 1)
{

    //****************** pc版 ******************
    include_once './goods_detail-pc.php';
}
else
{
    //****************** wap版 ******************
    include_once './goods_detail-wap.php';

}


$tpl->display();

// ========================= 最终模板输出  =======================



$goods_class = POCO::singleton('pai_paizhao_goods_class');
// $goods_info = $goods_class->get_goods_info(2150627);
// var_dump(unserialize($goods_info['goods_info']['prices_list']));
//var_dump($goods_class->add_consult_num(66, 'add_num', 5));
// $consult_class = POCO::singleton('pai_paizhao_consult_class');
// var_dump($consult_class);
// $data = array(
//     'name' => 'wlq',
//     'mobile' => '13750509651',
//     'goods_id' => '1',
//     'user_id' => '123',
//     'seller_id' => '12',
//     'photographers' => 'yt',
//     'package_info' => 'all',
//     'consult_type' => 'goods',
//     'source' => 'PC'
// );
// $result = $consult_class->add_consult($data);
// $result1 = $consult_class->add_consult_button($data);
// var_dump($result, $result1);
// $data = array(
//     'name' => $data['name'],
//     'mobile' => $data['mobile'],
//     'seller_id' => $data['seller_id'],
//     'user_id' => $data['user_id'],
//     'photographers' => $data['photographers'],
//     'package_info' => $data['package_info'],
//     'goods_id' => $data['goods_id'],
//     'source' => $data['source'] == 'PC' ? 'PC' : 'WAP',
//     'consult_type' => $data['consult_type'] == 'goods' ? 'goods' : 'seller',
//     'create_time' => $time,
//     'update_time' => $time,
//     'group_time' => strtotime('today'),
// );