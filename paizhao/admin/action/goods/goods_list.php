<?php
require(dirname(dirname(__FILE__)).'/common.inc.php');
require_once("goods_public.php");
$status = (int)$_INPUT ['status'];
$edit_status = (int)$_INPUT ['edit_status'];
$show = (int)$_INPUT ['show'];
$type_id = (int)$_INPUT ['type_id'];
$audit_again = (isset($_INPUT['audit_again']) and $_INPUT['audit_again']!=='')?(int)$_INPUT ['audit_again']:'';
$keyword = trim($_INPUT['keyword']);
$_INPUT['orderby'] = $_INPUT['orderby']?$_INPUT['orderby']:9;		
//$begin_time = $_INPUT ['begin_time']?$_INPUT ['begin_time']:date('Y-m-d', strtotime('-7 day'));
//$end_time = $_INPUT ['end_time']?$_INPUT ['end_time']:date('Y-m-d');
$begin_time = $_INPUT ['begin_time']?$_INPUT ['begin_time']:'';
$end_time = $_INPUT ['end_time']?$_INPUT ['end_time']:'';

$show_black = false;
$check_show_black = mall_check_admin_permissions($yue_login_id,'goods_show_black');
if($check_show_black['result'] == 1)
{
	$show_black = true;
}

if( ! isset($_INPUT['is_black']) )
{
    $_INPUT['is_black'] = 0;
}
$type_obj = POCO::singleton('pai_mall_goods_type_class');
//$type_list = $type_obj->get_type_cate(2);
$type_list = mall_get_open_type_list($yue_login_id);
$own_type_id = array();
foreach($type_list as $k => $v)
{
    if($v['id'] == $type_id)
    {
        $type_list[$k]['selected'] = 1;
    }
    $type_list_name[$v['id']] = $v;
	if($v['id'])
	{
		$own_type_id[] = $v['id'];
	}
}



$search_type_list = $type_obj->get_first_cate();

$profession_obj = poco::singleton('pai_mall_profession_class');

//$type_list_name = array();
//foreach($type_list as $key => $val)
//{
//    $type_list[$key]['show'] = true;
//    $type_list[$key]['selected'] = $val['id']==$type_id?true:false;
//    $type_list_name[$val['id']] = $val;
//}
$show_status = pai_mall_paizhao_load_config("goods_show");
foreach($show_status as $key => $val)
{
    $show_list[] = array(
                        'key'=>$key,
                        'name'=>$val,
                        'selected'=>$key==$show?true:false,
                        );
}
////////////
$goods_status_list[]=array(
                          'key' => 10,
                          'name' => "全部",
                          'selected' => $status===10?true:false,
                          );
foreach($status_name as $key => $val)
{
    $goods_status_list[] = array(
                                  'key' => $key,
                                  'name' => $val,
                                  'selected' => $status===$key?true:false,
                                  );
}
//$where = "type_id != '42'";
$where = $own_type_id?"type_id in (".implode(',',$own_type_id).") ":"1 ";
if($audit_again !== '')
{
	$where = "audit_again='$audit_again' ";
}
//$where .= $status != 10?" AND status = '{$status}'":"";
if($status != 10)
{
    if($type_id == 42 and $status == 0)
    {
        $where .= " AND (status = '{$status}' OR edit_status = 1)";
    }
    else
    {
        $where .= " AND status = '{$status}'";
    }
}
$where .= $show?" AND is_show = '{$show}'":"";
$where .= $type_id?" AND type_id = '{$type_id}'":"";
$where .= $keyword?" AND (titles like '%".pai_mall_change_str_in($keyword)."%' OR user_id = '".(int)$keyword."' OR goods_id = '".(int)$keyword."')":"";
if($status!=0 and $begin_time and $end_time)
{
    $begin_time_s = strtotime($begin_time);
    $begin_time_s = $begin_time_s?$begin_time_s:0;
    $end_time_s = strtotime($end_time);
    $end_time_s = $end_time_s?$end_time_s+86400:0;
    $where .= " AND audit_time >= {$begin_time_s} AND audit_time <= {$end_time_s}";
}
elseif($status==0 and $begin_time and $end_time)
{
    $begin_time_s = strtotime($begin_time);
    $begin_time_s = $begin_time_s?$begin_time_s:0;
    $end_time_s = strtotime($end_time);
    $end_time_s = $end_time_s?$end_time_s+86400:0;
    $where .= " AND add_time >= {$begin_time_s} AND add_time <= {$end_time_s}";
}
$is_black = 10;

