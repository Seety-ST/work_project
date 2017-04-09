<?
if(!function_exists("mall_simple_filter"))
{
	function mall_simple_filter($str,$server_id=101)
	{
		if (get_magic_quotes_gpc())
		{
			$str = stripslashes($str);
		}
		if (!is_numeric($str))
		{
			$server_id = $server_id*1;
			if( defined("G_DB_GET_REALTIME_DATA") )
			{
				if( $server_id==0 ) $server_id = 1;
			}
			if( $server_id==1 )
			{
				$analyse = 0;
			}
			elseif( $server_id>0 ) //用制定机
			{
				$analyse = $server_id;
			}
			else
			{
				$analyse = 1;
			}
			global $DB;
			$connection_id = $DB->get_connection_id($analyse);
			$str = mysql_real_escape_string($str, $connection_id);
		}
		return $str;
	}
}
?>