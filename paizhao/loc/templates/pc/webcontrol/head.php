<?php
/**
 * @author hudw <hudw@yueus.com>
 * 2016.10.8
 * 公共head
 */

include_once PAIZHAO_ROOT_URL.'common.inc.php';

function _get_wbc_head($params=array())
{	

	$tpl = getSmartyTemplate('head.tpl.htm', PAIZHAO_TEMPLATES_ROOT.'pc/webcontrol/', $caching = true, $clear_cache);
	$tpl ->assign("PAIZHAO_PLACEHOLER_IMG",PAIZHAO_PLACEHOLER_IMG);
	$tpl_html = $tpl->fetch();

	return $tpl_html;
}


?>