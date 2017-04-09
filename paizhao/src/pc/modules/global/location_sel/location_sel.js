/**
 *  //约摄地区选择模块，目前在约摄使用
 * @authors 星星
 */



/**
 * 地区数据切换
 *
 *
 */
function Location_sel(options)
{
    var self = this;
    var options = options || {};
    self.list_type = options.list_type || "list";//是列表还是摄影师
    self.can_use_district = options.can_use_district || false;//能否使用第三级地区数据
    self.$province_data = options.$province_data || $("[data-role='province-data']");//省按钮
    self.$city_data = options.$city_data || $("[data-role='city-data']");//市按钮
    self.$district_data = options.$district_data || $("[data-role='district-data']");//区按钮
    self.$common_data = options.$common_data || $("[common-data-role='common-data']");//公共tab按钮
    self.$province_data_list = options.$province_data_list || $("[data-role='province-data-list']");//省数据容器
    self.$city_data_list = options.$city_data_list || $("[data-role='city-data-list']");//市数据容器
    self.$district_data_list = options.$district_data_list || $("[data-role='district-data-list']");//区数据容器
    self.$common_data_list = options.$common_data_list || $("[common-data-role='common-data-list']");//数据容器
    self.condition_list = options.condition_list || {};//构造链接时候的条件数组
    self.location_json_obj = options.location_json_obj || {};//地区数据对象
    self.province_id = options.province_id || "101029";//省ID
    self.city_id = options.city_id || "101029001";//市ID
    self.district_id = options.district_id || "101029001001";//区ID
    self.$trigger_obj = options.$trigger || $("[data-role='location-li']");//触发的按钮对象
    self.$close_trigger_obj = options.$close_trigger_obj || $("[data-role='location-list-close']");//触发的按钮对象

    //数据转码
    var province_obj = JSON.parse(self.location_json_obj["province"]);
    var city_obj = JSON.parse(self.location_json_obj["city"]);
    var district_obj = JSON.parse(self.location_json_obj["district"]);
    self.province_obj = province_obj;
    self.city_obj = city_obj;
    self.district_obj = district_obj;
    //关闭按钮，待加上

    self.init(options);
    return self;
}

