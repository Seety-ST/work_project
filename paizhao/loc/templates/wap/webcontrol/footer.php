<?php
/**
 * @author [hudw] <[hduw@yueus.com]>
 * 2016.10.8
 * 公共footer
 */


include_once PAIZHAO_ROOT_URL.'common.inc.php';

function _get_wbc_footer($attribs = array())
{	
            $wx_json = mall_paizhao_wx_get_js_api_sign_package();	
	$tpl = getSmartyTemplate('footer.tpl.htm', PAIZHAO_TEMPLATES_ROOT.'wap/webcontrol/', $caching = true, $clear_cache);
    $tpl->assign('wx_json',$wx_json);
    $tpl_html = $tpl->fetch();

    return $tpl_html;
}


?>