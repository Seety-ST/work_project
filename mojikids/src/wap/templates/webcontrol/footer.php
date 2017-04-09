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
function _get_wbc_footer($params = array())
{
    if (!$params) {
        $params['share_open'] = ture;
    }

    $tpl = getSmartyTemplate('footer.tpl.htm', MOJIKIDS_TEMPLATES_ROOT . 'wap/webcontrol/', true,true);

    $user_agent_arr = mall_get_user_agent_arr();

    if ($user_agent_arr['is_mqq'] == 1)
    {
        $tpl->assign('is_mqq', 1);
    }

    // ======== 当前链接判断 ========
    $_PROTOCOL = 'http';
    if(!empty($_SERVER['HTTPS']))
    {
        $_PROTOCOL = 'https';
    }
    $page_url = "{$_PROTOCOL}://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
    // ======== 当前链接判断 ========

    if (MALL_UA_IS_WEIXIN == 1)
    {
        $wx_toolkit = POCO::singleton('pai_weixin_helper_class');
        $bind_id = 10;//mojikids
        $wx_json = $wx_toolkit->wx_get_js_api_sign_package($bind_id, $page_url);
        $wx_json_obj = json_encode($wx_json);
        $tpl->assign('wx_json', $wx_json);
        $tpl->assign('wx_json_obj', $wx_json_obj);
    }

    // ====== 分享字段 ======
    $title = 'MOJIKIDS莫吉照相馆';
    $content = '想把最暖心的话，用照片说给你听';
    $sina_content = $content;
    $share_url = $page_url;
    $share_url_v3 = $share_url;
    $share_img = 'http://image19.yueus.com/yueyue/20170112/20170112201325_662783_100002_13800.png?200x200_130';

    $share_text['title'] = $title;
    $share_text['content'] = $content;
    $share_text['sina_content'] = $sina_content.' '.$share_url;
    $share_text['sinacontent'] = $sina_content.' '.$share_url;//修复安卓BUG
    $share_text['remark'] = '';
    $share_text['url'] = $share_url;
    $share_text['url_v3'] = $share_url_v3;
    $share_text['img'] = $share_img;
    $share_text['user_id'] = '';
    $share_text['qrcodeurl'] = $share_url;

    $share_text = mojikids_output_format_data($share_text);
    // ====== 分享字段 ======

    $tpl->assign('share_text', $share_text);
    $tpl->assign('params', $params);
    $tpl->assign('is_weixin', MALL_UA_IS_WEIXIN);
    $tpl_html = $tpl->fetch();
    return $tpl_html;
}
?>