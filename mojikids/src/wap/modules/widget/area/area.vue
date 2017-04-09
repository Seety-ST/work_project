<template>
  <mt-popup v-model="visible" position="bottom" class="moji-area">
    <mt-picker
      :slots="slots"
      @change="address_change"
      class="mint-area-picker"
      ref="picker"
      show-toolbar>
      <span class="moji-area-action moji-area-cancel" @click="visible = false">取消</span>
      <span class="moji-area-action moji-area-confirm" @click="confirm" >确认</span>
    </mt-picker>
  </mt-popup>
</template>

<style lang="scss">
.moji-area {
width: 100%;
}
.moji-area .picker-slot-wrapper, .moji-area .picker-item {
-webkit-backface-visibility: hidden;
        backface-visibility: hidden;
}
.moji-area .picker-toolbar {
border-bottom: solid 1px #eaeaea;
}
.moji-area-action {
display: inline-block;
width: 50%;
text-align: center;
line-height: 40px;/*no*/
font-size: 16px;/*no*/
color: #26a2ff;
}
.moji-area-cancel {
float: left;
}
.moji-area-confirm {
float: right;
}
</style>

<script type="text/babel">
    import picker from '../picker/index.js';
    import popup from '../popup/index.js';

    let Util = require.syncLoad(__moduleId('/modules/global/util/index'));
    let global_location_data = {};

    export default
    {
        name : 'moji-area',
        props :
        {
            show :
            {
                type : Boolean,
                default : false
            },
            locationId :
            {
                type : String,
                default : '101029001'
            },
            title :
            {
                type : String,
                default : ''
            },

        },
        mounted()
        {
            var self = this;

            self.get_location_data();
        },
        data()
        {
            return {
                visible : false,
                slots: [{
                    flex: 1,
                    values: [],
                    defaultIndex : 7,
                    className: 'slot1',
                    textAlign: 'center'
                }, {
                    divider: true,
                    content: '-',
                    className: 'slot2'
                }, {
                    flex: 1,
                    values: [],
                    className: 'slot3',
                    textAlign: 'center'
                }],
                city_obj : {},
                cur_province_id : '',
                cur_province_name : '',
                cur_city_id : '',
                cur_city_name : '',
            }
        },
        components:
        {
            'mt-picker': picker,
            'mt-popup': popup
        },
        computed:
        {

        },
        methods :
        {
            open()
            {
                var self = this;
                self.$data.visible = true;
            },
            confirm()
            {
                var self = this;
                var location_id = self.$data.city_obj[self.$data.city] || '';
                var province_name = self.$data.province;
                var city_name = self.$data.city;

                self.$data.visible = false;

                self.$emit('confirm',
                {
                    location_id : location_id,
                    province_name : province_name,
                    city_name : city_name
                });
            },
            address_change(picker, values)
            {

                var self = this;
                picker.setSlotValues(1, global_location_data[values[0]]);

                self.$data.province = values[0];
                self.$data.city = values[1];

                if(self.$data.city_obj[self.$data.city])
                {
                    self.$data.location_id = self.$data.city_obj[self.$data.city];
                }


            },
            get_location_data()
            {
                var self = this;

                Util.request
                ({
                    url: 'http://yp.yueus.com/action/get_location_data_v2.php?callback=?',
                    dataType: 'JSONP',
                    timeout: 10000,
                    cache: true,
                    beforeSend: function() {
                        //self.$el.html('地区加载中...');
                    },
                    success: function(ret)
                    {
                        var province = JSON.parse(ret.data.province);
                        var city = JSON.parse(ret.data.city);

                        // 整合地区数据
                        var province_obj = {};
                        var city_obj = {};
                        var location_data = {};

                        for(var i=0;i<province.length;i++)
                        {

                            var province_id = province[i].location_id;
                            var province_name = province[i].location_name;
                            var temp = [];

                            for(var j=0;j<city.length;j++)
                            {
                                var parent_id = city[j].parent_id;
                                if(parent_id == province_id)
                                {
                                    temp.push(city[j].location_name);
                                }

                                city_obj[city[j].location_name] = city[j].location_id;

                                if(self.locationId == city[j].location_id && self.locationId.slice(0,6) == province_id)
                                {
                                    self.cur_province_id = province_id;
                                    self.cur_province_name = province_name;

                                    console.log(city[j].location_name);

                                    self.cur_city_id = city[j].location_id;
                                    self.cur_city_name = city[j].location_name;

                                }
                            }

                            location_data[province_name] = temp;
                        }

                        console.log(location_data);

                        global_location_data = location_data;

                        self.$data.slots[0].values = Object.keys(location_data);
                        self.$data.city_obj = city_obj;

                        setTimeout(function()
                        {
                            // 初始化设置
                            self.$refs.picker.setSlotValue(0,self.cur_province_name);
                            self.$refs.picker.setSlotValue(1,self.cur_city_name);
                        },500)
                    }
                })
            }
        }
    }
</script>
