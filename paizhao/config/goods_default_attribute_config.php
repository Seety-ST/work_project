<?php
//input 1单选 2多选 3输入框 4文本框 5在线编辑器 6日期 7日期时间 8地区 9隐藏域
return array(
			 array(
				   'name'  =>'商品名称',
				   'key'   =>'titles',
				   'input'  =>3,
				   'message'  =>'',
				   ),
				   /*
			 array(
				   'name'  =>'简介',
				   'key'   =>'introduction',
				   'input'  =>4,
				   'message'  =>$data['introduction'],
				   ),
				   */
			 array(
				   'name'  =>'价格',
				   'key'   =>'prices',
				   'input'  =>3,
				   'message'  =>'',
				   ),
			 array(
				   'name'  =>'单位',
				   'key'   =>'unit',
				   'input'  =>3,
				   'message'  =>'',
				   ),
			 array(
				   'name'  =>'库存',
				   'key'   =>'stock_num',
				   'input'  =>3,
				   'message'  =>'',
				   ),
			 array(
				   'name'  =>'标签',
				   'key'   =>'tags',
				   'input'  =>3,
				   'message'  =>'',
				   ),
			 array(
				   'name'  =>'所属职业',
				   'key'   =>'profession_id',
				   'input'  =>3,
				   'message'  =>'',
				   ),
			 array(
				   'name'  =>'服务方式',
				   'key'   =>'service_id',
				   'input'  =>1,
				   'message'  =>'',
				   'child_data' => array(
										 array('val'=>1,'name'=>'上门服务'),
										 array('val'=>2,'name'=>'定点服务'),
										 array('val'=>3,'name'=>'线上服务'),
										 array('val'=>4,'name'=>'实物寄送'),
										 array('val'=>5,'name'=>'活动'),//活动霸位
										 ),
				   ),
			 array(
				   'name'  =>'服务地区',
				   'key'   =>'location_id',
				   'input'  =>8,
				   'message'  =>'',
				   ),
			 array(
				   'name'  =>'经纬度',
				   'key'   =>'lng_lat',
				   'input'  =>3,
				   'message'  =>'',
				   ),				   
//			 array(
//				   'name'  =>'自动接受',
//				   'key'   =>'is_auto_accept',
//				   'input'  =>2,
//				   'message'  =>'',
//				   'child_data' => array(
//										   array('val'=>1,'name'=>'订单支付后，自动接受'),
//										   ),
//				   ),				   
//			 array(
//				   'name'  =>'自动签到',
//				   'key'   =>'is_auto_sign',
//				   'input'  =>2,
//				   'message'  =>'',
//				   'child_data' => array(
//										   array('val'=>1,'name'=>'订单接受后，自动签到'),
//										   ),
//				   ),				   
				   /*
			 array(
				   'name'  =>'视频',
				   'key'   =>'video',
				   'input'  =>4,
				   'message'  =>$data['video'],
				   ),
			 array(
				   'name'  =>'案例信息',
				   'key'   =>'demo',
				   'input'  =>4,
				   'message'  =>$data['demo'],
				   ),
				   */
			 array(
				   'name'  =>'图文详情',
				   'key'   =>'content',
				   'input'  =>5,
				   'message'  =>'',
				   ),
			 );
?>