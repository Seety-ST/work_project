<?php
/*
 * //商品列表页
 *
 * //author   xingxing
 *
 *
 * 2016-11-21
*/


$goods_class = POCO::singleton('pai_paizhao_goods_class');


/*******************配置数组*********************/
//套系配置
$goods_type_arr = require('../config/paizhao_goods_type_config.php');
//风格场景
$goods_style_arr = require('../config/paizhao_goods_style_config.php');
//摄影师类型
$goods_photographers_arr = array("2"=>array("name"=>"摄影机构"),"1"=>array("name"=>"独立摄影师"));

//配置主类型，自定义
$parent_list = array(
    "11"=>"type",
    "22"=>"style",
    "33"=>"photographers",
);

//配置主类型对应要拆分的数组
$parent_list_to_config_arr = array(
    "11"=>$goods_type_arr,
    "22"=>$goods_style_arr,
    "33"=>$goods_photographers_arr,
);

//间隔符编码处理
$parent_se = urlencode("@");
$child_se = urlencode("||");
/*******************配置数组end*********************/

//排序参数
$sort = trim($_INPUT['sort']);
$order = trim($_INPUT['order']);
$keyword = urldecode(trim($_INPUT['kw']));

//页面获取参数
$lid = (int)$_INPUT["lid"];//地址参数

if(!empty($lid))
{
    $location_name = get_location_name_by_location_id($lid,true);
    $lid_tmp = $lid;
}
else
{
    //如果没有传，获取默认第一个
    /*$lid_tmp = 101029001;
    $location_name = get_location_name_by_location_id($lid_tmp,true);*/
    $lid_tmp = 0;
    $location_name = "请选择";
}

//条件参数
$ev = urldecode(trim($_INPUT["ev"]));
$ev_array = explode("@",$ev);//主分类拆分
//搜索条件，查询使用
$type_id_array = array();
$style_id_array = array();
$j=0;//记录筛选条件
foreach($ev_array as $key => $mix_value)
{
    if(!empty($mix_value))
    {
        //混合结构内部拆分
        $mix_value_arr = explode("_",$mix_value);
        //主分类
        $parent_value = $mix_value_arr[0];
        //子分类
        $child_value = $mix_value_arr[1];
        //对子分类进行拆分
        $child_value_arr = explode("||",$child_value);

        //print_r($child_value_arr);
        //子分类的ID数组循环

        //构造搜索条件（支持多选栏）

        //根据类型设置筛选条件
        $title_str = "";
        switch($parent_list[$parent_value])
        {
            case "type":
                $type_id_array = $child_value_arr;
                $title_str = "套系类型：";
                break;
            case "style":
                $style_id_array = $child_value_arr;
                $title_str = "风格场景：";
                break;
            case "photographers":
                $photographers_type_id = $child_value_arr[0];
                $title_str = "摄影师类型：";
                break;
        }

    }
}

//排序条件


//临时排序数组
$tmp_sort_arr = array(
    array("key"=>"0","name"=>"综合"),
    array("key"=>"1","name"=>"人气"),
    array("key"=>"2","name"=>"最新"),
    array("key"=>"3","name"=>"价格")
);

//根据筛选条件，配置最终排序数组的结构状态
foreach($tmp_sort_arr as $key => $value)
{
    //区分是否地区跟其他条件排序，根据页面模式添加特殊class

    //判断是否有条件
    if(!empty($sort))
    {
        if($value["key"]==$sort)
        {
            $value["cur_class"] = "cur";

            if($value["key"]=="3")
            {
                //判断排序
                if($order=="2")
                {
                    //降序序，icon也为降序
                    $value["sort_down_class"] = "color-24c";
                    $value["sort_up_class"] = "";

                }
                else
                {
                    //升序，icon为升序
                    $value["sort_up_class"] = "color-24c";
                    $value["sort_down_class"] = "";
                }
            }


        }
        else
        {
            $value["sort_up_class"] = "";
            $value["sort_down_class"] = "";
        }

    }
    else
    {
        $value["sort_up_class"] = "";
        $value["sort_down_class"] = "";
        //没有条件，默认综合为选中
        if($value["key"]=="0")
        {
            $value["cur_class"] = "cur";
        }
    }

    $sort_arr[$key] = $value;

}

//默认降序
$order = $order == '1' ? $order : '2';


//对条件数组进行处理，方便模板选中
foreach($goods_type_arr as $key => $value)
{
    if(in_array($key,$type_id_array))
    {
        $goods_type_arr[$key]["class_type"] = "li-cur";
    }
    else
    {
        $goods_type_arr[$key]["class_type"] = "";
    }
}

foreach($goods_style_arr as $key => $value)
{
    if(in_array($key,$style_id_array))
    {
        $goods_style_arr[$key]["class_type"] = "li-cur";
    }
    else
    {
        $goods_style_arr[$key]["class_type"] = "";
    }
}

foreach($goods_photographers_arr as $key => $value)
{
    if($key==$photographers_type_id)
    {
        $goods_photographers_arr[$key]["class_type"] = "li-cur";
    }
    else
    {
        $goods_photographers_arr[$key]["class_type"] = "";
    }
}
//对条件数组进行处理，方便模板选中end


