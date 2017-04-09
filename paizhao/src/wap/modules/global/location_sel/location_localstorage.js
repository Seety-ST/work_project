
    if(!$)
    {
        alert('ȱ��Jquery����Zepto�ű�����������');
        return;
    }
    var storage = window.localStorage;
    var utility =
    {

        /**
         * ���ش洢��
         */
        storage :
        {
            /**
             * ǰ׺
             */
            prefix: 'poco-paizhao-app-',
            /**
             * ����
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
             * ��ȡ
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
             * ɾ��
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
     * //Լ��ĵ��������������������
     *
     */
    function Location_sel(options)
    {
        var self = this;
        var options = options || {};
        self.list_type = options.list_type || "list";//���б�������Ӱʦ
        self.$province_data = options.$province_data || $("[data-role='province-data']");//ʡ��ť
        self.$city_data = options.$city_data || $("[data-role='city-data']");//�а�ť
        self.$common_data = options.$common_data || $("[common-data-role='common-data']");//����tab��ť

        self.$province_data_text = options.$province_data_text || $("[data-role='province-data-text']");//ʡ��ť�İ�
        self.$city_data_text = options.$city_data_text || $("[data-role='city-data-text']");//�а�ť�İ�

        self.$province_data_list = options.$province_data_list || $("[data-role='province-data-list']");//ʡ��������
        self.$city_data_list = options.$city_data_list || $("[data-role='city-data-list']");//����������
        self.$province_data_list_wrap = options.$province_data_list_wrap || $("[data-role='province-data-list-wrap']");//ʡ���ݰ�������
        self.$city_data_list_wrap = options.$city_data_list_wrap || $("[data-role='city-data-list-wrap']");//�����ݰ�������
        self.$common_data_list_wrap = options.$common_data_list_wrap || $("[common-data-role='common-data-list-wrap']");//���������ݰ�����
        self.$reset_btn = options.$reset_btn || $("[data-role='reset']");//���ð�ť�������漰����ַ�����������õ�������ʵ��

        //��¼��ʼ���ĳ��е���ID;
        self.lid = options.lid;
        //�汾
        self.version = options.version || "1.0.0";

        self.$common_data_list = options.$common_data_list || $("[common-data-role='common-data-list']");//��������
        self.province_id = options.province_id || "0";//ʡID
        self.city_id = options.city_id || "0";//��ID

        //�洢��������
        self.$location_name_obj = options.$location_name_obj || $("[data-role='location-name']");//������������

        //����ת��
        self.province_obj = {};
        self.city_obj = {};
        //�رհ�ť��������

        //��ַ���ĸ߶�
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
        //���´���
        refresh : function()
        {
            var self = this;

            var version = utility.storage.get('paizhao_location_data_version') || '' ;
            console.log(version);
            //  �ж��Ƿ��л��汾�أ�����У���ȡ���أ�û�ж�ȡ����
            if(version)
            {
                // ƥ�仺��汾���봫��汾�ţ��ĸ������°�
                if (  self.version !== version )
                {
                    //�汾�Ų�ͬ�����»�ȡ���ݣ����°��������õ�����
                    self.action();
                }
                else
                {
                    var paizhao_data = utility.storage.get('paizhao_location_data');
                    self.province_obj = JSON.parse(paizhao_data["province"]);
                    self.city_obj = JSON.parse(paizhao_data["city"]);
                    //�ص���ʼ������
                    self.data_init_callback();
                    console.log("����local��һ��");

                }
            }
            else
            {
                //û�а汾�ţ����»�ȡ���ݣ����°��������õ����
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
                    //self.$el.html('����������...');
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

                    // ���û���
                    utility.storage.set('paizhao_location_data',paizhao_data);

                    // �ɹ������ð汾��
                    utility.storage.set('paizhao_location_data_version', self.version);

                    self.province_obj = JSON.parse(paizhao_data["province"]);
                    self.city_obj = JSON.parse(paizhao_data["city"]);
                    //�ص���ʼ������
                    self.data_init_callback();
                    console.log("����ajax��һ��");

                }
            });

            window.setTimeout(function()
            {
                if(!req_success)
                {
                    var error_html = '<div class="tc mt10">�����쳣������ˢ��</div>';
                    self.$province_data_list.html(error_html);
                }
            },timeout);
        },
        /*
         * //ͨ�����͸�ID��ȡ���ID������
         *type //��Ⱦ�����ͣ���Ϊprovince,city,district��������Ⱦ
         * parent_location_id //������ַID
         */
        //ͨ��location_id��ȡ��Ӧ�����ݶ���
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
                    //console.log("�����parent_location_id��"+parent_location_id);
                    //console.log(cur_type_location_obj[i]["parent_id"]);
                    tmp_cur_type_location_obj[j] = cur_type_location_obj[i];
                    j++;
                }
            }
            cur_type_location_obj = tmp_cur_type_location_obj;
            //console.log("���ɵ�list��"+type+cur_type_location_obj.length);


            return cur_type_location_obj;
        },
        /*
         * //ͨ�����͸�ID��ȡ���ID������
         *type //��Ⱦ�����ͣ���Ϊprovince,city,district��������Ⱦ
         * parent_location_id //������ַID
         * location_id//��ǰ��ID
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
                        location_name = "��ѡ��ʡ";
                        break;
                    case "city":
                        location_name = location_name = "��ѡ�����";
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
                        location_name = "��ѡ�����";
                    }

                }
            }

            console.log(location_name);
            return location_name;


        },
        //��Ⱦ�б�����
        /*
         type //��Ⱦ�����ͣ���Ϊprovince,city,district��������Ⱦ
         location_id //��ַID��
         */
        _render_list:function(type,parent_location_id){
            var self = this;
            var province_id = self.province_id;//ʡID
            var city_id = self.city_id;//��ID
            //��ȡ����
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
        //��Ⱦtab�б�
        /*
         *type //��Ⱦ�����ͣ���Ϊprovince,city,district��������Ⱦ
         * location_id //��Ⱦ��ID
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
                    city_html = '��ѡ�����';
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
                    //����ʡ��ѡ��
                    //�г���ID��������ʡ������
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

        //�¼��󶨴���
        _event_bind:function(){
            var self = this;
            var $common_data = self.$common_data;
            var $common_data_list = self.$common_data_list;
            var $common_data_list_wrap = self.$common_data_list_wrap;
            var $reset_btn = self.$reset_btn;


            //�����б�����Ĵ���
            $common_data_list.on("click",function(e){
                //return false;
                var target = e.target;
                var data_role = $(target).attr("data-role");
                var data_id = $(target).attr("data-id");
                console.log("������б�");
                console.log("data_role=:"+data_role);
                console.log("data_id=:"+data_id);
                if(data_role=="province-detail")
                {
                    //ʡ���
                    console.log("ʡ�б����");
                    self._render_tab_list("province","0",data_id);
                    self._render_tab_list("city",data_id,"");
                    //��Ⱦ�б�
                    self._render_list("city",data_id);
                    //��ʽ�����ݴ���
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

                    //������λ
                    console.log("���붥����"+self.location_module_offset_top);
                    $(".popup").scrollTop(self.location_module_offset_top);

                    //��������
                    self.$city_data.html("��ѡ�����");
                    self.$location_name_obj.html("��ѡ��");


                }
                else if(data_role=="city-detail")
                {
                    //������ֵ
                    $("[data-role='city-detail']").each(function(){
                        $(this).removeClass("li-cur");
                    });
                    $(target).addClass("li-cur");

                    //����ѡ���İ�
                    var target_html = $(target).html();
                    self.$city_data.html(target_html);
                    self.$location_name_obj.html(target_html);

                }

            });



            //tab��ť����л�
            $common_data.on("click",function(){
                //return false;
                var data_role = $(this).attr("data-role");
                if(data_role=="province-data" || data_role=="city-data")
                {
                    //������λ
                    console.log("���붥����"+self.location_module_offset_top);
                        $(".popup").scrollTop(self.location_module_offset_top);

                    console.log("�����common_data");
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
                            //console.log("������DN");
                        }
                        if($($common_data[i]).hasClass("sel-cur"))
                        {
                            $($common_data[i]).removeClass("sel-cur");
                        }

                    }
                    //��ʾѡ�е�ģ��
                    $("[data-role='"+data_role+"-list-wrap']").removeClass("dn");
                    $("[data-role='"+data_role+"']").addClass("sel-cur");


                }
            });


            $reset_btn.on("click",function(){
                //�����������ѡ��
                $("[common-data-role='condition-li']").each(function(){
                    $(this).removeClass("li-cur");
                });

                //�л������ݵ�����
                self._render_tab_list("province","0","0");

                //tab�л��������л�
                var data_list_len = $common_data_list.length;
                for(var i=0;i<data_list_len;i++)
                {
                    if($($common_data[i]).hasClass("sel-cur"))
                    {
                        $($common_data[i]).removeClass("sel-cur");
                    }
                }
                $("[data-role='province-data']").addClass("sel-cur");


                //����ѡ�е���
                $("[data-role='province-detail']").each(function(){
                    $(this).removeClass("li-cur");
                });
                $("[data-role='city-detail']").each(function(){
                    $(this).removeClass("li-cur");
                });

                //����ʡΪ0
                self.province_id = "0";

                //��������
                self.$city_data.html("��ѡ�����");
                self.$location_name_obj.html("��ѡ��");

            });

        }




    };

    module.exports = Location_sel;