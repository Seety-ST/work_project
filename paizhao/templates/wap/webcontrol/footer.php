<?php
/**
 * @author [hudw] <[hduw@yueus.com]>
 * 2016.10.8
 * 公共footer
 */


include_once PAIZHAO_ROOT_URL.'common.inc.php';

function _get_wbc_footer($params=array())
{	
       if (!$params) {
          $params['share_open'] = ture;
       }
    
	$tpl = getSmartyTemplate('footer.tpl.htm', PAIZHAO_TEMPLATES_ROOT.'wap/webcontrol/',false, $clear_cache);
      $user_agent_arr = mall_get_user_agent_arr();
      if($user_agent_arr['is_mqq'] == 1)
      {
          $tpl->assign('is_mqq',1);
      }
     $tpl->assign('params',$params);
     if (MALL_UA_IS_WEIXIN) {
       $wx_json = mall_paizhao_wx_get_js_api_sign_package();

       $tpl->assign('wx_json',$wx_json);
     }
     $tpl->assign('is_weixin',MALL_UA_IS_WEIXIN);
     $tpl_html = $tpl->fetch();

    return $tpl_html;
}


?>