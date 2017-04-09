<?php
/**
* @Desc:     首页      
* @User:     wlq<wulq@yueus.com>
* @Date:     2016年10月10日
* @Time:     下午4:17:37
* @Version:  
*/
include_once('../common.inc.php');

$id = trim($_INPUT['topic_id'])?trim($_INPUT['topic_id']):'10000050';



$topic_obj = POCO::singleton('pai_topic_class');
$re = $topic_obj->get_topic_info($id);
$ret = array();

$ret['content'] = mb_convert_encoding($re['content'], 'UTF-8','GBK');
$ret['title'] = mb_convert_encoding($re['title'], 'UTF-8','GBK');

if ($_INPUT['print']) {
    print_r($ret);
}
// 获取公共链接
$paizhao_page_url = pai_mall_paizhao_load_config('page_url');

// ========================= 区分pc，wap模板与数据格式整理 start  =======================
if(MALL_UA_IS_PC == 1)
{

	//****************** pc版 ******************
	include_once './index-pc.php';
}
else
{
	//****************** wap版 ******************
	include_once './index-wap.php';

}
$tpl->assign('ret',$ret);
$tpl->assign('paizhao_page_url',$paizhao_page_url);
$tpl->display();
// ========================= 最终模板输出  =======================