Location_sel.prototype = {

    init:function(){
        var self = this;
        var province_id = self.province_id;
        var city_id = self.city_id;
        var district_id = self.district_id;
        var can_use_district = self.can_use_district;


        //做相应渲染模板处理处理
        self._render_tab_list("province","0",province_id);
        self._render_tab_list("city",province_id,city_id);
        if(can_use_district)
        {
            self._render_tab_list("district",city_id,district_id);
        }
        self._render_list("province","0");
        self._render_list("city",province_id);
        if(can_use_district)
        {
            self._render_list("district",city_id);
        }
        self._event_bind();
    },
    /*
     * //通过类型跟ID获取这个ID的名字
     *type //渲染的类型，分为province,city,district，三种渲染
     * parent_location_id //父级地址ID
     */
    //通过location_id获取对应的数据对象
    _get_list_obj_by_id:function(type,parent_location_id){
        var self = this;
        var province_obj = self.province_obj;
        var city_obj = self.city_obj;
        var district_obj = self.district_obj;
        switch (type)
        {
            case "province":
                var cur_type_location_obj = province_obj;
                break;
            case "city":
                var cur_type_location_obj = city_obj;
                break;
            case "district":
                var cur_type_location_obj = district_obj;
                break;
        }

        var tmp_cur_type_location_obj = [];
        var cur_type_location_obj_len = cur_type_location_obj.length;
        console.log(type+"get_list:"+cur_type_location_obj_len);
        var j=0;
        for(var i=0;i<cur_type_location_obj_len;i++)
        {
            if(cur_type_location_obj[i]["parent_id"]==parent_location_id)
            {
                //console.log("传入的parent_location_id："+parent_location_id);
                //console.log(cur_type_location_obj[i]["parent_id"]);
                tmp_cur_type_location_obj[j] = cur_type_location_obj[i];
                j++;
            }
        }
        cur_type_location_obj = tmp_cur_type_location_obj;
            //console.log("生成的list："+type+cur_type_location_obj.length);


        return cur_type_location_obj;
    },
    /*
     * //通过类型跟ID获取这个ID的名字
     *type //渲染的类型，分为province,city,district，三种渲染
     * parent_location_id //父级地址ID
     * location_id//当前的ID
     */
    _get_location_name_by_id:function(type,parent_location_id,location_id){
        var self = this;
        var cur_type_location_obj = self._get_list_obj_by_id(type,parent_location_id);
        console.log("parent_location_id:"+parent_location_id);
        console.log(cur_type_location_obj);
        var location_name = "";
        if(cur_type_location_obj)
        {
            if(location_id!="")
            {
                var obj_len = cur_type_location_obj.length;
                for(var i=0;i<obj_len;i++)
                {
                    if(location_id==cur_type_location_obj[i]["location_id"])
                    {
                        location_name = cur_type_location_obj[i]["location_name"];
                        break;
                    }
                }
            }
            else
            {
                location_name = "请选择";
            }

        }
        console.log(location_name);
        return location_name;


    },
    //渲染列表数据
    /*
      type //渲染的类型，分为province,city,district，三种渲染
      location_id //地址ID，
    */
    _render_list:function(type,parent_location_id){
        var self = this;
        var can_use_district = self.can_use_district;
        var province_id = self.province_id;//省ID
        var city_id = self.city_id;//市ID
        var district_id = self.district_id;//区ID
        //获取数据
        var cur_type_location_obj = self._get_list_obj_by_id(type,parent_location_id);
        var ul_begin = '<ul class="location-tab-detail-list">';
        var ul_end = '</ul>';
        var li_html = "";
        if(cur_type_location_obj)
        {
            var obj_len = cur_type_location_obj.length;
            for(var i=0;i<obj_len;i++)
            {
                switch (type)
                {
                    case "province":
                        if(province_id==cur_type_location_obj[i]["location_id"])
                        {
                            li_html += '<li class="li-cur" data-role="province-detail" data-id="'+cur_type_location_obj[i]["location_id"]+'">'+cur_type_location_obj[i]["location_name"]+'</li>';
                        }
                        else
                        {
                            li_html += '<li data-role="province-detail" data-id="'+cur_type_location_obj[i]["location_id"]+'">'+cur_type_location_obj[i]["location_name"]+'</li>';
                        }
                        break;
                    case "city":
                        if(city_id==cur_type_location_obj[i]["location_id"])
                        {
                            li_html += '<li class="li-cur" data-role="city-detail" data-id="'+cur_type_location_obj[i]["location_id"]+'">'+cur_type_location_obj[i]["location_name"]+'</li>';
                        }
                        else
                        {
                            li_html += '<li data-role="city-detail" data-id="'+cur_type_location_obj[i]["location_id"]+'">'+cur_type_location_obj[i]["location_name"]+'</li>';
                        }
                        break;
                    case "district":
                        if(district_id==cur_type_location_obj[i]["location_id"])
                        {
                            li_html += '<li class="li-cur" data-role="district-detail" data-id="'+cur_type_location_obj[i]["location_id"]+'">'+cur_type_location_obj[i]["location_name"]+'</li>';
                        }
                        else
                        {
                            li_html += '<li data-role="district-detail" data-id="'+cur_type_location_obj[i]["location_id"]+'">'+cur_type_location_obj[i]["location_name"]+'</li>';
                        }
                        break;
                    default :
                        break;
                }
            }

        }
        var total_html = ul_begin+li_html+ul_end;
        switch (type)
        {
            case "province":
                self.$province_data_list.html(total_html);
                break;
            case "city":
                self.$city_data_list.html(total_html);
                break;
            case "district":
                self.$district_data_list.html(total_html);
                break;
        }

    },
    //渲染tab列表
    /*
    *type //渲染的类型，分为province,city,district，三种渲染
    * location_id //渲染的ID
    */
    _render_tab_list:function(type,parent_location_id,location_id){
        var self = this;

        var province_html = "";
        var city_html = "";
        var district_html = "";
        var location_name = "";
        var $province_data = self.$province_data;
        var $city_data = self.$city_data;
        var $district_data = self.$district_data;
        var $province_data_list = self.$province_data_list;
        var $city_data_list = self.$city_data_list;
        var $district_data_list = self.$district_data_list;

        switch (type)
        {
            case "province":
                location_name = self._get_location_name_by_id("province",parent_location_id,location_id);
                province_html = location_name+'<i class="arrow-icon"></i>';
                city_html = '请选择<i class="arrow-icon"></i>';
                district_html = '请选择<i class="arrow-icon"></i>';
                $province_data.html(province_html);
                $city_data.html(city_html);
                $district_data.html(district_html);

                $province_data_list.removeClass("dn");
                if(!$city_data_list.hasClass("dn"))
                {
                    $city_data_list.addClass("dn");
                }
                if(!$district_data_list.hasClass("dn"))
                {
                    $district_data_list.addClass("dn");
                }
                break;
            case "city":
                location_name = self._get_location_name_by_id("city",parent_location_id,location_id);
                //清理省的选中
                $province_data.removeClass("li-cur");

                city_html = location_name+'<i class="arrow-icon"></i>';
                district_html = '请选择<i class="arrow-icon"></i>';
                $city_data.addClass("li-cur");
                $city_data.html(city_html);
                $district_data.html(district_html);
                if(!$province_data_list.hasClass("dn"))
                {
                    $province_data_list.addClass("dn")
                }
                $city_data_list.removeClass("dn");
                if(!$district_data_list.hasClass("dn"))
                {
                    $district_data_list.addClass("dn")
                }
                break;
            case "district":
                //清理省的选中
                $city_data.removeClass("li-cur");
                location_name = self._get_location_name_by_id("district",parent_location_id,location_id);
                district_html = location_name+'<i class="arrow-icon"></i>';
                $district_data.addClass("li-cur");
                $district_data.html(district_html);
                if(!$province_data_list.hasClass("dn"))
                {
                    $province_data_list.addClass("dn")
                }
                if(!$city_data_list.hasClass("dn"))
                {
                    $city_data_list.addClass("dn")
                }
                $district_data_list.removeClass("dn");
                break;
        }

    },

    //事件绑定处理
    _event_bind:function(){
        var self = this;
        var can_use_district = self.can_use_district;
        var $common_data = self.$common_data;
        var $common_data_list = self.$common_data_list;
        var $trigger_obj = self.$trigger_obj;
        var $close_trigger_obj = self.$close_trigger_obj;

        $trigger_obj.on("click",function(e){
           if(!$(this).hasClass("location-li-cur"))
           {
               $(this).addClass("location-li-cur");

           }

        });

        /*$trigger_obj.on("mouseleave",function(e){
            if($(this).hasClass("location-li-cur"))
            {
                $(this).removeClass("location-li-cur");

            }

        });*/
        $("body").on("click",function(e){
            var target = e.target;
            //console.log($(target).parents(".location-li-list").length);

            if($(target).parents("[data-role='location-li']").length == 1)
            {
                //console.log(1);
            }
            else
            {
                //console.log(2);
                $trigger_obj.removeClass("location-li-cur");
            }


        });

        $close_trigger_obj.on("click",function(e){
            e.stopPropagation();
            if($trigger_obj.hasClass("location-li-cur"))
            {
                $trigger_obj.removeClass("location-li-cur");

            }
            else
            {
                $trigger_obj.addClass("location-li-cur");
            }

        });




        //数据列表点击的处理
        $common_data_list.on("click",function(e){
            var target = e.target;
            var data_role = $(target).attr("data-role");
            var data_id = $(target).attr("data-id");
            console.log("点击了列表");
            console.log("data_role=:"+data_role);
            console.log("data_id=:"+data_id);
            if(data_role=="province-detail")
            {
                //省点击
                console.log("省列表点击");
                self._render_tab_list("province","0",data_id);
                self._render_tab_list("city",data_id,"");
                //渲染列表
                self._render_list("city",data_id);
                //样式跟内容处理
                $("[data-role='province-detail']").each(function(){
                    $(this).removeClass("li-cur");
                    if($(this).attr("data-id")==data_id)
                    {
                        $(this).addClass("li-cur");
                    }
                });
                $("[data-role='city-detail']").each(function(){
                    $(this).removeClass("li-cur");
                });
                //清理第三层内容
                if(can_use_district)
                {
                    self.$district_data_list.find("ul").html("");
                }

            }
            else if(data_role=="city-detail")
            {
                //市点击
                console.log("市列表点击");
                if(can_use_district)
                {
                    var parent_location_id = $(".li-cur[data-role='province-detail']").attr("data-id");

                    self._render_tab_list("city",parent_location_id,data_id);
                    self._render_tab_list("district",data_id,"");
                    //渲染列表
                    self._render_list("district",data_id);
                    $("[data-role='city-detail']").each(function(){
                        $(this).removeClass("li-cur");
                        if($(this).attr("data-id")==data_id)
                        {
                            $(this).addClass("li-cur");
                        }
                    });
                }
                else
                {
                    //拼接跳转处理（待处理）
                    //alert("拼接跳转-市");
                    var condition_list = self.condition_list;
                    //构造跳转链接
                    var jump_link = condition_list["local_link_url"]+condition_list["local_ev_photographer_str"]+condition_list["local_ev_type_str"]+condition_list["local_ev_style_str"]+"&"+condition_list["local_sort_str"]+condition_list["local_order_str"]+"lid="+data_id+"&"+condition_list["local_keyword_str"]+"s=1"
                    window.location.href=jump_link;
                }

            }
            else if(data_role=="district-detail")
            {
                //区点击
                console.log("区列表点击");
                //直接跳转处理（待处理）
                //alert("拼接跳转-区");
                var condition_list = self.condition_list;
                var jump_link = condition_list["local_link_url"]+condition_list["local_ev_photographer_str"]+condition_list["local_ev_type_str"]+condition_list["local_ev_style_str"]+"&"+condition_list["local_sort_str"]+condition_list["local_order_str"]+"lid="+data_id+"&"+condition_list["local_keyword_str"]+"s=1";
                window.location.href=jump_link;
            }
        });



        //tab按钮点击切换
        $common_data.on("click",function(){
            var data_role = $(this).attr("data-role");
            if(data_role!="")
            {
                console.log("点击了common_data");
                console.log("data_role:"+data_role);
                console.log($common_data_list);
                var data_list_len = $common_data_list.length;
                console.log("common_list_len:"+data_list_len);
                for(var i=0;i<data_list_len;i++)
                {
                    if(!$($common_data_list[i]).hasClass("dn"))
                    {
                        $($common_data_list[i]).addClass("dn")
                    }
                    if($($common_data[i]).hasClass("li-cur"))
                    {
                        $($common_data[i]).removeClass("li-cur")
                    }

                }
                //显示选中的模块
                $("[data-role='"+data_role+"-list']").removeClass("dn");
                $("[data-role='"+data_role+"']").addClass("li-cur");


            }
        });



    }
}


module.exports = Location_sel;