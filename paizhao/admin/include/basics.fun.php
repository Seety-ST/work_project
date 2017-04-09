<?php
    //公共函数库
    function getExcel($fileName, $headArr, $data,$title='tt')//导出excel库
    {
        if (empty ( $data ) || ! is_array ( $data ))
        {
            die ( "data must be a array" );
        }
        if (empty ( $fileName ))
        {
            exit ();
        }
        $date = date ( "Y_m_d_H_i_s", time () );
        $fileName .= "_{$date}.xls";
        
        //创建新的PHPExcel对象
        $objPHPExcel = new PHPExcel ();
        $objProps = $objPHPExcel->getProperties ();
        //设置表头
        $key = ord ( "A" );
        $objActSheet = $objPHPExcel->getActiveSheet ();
        $objActSheet->getRowDimension ( '1' )->setRowHeight ( 22 );
        foreach ( $headArr as $v )
        {
            $colum = chr ( $key );
            $objActSheet->getColumnDimension ( $colum )->setWidth ( 20 );
            $objActSheet->getStyle ( $colum )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
            $objActSheet->getStyle ( $colum )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::VERTICAL_CENTER );
            $v = iconv ( 'GBK', 'utf-8', $v );
            $objPHPExcel->setActiveSheetIndex ( 0 )->setCellValue ( $colum . '1', $v );
            $key += 1;
        }
        //exit;
        $column = 2;
        //$objActSheet = $objPHPExcel->getActiveSheet();
        foreach ( $data as $key => $rows )
        { //行写入
            $span = ord ( "A" );
            foreach ( $rows as $keyName => $value )
            { // 列写入
                $j = chr ( $span );
                $objActSheet->getColumnDimension ( $j )->setWidth ( 20 );
                $value = iconv ( 'GBK', 'utf-8', $value );
                $objActSheet->setCellValue ( $j . $column, $value );
                $span ++;
            }
            $column ++;
        }
        
        //$fileName = iconv("utf-8", "gb2312", $fileName);
        //重命名表
        //$objActSheet->getColumnDimension( 'B')->setAutoSize(true);   //内容自适应
        $objPHPExcel->getActiveSheet ()->setTitle ( iconv ( 'GB2312', 'utf-8', $title ) );
        //设置活动单指数到第一个表,所以Excel打开这是第一个表
        $objPHPExcel->setActiveSheetIndex ( 0 );
        //将输出重定向到一个客户端web浏览器(Excel2007)
        //ob_end_clean();//清除缓冲区,避免乱码
        header ( 'Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' );
        header ( "Content-Disposition: attachment; filename=\"$fileName\"" );
        header ( 'Cache-Control: max-age=0' );
        $objWriter = PHPExcel_IOFactory::createWriter ( $objPHPExcel, 'Excel5' );
        /*   if(!empty($_GET['excel'])){
                $objWriter->save('php://output'); //文件通过浏览器下载
            }else{
              $objWriter->save($fileName); //脚本方式运行，保存在当前目录
            }*/
        $objWriter->save ( 'php://output' );
        exit ();
    }
    
    //后台系统菜单
    function mall_get_admin_menu($yue_login_id,$menu_id='')
    {
		if(empty($yue_login_id))
		{
			$r_url = urlencode($_SERVER['SCRIPT_URI']);
			header("location:http://www.yueus.com/yue_admin/login_e.php?referer_url=".$r_url);
			exit;
		}
		if(!in_array($menu_id,array('admin_menu_task')))
		{
			$menu_id = 'admin_menu_task';
		}
        
		$munu_list = pai_mall_paizhao_load_config($menu_id);
		$user_acl_obj = POCO::singleton('pai_mall_admin_user_class');
		$user_acl = $user_acl_obj->get_acl_user_cache($yue_login_id);
		//print_r($user_acl);
		$acl_obj = POCO::singleton('pai_mall_admin_acl_class');
		$acl = $acl_obj->get_md5_row_and_child_list();
        if($user_acl['type_id'] == 1)
		{
			return $munu_list;
		}
		$acl_type_obj = POCO::singleton('pai_mall_admin_type_class');
		$acl_type_detail = $acl_type_obj->get_admin_type_info($user_acl['type_id']);
		//print_r($acl_type_detail);
		$user_acl_detail = array_filter(array_unique(array_merge(explode(',',$user_acl['acl']),explode(',',$acl_type_detail['acl']))));
		//
		$user_permissions = mall_get_admin_goodstype_permissions($yue_login_id);
        
        $user_profession = mall_get_admin_profession($yue_login_id);
        
        
		//		
		//print_r($user_acl_detail);
		//print_r($munu_list);
		foreach($munu_list as $key => $val)
		{
			$un_set = true;
			foreach($val['file'] as $val_de)
			{
				$id = $acl[md5($val_de)]['id'];
				if(in_array($id,$user_acl_detail))
				{
					$un_set = false;
					break;
				}
			}
			if($un_set)
			{
				unset($munu_list[$key]);				
			}
			else
			{
				foreach($val['list'] as $key_de =>  $val_de)
				{
					$un_set_2 = true;
					foreach($val_de['file'] as $key_de_2 => $val_de_2)
					{
						if(in_array($acl[md5($val_de_2)]['id'],$user_acl_detail))
						{							
							$un_set_2 = false;
							break;
						}
					}
					if($un_set_2)
					{
						unset($munu_list[$key]['list'][$key_de]);						
					}
					elseif($val_de['children_list'])
					{
						foreach($val_de['children_list'] as $key_de_2 => $val_de_2)
						{
							$un_set_3 = true;
							if(in_array($acl[md5($val_de_2['file'])]['id'],$user_acl_detail))
							{
								if($val_de_2['action'])
								{
									foreach($val_de_2['action'] as $val_de_3)
									{
										if(in_array($acl[md5($val_de_2['file'])]['child_data'][md5($val_de_3)]['id'],$user_acl_detail))
										{
											$re_admin_goodstype_permissions = true;
											/////////////////////////////////////////
											if($val_de_2['type_id'])
											{
												foreach($val_de_2['type_id'] as $val_de_typeid)
												{
													$re_admin_goodstype_permissions = mall_check_admin_goodstype_permissions($yue_login_id,$val_de_typeid,true,$user_permissions);
													if(!$re_admin_goodstype_permissions)
													{
														break;
													}
												}
											}
                                            
                                            if($val_de_2['profession_id'])
											{
                                                                                                foreach($val_de_2['profession_id'] as $val_de_profession_id)
												{
													$re_admin_profession_permissions = $user_profession[$val_de_profession_id];
													if( ! $re_admin_profession_permissions )
													{
														break;
													}
												}
											}
                                            
											if($re_admin_goodstype_permissions)
											{
												$un_set_3 = false;
												break;
											}
											
											if($re_admin_profession_permissions)
											{
												$un_set_3 = false;
												break;
											}
											/////////////////////////////////////
//											$un_set_3 = false;
//											break;
										}
									}
								}
								else
								{
									$un_set_3 = false;
									break;
								}
							}
							if($un_set_3)
							{
								unset($munu_list[$key]['list'][$key_de]['children_list'][$key_de_2]);								
							}
						}
						$munu_list[$key]['list'][$key_de]['children_list'] = array_values($munu_list[$key]['list'][$key_de]['children_list']);
						if(!$munu_list[$key]['list'][$key_de]['children_list'])
						{
							unset($munu_list[$key]['list'][$key_de]);
						}
					}
				}
				$munu_list[$key]['list'] = array_values($munu_list[$key]['list']);
			}
		}
		$munu_list = array_values($munu_list);
		return $munu_list;
    }	
    
    //获取后台管理员姓名	
    function mall_get_admin_nickname_by_user_id($user_id)
    {
		$user_id = (int)$user_id;
		if(!$user_id)
		{
			return false;
		}
		$user_acl_obj = POCO::singleton('pai_mall_admin_user_class');
		$user_acl = $user_acl_obj->get_acl_user_cache($user_id);
		return $user_acl['name'];
    }
    
    //获取后台系统管理员所属管理商品类型	
    function mall_get_admin_goodstype($yue_login_id)
    {
       $admin_acl_obj = POCO::singleton('pai_mall_admin_acl_class');
       $type_list = $admin_acl_obj->get_acl_cate(215);
       $can_do_list = mall_get_user_role_and_acl_can_do($yue_login_id);
	   $all_goods_type = in_array('229',$can_do_list['can_do_acl_list'])?1:0;
	   $all_goods_type = 1;//开启所有
       $type_id_info = array();
       if( ! empty($type_list) )
       {
           foreach($type_list as $k => $v)
           {
               if($can_do_list['admin_type_id'] == 1)
               {
                   $type_id_info[$v['val']] = true;
               }else
               {
                   if($all_goods_type or in_array($v['id'],$can_do_list['can_do_acl_list']))
                   {
                       $type_id_info[$v['val']] = true;
                   }else
                   {
                       $type_id_info[$v['val']] = false;
                   }
               }
               
           }
       }
       return $type_id_info;
    }
    
    //获取后台系统管理员所属管理职业类型	
    function mall_get_admin_profession($yue_login_id)
    {
       $admin_acl_obj = POCO::singleton('pai_mall_admin_acl_class');
       $type_list = $admin_acl_obj->get_acl_cate(481);
       $can_do_list = mall_get_user_role_and_acl_can_do($yue_login_id);
	   $all_profession = in_array('482',$can_do_list['can_do_acl_list'])?1:0;
	   //$all_profession = 1;//开启所有
       $profession_info = array();
       if( ! empty($type_list) )
       {
           foreach($type_list as $k => $v)
           {
               if($can_do_list['admin_type_id'] == 1)
               {
                   $profession_info[$v['val']] = true;
               }else
               {
                   if($all_profession or in_array($v['id'],$can_do_list['can_do_acl_list']))
                   {
                       $profession_info[$v['val']] = true;
                   }else
                   {
                       $profession_info[$v['val']] = false;
                   }
               }
               
           }
       }
       return $profession_info;
    }
    
    /**
     * 获取后端已经开通权限的品类id
     * @param type $yue_login_id
     * @return type
     */
    function mall_get_open_type_list($yue_login_id)
    {
        $goods_type_obj = POCO::singleton('pai_mall_goods_type_class');
        $type_id_list = $goods_type_obj->get_type_cate(2);
        $get_admin_goods_type = mall_get_admin_goodstype($yue_login_id);
        $open_type_list = array();
        if($get_admin_goods_type['all'] == 1)
        {
            $open_type_list[] = array('type_id'=>'','name'=>'全部');
        }
        foreach($type_id_list as $k => $v)
        {
            if($get_admin_goods_type[$v['id']] or $get_admin_goods_type['all'] == 1)
            {
                $open_type_list[] = $v;
            }
        }
        return $open_type_list;
        
    }
    
    function mall_get_open_profession_list($yue_login_id)
    {
        $profession_obj = POCO::singleton('pai_mall_profession_class');
        $profession_list = $profession_obj->get_profession_list(false, '', $order_by = 'add_time DESC', '0,10000');;
        $get_admin_profession = mall_get_admin_profession($yue_login_id);
        $open_profession_list = array();
//        if($get_admin_profession['all'] == 1)
//        {
//            
//        }
        $open_profession_list[] = array('id'=>'-1','name'=>'全部');
        foreach($profession_list as $k => $v)
        {
            if($get_admin_profession[$v['id']] or $get_admin_profession['all'] == 1)
            {
                $open_profession_list[] = $v;
            }
        }
        return $open_profession_list;
    }
    
    /**
     * 拿用户每一个的开通的type_id
     * @param type $yue_login_id
     * @return type
     */
    function mall_get_first_open_type_id($yue_login_id)
    {
        $rs = mall_get_admin_goodstype($yue_login_id);
        $first_type_id = '';
        foreach($rs as $k => $v)
        {
            if((int)$k > 0 && $v)
            {
                $first_type_id = $k;
                break;
            }
        }
        return $first_type_id;
        
    }
    
    /**
     * 
     * @param type $yue_login_id
     * @return type获取角色名
     */
    function mall_get_role_name($yue_login_id)
    {
        $admin_user_obj = POCO::singleton('pai_mall_admin_user_class');
        $person = $admin_user_obj->get_acl_user_cache($yue_login_id);
        if( ! empty($person['type_id']))
        {
            $admin_type_obj = POCO::singleton('pai_mall_admin_type_class');
            $role = $admin_type_obj->get_admin_type_info($person['type_id']);
        }
        return $role['name'];
    }
    
    /**
     * 获取角色权限与个人权限的并集
     * @param type $yue_login_id
     */
    function mall_get_user_role_and_acl_can_do($yue_login_id)
    {
        //个人能做的
        $admin_user_obj = POCO::singleton('pai_mall_admin_user_class');
        $person = $admin_user_obj->get_acl_user_cache($yue_login_id);        
        //角色能做的
        if( ! empty($person['type_id']))
        {
            $admin_type_obj = POCO::singleton('pai_mall_admin_type_class');
            $role = $admin_type_obj->get_admin_type_info($person['type_id']);
        }
        $person_ary = explode(',',$person['acl']);
        $role_ary = explode(',',$role['acl']);
        $can_do_acl_list = array_unique(array_merge($person_ary,$role_ary));
        return array('admin_type_id'=>$person['type_id'],'can_do_acl_list'=>$can_do_acl_list);        
    }
    
    /**
     * 检查后台人员是否是超管或者超管的子级
     * @param type $yue_login_id
     * @return boolean
     */
    function mall_check_admin_is_super_or_super_son($yue_login_id)
    {
        //个人能做的
        $admin_user_obj = POCO::singleton('pai_mall_admin_user_class');
        $person = $admin_user_obj->get_acl_user_cache($yue_login_id);
        $type_id = $person['type_id'];
        if($type_id == 2)
        {
            return true;
        }else
        {
            $admin_type_obj = POCO::singleton('pai_mall_admin_type_class');
            $role = $admin_type_obj->get_admin_type_info($type_id);
            if($role['parent_id'] == 2)
            {
                return true;
            }
        }
        return false;
    }
    
    /**
     * 检查权限id是否是商家标签或者用户标签或者他们的子
     * @param type $id
     * @return boolean
     */
    function mall_check_acl_id_is_seller_or_user_tags_son($id)
    {
        if( in_array( $id,array(2,3) ) )
        {
            return true;
        }
        $admin_acl_obj = POCO::singleton('pai_mall_admin_acl_class');
        $admin_acl_info = $admin_acl_obj->get_admin_acl_info($id);
        $goods_keyword_obj = POCO::singleton('pai_mall_goods_keywords_class');
        $seler_tags_son =  $goods_keyword_obj->get_all_childs(3);
        $user_tags_son =  $goods_keyword_obj->get_all_childs(2);
        if( in_array($id,$seler_tags_son) || in_array($id,$user_tags_son) )
        {
            return true;
        }
        return false;
    }
    
    /**
     * 约约后台入口权限检测
     * @param type $user_id
     * @return int
     */
    function check_admin_entrance_acl($user_id)
    {
        $admin_entrance_menu_config = pai_mall_paizhao_load_config('admin_entrance_menu');
        $rs = mall_get_user_role_and_acl_can_do($user_id);
        if($rs['admin_type_id'] == 1)
        {
            return $admin_entrance_menu_config;
        }
        foreach($admin_entrance_menu_config as $k => $v)
        {
            $not_can_show = true;
            //取交集
            $acl_relate_ary = array_intersect($v['acl_relate'],$rs['can_do_acl_list']);
            if( ! empty($acl_relate_ary) )
            {
                $not_can_show = false;
            }
            
            if($not_can_show)
            {
                unset($admin_entrance_menu_config[$k]);
            }
            
        }
        
        return $admin_entrance_menu_config;
        
    }
    
    
    //获取后台系统商家管理权限	
    function mall_check_admin_seller_permissions($yue_login_id,$seller_user_id)
    {
        $seller_obj = POCO::singleton('pai_mall_seller_class');
        $seller_info = $seller_obj->get_seller_info($seller_user_id,2);
        $belong_user_id_ary = explode(',',$seller_info['seller_data']['cooperation']['belong_userid']);
        if(in_array($yue_login_id,$belong_user_id_ary))
        {
            return true;
        }
		else
        {
            return false;
        }
        
    }
    
    //重组黑名单屏蔽语句
    function mall_get_admin_specialblack_sqlwhere($where_str)
    {
		$where = '(user_id>=700100 or user_id<=400001)';
		if(defined('TASK_ADMIN_USER_ID'))
		{
			$system_black = mall_check_admin_permissions(TASK_ADMIN_USER_ID,'system_black','/disk/data/htdocs232/poco/pai/yue_admin/task/system_permissions_goods',false);
			if($system_black['result']==1)
			{
				$where= '';
			}
		}
		$where_arr[] = $where;
		$where_arr[] = $where_str;
		$where_string = implode(' and ',array_filter($where_arr));
		//echo $where_string;
		return $where_string;
    }
    
    //获取后台系统商品类型管理权限	
    function mall_get_admin_goodstype_permissions($yue_login_id)
    {
		$admin_user_permissions = mall_check_admin_permissions($yue_login_id,'all','/disk/data/htdocs232/poco/pai/yue_admin/task/goods_type',false);
		//
		$type_obj = POCO::singleton('pai_mall_goods_type_class');
		$type_list = $type_obj->get_first_cate();
		foreach($type_list as $val)
		{
			$user_permissions[$val['id']] = mall_check_admin_permissions($yue_login_id,$val['id'],'/disk/data/htdocs232/poco/pai/yue_admin/task/goods_type',false);
		}
		//
		$return = array(
		                'admin_user_permissions'=>$admin_user_permissions,
		                'user_permissions'=>$user_permissions,
						);
		return $return;
    }
    
    //获取后台系统商品类型管理权限	
    function mall_check_admin_goodstype_permissions($yue_login_id,$type_id,$return = false,$user_permissions=array())
    {
		if($user_permissions)
		{
			$admin_user_permissions = $user_permissions['admin_user_permissions'];
			if($admin_user_permissions['result'] == 1)
			{
				return true;
			}
			$user_permissions = $user_permissions['user_permissions'][$type_id];
		}
		else
		{
			$admin_user_permissions = mall_check_admin_permissions($yue_login_id,'all','/disk/data/htdocs232/poco/pai/yue_admin/task/goods_type',false);
			if($admin_user_permissions['result'] == 1)
			{
				return true;
			}
			$user_permissions = mall_check_admin_permissions($yue_login_id,$type_id,'/disk/data/htdocs232/poco/pai/yue_admin/task/goods_type',false);
		}
		if($user_permissions['result']<1)
		{
			if(mall_is_ajax())
			{
				if($return)
				{
					return false;
				}
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
				if($return)
				{
					return false;
				}
				if($user_permissions['result'] == -4)
				{
					header("Location:verification.php?r_url=".$user_permissions['r_url']);
					exit;
				}
				echo "<script type='text/javascript'>window.alert('".$user_permissions['message']."');</script>";
				exit;
			}
		}
		else
		{
			return true;
		}
    }
	
    
    //获取后台系统职业管理权限	
    function mall_check_admin_profession_permissions($yue_login_id,$profession_id,$return = false,$user_permissions=array())
    {
        if($user_permissions)
		{
			$admin_user_permissions = $user_permissions['admin_user_permissions'];
			if($admin_user_permissions['result'] == 1)
			{
				return true;
			}
			$user_permissions = $user_permissions['user_permissions'][$profession_id];
		}
		else
		{
			$admin_user_permissions = mall_check_admin_permissions($yue_login_id,'all','/disk/data/htdocs232/poco/pai/yue_admin/task/seller_profession',false);
			if($admin_user_permissions['result'] == 1)
			{
				return true;
			}
			$user_permissions = mall_check_admin_permissions($yue_login_id,$profession_id,'/disk/data/htdocs232/poco/pai/yue_admin/task/seller_profession',false);
		}
		if($user_permissions['result']<1)
		{
			if(mall_is_ajax())
			{
				if($return)
				{
					return false;
				}
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
				if($return)
				{
					return false;
				}
				if($user_permissions['result'] == -4)
				{
					header("Location:verification.php?r_url=".$user_permissions['r_url']);
					exit;
				}
				echo "<script type='text/javascript'>window.alert('".$user_permissions['message']."');</script>";
				exit;
			}
		}
		else
		{
			return true;
		}
    }
    
    
    //后台系统登录权限	
    function mall_check_admin_permissions($yue_login_id,$action='',$url_file='',$phone_v=true)
    {
		$return = array(
		                'result'=>-1,
		                'message'=>"你没有权限,请联系管理员获取权限!",
						);
		if(empty($yue_login_id))
		{
			$r_url = urlencode($_SERVER['SCRIPT_URI']);
			header("location:http://www.yueus.com/yue_admin/login_e.php?referer_url=".$r_url);
			exit;
		}
		$url_file = $url_file?$url_file:strtolower($_SERVER['SCRIPT_FILENAME']);
		$url_fileName = md5($url_file);
        $action = md5($action?$action:($_GET['action']?$_GET['action']:($_POST['action']?$_POST['action']:"default")));
		$user_acl_obj = POCO::singleton('pai_mall_admin_user_class');
		$user_acl = $user_acl_obj->get_acl_user_cache($yue_login_id);
        
		if(empty($user_acl))
		{
			$r_url = urlencode($_SERVER['SCRIPT_URI']);
			header("location:http://www.yueus.com/yue_admin/login_e.php?referer_url=".$r_url);
			exit;
		}
        
		$acl_type_obj = POCO::singleton('pai_mall_admin_type_class');
		$acl_type_detail = $acl_type_obj->get_admin_type_info($user_acl['type_id']);
        
        if($acl_type_detail['parent_id'] == 2 or $acl_type_detail['parent_id'] == 0)
        {
            $user_acl['type_name'] = $acl_type_detail['name'];
        }
		else
        {
            $user_acl['type_name'] = '';
            $role_ary = explode(",",$acl_type_detail['parents_list']);
            foreach($role_ary as $k => $v)
            {
                if($v == 0)
                {
                    continue;
                }
                $role_info = $acl_type_obj->get_admin_type_info($v);
                $user_acl['type_name'] .= $role_info['name'].'-';
            }
            $user_acl['type_name'] .=$acl_type_detail['name']; 
        }
		
		//$user_acl['acl_type_detail'] = $acl_type_detail;
		//
/*		if($user_acl['s_id']!=md5(session_id()."|".mall_get_ip()))
		{
			$user_acl_obj->update_user_session($yue_login_id,session_id());
		}
*/		//
        $no_v_file = array(
		                   '2e88d9bed6821e95da21c6645c31ea77',//index
						   '2d2846816ea206a339dfce55824ec348',//logout
						   'c2af776530ff39b331f6090cf63c7f77',//top
						   '5c948a30885e3962c723fe31757001bc',//manage/index.php
						   //'620a577eae95bd02a239a224f796a206',//
						   '106e541afeac6066265078d57395533d',//verification
						   );
		if(!in_array($url_fileName,$no_v_file))
        {
            if($user_acl['type_id'] == 1)
			{
				if( $phone_v and $user_acl['s_id']!=md5(session_id()."|".mall_get_ip()) )
				{
					$user_acl_obj->update_user_session($yue_login_id,session_id(),false);
					$return = array(
									'result'=>-4,
									'message'=>"请输入你的手机验证码!",
									'r_url'=>urlencode($_SERVER['REQUEST_URI']),
									);
					return $return;
				}
				$return = array(
								'result'=>1,
								'message'=>1,
								'data'=>$user_acl,
								);
				return $return;
			}
			if($user_acl['status'] == 0)
			{
				return $return;
			}
			$acl_obj = POCO::singleton('pai_mall_admin_acl_class');
			$acl = $acl_obj->get_md5_row_and_child_list();			
			if($acl and $user_acl)
			{
				$user_acl_detail = array_filter(array_unique(array_merge(explode(',',$user_acl['acl']),explode(',',$acl_type_detail['acl']))));
				if(in_array($acl[$url_fileName]['child_data'][$action]['id'],$user_acl_detail))
				{
                    
//					$user_ip = explode(',',$user_acl['ip']);
//					$ip = mall_get_ip();
//					$ip_config = pai_mall_paizhao_load_config('admin_ip');
//					if(!in_array($ip,$user_ip) and !in_array($ip,$ip_config))
//					{
//						return -3;
//					}
					//if(in_array($user_acl['type_id'],array(2)) and $user_acl['s_id']!=md5(session_id()."|".mall_get_ip()))
					if($phone_v and $user_acl['s_id']!==md5(session_id()."|".mall_get_ip()))
					{
						$user_acl_obj->update_user_session($yue_login_id,session_id(),false);
						$return = array(
										'result'=>-4,
										'message'=>"请输入你的手机验证码!",
										'r_url'=>urlencode($_SERVER['REQUEST_URI']),
										);
						return $return;
					}
					$return = array(
									'result'=>1,
									'data'=>$user_acl,
									);
                    
					return $return;
				}
				else
				{
                    $return = array(
									'result'=>-3,
									'message'=>"你没有权限[".$acl[$url_fileName]['child_data'][$action]['id']."],请联系管理员获取权限!",
									);
					return $return;
				}
			}
			else
			{
				$return = array(
								'result'=>-2,
								'message'=>"你没有权限,请联系管理员获取权限!",
								);
				return $return;
			}			
        }
        
		$return = array(
						'result'=>1,
						'data'=>$user_acl,
						);
		return $return;
    }
    
    //后台系统登录权限	
    function mall_check_admin_permissions_test($yue_login_id,$action='',$url_file='',$phone_v=true)
    {
		$return = array(
		                'result'=>-1,
		                'message'=>"你没有权限,请联系管理员获取权限!",
						);
		if(empty($yue_login_id))
		{
			$r_url = urlencode($_SERVER['SCRIPT_URI']);
			header("location:http://www.yueus.com/yue_admin/login_e.php?referer_url=".$r_url);
			exit;
		}
		$url_fileName=$url_file?md5($url_file):md5(strtolower($_SERVER['SCRIPT_FILENAME']));
        $action = md5($action?$action:($_GET['action']?$_GET['action']:($_POST['action']?$_POST['action']:"default")));
		$user_acl_obj = POCO::singleton('pai_mall_admin_user_class');
		$user_acl = $user_acl_obj->get_acl_user_cache($yue_login_id);
		$acl_type_obj = POCO::singleton('pai_mall_admin_type_class');
		$acl_type_detail = $acl_type_obj->get_admin_type_info($user_acl['type_id']);
		print_r($acl_type_detail);
		print_r($user_acl);
		$user_acl['type_name'] = $acl_type_detail['name'];
		//
/*		if($user_acl['s_id']!=md5(session_id()."|".mall_get_ip()))
		{
			$user_acl_obj->update_user_session($yue_login_id,session_id());
		}
*/		//
        $no_v_file = array(
		                   '49f79755d8d0d7bb0d3ac42ed788fc36',//index
						   'd95137ef8ac48a985d0177e7148efa26',//logout
						   '689fa408db68477c753ad04214cdebd6',//top
						   //'dac3dfb0db40bbbf42d58368cf5faf27',//
						   //'620a577eae95bd02a239a224f796a206',//
						   '8058c5a85340bb1e54195a175ffe2fbd',//verification
						   );
		if(!in_array($url_fileName,$no_v_file))
        {
            if($user_acl['type_id'] == 1)
			{
				echo $user_acl['s_id'].'=='.session_id().'--'.mall_get_ip().'=='.md5(session_id()."|".mall_get_ip());
				if( $phone_v and $user_acl['s_id']!=md5(session_id()."|".mall_get_ip()) )
				{
					$user_acl_obj->update_user_session($yue_login_id,session_id(),false);
					$return = array(
									'result'=>-4,
									'message'=>"请输入你的手机验证码!",
									'r_url'=>urlencode($_SERVER['REQUEST_URI']),
									);
					return $return;
				}
				$return = array(
								'result'=>1,
								'message'=>1,
								'data'=>$user_acl,
								);
				return $return;
			}
			if($user_acl['status'] == 0)
			{
				return $return;
			}
			$acl_obj = POCO::singleton('pai_mall_admin_acl_class');
			$acl = $acl_obj->get_md5_row_and_child_list();
			dump($acl);
			dump($acl[$url_fileName]);
			if($acl and $user_acl)
			{
				$user_acl_detail = array_filter(array_unique(array_merge(explode(',',$user_acl['acl']),explode(',',$acl_type_detail['acl']))));
				echo "<br>filename:".$url_fileName.'-->action:'.$action.'-->action_id:'.$acl[$url_fileName]['child_data'][$action]['id']."<br>";
				print_r($user_acl_detail);
				if(in_array($acl[$url_fileName]['child_data'][$action]['id'],$user_acl_detail))
				{
                    
//					$user_ip = explode(',',$user_acl['ip']);
//					$ip = mall_get_ip();
//					$ip_config = pai_mall_paizhao_load_config('admin_ip');
//					if(!in_array($ip,$user_ip) and !in_array($ip,$ip_config))
//					{
//						return -3;
//					}
					//if(in_array($user_acl['type_id'],array(2)) and $user_acl['s_id']!=md5(session_id()."|".mall_get_ip()))
					if($phone_v and $user_acl['s_id']!=md5(session_id()."|".mall_get_ip()))
					{
						$user_acl_obj->update_user_session($yue_login_id,session_id(),false);
						$return = array(
										'result'=>-4,
										'message'=>"请输入你的手机验证码!",
										'r_url'=>urlencode($_SERVER['REQUEST_URI']),
										);
						return $return;
					}
					$return = array(
									'result'=>1,
									'data'=>$user_acl,
									);
                    
					return $return;
				}
				else
				{
                    $return = array(
									'result'=>-3,
									'message'=>"你没有权限[".$acl[$url_fileName]['child_data'][$action]['id']."],请联系管理员获取权限!",
									);
					return $return;
				}
			}
			else
			{
				$return = array(
								'result'=>-2,
								'message'=>"你没有权限,请联系管理员获取权限!",
								);
				return $return;
			}			
        }
        
		$return = array(
						'result'=>1,
						'data'=>$user_acl,
						);
		return $return;
    }
    
    //判断商家系统登录权限	
    function mall_check_seller_permissions($yue_login_id)
    {
        $url_fileName=basename(strtolower($_SERVER['PHP_SELF']),".php");
        
        $login_info = urldecode($_GET['login_info']);
        $json_arr = json_decode($login_info,true);

        $cur_url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        $cur_url_clean = preg_replace(array("/login_info=[^&]*/i", '/[&]+/', '/\?[&]+/', '/[?&]+$/',),array('','&','?','',),$cur_url);//去除login info
        $r_url = urlencode($cur_url_clean);

        $mall_user_agent = mall_get_user_agent_arr();
        $__is_yueseller_app = $mall_user_agent['is_yueyue_seller'];
        $__is_yueyue_app = $mall_user_agent['is_yueyue_app'];
        
        if($json_arr['user_id'] && $json_arr['token'] && ($__is_yueseller_app||$__is_yueyue_app))
        {
            $login_status = mall_app_check_login();
            
            if($login_status['result'] == 1)
            {
                header("Location:{$cur_url_clean}");
                exit;
            }
            else
            {
                die("TOKEN验证失败");
            }
        }
        
        if(!in_array($url_fileName,array('index','app-index-tmp')))
        {
            if(empty($yue_login_id))
            {
                header("location:http://www.yueus.com/pc/login.php?r_url=".$r_url);
                exit;
            }
            $mall_obj = POCO::singleton('pai_mall_seller_class');
            $seller_info=$mall_obj->get_seller_info($yue_login_id,2);
            $seller_status=$seller_info['seller_data']['status'];
            if(!in_array($url_fileName,array('check_out','goods_edit_choose','goods_list','activity_list','buy_bill_list','buy_goods_list','normal_certificate_basic','normal_certificate_check','normal_certificate_choose','normal_certificate_detail','service_certificate_detail','pai_mall_certificate_basic_op','pai_mall_certificate_service_op','normal_certificate_list','app_guide')))
            {
                if(!$seller_info or $seller_status == 2)
                {
                    header("location:./normal_certificate_choose.php");
                    exit;
                }
            }
            return $seller_info;
        }
    }
    
    
    //判断用户系统登录权限	
    function mall_check_user_permissions($yue_login_id)
    {

        $user_obj = POCO::singleton('pai_user_class');

        $login_info = urldecode($_GET['login_info']);
        $json_arr = json_decode($login_info,true);

        $mall_user_agent = mall_get_user_agent_arr();

        $__is_yueseller_app = $mall_user_agent['is_yueyue_seller'];
        $__is_yueyue_app = $mall_user_agent['is_yueyue_app'];

        $current_url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        $current_url_clean = preg_replace(array("/login_info=[^&]*/i", '/[&]+/', '/\?[&]+/', '/[?&]+$/',),array('','&','?','',),$current_url); //去除login info
        $r_url = urlencode($current_url_clean);

        $log_arr['result'] = $json_arr;
        pai_log_class::add_log($log_arr, 'check_start', 'check_fail');

        $parse_url = parse_url($current_url);
        $parse_url['query'];
        $__is_app_login_info = (preg_match('/login_info=/',$parse_url['query'])) ? true : false;

        //开发环境 正式环境配置域名
        $fe_webpage_config = include(G_YUEYUE_CONF_PATH . '/pai_fe_webpage_config.php');

        //判断是有login_info标识
        if($__is_app_login_info && ($__is_yueseller_app||$__is_yueyue_app)) {
            if ($json_arr['user_id'] && $json_arr['token']) {
                
                //登录验证
                $login_status = mall_app_check_login();


                //$log_arr['json_arr'] = $json_arr;
                //$log_arr['login_status'] = $login_status;
                //$log_arr['query'] = $parse_url['query'];
                //pai_log_class::add_log($log_arr, 'app_check_login', 'app_check_login');

                if ($login_status['result'] == 1) {
                    header("Location:{$current_url_clean}");
                    exit;

                } else {
                    //$log_arr['user_id'] = $yue_login_id;
                    //pai_log_class::add_log($log_arr, 'check_fail', 'check_fail');

                    die("TOKEN验证失败");
                }

            }
            else
            {
                //有login_info标识，并且login_info json为空，执行网页登出
                $user_obj->logout();

                if(!defined('MALL_NOT_REDIRECT_LOGIN'))
                {
                    header("location:".$fe_webpage_config['mobile_origin']."/mobile/auth_jump_page.php?r_url=" . $r_url);
                    exit;
                }
                return true;
            }
        }


        $log_arr['result'] = $yue_login_id;

        pai_log_class::add_log($log_arr, 'yue_login_id', 'check_fail');


        if(empty($yue_login_id))
        {

            if($__is_yueyue_app)
            {
                header("location:".$fe_webpage_config['mobile_origin']."/mobile/auth_jump_page.php?r_url=".$r_url);
            }
            else
            {
                header("location:http://www.yueus.com/pc/register.php?r_url=".$r_url);
            }

            exit;
        }

        return true;
    }

    //编辑器补全HTML代码的函数处理2015-7-6添加
    function mall_closetags($html) 
    {	
    // 不需要补全的标签	
        $arr_single_tags = array('meta', 'img', 'br', 'link', 'area' ,'embed');	
    // 匹配开始标签	
        preg_match_all('#<([a-z]+)(?: .*)?(?<![/|/ ])>#iU', $html, $result);	
        $openedtags = $result[1];	
    // 匹配关闭标签	
        preg_match_all('#</([a-z]+)>#iU', $html, $result);	
        $closedtags = $result[1];	
    // 计算关闭开启标签数量，如果相同就返回html数据	
        $len_opened = count($openedtags);	
        if (count($closedtags) == $len_opened)
        {	
            return $html;	
        }	
    // 把排序数组，将最后一个开启的标签放在最前面	
        $openedtags = array_reverse($openedtags);	
    // 遍历开启标签数组	
        for ($i = 0; $i < $len_opened; $i++) 
        {	
    // 如果需要补全的标签	
            if (!in_array($openedtags[$i], $arr_single_tags)) 
            {	
    // 如果这个标签不在关闭的标签中	
                if (!in_array($openedtags[$i], $closedtags)) 
                {	
    // 直接补全闭合标签	
                    $html .= '</' . $openedtags[$i] . '>';
    
                } 
                else 
                {	
                    unset($closedtags[array_search($openedtags[$i], $closedtags)]);	
                }	
            }	
        }	
        return $html;	
    }

    /*
     * APP检查是否登录
     */
    function mall_app_check_login()
    {
        global $yue_login_id;
    
        $login_info = urldecode($_GET['login_info']);
        //var_dump($login_info);exit;
        
//		include_once("/disk/data/htdocs232/poco/pai/mobile_app/protocol_common.inc.php");
//		$cp = new poco_communication_protocol_class ();
        require_once('/disk/data/htdocs232/poco/pai/protocol/yue_protocol.inc.php');
        $cp = new yue_protocol_system();
        $user_obj = POCO::singleton('pai_user_class');
        
        //exit;
        $json_arr = json_decode($login_info,true);
        $user_id = $json_arr['user_id'];
        $token = $json_arr['token'];

        $agent   = strtolower($_SERVER['HTTP_USER_AGENT']);
        $iphone  = (strpos($agent,'iphone')) ? true : false;
        $android = (strpos($agent,'android')) ? true : false;
        if( $android ) {

            $app_name = 'poco_yuepai_android';

        }
        elseif( $iphone ){

            $app_name = 'poco_yuepai_iphone';

        }
        else{

            $app_name = 'poco_yuepai_android';

        }

        $access_info = $cp->get_access_info($user_id, $app_name, false, false);
        
        
        if($access_info['access_token']==$token)
        {
            $user_obj->load_member($user_id);
            
            $result['switch'] = 0;
            
            if($user_id!=$yue_login_id)
            {
                $result['switch'] = 1;
            }

            $result['result'] = 1;
            return $result;
        }


        $log_arr['user_id'] = $yue_login_id;
        $log_arr['ua'] = $_SERVER['HTTP_USER_AGENT'];
        $log_arr['access_info'] = $access_info;
        $log_arr['login_info'] = $json_arr;
        pai_log_class::add_log($log_arr, 'app_check_login', 'app_check_login');
        
        $result['result'] = 0;
        $result['url'] = "";
        return $result;
    }
    
    
    /*
     * 数组串成get方式参数
     */
    function mall_query_str($params,$is_do_unset=true) 
    {
        if($is_do_unset)
        {
            unset($params['status']);
            unset($params['is_show']);
            unset($params['city']);
            unset($params['is_black']);
        }
		
		if ( !is_array($params) || count($params) == 0 ) return false;
		$fga = func_get_args();
		$akey = ( !isset($fga[1]) ) ? false : $fga[1];        
		static $out = Array();
		
		foreach ( $params as $key=>$val ) 
		{
			if ( is_array($val) ) 
			{    
				mall_query_str($val,$key);
				continue;
			}
			$thekey = ( !$akey ) ? $key : $akey.'['.$key.']';
			$out[] = $thekey."=".trim($val);
		}
		return implode("&",$out);    
    }
    
    //过滤script 标签
    function del_script($str)
    {
        $preg = "/<script[\s\S]*?<\/script>/i";

        $new_str = preg_replace($preg,"",$str);
        
        return $new_str;
    }
    
    //校验图文编辑器的图片链接是否为本站链接
    //SRC链接校验
    function mall_src_link_check($text)
    {
        //匹配出SRC地址是否符合预期
        $text = htmlspecialchars_decode($text);//对INPUT的进行解码
        //匹配出字符串的图片
        preg_match_all("#<img(.*?)src=(\"|')(.*?)(\"|')#i",$text,$src_match);
        $check = true;
        foreach($src_match[3] as $k => $v)
        {
            $check = preg_match('#http://(.*).(poco.cn|yueus.com)/#',$v);
            if(!$check)
            {
                break;

            }

        }

        return $check;

    }

    //替换图文编辑不符合规则图片为制定图片
    function mall_src_link_replace($text)
    {
        //匹配出SRC地址是否符合预期
        $text = str_replace(array('&lt;','&gt;','&quot;'),array('<','>','"'),$text);
        //匹配出字符串的图片
        preg_match_all("#<img(.*?)src=(\"|')(.*?)(\"|')#i",$text,$src_match);
        $check = true;
        foreach($src_match[3] as $k => $v)
        {
            $check = preg_match('#http://(.*).(poco.cn|yueus.com)/#',$v);
            if(!$check)
            {
                $text = str_replace($v,"http://static-c.yueus.com/mall/seller/static/pc/images/editor_occupy_img.jpg",$text);
            }
        }
        return $text;

    }

    //校验图文编辑器的图片链接张数
    function mall_src_link_len_check($text)
    {
        //匹配出字符串的图片
        preg_match_all("/src=((&quot;)|(&#39;))(.*?(?:))((&quot;)|(&#39;))/is",$text,$src_match);
        $img_link_len = count($src_match[0]);
        return $img_link_len;
    }


    function mall_echo_debug($data)
    {
        if($_COOKIE['debug'])
        {
            dump($data);
        }
    }
    
    function mall_wx_get_js_api_sign_package()
    {
        //$app_id = 'wx8a082d58347117f7';	//约约测试号
        $app_id = 'wx25fbf6e62a52d11e';	//约约正式号
        $url = 'http://'.$_SERVER['SERVER_NAME'].$_SERVER["REQUEST_URI"];
        
        $weixin_helper_obj = POCO::singleton('pai_weixin_helper_class');
        $ret = $weixin_helper_obj->wx_get_js_api_sign_package_by_app_id($app_id, $url);
        
        //临时日志
        $payment_obj = POCO::singleton('pai_payment_class');
        ecpay_log_class::add_log(array('url'=>$url), __FILE__ , 'pai_weixin_js_api');
        
        return $ret;
    }

	function mall_wx_get_js_api_sign_package_for_sale()
	{
		$app_id = 'wxebe665c4377cd05a'; //约摄联盟企业号
		$url = 'http://'.$_SERVER['SERVER_NAME'].$_SERVER["REQUEST_URI"];

		$weixin_helper_obj = POCO::singleton('pai_weixin_helper_class');
		$ret = $weixin_helper_obj->wx_get_js_api_sign_package_by_app_id($app_id, $url);

		//临时日志
		$payment_obj = POCO::singleton('pai_payment_class');
		ecpay_log_class::add_log(array('url'=>$url), __FILE__ , 'pai_weixin_js_api');

		return $ret;
	}

    /**
     * 格式化配置数组的方法 
     * @param type $config_data
     * @param type $select_one
     * @param type $which_select
     */
    function config_data_format($config_data,$select_one,$which_select)
    {
         foreach($config_data as $key => $val)
        {
            if($which_select == 'key')
            {
                $selected = $select_one===$key ? true : false;
            }else if($which_select == 'val')
            {
                $selected = $select_one===$val ? true : false;
            }
            $config_list[] = array(
                'key'=>$key,
                'val'=>$val,
                'selected'=> $selected
            );
            unset($selected);
        }
        
        return $config_list;
    }
	
	    
    /**
     * 导出csv
     * @param type $data
     * @param type $title_arr
     * @param type $file_name
     */
    function export_csv(&$data, $title_arr, $file_name = '') 
    {
        ini_set("max_execution_time", "3600");
        $csv_data = '';
        /** 标题 */
        $nums = count($title_arr);
        for ($i = 0; $i < $nums - 1; ++$i) {
            $csv_data .= '"' . $title_arr[$i] . '",';
        }
        if ($nums > 0) {
         $csv_data .= '"' . $title_arr[$nums - 1] . "\"\r\n";
        }
        foreach ($data as $k => $row) {
            for ($i = 0; $i < $nums - 1; ++$i) {
                $row[$i] = str_replace("\"", "\"\"", $row[$i]);
                $csv_data .= '"' . $row[$i] . '",';
            }
            $csv_data .= '"' . $row[$nums - 1] . "\"\r\n";
            unset($data[$k]);
        }
        $csv_data = mb_convert_encoding($csv_data, "utf-8", "gbk");
        $file_name = empty($file_name) ? date('Y-m-d-H-i-s', time()) : $file_name;
        if (strpos($_SERVER['HTTP_USER_AGENT'], "MSIE")) { // 解决IE浏览器输出中文名乱码的bug
         $file_name = urlencode($file_name);
         $file_name = str_replace('+', '%20', $file_name);
        }
        $file_name = $file_name . '.csv';
        header("Content-type:text/csv;");
        header("Content-Disposition:attachment;filename=" . $file_name);
        header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
        header('Expires:0');
        header('Pragma:public');
        echo $csv_data;
    }
    
    ////获得访客浏览器类型
    function mall_get_browser()
    {
        if( ! empty($_SERVER['HTTP_USER_AGENT']) )
        {
           $br = $_SERVER['HTTP_USER_AGENT'];
            if (preg_match('/MSIE/i',$br)) 
            {    
                $br = 'MSIE';
            }elseif (preg_match('/Firefox/i',$br)) 
            {
                $br = 'Firefox';
            }elseif (preg_match('/Chrome/i',$br)) 
            {
                $br = 'Chrome';
            }elseif (preg_match('/Safari/i',$br)) 
            {
                $br = 'Safari';
            }elseif (preg_match('/Opera/i',$br)) 
            {
                $br = 'Opera';
            }
            else 
            {
                $br = 'Other';
            }
            
            return $br;
        }else
        {
            return "获取浏览器信息失败！";
        } 
    }


    ////获取访客操作系统
    function mall_get_os()
    {
        if( ! empty($_SERVER['HTTP_USER_AGENT']) )
        {
            $OS = $_SERVER['HTTP_USER_AGENT'];
            if (preg_match('/win/i',$OS)) 
            {
               $OS = 'Windows';
            }elseif (preg_match('/mac/i',$OS)) 
            {
               $OS = 'MAC';
            }elseif (preg_match('/linux/i',$OS)) 
            {
               $OS = 'Linux';
            }elseif (preg_match('/unix/i',$OS)) 
            {
               $OS = 'Unix';
            }elseif (preg_match('/bsd/i',$OS)) 
            {
               $OS = 'BSD';
            }
            else 
            {
               $OS = 'Other';
            }
            return $OS;  
        }else
        {
            return "获取访客操作系统信息失败！";
        }   
   }
 
    //获取ip
    function mall_get_ip()
    {
        if(!empty($_SERVER["HTTP_CLIENT_IP"]))
        {
          $cip = $_SERVER["HTTP_CLIENT_IP"];
        }
        elseif(!empty($_SERVER["HTTP_X_FORWARDED_FOR"]))
        {
          $cip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        }
        elseif(!empty($_SERVER["REMOTE_ADDR"]))
        {
          $cip = $_SERVER["REMOTE_ADDR"];
        }
        else
        {
          $cip = "无法获取！";
        }
        return $cip;
    }
    
    //ljl打印调试
    function ljl_dump($data,$is_exit=false)
    {
        global $yue_login_id;
        if( in_array($yue_login_id,array(365394,115203)) )
        {
            echo "<pre>";
            print_r($data);
            echo "</br>";
            echo ("$####$");
            if($is_exit)
            {
                exit;
            }
        }
    }
    
    
    //二维 数组排序 asc是升序 desc是降序
    function arr_sort($array,$key,$order="asc")
    {
        $arr_nums=$arr=array();

        foreach($array as $k=>$v)
        {
            $arr_nums[$k]=$v[$key];
        }

        if($order=='asc')
        {
            asort($arr_nums);
        }else
        {
            arsort($arr_nums);
        }

        foreach($arr_nums as $k=>$v)
        {
            $arr[$k]=$array[$k];
        }

        return $arr;
    }
    
	//判断是否ajax请求
	function mall_is_ajax()
	{
		if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) and strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == "xmlhttprequest")
		{
			return true;
		}
		else
		{
			return false;
		}
	}
    
    
	//引用action文件
	function mall_load_action($action,$filename='',$param=array())
	{
        global $yue_login_id;
        global $_INPUT;
        global $my_app_pai;
		$filename = $filename?$filename:basename(strtolower($_SERVER['PHP_SELF']),".php");
		$file = 'action/'.$filename.'/'.str_replace(array('\\','/','.'),array('','',''),$action).'.php';
        if(is_file($file))
		{
			require($file);
			return $return;
		}
		else
		{
			exit('Access Invalid!');
		}
	}
    
	//search_url 编码
	function mall_search_url_encode($url_str)
	{
		//
		if(is_array($url_str))
		{
			$ary = $url_str;			
		}
		else
		{
			if( ! $url_str )
			{
				return false;
			}
			parse_str($url_str, $ary);			
		}		
		if(empty($ary))
		{
			return false;
		}
		$belong_ary = array();
		$url_search_config = pai_mall_paizhao_load_config('url_search');
		$common_filip = array_flip($url_search_config['common']);
		if( ! empty($ary['s_action']))
		{
			$type_flip = array_flip($url_search_config[$ary['s_action']]);
			foreach($ary as $k => $v)
			{
				if($type_flip[$k])
				{
					$belong_ary[$k] = $ary['s_action'];
				}else if($common_filip[$k])
				{
					$belong_ary[$k] = 'common';
				}else
				{
					$belong_ary[$k] = '';
				}
			}
            
		}
		$ary['field_info'] = $belong_ary;
		//unset($ary['s_action']);
		//
		if( empty($ary) )
		{
			return false;
		}
		if( empty($ary['field_info']) )
		{
			return false;
		}
		$field_info = $ary['field_info'];
		unset($ary['field_info']);
		//$url_search_config = pai_mall_paizhao_load_config('url_search');
		$unit = array();	
		$key_blong_ary = array(
								'seller'=>'ss_',
								'order'=>'or_',
								'common'=>'gg_',
							   );	
		foreach($ary as $k => $v)
		{
            //如果是空的就可以不处理了
             if(trim($v)==='')
             {
                 continue;
             }
			 $key_blong = '';
			 $field_info_flip = array();
			 $short_tag = '';
			 $key_blong = $field_info[$k];
			 if($key_blong == 'goods')  //属于商品属性
			 {
				 $field_info_flip = array_flip($url_search_config['goods']);
				 $short_tag = $field_info_flip[$k];
				 if($short_tag)
				 {
					if($k == 'detail' || $k == 'third')
					{
						$v_str = '';
						if(is_array($v))
						{
							foreach($v as $vk => $vv)
							{
								$v_str .= urlencode($vk).",".urlencode($vv)."_";
							}
							$v_str = substr($v_str,0,-1);
						}
						$unit[$short_tag."_"] = $v_str;
					}
					else
					{
						//$unit['go_'.$short_tag] = urlencode($v);
						$unit['go_'.$short_tag] = $v;
					}	
				 }
			 }
			 else if($key_blong == 'seller' || $key_blong == 'order' || $key_blong == 'common') //属于商家属性
			 {
				 $field_info_flip = array_flip($url_search_config[$key_blong]);
				 $short_tag = $field_info_flip[$k];
				 if($short_tag)
				 {
					 $prev = '';
					 $prev = $key_blong_ary[$key_blong];
					 //$unit[$prev.$short_tag] = urlencode($v);
					 $unit[$prev.$short_tag] = $v;
				 }	
			 }
			 else if($key_blong == '')  //没有被短标记的
			 {
				 //$unit[$k] = urlencode($v);
				 $unit[$k] = $v;
			 }   
		}	
		$unit_str = '';
		if( ! empty($unit) )
		{
			foreach($unit as $k => $v)
			{
				$unit_str .= $k.":".$v."|";
				//$unit_str .= $k.":".urlencode($v)."|";
			}
			$unit_str = substr($unit_str, 0,-1);
		}	
		return $unit_str;
	}
	
	//search_url 反码
	function mall_search_url_decode($url)
	{
        //如果url包含&字符就直接解出来
        if(preg_match("/&/",$url))
        {
            parse_str($url,$ary);
            return $ary;
        }
        
		$url_search_config = pai_mall_paizhao_load_config('url_search');	
		if( empty($url) )
		{
			return false;
		}
		$url=urldecode($url);
        $url_ary = explode("|",$url);	
        $unit = array();
		//排除一些"|" 这个参数的一些影响
		$res = array();
		$first_k = '';
		foreach($url_ary as $k => $v)
		{
			if(strpos($v,':') === false)
			{
				$res[$k] = $v;
			}
		}		
		$i=1;
		$first_k = '';
		$j = array();
		foreach($res as $k => $v)
		{
			if( isset($res[$k+1]) )
			{
				if($i == 1)
				{
					$first_k = $k;
					$j[$first_k][$k] = $v;
				}
				else
				{
					$j[$first_k][$k] = $v;
				}				
				$i++;
			}
			else
			{
				if( isset($res[$k-1]) )
				{
					//连续的最后一个
					$j[$first_k][$k] = $v;
					$i = 1;
	
				}
				else
				{
					//单单一个
					if(empty($first_k))
					{
						$first_k = $k;
					}
					$j[$first_k][$k] = $v;
					$i = 1;
				}
			}
		}		
		foreach($j as $k => $v)
		{
			foreach($v as $vk => $vv)
			{
				$f[$k] .= "|".$vv;
				unset($url_ary[$vk]);
			}
	
		}
		foreach($f as $k => $v)
		{
			if($url_ary[$k-1])
			{
				$url_ary[$k-1] = $url_ary[$k-1].$v;
			}
			else
			{
				$url_ary[$k] = $url_ary[$k].$v;
			}
		}
		
		if( ! empty($url_ary) )
		{
			foreach($url_ary as $uk => $uv)
			{
				$uv_ary = explode(":",$uv);
				if( ! empty($uv_ary) )
				{
					if(preg_match("/gd_/",$uv_ary['0']))  //全文detail用的
					{
						$detail_data_ary = $detail_data = array();
						$detail_data = $uv_ary['1'];
						$detail_data_ary = explode("_",$detail_data);
						foreach($detail_data_ary as $dk => $dv)
						{
							$dv_ary = array();
							$dv_ary = explode(",",$dv);
							if( ! empty($dv_ary) )
							{
								$unit['detail'][$dv_ary['0']] = $dv_ary['1'];
								$unit['field_info']['detail'] = 'goods';
							}
	
						}
					}
					else if(preg_match("/gt_/",$uv_ary['0'])) //全文三级third 用的
					{
						$third_data = '';
						$third_data_ary = array();
	
						$third_data = $uv_ary['1'];
						$third_data_ary = explode("_",$third_data);
	
						if( ! empty($third_data_ary) )
						{
							foreach($third_data_ary as $tk => $tv)
							{
								$tv_ary = array();
								$tv_ary = explode(",",$tv);
								if( ! empty($tv_ary) )
								{
									$unit['third'][$tv_ary['0']] = $tv_ary['1'];
									$unit['field_info']['third'] = 'goods';
								}
							}
						}
					}
					else if(preg_match("/go_/",$uv_ary['0'])) //商品属性
					{
						$short_tag = $real_tag ='';
						$short_tag = str_replace("go_","",$uv_ary['0']);
						$real_tag = $url_search_config['goods'][$short_tag];
						if( ! empty($real_tag) )
						{
							$unit[$real_tag] = $uv_ary['1'];
							$unit['field_info']['third'] = 'goods';
						}
					}
					else if(preg_match("/ss_/",$uv_ary['0'])) // 商家属性
					{
						$short_tag = $real_tag ='';
						$short_tag = str_replace("ss_","",$uv_ary['0']);
						$real_tag = $url_search_config['seller'][$short_tag];
						if( ! empty($real_tag) )
						{
							$unit[$real_tag] = $uv_ary['1'];
							$unit['field_info'][$real_tag] = 'seller';
						}
	
					}
					else if(preg_match("/gg_/",$uv_ary['0'])) //公共属性
					{
						$short_tag = $real_tag ='';
						$short_tag = str_replace("gg_","",$uv_ary['0']);
						$real_tag = $url_search_config['common'][$short_tag];
						if( ! empty($real_tag) )
						{
							$unit[$real_tag] = $uv_ary['1'];
							$unit['field_info'][$real_tag] = 'common';
						}
					}
					else
					{
						//如果没找到就是没有短标记的直接key=>value就可以了
                        if( ! empty($uv_ary['0']) )
						{
                            $all_str = implode(":",$uv_ary);
							$unit[$uv_ary['0']] = str_replace($uv_ary['0'].":",'',$all_str);
							$unit['field_info'][$uv_ary['0']] = '';
						}
						
					}
				}
			}
		}	
		return $unit;	
	}
    
    //最近天数登录时间
    function get_last_login_time_text($time)
    {
        $now_time = date('Ymd',time());
        $login_time = date('Ymd',$time);
        $days = $now_time-$login_time;
        if($days <= 1)
        {
            $last_login_time_text = '一天内登录';
        }else if($days <= 3)
        {
            $last_login_time_text = '三天内登录';
        }else if($days <= 7)
        {
            $last_login_time_text = '七天内登录';
        }else if($days <= 30)
        {
            $last_login_time_text = '一个月内登录';
        }else if($days <= 180)
        {
            $last_login_time_text = '六个月内登录';
        }else if($days <= 365)
        {
            $last_login_time_text = '六个月内登录';
        }else if($days >365)
        {
            $last_login_time_text = '六个月内登录';
        }

        return $last_login_time_text;
    }
    
    //图文内容过滤
    function content_filter($data)
    {
        if(empty($data))
        {
            return array('result'=>-1,'message'=>'内容为空');
        }

         /**********编辑内容校验处理*************/
        //检查图片是否有Loading图
        $loadingclass_res = strpos($data,"loadingclass");
        if($loadingclass_res>0)
        {
            return array('result'=>-2,'message'=>'还有图片在加载，请稍后');
        }
        //检查图片张数
        $content_img_len = mall_src_link_len_check($data);
        if($content_img_len>20)
        {
            return array('result'=>-3,'message'=>'服务详情图片张数不能超过20张');

        }
        //检查图片是否本站上传
        $check = mall_src_link_check($data);
        if(!$check)
        {
            return array('result'=>-4,'message'=>'服务详情图片必须是本站上传的');
        }
        //编辑内容过滤处理2015-12-31处理BY星星
        $tmp_content = str_replace(array('&lt;','&gt;','&quot;'),array('<','>','"'),$data);
        //闭合标签处理
        $tmp_content = mall_closetags($tmp_content);
        //过滤处理
        $tmp_content = strip_tags($tmp_content,'<p><img><br><br/><br /><embed>');
        //对html字符串里进行属性过滤处理
        $tmp_content = preg_replace("/style=\"(.*)\"/isU","",$tmp_content);
        $tmp_content = preg_replace("/style=\'(.*)\'/isU","",$tmp_content);
        $tmp_content = preg_replace("/align=center/is","",$tmp_content);

        return array('result'=>1,'message'=>'成功返回','data'=>$tmp_content);

    }
	
	//显示信息
