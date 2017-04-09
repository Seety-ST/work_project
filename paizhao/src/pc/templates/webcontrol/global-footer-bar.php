<?php
/**
 * @authors huangst
 * @date    2016-10-20 16:30:44
 * @version $Id$
 */


include_once PAIZHAO_ROOT_URL.'common.inc.php';

function _get_wbc_footer_bar()
{		
    $tpl = getSmartyTemplate('global-footer-bar.tpl.htm', PAIZHAO_TEMPLATES_ROOT.'pc/webcontrol/');
    $current_year = date('Y');
    $paizhao_page_url = pai_mall_paizhao_load_config('page_url');
    $tpl->assign('paizhao_page_url',$paizhao_page_url);
    $tpl ->assign("current_year",$current_year);
    $tpl_html = $tpl->fetch();
    return $tpl_html;
}


?>