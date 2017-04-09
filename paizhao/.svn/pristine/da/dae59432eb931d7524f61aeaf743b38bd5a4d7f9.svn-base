<?php
//测试
require('common.inc.php');

$obj = POCO::singleton('pai_paizhao_goods_class');

$re2 = $obj->get_goods_list('','0,4','goods_id desc','goods_id,titles,detail');

dump(unserialize($re2[0]['detail']));
$re=search_seller_list_by_fulltext(array(),'');
print_r($re);
	function search_seller_list_by_fulltext($data,$limit = '0,10')
	{
		$data['type_id']=40;
		//print_r($data);
		if($data['detail'])
		{			
			foreach($data['detail'] as $key => $val)
			{
				$data[$key] = $val;
			}
		}
		////////////////总交易价格
		$data['prices'] = array();
		if($data['total_money_s']!="")
		{
			$data['prices'][] = (int)$data['total_money_s'];
		}
		if($data['total_money_e']!="")
		{
			$data['prices'][] = (int)$data['total_money_e'];
		}
		//$data['prices'] = array_filter($data['prices']);
		sort($data['prices']);
		
		////////////////总交易次数
		$data['total_times'] = array();
		if($data['total_times_s']!="")
		{
			$data['total_times'][] = (int)$data['total_times_s'];
		}
		if($data['total_times_e']!="")
		{
			$data['total_times'][] = (int)$data['total_times_e'];
		}
		//$data['total_times'] = array_filter($data['total_times']);
		sort($data['total_times']);
		
		////////////////上架数
		$data['onsale_num'] = array();
		if($data['list_s']!="")
		{
			$data['onsale_num'][] = (int)$data['list_s'];
		}
		if($data['list_e']!="")
		{
			$data['onsale_num'][] = (int)$data['list_e'];
		}
		//$data['total_times'] = array_filter($data['total_times']);
		sort($data['onsale_num']);
		
		////////////////地区
		if($data["location_id"])
		{
			$data["location_id"] = "*,".substr($data["location_id"],0,6).",*|*,".$data["location_id"].",*";
		}
		else
		{
			if($data["city"])
			{
				$data["location_id"] = "*,".$data["city"]."*";
			}
		}
		//////////////id
		if(is_numeric($data["keywords"]))
		{
			$user_id = $data["keywords"];
			$status = $data['status'];
			$is_cooperation = $data['is_cooperation'];
			unset($data);
			$data["user_id"] = $user_id;
			$data['status']=$status;
			$data['is_cooperation']=$is_cooperation;
			//$limit = '0,13';
		}
		
			
		$data["keywords"]?$querys["keywords"] = $data["keywords"]:"";//关键字 content,introduction
		isset($data["is_black"])?$querys["seller_is_black"] = $data["is_black"]:"";//黑名单
		$data["type_id"]?$querys["type_id"] = $data["type_id"]:"";//类型
		$data["user_id"]?$querys["seller_user_id"] = $data["user_id"]:"";//用户
		in_array($data["is_cooperation"],array(1,0))?$querys["is_cooperation"] = $data["is_cooperation"]:"";//用户
		
		

		$data["seller_onsale_num"]?$querys["seller_onsale_num"] = implode(',',array($data["seller_onsale_num"])):"";//商家状态
		$data["status"]?$querys["seller_status"] = $data["status"]:"";//商家状态
		$data["basic_type"]?$querys["basic_type"] = $data["basic_type"]:"";//商家状态		
		
		$data["location_id"]?$querys["seller_location_id"] = $data["location_id"]:"";//地区
		//$data["profession_id"]?$querys["profession_id"] = $data["profession_id"]:"";//职业id,可以多选,多选用","号隔开
		if($data["profession_id"])//职业id,可以多选,多选用","号隔开
		{
			if(is_array($data["profession_id"]))
			{
				$querys["profession_id"] = implode('|',$data["profession_id"]);
			}
			elseif((int)$data["profession_id"]>0)
			{
                $querys["profession_id"] = (int)$data["profession_id"];
			}
		}
		
		if($data["seller_level"])//商家等级id,可以多选,多选用","号隔开
		{
			if(is_array($data["seller_level"]))
			{
				$querys["seller_level"] = implode(',',$data["seller_level"]);
			}
			elseif((int)$data["seller_level"]>0)
			{
				$querys["seller_level"] = (int)$data["seller_level"];
			}
		}
		
		
		$data["prices"]?$querys["seller_prices"] = implode(',',$data["prices"]):"";//价格
		$data["total_times"]?$querys["seller_bill_finish_num"] = implode(',',$data["total_times"]):"";//订单完成数量
		$data["onsale_num"]?$querys["seller_onsale_num"] = implode(',',$data["onsale_num"]):"";//上架数
		strtotime($data["begin_time"])?$querys["seller_add_time"] = strtotime($data["begin_time"]).','.(strtotime($data["end_time"])+86400):"";//上架时间
		strtotime($data["add_cooperation_time_s"])?$querys["add_cooperation_time"] = strtotime($data["add_cooperation_time_s"]).','.(strtotime($data["add_cooperation_time_e"])+86400):"";//上架时间

		$data["rating"] and $data["type_id"]?$querys["rating"] = $data["type_id"]."-".$data["rating"]:"";//评级		
		
		if($data['operator_id'])
		{
			switch($data['operator_id'])
			{
				case "批量":
					$querys["seller_add_user"] = '0';
				break;
				case "其他":
					$querys["seller_add_user"] = '0,not';
				break;
				default:
					$querys["seller_add_user"] = (int)$data['operator_id'];
				break;
			}
		}

		/////////////////////////
		if($data["type_id"] == 40)//摄影服务(ID:40)
		{
			$data["p_experience"]?$querys["p_experience"] = $data["p_experience"]:"";//类型数据data
			$data["p_goodat"]?$querys["p_goodat"] = $data["p_goodat"]:"";//类型数据data
			$data["p_team"]?$querys["p_team"] = $data["p_team"]:"";//类型数据data
			$data["p_order_income"]?$querys["p_order_income"] = $data["p_order_income"]:"";//类型数据data
		}
		/////////////////////////
		$querys["limit"] = $limit?$limit:"0,20";
		
		$order_by_type = $data["order"]?$data["order"]:5;
		$querys["order_type"] = $data["order_type"]==2?"ASC":"DESC";
		
		switch($order_by_type)
		{
			case 1:
				$querys["order"] = 'seller_id '.$querys["order_type"].' 3';//商品id
			break;
			case 2:
				$querys["order"] = 'seller_bill_buy_num '.$querys["order_type"].' 4,seller_id DESC 3';//商品id
			break;
			case 3:
				$querys["order"] = 'seller_bill_finish_num '.$querys["order_type"].' 4,seller_id DESC 3';//商品id
			break;
			case 4:
				$querys["order"] = 'total_average_score '.$querys["order_type"].' 4,seller_id DESC 3';//评价
			break;
			case 5:
				$querys["order"] = '_SCORE,seller_seller_level_point '.$querys["order_type"].' 5,seller_id DESC 3';//评价
			break;
			default:
				$querys["order"] = 'seller_id DESC 3';
			break;
		}
		
		
		if($data["debug"])
		{
			print_r($data);
			print_r($querys);		
		}
		
/*		$system_conf = $this->get_system_config();
		$fulltext = $system_conf['FULLTEXT_SELLER'];	
*/		$fulltext = 1;	
		//
		include_once("/disk/data/htdocs232/poco/pai/core/include/fulltext_search_helper/lucoservice/real_search_client.class.php");
		$fulltext_config = array(
								 'conf'=>'mall',
								 );
		$max_limit_num = explode(',',$querys["limit"]);
		if($data["max_limit"] == "max" and $max_limit_num[0]>=5000)
		{
			$querys["max_limit"] = "max";
			$fulltext_config = array(
									 'conf'=>'mall_max',
									 );
		}
		$lucoclient_server_conf = $GLOBALS['LUCOCLIENT_SERVER_CONFIG'][$fulltext_config['conf']];
		$client = new LucoClient($lucoclient_server_conf['host'], $lucoclient_server_conf['port']);	
		//$res = $client ->searchFun("actions.MallFunction.searchMallSeller",$querys);
		$res = $client ->searchFun("actions.MallFunction.searchMallSellerNewTest",$querys);
		
		//$res = $client ->searchFun("actions.MallFunction.searchMallSellerTest",$querys);
		//$res = $client ->searchFun("actions.MallFunction.searchTestSeller",$querys);
		$client->close();//把链接关
		$return = array(
		                'total'=>$res->total,
		                'data'=>$res->resultRow,
						);
		//print_r($res->resultRow);
		return $return;
	}