/*	$data=array(
	            'message',
	            'url',
	            'open',
	            'reload',
	            'close',
	            'q_exit',
				);
*/	//function mall_show_message($message,$url="",$open='',$reload=0,$close=false,$q_exit=1)
	function mall_show_message($message,$url="",$open='',$window='',$reload='',$close='',$q_exit=true)
	{
		$is_ajax = mall_is_ajax();
//		$message = $data['message'];
//		$url = $data['url'];
//		$open = $data['open'];
//		$reload = $data['reload'];
//		$close = $data['close'];
//		$q_exit = $data['q_exit'];
		
		$mess=$is_ajax?"":"<meta http-equiv='Content-Type' content='text/html; charset=".TASK_CHARSET."' />
		<script language='javascript'>";
		
		$mess.="alert('".$message."');";
		if($url!="")
		{
			$open_window = $open?$open:'_self';
			$window_id = $window?'.'.$window:'';
			$mess.="window{$window_id}.open('".$url."','".$open_window."','');";
		}
		elseif($reload==true)
		{
			$mess.="window.opener.document.location.reload();";
		}
		$mess.=$close?"window.close();":"";
		$mess.=$is_ajax?"":"</script>";
		echo $mess;
        if($q_exit)
        {
            exit;
        }
	}
    
    function mall_simple_filter($str)
    {
        return pai_mall_change_str_in(trim($str));
    }
    
    /**
     * 检查是否真实存在goods_id
     * @param type $goods_id
     * @return boolean
     */
    function mall_check_goods_id_is_exist($goods_id)
    {
        $goods_id = (int)$goods_id;
        if( ! $goods_id )
        {
            return false;
        }
        $obj = POCO::singleton('pai_mall_goods_class');
        $rs = $obj->get_goods_info($goods_id);
        if( $rs )
        {
            return true;
        }else
        {
            return false;
        }
    }
    /**
     * 检查商家user_id是否真实存在 
     * @param type $seller_user_id
     * @return boolean
     */
    function mall_check_seller_user_id_is_exist($seller_user_id)
    {
        $seller_user_id = (int)$seller_user_id;
        if( ! $seller_user_id )
        {
            return false;
        }
        $obj = POCO::singleton('pai_mall_seller_class');
        $rs = $obj->get_seller_info($seller_user_id,2);
        if($rs)
        {
            return true;
        }else
        {
            return false;
        }
        
    }
    /**
     * 检查event_type是否真实存在
     * @param type $event_type
     * @return boolean
     */
    function mall_check_event_type_is_exist($event_type)
    {
        $event_type = (int)$event_type;
        if( ! $event_type )
        {
            return false;
        }
        $obj = POCO::singleton('pai_mall_event_driven_class');
        $rs = $obj->get_event_driven_type_info_by_event_type($event_type);
        if($rs)
        {
            return true;
        }else
        {
            return false;
        }
        
        
    }
    
    /**
     * 检查商品分类id是否存在
     * @param type $type_id
     * @return boolean
     */
    function mall_check_type_id_is_exist($type_id)
    {
        $type_id = (int)$type_id;
        if( ! $type_id )
        {
            return false;
        }
        $obj = POCO::singleton('pai_mall_goods_type_class');
        $rs = $obj->get_type_info($type_id);
        if($rs)
        {
            return true;
        }else
        {
            return false;
        }
        
        
    }
    /**
     * 检查品类是否需要基础认证
     * @param type $type_id
     * @return boolean
     */
    function mall_check_is_need_basic($type_id)
    {
        $type_id = (int)$type_id;
        if( ! $type_id )
        {
            return false;
        }
        $obj = POCO::singleton('pai_mall_goods_type_class');
        $rs = $obj->get_type_for_cache($type_id);
        if($rs['is_audit'] == 1)
        {
            return true;
        }else
        {
            return false;
        }
    }
    
    /**
     * 检查品类是否需要品类认证
     * @param type $type_id
     * @return boolean
     */
    function mall_check_is_need_service($type_id)
    {
        $type_id = (int)$type_id;
        if( ! $type_id )
        {
            return false;
        }
        $obj = POCO::singleton('pai_mall_goods_type_class');
        $rs = $obj->get_type_for_cache($type_id);
        if($rs['is_type_audit'] == 1)
        {
            return true;
        }else
        {
            return false;
        }
    }
    
    /**
     * 检查商家是否能操作这个品类的商品
     * @param type $seller_user_id
     * @param type $type_id
     */
    function mall_check_seller_user_id_can_operate_type_id_goods($seller_user_id,$type_id)
    {
        $seller_user_id = (int)$seller_user_id;
        $type_id = (int)$type_id;
		$return = array(
						'need_audit'=>false, //是否需要基础认证
						'need_type_audit'=>false, //是否需要品类认证
						
						'audit'=>false, //有无通过基础认证
						'type_audit'=>false, //有无通过品类认证						
						
						'add_goods'=>false, //添加商品
						'update_goods'=>false,//更新商品
						'status'=>false, //能否审核
						'show_status'=>false, //能否上架
						'message'=>'参数错误不能操作',
						);
        if( ! $seller_user_id || ! $type_id )
        {
            return $return;
        }
		$message = '';
		//是否需要基础认证
        $need_audit = mall_check_is_need_basic($type_id);
		//有无通过基础认证
		$audit = false;
		$obj = POCO::singleton('pai_mall_certificate_basic_class');
		$user_info = $obj->get_person_status_by_user_id($seller_user_id);
		if($user_info['status'] == 1)
		{
			$audit = true;
		}
		//是否需要品类认证
        $obj = POCO::singleton('pai_mall_goods_type_class');
        $type_info = $obj->get_type_for_cache($type_id);
		$need_type_audit = $type_info['is_type_audit']==1?true:false;
		//是否有通过品类认证
		$type_audit = false;
		$obj = POCO::singleton('pai_mall_certificate_service_class');
		$rs = $obj->get_service_open_or_not($seller_user_id,$type_id);
		$message = "品类[{$type_info['name']}]需要通过品类认证的,商家还没通过请先通过";
		if( $rs )
		{
			$type_audit = true;
			$message = '';
		}
		elseif( !$need_type_audit)
		{
			$message = '';
		}
		//操作权限
		$add_goods = true;
		$update_goods = true;
		$status = false;
		$show_status = false;
		//
		if($need_audit)
		{
			if($audit)
			{
				if($need_type_audit)
				{
					if($type_audit)
					{
						$status = true;
						$show_status = true;			
					}
				}
				else
				{
					$status = true;
					$show_status = true;			
				}
			}
		}
		else
		{
			if($need_type_audit)
			{
				if($type_audit)
				{
					$status = true;
					$show_status = true;			
				}
			}
			else
			{
				$status = true;
				$show_status = true;			
			}
		}

		//
//		if(!$need_audit or !$need_type_audit or $type_audit)
//		{
//			$status = true;
//			$show_status = true;			
//		}
		//
		$return = array(
						'need_audit'=>$need_audit, //是否需要基础认证
						'need_type_audit'=>$need_type_audit, //是否需要品类认证
						
						'audit'=>$audit, //有无通过基础认证
						'type_audit'=>$type_audit, //有无通过品类认证						
						
						'add_goods'=>$add_goods, //添加商品
						'update_goods'=>$add_goods,//更新商品
						'status'=>$status, //能否审核
						'show_status'=>$show_status, //能否上架
						
						'message'=>$message,
						);
		return $return;
    }
    
    /**
     * 默认开通一些不需要服务认证的品类
     */
    //个人不要，服务不要 （成为商家时默认开通一些品类）
    // 个人要，服务不要  （通过基础认证时成为商家,默认开通过一些品类）
    function mall_open_type_id_default($user_id)
    {
        $user_id = (int)$user_id;
        if( ! $user_id )
        {
            return array('result'=>-1,'message'=>'user_id不能为空');
        }
        $default_open_type_id = array();
        $obj = POCO::singleton('pai_mall_goods_type_class');
        $list = $obj->get_type_cate(2);
        $type_id_ary = array();
        foreach($list as $k => $v)
        {
            if($v['is_type_audit'] != 1)
            {
                $type_id_ary[] = $v['id'];
            }
        }
        //开通品类的代码
        $mall_seller_obj = POCO::singleton('pai_mall_seller_class');
        $seller_info = $mall_seller_obj->get_seller_info($user_id,2);
        $res = $mall_seller_obj->update_seller_profile_type_id($seller_info['seller_data']['profile']['0']['seller_profile_id'],$type_id_ary,false);
        
//        if($res['result'] == 1)
//        {
//            $service_obj = POCO::singleton('pai_mall_certificate_service_class');
//            //通过服务就将商家状态变为1
//            $service_obj->update_seller_status($user_id,1);
//        }
        
        return $res;
    }
    
    
    /**
     * 加入后台日志
     * @global type $yue_login_id
     * @global type $_INPUT
     * @param type $type_name_id
     * @param type $type_id
     * @param type $action_id
     * @param type $note
     */
    function mall_add_admin_log($type_name_id,$type_id,$action_id,$note='')
    {
        global $yue_login_id;
        global $_INPUT;
        
        //删除的log
        $task_log_obj = POCO::singleton('pai_task_admin_log_class');
        $task_log_obj->add_log($yue_login_id,$type_name_id,$type_id,$_INPUT,$note,$action_id);
        
    }
    
    /**
     * 获取后台日志列表按类型
     * @param type $action_type
     * @param type $action_id
     * @return type
     */
    function mall_get_admin_log_list($action_type,$action_id)
    {
        $task_log_obj = POCO::singleton('pai_task_admin_log_class');
        $log_list = $task_log_obj->get_log_by_type(array('action_type'=>$action_type,'action_id'=>$action_id));
        if($log_list)
        {
            foreach($log_list as $key => $val)
            {
                $log_list[$key]['add_time'] = date('Y-m-d H:i:s',$val['add_time']);
                $log_list[$key]['user_name'] = mall_get_admin_nickname_by_user_id($val['admin_id']);
            }
        }
        return $log_list;
    }
    
    /**
     * 获取头条信息列表模板
     * @param array $data
     * @return int
     */
    function mall_get_news_style_id($data)
    {
		$style = 0;
		$class_id = (int)$data['class_id'];
		switch($class_id)
		{
			case 1://文章
				$style = 18;
			    if($data['images'])
				{
					$style = 19;//有封面图
					if($data['go_url'])//跳转页面
					{
						$style = 22;
					}
				}
			break;
			case 2://图片
				$style = 21;
			break;
			case 3://视频
				$style = 20;
			break;
		}
		return $style;
    }
	
	
    /**
     * 过滤特殊字符
     * @return str
     */
	function mall_clean_value($val)
	{
		if(defined('G_INPUT_NO_CLEAN_VALUE'))
		{
			return $val;
		}

		if ($val == "")
		{
			return "";
		}

		if (ini_get("magic_quotes_gpc"))
		{
			$val = stripslashes($val);
		}


		$val = preg_replace("/&lt;br rel=auto&gt;/i", "\n", $val); //tony 20080320
		$val = preg_replace("/&amplt;br rel=auto&ampgt;/i", "\n", $val); //tony 20080320

		$val = str_replace("&#032;", " ", $val);
		/*
		if (!defined('G_SIMPLE_INPUT_CLEAN_VALUE'))
		{
		$val = str_replace("&", "&amp;", $val);
		}*/
		$val = str_replace("<!--", "&#60;&#33;--", $val);
		$val = str_replace("-->", "--&#62;", $val);
		$val = str_replace("script", "", $val);
		$val = preg_replace("/<script/i", "&#60;script", $val);
		$val = str_replace("%3E", "&gt;", $val); // > 的encode
		$val = str_replace("%3C", "&lt;", $val); // < 的encode
		$val = str_replace(">", "&gt;", $val);
		$val = str_replace("<", "&lt;", $val);
		$val = str_replace("\"", "&quot;", $val);
		//$val = preg_replace("/\|/", "&#124;", $val);
		if (!defined('G_SIMPLE_INPUT_CLEAN_VALUE'))
		{
			$val = preg_replace("/\n/", "<br rel=auto>", $val); // Convert literal newlines
			$val = preg_replace("/\r/", "", $val); // Remove literal carriage returns
		}
		$val = preg_replace("/\\\$/", "&#036;", $val);


		//$val = str_replace("!", "&#33;", $val);
		$val = str_replace("'", "&#39;", $val); // IMPORTANT: It helps to increase sql query safety.
		$val = str_replace("%27", "&#39;", $val);

		//$val = stripslashes($val); // Swop PHP added backslashes
		$val = preg_replace("/\\\/", "&#092;", $val); // Swop user inputted backslashes
		return $val;
	}
	
    
    /**
     * 接收get post 数据 转换特殊字符 $_INPUT
     * @return array
     */
    function mall_format_input_data($data)
    {
		foreach($data as $key => $val)
		{
			if(is_array($val))
			{
				$data[$key]=mall_format_input_data($val);
			}
			else
			{
				//$data[$key] = str_replace(array("'","\\"),array("&#39;","&#092;"),htmlspecialchars($val));
				$data[$key] = mall_clean_value($val);
			}
		}
		return $data;
    }

    
    /**
     * 获取log_list的html
     * @param type $action_type
     * @param type $action_id
     * @return type
     */
    function mall_get_log_list_html($action_type,$action_id)
    {
        $log_list = mall_get_admin_log_list($action_type,$action_id);
        $log_content = '';
        foreach($log_list as $k => $v)
        {
            $tr_str = '';
            $tr_str = "<tr>"
                        . "<td width='26%' height='25' align='center' valign='middle'>{$v['type_name']}</td>"
                        . "<td width='26%' height='25' align='center' valign='middle'>{$v['add_time']}</td>"
                        . "<td width='26%' height='25' align='center' valign='middle'>{$v['user_name']}</td>"
                        . "<td width='26%' style='word-break: break-all;' height='25' align='center' valign='middle'>{$v['note']}</td>"
                    . "</tr>";
            $log_content .= $tr_str;
        }
        $log_list_html = <<<log_list_html
            <div class="mainbox">
               <div id="tabs2" style="margin-top:10px;">
                   <div class="tabbox">
                       <div class="table-list">
                           <div class="table" style="width:100%;">
                               <fieldset>
                                   <legend>操作记录</legend>
                                   <form id="form2" name="form1" method="post" action="" target="_self">
                                       <table width="90%" align="center">
                                           <tr>
                                               <td width="26%" height="25" align="center" valign="middle">操作类型</td>
                                               <td width="26%" height="25" align="center" valign="middle">时间</td>
                                               <td width="26%" height="25" align="center" valign="middle">操作人</td>
                                               <td width="26%" heigth="25" align="center" valign="middle">备注</td>
                                           </tr>
                                           $log_content
                                           <tr>
                                               <td colspan="4" align="center">&nbsp;</td>
                                           </tr>
                                       </table>
                                   </form>
                               </fieldset>
                           </div>
                       </div>
                   </div>
               </div>
          </div>       
log_list_html;

        return $log_list_html;
    }
	
    
    /**
     * js iframe 回调方法
     * @param type $json_data
     */
    function mall_reponse_js($json_data,$on_which)
    {
        if($on_which == 'parent')
        {
            $js_html = <<<js_html
            <script>
                parent.js_callback($json_data);
            </script>
js_html;
        }else
        {
            $js_html = <<<js_html
            <script>
                top.js_callback($json_data);
            </script>
js_html;
        }
        
        echo $js_html;
    }
    
    /**
     * 对这类字符串过滤 123,456,789
     * @param type $str
     * @return str
     */
    function mall_format_data_input_for_string_int($string)
    {
        $str_new = '';
        if(is_array($string))
        {
            $str = implode(',',$string);
        }
        else
        {
            $str = $string;
        }
        $str = mall_simple_filter($str);
        $str = str_replace(array('，','，'),array(',',','), $str);
        if( empty($str) )
        {
            return $str_new;
        }
        $str_ary = explode(',',$str);
        if( ! empty($str_ary) )
        {
            $str_new_ary = array();
            foreach($str_ary as $k => $v)
            {
                if((int)$v)
                {
                    $str_new_ary[] = (int)$v;
                }
            }
            $str_new_ary = array_unique(array_filter($str_new_ary));
            if( ! empty($str_new_ary) )
            {
                $str_new = implode(',',$str_new_ary);
            }

        }
        return $str_new;
    }
    
    function mall_online_editor_content_filter($data)
    {
         $data = str_replace(array('&lt;','&gt;','&quot;'),array('<','>','"'),$data);
         return $data;
    }



    /*
     * @param str $page_match_location_id//做兼容处理的页面地区ID
     * @param int $level//显示层级，最小为两层，目前函数支持三级
     * @param str $input_name_id//组件提交form的地区隐藏框的name跟id值，传入不同，增加多个地区筛选
     * @param str $attr_str//组件的属性值，以字符串形式添加，可以不传，为默认值
     * @param bool $need_default//是否需要缺省值
     * @param array $select_name_array //对应的select的name的配置数组，按顺序为省-市-区
     *
     * return array //兼容处理后的地区ID以及地区组件
    */
    function mall_location_widgets_handle($page_match_location_id,$level,$input_name_id="widget_location_id",$attr_str='class="ui-admin-location-widget"',$need_default=true,$select_name_array = array("ui-province-select","ui-city-select","ui-district-select"))
    {
        $pai_mall_zone_obj = POCO::singleton('pai_mall_zone_class');
        require_once '/disk/data/htdocs232/poco/pai/yue_ui/module/pai_fe_widgets_class.inc.php';//地区组件
        $widgets_obj = new pai_fe_widgets_class();
        $level = (int)$level;

        if($level<2)
        {
            return "层级参数不正确，目前支持2层或者3层";
        }

        if($level>3)
        {
            $level = 3;
        }
        
        if(empty($page_match_location_id))
        {
            if($need_default)
            {
                $match_location_id = 101029001001;//默认广州天河
                $info_city_id = substr($match_location_id,0,9);//保证是市地区id,9位
                $info_province_id = substr($info_city_id,0,6);//保证是省id，6位
                $info_district_id = $match_location_id;
            }
            else
            {
                $match_location_id = "";
                $info_city_id = 0;
                $info_province_id = 0;
                $info_district_id = 0;
            }

        }
        else
        {
            //判断当前的location_id的位数
            $match_location_array = $pai_mall_zone_obj->get_zone_info_by_location_id($page_match_location_id);
            $match_location_id = $match_location_array["id"];
            //$match_location_id = $page_match_location_id;//商家版1.9上线，还未使用最新数据
            $location_id_len = strlen($match_location_id);
            //返回一个省的值
            if($location_id_len==6)//6位
            {
                $info_district_id = 0;
                $info_city_id = 0;
                $info_province_id = substr($info_city_id,0,6);
            }
            else if($location_id_len==9)//9位
            {
                $info_district_id = 0;
                $info_city_id = substr($match_location_id,0,9);
                $info_province_id = substr($match_location_id,0,6);
            }
            else if($location_id_len==12)//12位
            {
                $info_district_id = $match_location_id;
                $info_city_id = substr($info_district_id,0,9);//保证是市地区id,9位
                $info_province_id = substr($info_city_id,0,6);//保证是省id，6位
            }

        }
        if($level==2)
        {
            $widget_location = $widgets_obj->get_location_widget($input_name_id,true,array('province_id'=>$info_province_id,'province_name'=>$select_name_array[0],'city_id'=>$info_city_id,'city_name'=>$select_name_array[1]),$attr_str);
            
        }
        else if($level==3)
        {
            $widget_location = $widgets_obj->get_location_widget($input_name_id,true,array('province_id'=>$info_province_id,'province_name'=>$select_name_array[0],'city_id'=>$info_city_id,'city_name'=>$select_name_array[1],'district_id'=>$info_district_id,'district_name'=>$select_name_array[2]),$attr_str);
        }


        $return_array = array(
            "match_location_id" => $match_location_id,
            "widget_location" => $widget_location,

        );
        return $return_array;

    }
    
