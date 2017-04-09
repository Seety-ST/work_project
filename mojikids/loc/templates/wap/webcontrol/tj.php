<?php
/**
 * @author [hudw] <[hduw@yueus.com]>
 * 2016.10.8
 * tj
 */

include_once MOJIKIDS_ROOT_URL.'common.inc.php';

function _get_wbc_tj($attribs = array())
{
	$tpl = getSmartyTemplate('tj.tpl.htm', MOJIKIDS_TEMPLATES_ROOT.'wap/webcontrol/', $caching = true, $clear_cache);

    $tpl_html = $tpl->fetch();

    return $tpl_html;
}


?>
