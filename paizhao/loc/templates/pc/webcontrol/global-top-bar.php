<?php
/**
 * @authors huangst
 * @date    2016-10-20 16:30:44
 * @version $Id$
 */


include_once PAIZHAO_ROOT_URL.'common.inc.php';

function _get_wbc_global_top_bar()
{		
    $tpl = getSmartyTemplate('global-top-bar.tpl.htm', PAIZHAO_TEMPLATES_ROOT.'pc/webcontrol/');
    $tpl_html = $tpl->fetch();
    return $tpl_html;
}


?>