//    $ary['name'] = 'fruit[]';
//    $ary['data'] = array(
//        array('id'=>'1','text'=>'苹果'),
//        array('id'=>'2','text'=>'香蕉'),
//        array('id'=>'3','text'=>'雪梨'),
//    );
//    $ary['selected'] = 2; 可以传数组array(1,2)达到复选效果
    
    /**
     * checkbox视图化
     * @param type $ary
     * @return type
     */
    function mall_checkbox_html($ary)
    {
        $check_box_html = '';
        if($ary['show_name'])
        {
            $check_box_html .= "<span>{$ary['show_name']}:</span>";
        }
        foreach($ary['data'] as $k => $v)
        {
            $is_selected = '';
            if( in_array($v['id'],$ary['selected']) )
            {
                $is_selected = "checked='checked'";
            }
            $check_box_html .= "<input type='checkbox' name='{$ary['name']}' {$is_selected} value='{$v['id']}'/>{$v['text']}&nbsp;&nbsp;";
        }
        return $check_box_html;

    }

    /**
     * radio视图化
     * @param type $ary
     * @return type
     */
    function mall_radio_html($ary)
    {
        $radio_html = '';
        if($ary['show_name'])
        {
            $radio_html .= "<span>{$ary['show_name']}:</span>";
        }
        foreach($ary['data'] as $k => $v)
        {
            $is_selected = '';
            if( $v['id'] == (int)$ary['selected'] )
            {
                $is_selected = "checked='checked'";
            }
            $radio_html .= "<input type='radio' name='{$ary['name']}' {$is_selected} value='{$v['id']}'/>{$v['text']}&nbsp;&nbsp;";
        }
        return $radio_html;
    }

    /**
     * select视图化
     * @param type $ary
     * @return type
     */
    function mall_select_html($ary,$is_need_all=true)
    {
        $select_html = '';
        if($ary['show_name'])
        {
            $select_html .= "<span>{$ary['show_name']}:</span>";
        }
        $select_html .= "<select name='{$ary['name']}' >";
        
        if($is_need_all)
        {
            $select_html .= "<option value='-1'>请选择</option>";
        }
        
        foreach($ary['data'] as $k => $v)
        {
            $is_selected = '';
            if($v['id'] == $ary['selected'])
            {
                $is_selected = "selected='selected'";
            }
            $select_html .= "<option value='{$v['id']}' {$is_selected} >{$v['text']}</option>";
        }
        $select_html .= "</select>";

        return $select_html;
    }
    
    /**
     * input视图化
     * @param type $ary
     * @return type
     */
    function mall_input_html($ary)
    {
        $input_html = '';
        if($ary['show_name'])
        {
            $input_html .= "<span>{$ary['show_name']}:</span>";
        }
        $input_html .= "<input type='text' name='{$ary['name']}' value='{$ary['selected']}' placeholder='{$ary['placeholder']}' />";
        return $input_html;
    }
    
    //状态后台下拉html
    function get_status_list_for_admin_html($status='',$is_show_name=true)
    {
         $data = array();
         $data = array(
             array('id'=>'1','text'=>'有效'),
             array('id'=>'2','text'=>'无效'),
         );
         $ary = array();
         if($is_show_name)
         {
             $ary['show_name'] = '状态';
         }
         $ary['selected'] = $status;
         $ary['name'] = 'status';
         $ary['data'] = $data;
         $select_html = mall_select_html($ary);
         return $select_html;
    }
    
    //关键字后台搜索html
    function get_keywords_input_for_admin_html($ary)
    {
        $ary['show_name'] = '关键字';
        $ary['selected'] = $ary['keywords'];
        $ary['name'] = 'keywords';
        $input_html = mall_input_html($ary);
        return $input_html;
    }
    
    
    //socket 请求  
    //
    //要设置这三样
    //set_time_limit(0);
    //ignore_user_abort(true);
    //ini_set('memory_limit', '512M');
    //
    //$url = "http://www.yueus.com/yue_admin/task/__koko_test.php?token=doaction_yue_admin_go&doit=Y";
    //mall_fsockopen_curl("www.yueus.com", $url.'&t_id=3&limit='.$i);
    function mall_fsockopen_curl($hostname, $url)
    {
        echo $url.'<br>';
        $fp = fsockopen($hostname, 80, $errno, $errstr, 5);  
        if (!$fp) {  
            echo "$errstr ($errno)";  
            return false;  
        }
        stream_set_blocking($fp,0);//非阻塞模式  
        $header = "GET $url HTTP/1.1\r\n";  
        $header.="Host: $hostname\r\n";  
        $header.="Connection: Close\r\n\r\n";//长连接关闭  
        fwrite($fp, $header);  
        fclose($fp);  
    }
    
    /**
     * 动态调用类里的方法
     * @param type $class
     * @param type $method
     * @param type $params
     * @return type
     */
    function mall_use_class_method($class,$method,$params='')
    {
        $obj = POCO::singleton($class);
        return call_user_func(array($obj,$method),$params); 
    }
    
    /**
     * 获取后台菜单 
     * 
     */
    function mall_get_admin_menu_v2()
    {
        global $yue_login_id;
        //没登录
        if(empty($yue_login_id))
        {
            $r_url = urlencode($_SERVER['SCRIPT_URI']);
            header("location:http://www.yueus.com/yue_admin/login_e.php?referer_url=".$r_url);
            exit;
        }
        $admin_user_obj = POCO::singleton('pai_mall_admin_user_class');
        $person = $admin_user_obj->get_acl_user_cache($yue_login_id); 
        //已经失效
        if($person['status'] == 2)
        {
            return false;
        }
        
        $admin_info = mall_get_user_role_and_acl_can_do($yue_login_id);
        $menu_config = pai_mall_paizhao_load_config('admin_menu_task');
        foreach($menu_config as $k => $v)
        {
            if(in_array($v['acl_root_id'],$admin_info['can_do_acl_list']) || $admin_info['admin_type_id'] == 1)
            {
                $menu_config[$k]['is_show'] = 1;
            }
            foreach($v['list'] as $lk => $lv)
            {
                if(in_array($lv['acl_root_id'],$admin_info['can_do_acl_list']) || $admin_info['admin_type_id'] == 1)
                {
                    $menu_config[$k]['list'][$lk]['is_show'] = 1;
                }

                foreach($lv['children_list'] as $ck => $cv)
                {
                    
                    if($admin_info['admin_type_id'] == 1) //是否为1的超级管理员
                    {
                       $menu_config[$k]['list'][$lk]['children_list'][$ck]['is_show'] = 1;
                    }
                    elseif( ! empty($cv['profession_id']) ) //职业的id不为空
                    {
                        //是否有全职业权限
                        if( in_array(482,$admin_info['can_do_acl_list']) )
                        {
                            $menu_config[$k]['list'][$lk]['children_list'][$ck]['is_show'] = 1;
                        }else if( in_array($cv['acl_root_id'],$admin_info['can_do_acl_list']) )
                        {
                            $menu_config[$k]['list'][$lk]['children_list'][$ck]['is_show'] = 1;
                        }
                        
                    }elseif( ! empty($cv['type_id']) ) //分类的id不为空
                    {
                        //是否有全品类权限
                        if(in_array(229,$admin_info['can_do_acl_list']))
                        {
                            $menu_config[$k]['list'][$lk]['children_list'][$ck]['is_show'] = 1;
                        }else if( in_array($cv['acl_root_id'],$admin_info['can_do_acl_list']) )
                        {
                            $menu_config[$k]['list'][$lk]['children_list'][$ck]['is_show'] = 1;
                        }
                    }
                    elseif( in_array($cv['acl_root_id'],$admin_info['can_do_acl_list']) )
                    {
                        $menu_config[$k]['list'][$lk]['children_list'][$ck]['is_show'] = 1;
                    }
                }

            }
        }
        
        return $menu_config;

    }
    
    /**
     * 获取新地区名
     * @param type $location_id
     * @return type
     */
    function mall_get_location_name_new($location_id)
    {
        $location_id = mall_simple_filter($location_id);
        $obj = POCO::singleton('pai_mall_zone_class');
        $rs = $obj->get_zone_info_by_location_id($location_id);
        if( ! empty($rs['parents_name_list']) )
        {
            return $rs['parents_name_list'];
        }else
        {
            return $rs['name'];
        }
    }
    
    /**
     * 过滤xss函数 
     * @param type $string
     * @param type $low 安全级别低
     * @return boolean
     */
    function mall_clean_xss(&$string, $low = False)
    {
        if (! is_array ( $string ))
        {
            $string = trim ( $string );
            $string = strip_tags ( $string );
            $string = htmlspecialchars ( $string );
            if ($low)
            {
                return True;
            }
            $string = str_replace ( array ('"', "\\", "'", "/", "..", "../", "./", "//" ), '', $string );
            $no = '/%0[0-8bcef]/';
            $string = preg_replace ( $no, '', $string );
            $no = '/%1[0-9a-f]/';
            $string = preg_replace ( $no, '', $string );
            $no = '/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/S';
            $string = preg_replace ( $no, '', $string );
            return True;
        }
        $keys = array_keys ( $string );
        foreach ( $keys as $key )
        {
            mall_clean_xss ( $string [$key] );
        }
    }
    
    
    /**
     * 过滤xss函数 
     * @param type $string
     * @param type $low 安全级别低
     * @return boolean
     */
    function mall_url_to_array($str)
    {
        $data = array();
        $parameter = explode('&',end(explode('?',$str)));
        foreach($parameter as $val)
        {
            $tmp = explode('=',$val);
            $data[$tmp[0]] = $tmp[1]; 
        }
        return $data;
    }
    
	
?>