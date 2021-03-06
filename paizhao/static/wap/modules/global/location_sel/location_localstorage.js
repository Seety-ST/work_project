define('wap-huipai:global/location_sel/location_localstorage', function(require, exports, module) {

  
      if(!$)
      {
          alert('缺少Jquery或者Zepto脚本，请先引用');
          return;
      }
      var storage = window.localStorage;
      var utility =
      {
  
          /**
           * 本地存储器
           */
          storage :
          {
              /**
               * 前缀
               */
              prefix: 'poco-paizhao-app-',
              /**
               * 设置
               * @param key
               * @param val
               * @returns {*}
               */
              set: function(key, val)
              {
                  try
                  {
                      if(!storage || typeof storage == 'undefined')
                      {
                          return false;
                      }
  
                      if (typeof val == 'undefined')
                      {
                          return utility.storage.remove(key);
                      }
  
                      storage.setItem(utility.storage.prefix + key, JSON.stringify(val));
  
                      return val;
                  }
                  catch(err)
                  {
                      console.warn(err);
  
                      return false;
                  }
  
  
              },
              /**
               * 获取
               * @param key
               * @returns {*}
               */
              get: function(key)
              {
                  try
                  {
                      var item = storage.getItem(utility.storage.prefix + key);
  
                      if(!item)
                      {
                          return item;
                      }
                      else
                      {
                          return JSON.parse(item);
                      }
                  }
                  catch(err)
                  {
                      console.warn(err);
  
                      return false;
                  }
  
  
  
              },
              /**
               * 删除
               * @param key
               * @returns {*}
               */
              remove: function(key)
              {
                  return storage.removeItem(utility.storage.prefix + key);
              }
          },
  
          ajax_request : function(options)
          {
              var options = options || {};
  
              var url = options.url;
              var data = options.data || {};
              var cache = options.cache || false;
              var beforeSend = options.beforeSend || function(){};
              var success = options.success || function(){};
              var error = options.error || function(){};
              var complete = options.complete || function(){};
              var type = options.type || 'GET';
              var dataType = options.dataType || 'json';
              var async = (options.async == null)?true:false;
  
              var ajax_obj = $.ajax
              ({
                  url: url,
                  type : type,
                  data : data,
                  cache: cache,
                  async : async,
                  dataType : dataType,
                  beforeSend: beforeSend,
                  success: success,
                  error:error,
                  complete: complete
              });
  
              ajax_obj = $.extend(ajax_obj,{xhr_data : data});
  
              console.log(async)
  
              return ajax_obj;
          }
  
      };
  
  
  
  
      /**
       * //约摄的地区组件，仅仅两层数据
       *
       */
      function Location_sel(options)
      {
          var self = this;
          var options = options || {};
          self.list_type = options.list_type || "list";//是列表还是摄影师
          self.$province_data = options.$province_data || $("[data-role='province-data']");//省按钮
          self.$city_data = options.$city_data || $("[data-role='city-data']");//市按钮
          self.$common_data = options.$common_data || $("[common-data-role='common-data']");//公共tab按钮
  
          self.$province_data_text = options.$province_data_text || $("[data-role='province-data-text']");//省按钮文案
          self.$city_data_text = options.$city_data_text || $("[data-role='city-data-text']");//市按钮文案
  
          self.$province_data_list = options.$province_data_list || $("[data-role='province-data-list']");//省数据容器
          self.$city_data_list = options.$city_data_list || $("[data-role='city-data-list']");//市数据容器
          self.$province_data_list_wrap = options.$province_data_list_wrap || $("[data-role='province-data-list-wrap']");//省数据包裹容器
          self.$city_data_list_wrap = options.$city_data_list_wrap || $("[data-role='city-data-list-wrap']");//市数据包裹容器
          self.$common_data_list_wrap = options.$common_data_list_wrap || $("[common-data-role='common-data-list-wrap']");//公共的数据包裹器
          self.$reset_btn = options.$reset_btn || $("[data-role='reset']");//重置按钮，由于涉及到地址栏操作，放置到类里面实现
  
          //记录初始化的城市地区ID;
          self.lid = options.lid;
          //版本
          self.version = options.version || "1.0.0";
  
          self.$common_data_list = options.$common_data_list || $("[common-data-role='common-data-list']");//数据容器
          self.province_id = options.province_id || "0";//省ID
          self.city_id = options.city_id || "0";//市ID
  
          //存储地区名字
          self.$location_name_obj = options.$location_name_obj || $("[data-role='location-name']");//数据名字容器
  
          //数据转码
          self.province_obj = {};
          self.city_obj = {};
          //关闭按钮，待加上
  
          //地址栏的高度
          self.location_module_offset_top = self.$common_data.offset().top;
  
          self.init(options);
          return self;
      }
      Location_sel.prototype =
      {
          init:function(){
              var self = this;
              self.refresh();
              return self;
  
          },
          data_init_callback : function()
          {
              var self = this;
              var province_id = self.province_id;
              var city_id = self.city_id;
  
              self._render_tab_list("province","0",province_id);
              self._render_tab_list("city",province_id,city_id);
  
              self._render_list("province","0");
              self._render_list("city",province_id);
              self._event_bind();
  
          },
          //更新处理
          refresh : function()
          {
              var self = this;
  
              var version = utility.storage.get('paizhao_location_data_version') || '' ;
              console.log(version);
              //  判断是否有缓存本地，如果有，读取本地，没有读取线上
              if(version)
              {
                  // 匹配缓存版本号与传入版本号，哪个是最新版
                  if (  self.version !== version )
                  {
                      //版本号不同，重新获取数据，重新把数据设置到缓存
                      self.action();
                  }
                  else
                  {
                      var paizhao_data = utility.storage.get('paizhao_location_data');
                      self.province_obj = JSON.parse(paizhao_data["province"]);
                      self.city_obj = JSON.parse(paizhao_data["city"]);
                      //回调初始化流程
                      self.data_init_callback();
                      console.log("调用local了一次");
  
                  }
              }
              else
              {
                  //没有版本号，重新获取数据，重新把数据设置到混村
                  self.action();
              }
  
  
          },
          action : function()
          {
              var self = this;
              var timeout = 10000;
              var req_success = false;
              var url = '../ajax/search/get_location_data.php';
  
              utility.ajax_request
              ({
                  url: url,
                  dataType: 'JSON',
                  timeout : timeout,
                  cache: true,
                  beforeSend: function()
                  {
                      //self.$el.html('地区加载中...');
                  },
                  success: function(ret)
                  {
                      req_success = true;
  
                      //console.log(ret);
                      console.log(typeof ret);
                      var ret = JSON.parse(ret);
                      console.log(ret);
  
                      var paizhao_data = ret["result_data"];
                      console.log(paizhao_data);
  
                      self.version = paizhao_data["version"];
  
                      // 设置缓存
                      utility.storage.set('paizhao_location_data',paizhao_data);
  
                      // 成功后设置版本号
                      utility.storage.set('paizhao_location_data_version', self.version);
  
                      self.province_obj = JSON.parse(paizhao_data["province"]);
                      self.city_obj = JSON.parse(paizhao_data["city"]);
                      //回调初始化流程
                      self.data_init_callback();
                      console.log("调用ajax了一次");
  
                  }
              });
  
              window.setTimeout(function()
              {
                  if(!req_success)
                  {
                      var error_html = '<div class="tc mt10">网络异常，请点击刷新</div>';
                      self.$province_data_list.html(error_html);
                  }
              },timeout);
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
              switch (type)
              {
                  case "province":
                      var cur_type_location_obj = province_obj;
                      break;
                  case "city":
                      var cur_type_location_obj = city_obj;
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
              if(location_id==0)
              {
                  switch (type)
                  {
                      case "province":
                          location_name = "请选择省";
                          break;
                      case "city":
                          location_name = location_name = "请选择城市";
                          break;
  
                  }
  
              }
              else
              {
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
                          location_name = "请选择城市";
                      }
  
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
              var province_id = self.province_id;//省ID
              var city_id = self.city_id;//市ID
              //获取数据
              var cur_type_location_obj = self._get_list_obj_by_id(type,parent_location_id);
              var ul_begin = '<ul>';
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
                          default :
                              break;
                      }
                  }
  
              }
              var total_html = ul_begin+li_html+ul_end;
              //total_html = "";
              switch (type)
              {
                  case "province":
                      self.$province_data_list.html(total_html);
                      break;
                  case "city":
                      self.$city_data_list.html(total_html);
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
              var location_name = "";
              var $province_data = self.$province_data;
              var $city_data = self.$city_data;
              var $province_data_text = self.$province_data_text;
              var $city_data_text = self.$city_data_text;
              var $province_data_list_wrap = self.$province_data_list_wrap;
              var $city_data_list_wrap = self.$city_data_list_wrap;
  
              switch (type)
              {
                  case "province":
                      location_name = self._get_location_name_by_id("province",parent_location_id,location_id);
                      province_html = location_name;
                      city_html = '请选择城市';
                      $province_data_text.html(province_html);
                      $city_data_text.html(city_html);
  
  
                      $province_data_list_wrap.removeClass("dn");
                      if(!$city_data_list_wrap.hasClass("dn"))
                      {
                          $city_data_list_wrap.addClass("dn");
                      }
                      break;
                  case "city":
                      location_name = self._get_location_name_by_id("city",parent_location_id,location_id);
                      //清理省的选中
                      //有城市ID，才隐藏省的数据
                      if(location_id!="0")
                      {
                          $province_data.removeClass("sel-cur");
                          city_html = location_name;
                          $city_data.addClass("sel-cur");
                          $city_data_text.html(city_html);
                          if(!$province_data_list_wrap.hasClass("dn"))
                          {
                              $province_data_list_wrap.addClass("dn")
                          }
                          $city_data_list_wrap.removeClass("dn");
                      }
                      break;
              }
  
          },
  
          //事件绑定处理
          _event_bind:function(){
              var self = this;
              var $common_data = self.$common_data;
              var $common_data_list = self.$common_data_list;
              var $common_data_list_wrap = self.$common_data_list_wrap;
              var $reset_btn = self.$reset_btn;
  
  
              //数据列表点击的处理
              $common_data_list.on("click",function(e){
                  //return false;
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
  
                      //滚动定位
                      console.log("距离顶部："+self.location_module_offset_top);
                      $(".popup").scrollTop(self.location_module_offset_top);
  
                      //地区容器
                      self.$city_data.html("请选择城市");
                      self.$location_name_obj.html("请选择");
  
  
                  }
                  else if(data_role=="city-detail")
                  {
                      //变量赋值
                      $("[data-role='city-detail']").each(function(){
                          $(this).removeClass("li-cur");
                      });
                      $(target).addClass("li-cur");
  
                      //处理选中文案
                      var target_html = $(target).html();
                      self.$city_data.html(target_html);
                      self.$location_name_obj.html(target_html);
  
                  }
  
              });
  
  
  
              //tab按钮点击切换
              $common_data.on("click",function(){
                  //return false;
                  var data_role = $(this).attr("data-role");
                  if(data_role=="province-data" || data_role=="city-data")
                  {
                      //滚动定位
                      console.log("距离顶部："+self.location_module_offset_top);
                          $(".popup").scrollTop(self.location_module_offset_top);
  
                      console.log("点击了common_data");
                      console.log("data_role:"+data_role);
                      console.log($common_data_list);
                      var data_list_len = $common_data_list.length;
                      console.log("common_list_len:"+data_list_len);
                      for(var i=0;i<data_list_len;i++)
                      {
                          if(!$($common_data_list_wrap[i]).hasClass("dn"))
                          {
                              $($common_data_list_wrap[i]).addClass("dn");
                              console.log($($common_data_list_wrap[i]));
                              //console.log("添加了DN");
                          }
                          if($($common_data[i]).hasClass("sel-cur"))
                          {
                              $($common_data[i]).removeClass("sel-cur");
                          }
  
                      }
                      //显示选中的模块
                      $("[data-role='"+data_role+"-list-wrap']").removeClass("dn");
                      $("[data-role='"+data_role+"']").addClass("sel-cur");
  
  
                  }
              });
  
  
              $reset_btn.on("click",function(){
                  //条件清理相关选中
                  $("[common-data-role='condition-li']").each(function(){
                      $(this).removeClass("li-cur");
                  });
  
                  //切换到广州的数据
                  self._render_tab_list("province","0","0");
  
                  //tab切换，数据切换
                  var data_list_len = $common_data_list.length;
                  for(var i=0;i<data_list_len;i++)
                  {
                      if($($common_data[i]).hasClass("sel-cur"))
                      {
                          $($common_data[i]).removeClass("sel-cur");
                      }
                  }
                  $("[data-role='province-data']").addClass("sel-cur");
  
  
                  //清理选中地区
                  $("[data-role='province-detail']").each(function(){
                      $(this).removeClass("li-cur");
                  });
                  $("[data-role='city-detail']").each(function(){
                      $(this).removeClass("li-cur");
                  });
  
                  //设置省为0
                  self.province_id = "0";
  
                  //地区容器
                  self.$city_data.html("请选择城市");
                  self.$location_name_obj.html("请选择");
  
              });
  
          }
  
  
  
  
      };
  
      module.exports = Location_sel;
  

});