foreach($sort_arr as $key => $value)
{
    $sort_arr[$key]["link"] = link_construct($ev_array,"sort_type","","",$sort,$value["key"],$order,$lid,$keyword);
}



//赋值到模板进行连接构造
$tpl->assign("parent_se",$parent_se);
$tpl->assign("child_se",$child_se);
//构造地区地址
$province_id = substr($lid_tmp,0,6);//保证是省id，6位
$city_id = substr($lid_tmp,0,9);//保证是市地区id,9位
$tpl->assign("province_id",$province_id);
$tpl->assign("city_id",$city_id);
//meta赋值
$tpl->assign("page_title","摄影套系分类-写真，婚纱，亲子，商业，特色，证件，形象");
$tpl->assign("meta_keywords","摄影套系，约拍推荐，场景拍摄，风格拍摄");
$tpl->assign("meta_description","约摄提供不同拍摄场景，多种风格的摄影服务，选择多多，想拍就拍。");
//数组数据赋值
$tpl->assign("goods_type_arr",$goods_type_arr);
$tpl->assign("goods_style_arr",$goods_style_arr);
$tpl->assign("goods_photographers_arr",$goods_photographers_arr);
//单变量
$tpl->assign("location_name",$location_name);
//获取信息的条件参数
$type_id_str = implode("||",$type_id_array);
$style_id_str = implode("||",$style_id_array);
$tpl->assign("type_id_str",$type_id_str);
$tpl->assign("style_id_str",$style_id_str);
$tpl->assign("photographers_type_id",$photographers_type_id);
$tpl->assign("lid",$lid);
$tpl->assign("sort",$sort);
$tpl->assign("order",$order);
$tpl->assign("kw",urlencode($keyword));
$tpl->assign("decode_kw",$keyword);
//赋值数组,排序
$tpl->assign("sort_arr",$sort_arr);



if($_INPUT["print"]==2)
{
    $submit_array  = array();
    $submit_array["t"] = $type_id_array;
    $submit_array["s"] = $style_id_array;
    $submit_array["kw"] = $keyword;
    $submit_array["sr"] = $sort;
    $submit_array["o"] = $order;
    $submit_array["l"] = $lid;
    $submit_array["pt"] = $photographers_type_id;
    print_r($submit_array);
}

/*******页面连接构造通用函数********/
/*
 *  @param
 *  array $ev_array //分类条件拆分数组
 *  str $link_type //页面链接类型（选择条件类型，删除条件型，排序筛选型，选择地区型）（值：condition_type,del_type,sort_type,lid_type）
 *  str $condition_type //(值：type,style,photographers)，条件类型
 *  int $condition_type_id //选择的条件类型ID
 *  str $sort //排序参数
 *  str $sort_id //(构造排序数组链接时候，传入的排序参数)
 *  str $order //升序降序
 *  int $lid //地址
 *  str $keyword //关键字
 *
*/
function link_construct($ev_array,$link_type,$condition_type,$condition_type_id,$sort,$sort_id,$order,$lid,$keyword)
{
    $link_url = "./index.php?tp=list&ev=";
    $sort_str = "sort=".$sort."&";
    $order_str = "order=".$order."&";
    $lid_str = "lid=".$lid."&";
    $keyword_str = "kw=".urlencode($keyword)."&";
    $type_str = "";//11
    $style_str = "";//22
    $photographers_str = "";//33
    //配置类型对应数组
    $condition_list = array(
        "type"=>"11",
        "style"=>"22",
        "photographers"=>"33",
    );


    //拆分条件数组
    foreach($ev_array as $key => $mix_value)
    {
        if(!empty($mix_value))
        {
            //混合结构内部拆分
            $mix_value_arr = explode("_",$mix_value);
            //主分类
            $parent_value = $mix_value_arr[0];
            //子分类
            $child_value = $mix_value_arr[1];
            //对子分类进行拆分
            //$child_value_arr = explode("||",$child_value);
            $tmp_urlencode_str = urlencode($parent_value."_".$child_value."@");
            switch($parent_value)
            {
                case "11":
                    $type_str = $tmp_urlencode_str;
                    break;
                case "22":
                    $style_str = $tmp_urlencode_str;
                    break;
                case "33":
                    $photographers_str = $tmp_urlencode_str;
                    break;

            }

        }
    }

    //根据条件构造链接结构
    switch($link_type)
    {
        case "sort_type":
            if($sort == $sort_id && ($sort == "3" || $sort == "1"))//构造排序链接时候，刚好是选中的排序条件，顺序要翻转
            {
                if($order=="2" || empty($order))
                {
                    $order = "1";
                    if($sort == "1")//人气默认降序
                    {
                        $order = "2";
                    }
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
                if($sort_id=="3")
                {
                    $order_str = "order=1&";
                }
            }

            //排序
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

    $return_url = $link_url.$photographers_str.$type_str.$style_str."&".$sort_str.$order_str.$lid_str.$keyword_str."s=1";
    return $return_url;


}
?>