<?php
/*
 * //摄影师列表模块
 *
 *
 *
 * */

$photographers = POCO::singleton('pai_paizhao_photographers_class');
//$page_obj =POCO::singleton('show_page');
$page_obj =POCO::singleton('pai_paizhao_page_class');

$lid = (int)$_INPUT["lid"];
$pt = (int)$_INPUT["pt"];
$keyword = urldecode(trim($_INPUT["kw"]));
$sort = trim($_INPUT["sort"]);
$order = trim($_INPUT["order"]);

//获取地址
/*$i=0;
foreach($goods_location_array_config as $key => $value)
{
    $goods_location_array[$i]["location_id"] = $key;
    $goods_location_array[$i]["location_name"] = $value;
    $i++;
}*/
$can_use_district = false;
if(!empty($lid))
{
    $location_name = get_location_name_by_location_id($lid,true);
    $lid_tmp = $lid;
}
else
{
    //如果没有传，获取默认第一个
    if($can_use_district)
    {
        $lid_tmp = 101029001001;
        $location_name = get_location_name_by_location_id($lid_tmp,true);
    }
    else
    {
        $lid_tmp = 101029001;
        $location_name = get_location_name_by_location_id($lid_tmp,true);
    }

}



//摄影师类型
$types_photographers_arr = array("0"=>array("name"=>"全部"),"2"=>array("name"=>"摄影机构"),"1"=>array("name"=>"独立摄影师"));
$tmp_sort_photographers_arr = array(
    array("key"=>"0","name"=>"综合"),
    array("key"=>"1","name"=>"人气"),
    array("key"=>"location","name"=>$location_name));

//跟距参数控制配置数组状态
foreach($types_photographers_arr as $key => $value)
{
    if($key==$pt)
    {
        $value["cur_class"] = "nav-list-li-cur";
    }
    else
    {
        $value["cur_class"] = "";
    }
    $types_photographers_arr[$key] = $value;
}

foreach($tmp_sort_photographers_arr as $key => $value)
{
    //区分是否地区跟其他条件排序，根据页面模式添加特殊class
    if($value["key"]=="location")
    {
        $value["sort_arrow_class"] = "";
        $value["cur_class"] = "";
        $value["lid_class"] = "location-li";
        $value["sort_arrow_class"] = "ui-triangle ui-triangle-bottom-mid-gray";
    }
    else
    {
        //判断是否有条件
        if(!empty($sort))
        {
            if($value["key"]==$sort)
            {
                $value["cur_class"] = "nav-list-li-cur";
                //判断排序
                if(empty($order) || $order=="2")
                {
                    //降序序，icon也为降序
                    $value["sort_arrow_class"] = "sort-down-arrow";

                }
                else
                {
                    //升序，icon为升序
                    $value["sort_arrow_class"] = "sort-up-arrow";
                }

            }
            else
            {
                //有条件，默认为降序
                $value["sort_arrow_class"] = "sort-down-arrow";
            }
        }
        else
        {
            //没有条件，默认综合为选中
            if($value["key"]=="0")
            {
                $value["sort_arrow_class"] = "";
                $value["cur_class"] = "nav-list-li-cur";
            }
            else
            {
                //移动上去默认是降序
                $value["sort_arrow_class"] = "sort-down-arrow";
                $value["cur_class"] = "";
            }

        }
    }
    $sort_photographers_arr[$key] = $value;
}

//列表数组构造链接
foreach($types_photographers_arr as $key => $value)
{
    $types_photographers_arr[$key]["link"] = link_construct("",$key,$sort,"",$order,$lid,$keyword);
}

foreach($sort_photographers_arr as $key => $value)
{
    $sort_photographers_arr[$key]["link"] = link_construct("sort_type",$pt,$sort,$value["key"],$order,$lid,$keyword);
}

/*foreach($goods_location_array as $key => $value)
{
    $goods_location_array[$key]["link"] = link_construct("lid_type",$pt,$sort,"",$order,$value["location_id"],$keyword);
}*/



//默认降序
$order = $order == '1' ? $order : '2';

//通过条件获取数据，并且做分页处理
$show_count  = 16;	//每页显示数
$p = (int)$_INPUT['p'];
if($p<=0)
{
    $p = 1;
}
$limit = ($p-1)*$show_count;
$limit_str = "{$limit},{$show_count}";

$submit_array  = array();
$submit_array["kw"] = $keyword;
$submit_array["sr"] = $sort;
$submit_array["o"] = $order;
$submit_array["l"] = $lid;
$submit_array["pt"] = $pt;
if($_INPUT["print"]==2)
{
    print_r($submit_array);
}

if($_INPUT["print"]==3)
{
    foreach($submit_array as $key => $value)
    {
        echo "键值：".$key."值：".$value."<br>";
    }
}

