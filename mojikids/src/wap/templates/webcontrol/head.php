<?php
/**
 * @author hudw <hudw@yueus.com>
 * 2016.10.8
 * 公共head
 */

include_once MOJIKIDS_ROOT_URL.'fe_include/common.inc.php';
function _get_wbc_head($params=array())
{
    $page_url_arr = include(MOJIKIDS_ROOT_URL.'config/page_url_config.inc.php');

    $tpl = getSmartyTemplate('head.tpl.htm', MOJIKIDS_TEMPLATES_ROOT.'wap/webcontrol/', $caching = false, $clear_cache);
    $tpl ->assign(" MOJIKIDS_PLACEHOLER_IMG", MOJIKIDS_PLACEHOLER_IMG);
    $tpl->assign($params);

    if($page_url_arr)
    {
        $tpl->assign('page_url_arr',$page_url_arr);
    }
    $tpl_html = $tpl->fetch();
    return $tpl_html;
}


?>
