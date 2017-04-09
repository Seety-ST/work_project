<?php
/**
* @Desc:     作为ajax的请求文件      
* @User:     wlq<wulq@yueus.com>
* @Date:     2016年10月11日
* @Time:     下午2:26:31
* @Version:  
*/
include_once ('/disk/data/htdocs232/poco/pai/yue_admin/cms/cms_common.inc.php');
$obj = new cms_system_class();
$rank_names = array(
    1985 => 'banner',
    1986 => 'package_type',
    1987 => 'custom_recommend',
    1988 => 'organization_1',
    1989 => 'organization_2',
    1990 => 'organization_3',
    1991 => 'photographers',
    1992 => 'hot_goods',
    1993 => 'footer',
);
$rank_ids_config = range(1985, 1993);
$rank_ids = $_INPUT['rank_ids'] ? $_INPUT['rank_ids'] : $rank_ids_config;
$limit = $_INPUT['limit'] ? $_INPUT['limit'] : '0,200';
$list = $obj ->get_last_issue_record_list(false, $limit, 'place_number ASC', $rank_ids, '');
if ($list)
{
    $result = array();
    foreach ($list as $k=>$v)
    {
        foreach ($v as $m=>$n)
        {
            $v[$m] = iconv('gbk', 'utf-8', $n);
        }
        $result[$rank_names[$v['rank_id']]][] = $v;
    }
echo json_encode($result);
exit;
}