<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
// +----------------------------------------------------------------------+
// | PHP version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 1997-2004 The PHP Group                                |
// +----------------------------------------------------------------------+
// | This source file is subject to version 3.0 of the PHP license,       |
// | that is bundled with this package in the file LICENSE, and is        |
// | available through the world-wide-web at the following url:           |
// | http://www.php.net/license/3_0.txt.                                  |
// | If you did not receive a copy of the PHP license and are unable to   |
// | obtain it through the world-wide-web, please send a note to          |
// | license@php.net so we can mail you a copy immediately.               |
// +----------------------------------------------------------------------+
// | Authors: Original Author <author@example.com>                        |
// |          Your Name <you@example.com>                                 |
// +----------------------------------------------------------------------+
//
// $Id:$
/**
 * @author [hudw] <[hduw@yueus.com]>
 * 2016.10.8
 * footer
 */
include_once MOJIKIDS_ROOT_URL . 'common.inc.php';
function _get_wbc_global_footer_bar($params = array())
{
    
    $tpl = getSmartyTemplate('global_footer_bar.tpl.htm', MOJIKIDS_TEMPLATES_ROOT . 'wap/webcontrol/', true,true);
    
    $tpl_html = $tpl->fetch();
    return $tpl_html;
}
?>