if( isset($_INPUT['is_black']) and $show_black)
{
    $is_black = $_INPUT['is_black'];
    if($is_black != 10)
    {
        $where .= " AND is_black= '{$is_black}'";
    }
}
elseif($_INPUT['action'] == 'goods_auto_list')
{
	//$where .= "";
}
else
{
	$where .= " AND is_black= 0";
}
$is_auto_accept = $_INPUT['is_auto_accept'];
$is_auto_sign = $_INPUT['is_auto_sign'];
if($_INPUT['action'] == 'goods_auto_list')
{
    $where .= " and (is_auto_sign='1' or is_auto_accept='1')";
}

$profession_id = (int)$_INPUT['profession_id'];
if($profession_id > 0)
{
    $where .= " and profession_id='{$profession_id}'";
}
$att_id = (int)$_INPUT['att_id'];
if($att_id > 0)
{
    $where .= " and att_type_id like '%{$att_id}%'";
}
$user_profession = mall_get_admin_profession($yue_login_id);
if($user_profession['all'] != 1)
{
    $profession_ary = array();
    foreach($user_profession as $k => $v)
    {
        if($v == 1 && $k !='all' )
        {
            $profession_ary[] = $k;
        }
        
    }
    if( ! empty($profession_ary) )
    {
        $where .= " and profession_id in (".implode(',',$profession_ary).")";
    }else
    {
        $where .= " and profession_id = '-1'";
    }
    
    
}

if(isset($_INPUT['basic_status']))
{
    $basic_status = (int)$_INPUT['basic_status'];
    if($basic_status >= 0)
    {
        //未提交资料
        if($basic_status == 3)
        {
            $where .= " and user_id not in ( select DISTINCT(user_id) from mall_db.mall_certificate_basic_tbl where 1 )";
            
        }else
        {
            //搜索不通过时，要过滤了那些最终通过了的数据
            if($basic_status == 2)
            {
                $where .= " and user_id not in (select user_id from mall_db.mall_certificate_basic_tbl where status='1' order by basic_id desc)";
            }
            $where .= " and user_id in (select user_id from mall_db.mall_certificate_basic_tbl where status='{$basic_status}')";
        }
        
    }
    
}else
{
    $_INPUT['basic_status'] = -1;
}

//$post = mall_set_post_for_search($_POST);
//echo $post;
//print_r(mall_get_post_for_search($post));
$total_count = $task_goods_obj->get_goods_list(true, $where);		
$page_obj = new show_page ();
$show_count = 20;
$page_obj->setvar ( 
    array (
        "audit_again"=>$audit_again,
        "status" => $status,
        "begin_time" => $begin_time,
        "end_time" => $end_time,
        "show" => $show,
        "type_id" => $type_id,
        "keyword" => $keyword,
        'is_black'=>$is_black,
        'is_auto_accept'=>$is_auto_accept,
        'is_auto_sign'=>$is_auto_sign,
        'hide_other_button'=>(int)$_INPUT['hide_other_button'],
        'action'=>$_INPUT['action'],
        'wap'=>$_INPUT['wap'],
        'profession_id'=>$_INPUT['profession_id'],
        'att_id'=>$_INPUT['att_id'],
        'basic_status'=>$_INPUT['basic_status'],
    ) 
);

