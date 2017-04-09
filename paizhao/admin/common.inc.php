<?php
session_start();
define('G_SIMPLE_INPUT_CLEAN_VALUE',1);
require_once('/disk/data/htdocs232/poco/paizhao/poco_yueyue_app_common.inc.php');
require_once('/disk/data/htdocs232/poco/paizhao/poco_app_common.inc.php');
require_once('/disk/data/htdocs232/poco/pai/include/pai_mall_admin_acl_class.inc.php');
require_once('/disk/data/htdocs232/poco/pai/include/pai_mall_admin_group_class.inc.php');
require_once('/disk/data/htdocs232/poco/pai/include/pai_mall_admin_smarty_class.inc.php');
require_once('/disk/data/htdocs232/poco/pai/include/pai_mall_admin_acl_class.inc.php');
require_once('/disk/data/htdocs232/poco/pai/include/pai_mall_admin_user_class.inc.php');
require_once('/disk/data/htdocs232/poco/pai/include/pai_mall_admin_type_class.inc.php');

require_once('/disk/data/htdocs232/poco/paizhao/include/basics.fun.php');
define('TASK_DEBUG',true);
define('TASK_CHARSET','UTF-8');
define('TASK_ADMIN_USER_ID',$yue_login_id);
define('IsYueUsAdmin',true);
define('PAIZHAO_SITE_URL', '51snap.yueus.com');
$user_agent = mall_get_user_agent_arr();
if($user_agent['is_mobile']!==1)
{
	define('TASK_TEMPLATES_ROOT',"templates/pc/");
}
else
{
	define('TASK_TEMPLATES_ROOT',"templates/wap/");
}

if (empty($yue_login_id) || !isset($yue_login_id)) 
{
   	echo "<script type='text/javascript'>window.top.location.href='http://www.yueus.com/yue_admin/login_e.php?referer_url=http%3A%2F%2F".PAIZHAO_SITE_URL."%2Fadmin%2Findex.php'</script>";
    exit;
}
$action = $_INPUT['action'];
$user_permissions = mall_check_admin_permissions($yue_login_id,'default','/disk/data/htdocs232/poco/pai/yue_admin/paizhao/paizhao/',false);
//if($yue_login_id==365394)
//{
//print_r($user_permissions);
//exit;
//}
if($user_permissions['result']<1)
{
	if(mall_is_ajax())
	{
        $user_permissions['message']=iconv('gbk','utf-8',$user_permissions['message']);
		if($user_permissions['result'] == -4)
		{
			$user_permissions['message']='为了你的账户安全,请按F5刷新网页重新登录';
		}
		echo json_encode($user_permissions);
		exit;
	}
	else
	{
        if($user_permissions['result'] == -4)
		{
			header("Location:verification.php?r_url=".$user_permissions['r_url']);
			exit;
		}
		echo "<script type='text/javascript'>window.alert('".$user_permissions['message']."');</script>";
		exit;
	}
}