$list_data_count = $photographers->get_photographers_list(true,$submit_array);
//判断页码，如果传入页码大于最大页码，则显示最大页码
$max_p = ceil($list_data_count/$show_count);
if($p>$max_p)
{
    $p = $max_p;
    $limit = ($p-1)*$show_count;
    $limit_str = "{$limit},{$show_count}";
}
$list_data = $photographers->get_photographers_list(false,$submit_array, $limit_str);


$tpl->assign("list_data_count",$list_data_count);

if($_INPUT["print"]==1)
{
    print_r($list_data);
}

$list_data_html = _get_wbc_data_list($list_data,"photographers_list");

/***********构造分页结构分页处理*************/
$page_obj->setvar(array(
    "pt"=>$pt,
    "sort"=>$sort,
    "order"=>$order,
    "lid"=>$lid,
    "kw"=>urlencode($keyword),
    "s"=>1

));
$page_obj->set($show_count, $list_data_count,false,5);
if ($show_count > $list_data_count)
{
    $page_select = '';
}
else
{
    $page_select = $page_obj->output ( 1,1 ) ;
}
$page_select = str_replace('font-size:9pt;','',$page_select);
/***********构造分页结构分页处理end*************/




$tpl->assign("types_photographers_arr",$types_photographers_arr);
$tpl->assign("sort_photographers_arr",$sort_photographers_arr);
//$tpl->assign("goods_location_array",$goods_location_array);
$tpl->assign("page_select",$page_select);
$tpl->assign("list_data_html",$list_data_html);
$tpl->assign("keyword",$keyword);
$tpl->assign("location_name",$location_name);
$tpl->assign("page_title","约摄-预约优质摄影师");
$tpl->assign("meta_keywords","影楼,摄影工作室,独立摄影师,摄影服务");
$tpl->assign("meta_description","约摄，一个提供海量高质量摄影师的约拍平台。影楼、摄影工作室、独立摄影师应有尽有。");

//构造地区地址
$ev_photographer_str = $pt?$pt:"";
$ev_photographer_str = "pt=".$ev_photographer_str;
$sort_str = "sort=".$sort."&";
$order_str = "order=".$order."&";
$lid_str = "lid=".$lid."&";
$keyword_str = "kw=".urlencode($keyword)."&";

$tpl->assign("ev_photographer_str",$ev_photographer_str);
$tpl->assign("sort_str",$sort_str);
$tpl->assign("order_str",$order_str);
$tpl->assign("lid_str",$lid_str);
$tpl->assign("keyword_str",$keyword_str);

$province_id = substr($lid_tmp,0,6);//保证是省id，6位
$city_id = substr($lid_tmp,0,9);//保证是市地区id,9位
$district_id = $lid_tmp;
$tpl->assign("province_id",$province_id);
$tpl->assign("city_id",$city_id);
$tpl->assign("district_id",$district_id);//搜索条件



/*******页面连接构造通用函数********/
/*
 *  @param
 *  str $link_type //页面链接类型（排序筛选型，选择地区型）（值：sort_type,lid_type）
 *  int $condition_type_id //选择的条件类型ID
 *  str $sort //排序参数
 *  str $sort_id //(构造排序数组链接时候，传入的排序参数)
 *  str $order //升序降序
 *  int $lid //地址
 *  str $keyword //关键字
 *
*/
function link_construct($link_type,$condition_type_id,$sort,$sort_id,$order,$lid,$keyword)
{
    $link_url = "./index.php?tp=per&";
    $sort_str = "sort=".$sort."&";
    $order_str = "order=".$order."&";
    $lid_str = "lid=".$lid."&";
    $keyword_str = "kw=".urlencode($keyword)."&";
    if(empty($condition_type_id))
    {
        $condition_type_id = "";
    }
    $photographers_str = "pt=".$condition_type_id."&";

    //根据条件构造链接结构
    switch($link_type)
    {
        //排序筛选型
        case "sort_type":
            //有排序加处理
            /*if($sort == $sort_id)//构造排序链接时候，刚好是页面选中的排序条件，顺序要翻转
            {
                if($order=="2")
                {
                    $order = "1";
                }
                else if($order=="1")
                {
                    $order = "2";
                }
                $order_str = "order=".$order."&";
            }
            else
            {
                $order_str = "order=2&";
            }*/
            $order_str = "order=2&";
            $sort_str = "sort=".$sort_id."&";
            break;
        //选择地区型
        case "break;
            lid_type":
            //暂时不用调整
            break;
        default:
            break;
    }

    $return_url = $link_url.$photographers_str.$sort_str.$order_str.$lid_str.$keyword_str."s=1";
    return $return_url;
}


?>