$page_obj->set ( $show_count, $total_count );		
$order_by = $audit_again!==''?"add_time asc":"add_time desc";
$list = $task_goods_obj->get_goods_list(false, $where,$order_by , $page_obj->limit());
$task_seller_obj = POCO::singleton('pai_mall_seller_class');
$add_time_color = '';
$type_id_and_property_id_config = pai_mall_paizhao_load_config('goods_type_id_and_property_id');
$basic_obj = poco::singleton('pai_mall_certificate_basic_class');
foreach($list as $key => $val)
{
    $seller_info = $task_seller_obj->get_seller_info($val['user_id'],2);
    $list[$key]['user_name'] = $seller_info['seller_data']['name'];
    $profession_info = $profession_obj->get_profession_info_for_front($val['profession_id']);
    $list[$key]['profession_name'] = $profession_info['name'];
    $list[$key]['type_name'] = $type_list_name[$val['type_id']]['name'];
    $list[$key]['status_name'] = $status_name[$val['status']];
    $list[$key]['show_name'] = $show_status[$val['is_show']];
    $list[$key]['goods_pid'] = $val['type_id']==42?1220152:1220102;
    $basic_rs = $basic_obj->get_basic_status_text($val['user_id']);
    $list[$key]['basic_status_text'] = $basic_rs['message'];

    if( $val['update_time'] > 0)
    {
        $compare_time = $val['update_time'];
    }else if($val['add_time'] > 0)
    {
        $compare_time = $val['add_time'];
    }

    $now = time();

    if( ($now-$compare_time) > 24*3600 && $val['status'] == 0 )
    {
        $add_time_color = 'rgb(245, 60, 60);';
    }

    $list[$key]['add_time_color'] = $add_time_color;
    $list[$key]['add_time'] = date("Y-m-d H:i:s",$val['add_time']);
    $list[$key]['update_time'] = $val['update_time']>0?date("Y-m-d H:i:s",$val['update_time']):"未修改";
    $list[$key]['audit_time'] = $val['audit_time']>0?date("Y-m-d H:i:s",$val['audit_time']):"未审核";
    $location_id = explode(',',$val['location_id']);
    $location_name = '';
    foreach($location_id as $val_de)
    {
        $location_name .= ($location_name?"<br>":"").($val_de>10?get_poco_location_name_by_location_id($val_de):($val_de==0?"全国":"其他地区"));
    }
    $list[$key]['location_name'] = $location_name;
    $list[$key]['seller_status'] = $task_seller_obj->get_seller_status($val['user_id'],2);

    $goods_data = $task_goods_obj->get_goods_info_for_search($val['goods_id']);
    
    //分类:摄影培训->体验/速成培训
    foreach($goods_data['system_data'] as $sk => $sv)
    {
        if($sv['id'] == $type_id_and_property_id_config[$val['type_id']])
        {
            foreach($sv['child_data'] as $sck => $scv)
            {
                if($scv['is_select'] == 1)
                {
                    if( empty($scv['child_data']) )
                    {
                        $list[$key]['first_cate_name'] = $scv['name'];
                        break;
                    }else
                    {
                        foreach($scv['child_data'] as $scvk => $scvv)
                        {
                            if($scvv['is_select'] == 1)
                            {
                                $list[$key]['first_cate_name'] = $scv['name'].'->'.$scvv['name'];
                                break;
                            }
                        }
                    }
                    
                    
                }
            }
        }
    }
    
    
    //$list[$key]['edit_status_real'] = $goods_data['goods_data']['edit_status'];
    $list[$key]['edit_status_real'] = $val['edit_status'];
    if ($val['edit_status'] == 2)
    {
        $list[$key]['edit_status_text'] = '<br>修改审核通过';
    }
    else if ($val['edit_status'] == 3)
    {
        $list[$key]['edit_status_text'] = '<br>修改审核不通过';
    }
    else if ($val['edit_status'] == 1)
    {
        $list[$key]['edit_status_text'] = '<br>修改未审核';
    }

    $list[$key]['seller_rating'] = $seller_info['seller_data']['rating'];
    $seller_rating_config = pai_mall_paizhao_load_config('seller_rating');
    $type_id_rating_config = $seller_rating_config[$val['type_id']];
    $seller_rating_ary = array();
    $seller_rating_ary = explode(',',$list[$key]['seller_rating']);
    $type_id_is_rating = true;
    foreach($seller_rating_ary as $ks => $vs)
    {
        if( ! empty($vs) )
        {
            $vs_ary = array();
            $vs_ary = explode("-",$vs);
            if($val['type_id'] == $vs_ary['0'])
            {
                $type_id_is_rating = false;
                $list[$key]['cur_type_rating'] = $type_list_name[$vs_ary['0']]['name'].":".$seller_rating_config[$vs_ary['0']][$vs_ary['1']]['text'];
            }

            $list[$key]['all_type_rating'][] = array(
                'type_name'=>$type_list_name[$vs_ary['0']]['name'],
                'rating_name'=>$seller_rating_config[$vs_ary['0']][$vs_ary['1']]['text'],
            );
        }
    }
    if($type_id_is_rating)
    {
        $list[$key]['cur_type_rating'] = $type_list_name[$val['type_id']]['name'].":"."未评级";

    }

    $rs = $pai_app_last_login_obj->get_app_last_login_time($val['user_id'],'yueseller');
    $last_time = $rs['yueseller_login_time'];
    $list[$key]['last_login_text'] = get_last_login_time_text($last_time);


    ////活动还在测试，所以拿状态时，传个false,默认是true代表线上
//    if($val['type_id'] == 42)
//    {
//        $service_status = $service_obj->check_user_activity_open_or_not($val['user_id']);
//    }else
//    {
//       $service_status = $service_obj->get_service_open_or_not($val['user_id'],$val['type_id'],false);
//    }
//
//    if(!$service_status )
//    {
//        $list[$key]['seller_status'] = 0;
//    }
    ////
    unset($add_time_color);

    $pai_risk_obj = POCO::singleton('pai_risk_class');
    $seller_scalping_info = $pai_risk_obj->get_scalping_seller_info($val['user_id']);
    if ($seller_scalping_info['add_by']=='system')
    {
        //系统
        $list[$key]['is_flush'] = 1;
        $list[$key]['scalping_titile'] = '刷单嫌疑(系统)';
    } elseif($seller_scalping_info['add_by']=='manual')
    {
        //人工
        $list[$key]['is_flush'] = 1;
        $list[$key]['scalping_titile'] = '刷单嫌疑(人工)';
    }
    
    $task_log_obj = POCO::singleton('pai_task_admin_log_class');
    $not_pass_log_list = $task_log_obj->get_log_by_type_last(array('type_id'=>2007,'action_id'=>$val['goods_id']));
    if( empty($not_pass_log_list['note']) )
    {
        $not_pass_log_list['note'] = '空';
    }
    
    $list[$key]['not_pass_log_list'] = $not_pass_log_list;
    $obj = poco::singleton('pai_mall_certificate_service_class');
    $service_info = $obj->get_service_id_and_service_type_by_user_id($val['user_id'],$val['type_id']);
    if($service_info)
    {
        $list[$key]['have_certificate'] = 1;
        $list[$key]['service_id'] = $service_info['service_id'];
        $list[$key]['service_type'] = $service_info['service_type'];
    }
    $rs = mall_check_seller_user_id_can_operate_type_id_goods($val['user_id'],$val['type_id']);
    $list[$key]['can_status'] = $rs['status']; 
    $list[$key]['can_show_status'] = $rs['show_status']; 
    
}


