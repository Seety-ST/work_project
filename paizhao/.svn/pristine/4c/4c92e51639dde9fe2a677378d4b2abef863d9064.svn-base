<?php
ob_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="Content-Type" content="text/html; charset=GBK">
<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript" src="js/admin.js?x"></script>
<link rel="stylesheet" type="text/css" href="css/style.css">
<title>供应商审核后台</title>

</head>
<body style="background:#E2E9EA;overflow:hidden;">
<!--header-->
<div id="header" class="header">
	<div class="logo">
		<a href="http://www.yueus.com" target="_blank"><img src="images/admin_logo.gif" width="180"></a>
	</div>
	
	<div class="nav"><?php echo G_PHP_COMMON_ENVIRONMENT_TYPE=="dev"?"<font color='#ff0000'>开发机</font>":(G_PHP_COMMON_ENVIRONMENT_TYPE=="test"?"<font color='#f2ce18'>测试机</font>":"")?>&nbsp;&nbsp;&nbsp;&nbsp;欢迎你！<?php echo mall_get_admin_nickname_by_user_id($yue_login_id);?>[<?=$user_permissions['data']['type_name']?>] &nbsp;&nbsp;( <?=$yue_login_id?> )&nbsp;&nbsp;<a href='logout.php'>退出系统</a> <!--[<?=session_id();?>]-->[<?=mall_get_ip();?>]</div>
	<div class="topmenu" id="top_tag">
		<ul>
        <?php
        foreach($list as $key => $val)
		{
                        if($val['is_show'] == 1)
                        {
                            echo '<li><span id="top_menu_'.$val['index_key'].'" '.($key==0?'class="current"':'').' role-data="'.$val['index_key'].'"><a href="#this">'.$val['name'].'</a></span></li>';
                        }
			
		}
		?>
		</ul>
	</div>
	<div class="header_footer"></div>
</div>
<script>
	switch_tab2('top_tag','top_menu_','nav_','current',<?=count($list)?>);
	$(function() {
		$(".nav_info").find("a").click(function () {
			$(".nav_info div").removeClass('on');
			$(".nav_info span").removeClass('on');
			$(this).parent().addClass('on');
		});
	});
</script>
<!--header-->
<?php
$_POCO_STAT_YUE_ADMIN_REPORT_HEADER = ob_get_contents();
ob_end_clean();
?>