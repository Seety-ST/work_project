<?php
/**
 * @author [hudw] <[hduw@yueus.com]>
 * 2016.10.8
 * 公共footer
 */


include_once PAIZHAO_ROOT_URL.'common.inc.php';

function _get_wbc_footer($attribs = array())
{		
	$tpl = getSmartyTemplate('footer.tpl.htm', PAIZHAO_TEMPLATES_ROOT.'pc/webcontrol/', $caching = true, $clear_cache);
    
    $tpl_html = $tpl->fetch();

    return $tpl_html;
}


?>