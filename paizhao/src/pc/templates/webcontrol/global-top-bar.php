<?php
/**
 * @authors huangst
 * @date    2016-10-20 16:30:44
 * @version $Id$
 */


include_once PAIZHAO_ROOT_URL.'common.inc.php';

function _get_wbc_global_top_bar($params=array())
{	

    $paizhao_page_url = pai_mall_paizhao_load_config('page_url');

    if(!$params)
    {
        $params['nav'] = '';
    }

    $tpl = getSmartyTemplate('global-top-bar.tpl.htm', PAIZHAO_TEMPLATES_ROOT.'pc/webcontrol/');
    $tpl->assign('paizhao_page_url',$paizhao_page_url);
    $tpl->assign('params',$params);
    $tpl_html = $tpl->fetch();
    return $tpl_html;
}


?>