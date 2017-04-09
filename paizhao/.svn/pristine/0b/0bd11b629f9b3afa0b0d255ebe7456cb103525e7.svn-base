<?php
/**
* @Desc:     首页      
* @User:     wlq<wulq@yueus.com>
* @Date:     2016年10月10日
* @Time:     下午4:17:37
* @Version:  
*/
include_once('common.inc.php');
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
$tpl->assign('paizhao_page_url',$paizhao_page_url);
$tpl->display();
// ========================= 最终模板输出  =======================