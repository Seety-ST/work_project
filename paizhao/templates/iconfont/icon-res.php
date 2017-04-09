<?php
/**
 * @author hudw <hudw@yueus.com>
 * 2016.10.8
 * 公共head
 */

include_once PAIZHAO_ROOT_URL.'common.inc.php';
function _get_icon_font($params=array())
{	

	$tpl = getSmartyTemplate('icon-res.tpl.htm', PAIZHAO_TEMPLATES_ROOT.'iconfont/', $caching = true, $clear_cache);
	$tpl_html = $tpl->fetch();

	return $tpl_html;
}


?>