$tpl = new SmartTemplate ( TASK_TEMPLATES_ROOT."/goods/goods_list.tpl.htm" );

$tpl->assign ( 'page', $page_obj->output ( 1 ) );


$profession_list_html = $profession_obj->get_profession_list_for_admin_html($_INPUT['profession_id']);
$basic_status_html = $basic_obj->get_basic_status_for_admin_html($_INPUT['basic_status']);

$profession_property_list_json = $profession_obj->get_profesion_property_list_for_json();
$tpl->assign('profession_property_list_json',json_encode($profession_property_list_json));
$tpl->assign('basic_status_html',$basic_status_html);
$tpl->assign('profession_id',$_INPUT['profession_id'] ? $_INPUT['profession_id'] : 0);
$tpl->assign('att_id',$_INPUT['att_id'] ? $_INPUT['att_id'] : 0);
$tpl->assign('profession_list_html',$profession_list_html);
$tpl->assign ( 'list', $list );
$tpl->assign ( 'orderby', $_INPUT['orderby'] );
$tpl->assign('action',$_INPUT['action']);
$tpl->assign('is_black',$is_black);
$tpl->assign ( 'status', $status );
$tpl->assign ( 'goods_status_list', $goods_status_list );
$tpl->assign ( 'show', $show );
$tpl->assign ( 'audit_again', $audit_again );
$tpl->assign ( 'keyword', $keyword );
$tpl->assign ( 'type_id', $type_id );
//$tpl->assign ( 'user_id', $user_id );
$tpl->assign ( 'type_list', $type_list );
$tpl->assign( 'search_type_list' , json_encode($search_type_list));
$json_post_data = json_encode($_POST);
$tpl->assign('post', $json_post_data );
$tpl->assign ( 'show_list', $show_list );
$tpl->assign ( 'begin_time', $begin_time );
$tpl->assign ( 'end_time', $end_time );
$tpl->assign ( 'edit_status', $edit_status );
$del_goods = mall_check_admin_permissions($yue_login_id,'goods_delete');
$tpl->assign('del_goods', $del_goods['result']);
$tpl->assign('show_black', $show_black);

$tpl->assign('hide_other_button',(int)$_INPUT['hide_other_button']);



$tpl->output();
?>