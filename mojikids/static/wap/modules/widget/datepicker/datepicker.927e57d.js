define('wap-kids:widget/datepicker/datepicker', function(require, exports, module) {

  "use strict";function _interopRequireDefault(t){return t&&t.__esModule?t:{"default":t}}Object.defineProperty(exports,"__esModule",{value:!0});var _index=require("wap-kids:widget/picker/index"),_index2=_interopRequireDefault(_index),_index3=require("wap-kids:widget/popup/index"),_index4=_interopRequireDefault(_index3),FORMAT_MAP={Y:"year",M:"month",D:"date",H:"hour",m:"minute"};exports.default={name:"mt-datetime-picker",props:{cancelText:{type:String,"default":"取消"},confirmText:{type:String,"default":"确定"},type:{type:String,"default":"datetime"},curDate:{type:String,"default":""},startDate:{type:Date,"default":function(){return new Date((new Date).getFullYear()-10,0,1)}},endDate:{type:Date,"default":function(){return new Date((new Date).getFullYear()+10,11,31)}},startHour:{type:Number,"default":0},endHour:{type:Number,"default":23},yearFormat:{type:String,"default":"{value}"},monthFormat:{type:String,"default":"{value}"},dateFormat:{type:String,"default":"{value}"},hourFormat:{type:String,"default":"{value}"},minuteFormat:{type:String,"default":"{value}"},value:null},data:function(){return{visible:!1,startYear:null,endYear:null,startMonth:1,endMonth:12,startDay:1,endDay:31,currentValue:null,selfTriggered:!1,dateSlots:[],shortMonthDates:[],longMonthDates:[],febDates:[],leapFebDates:[]}},components:{"mt-picker":_index2.default,"mt-popup":_index4.default},methods:{open:function(){this.visible=!0},close:function(){this.visible=!1},isLeapYear:function(t){return t%400===0||t%100!==0&&t%4===0},isShortMonth:function(t){return[4,6,9,11].indexOf(t)>-1},getMonthEndDay:function(t,e){return this.isShortMonth(e)?30:2===e?this.isLeapYear(t)?29:28:31},getTrueValue:function(t){if(t){for(;isNaN(parseInt(t,10));)t=t.slice(1);return parseInt(t,10)}},getValue:function(t){var e=this,i=void 0;if("time"===this.type)i=t.map(function(t){return("0"+e.getTrueValue(t)).slice(-2)}).join(":");else{var r=this.getTrueValue(t[0]),n=this.getTrueValue(t[1]),a=this.getTrueValue(t[2]),s=this.getMonthEndDay(r,n);a>s&&(this.selfTriggered=!0,a=1);var u=this.typeStr.indexOf("H")>-1?this.getTrueValue(t[this.typeStr.indexOf("H")]):0,l=this.typeStr.indexOf("m")>-1?this.getTrueValue(t[this.typeStr.indexOf("m")]):0;i=new Date(r,n-1,a,u,l)}return i},onChange:function(t){var e=t.$children.filter(function(t){return void 0!==t.currentValue}).map(function(t){return t.currentValue});return this.selfTriggered?void(this.selfTriggered=!1):(this.currentValue=this.getValue(e),void this.handleValueChange())},fillValues:function(t,e,i){for(var r=[],n=e;i>=n;n++)r.push(10>n?this[FORMAT_MAP[t]+"Format"].replace("{value}",("0"+n).slice(-2)):this[FORMAT_MAP[t]+"Format"].replace("{value}",n));return r},pushSlots:function(t,e,i,r){t.push({flex:1,values:this.fillValues(e,i,r)})},generateSlots:function(){var t=this,e=[],i={Y:this.rims.year,M:this.rims.month,D:this.rims.date,H:this.rims.hour,m:this.rims.min},r=this.typeStr.split("");r.forEach(function(r){i[r]&&t.pushSlots.apply(null,[e,r].concat(i[r]))}),"Hm"===this.typeStr&&e.splice(1,0,{divider:!0,content:":"}),this.dateSlots=e,this.handleExceededValue()},handleExceededValue:function(){var t=this,e=[];"time"===this.type?e=this.currentValue.split(":"):(e=[this.yearFormat.replace("{value}",this.getYear(this.currentValue)),this.monthFormat.replace("{value}",("0"+this.getMonth(this.currentValue)).slice(-2)),this.dateFormat.replace("{value}",("0"+this.getDate(this.currentValue)).slice(-2))],"datetime"===this.type&&e.push(this.hourFormat.replace("{value}",("0"+this.getHour(this.currentValue)).slice(-2)),this.minuteFormat.replace("{value}",("0"+this.getMinute(this.currentValue)).slice(-2)))),this.dateSlots.filter(function(t){return void 0!==t.values}).map(function(t){return t.values}).forEach(function(t,i){-1===t.indexOf(e[i])&&(e[i]=t[0])}),this.$nextTick(function(){t.setSlotsByValues(e)})},setSlotsByValues:function(t){var e=this.$refs.picker.setSlotValue;"time"===this.type&&(e(0,t[0]),e(1,t[1])),"time"!==this.type&&(e(0,t[0]),e(1,t[1]),e(2,t[2]),"datetime"===this.type&&(e(3,t[3]),e(4,t[4]))),[].forEach.call(this.$refs.picker.$children,function(t){return t.doOnValueChange()})},rimDetect:function(t,e){var i="start"===e?0:1,r="start"===e?this.startDate:this.endDate;this.getYear(this.currentValue)===r.getFullYear()&&(t.month[i]=r.getMonth()+1,this.getMonth(this.currentValue)===r.getMonth()+1&&(t.date[i]=r.getDate(),this.getDate(this.currentValue)===r.getDate()&&(t.hour[i]=r.getHours(),this.getHour(this.currentValue)===r.getHours()&&(t.min[i]=r.getMinutes()))))},isDateString:function(t){return/\d{4}(\-|\/|.)\d{1,2}\1\d{1,2}/.test(t)},getYear:function(t){return this.isDateString(t)?t.split(" ")[0].split(/-|\/|\./)[0]:t.getFullYear()},getMonth:function(t){return this.isDateString(t)?t.split(" ")[0].split(/-|\/|\./)[1]:t.getMonth()+1},getDate:function(t){return this.isDateString(t)?t.split(" ")[0].split(/-|\/|\./)[2]:t.getDate()},getHour:function(t){if(this.isDateString(t)){var e=t.split(" ")[1]||"00:00:00";return e.split(":")[0]}return t.getHours()},getMinute:function(t){if(this.isDateString(t)){var e=t.split(" ")[1]||"00:00:00";return e.split(":")[1]}return t.getMinutes()},confirm:function(){this.visible=!1,this.$emit("confirm",this.currentValue)},handleValueChange:function(){this.$emit("input",this.currentValue)}},computed:{rims:function(){if(!this.currentValue)return{year:[],month:[],date:[],hour:[],min:[]};var t=void 0;return"time"===this.type?t={hour:[this.startHour,this.endHour],min:[0,59]}:(t={year:[this.startDate.getFullYear(),this.endDate.getFullYear()],month:[1,12],date:[1,this.getMonthEndDay(this.getYear(this.currentValue),this.getMonth(this.currentValue))],hour:[0,23],min:[0,59]},this.rimDetect(t,"start"),this.rimDetect(t,"end"),t)},typeStr:function(){return"time"===this.type?"Hm":"date"===this.type?"YMD":"YMDHm"}},watch:{value:function(t){this.currentValue=t},rims:function(){this.generateSlots()}},mounted:function(){if(this.currentValue=this.value,!this.value)if(this.type.indexOf("date")>-1)if(this.curDate){var t=this.curDate;this.currentValue=new Date(t)}else this.currentValue=this.startDate;else this.currentValue=("0"+this.startHour).slice(-2)+":00";this.generateSlots()}},function(t){module&&module.exports&&(module.exports.template=t),exports&&exports.default&&(exports.default.template=t)}('<mt-popup v-model="visible" position="bottom" class="mint-datetime">\n  <mt-picker :slots="dateSlots" @change="onChange" :visible-item-count="7" class="mint-datetime-picker" ref="picker" show-toolbar="">\n    <span class="mint-datetime-action mint-datetime-cancel" @click="visible = false">{{ cancelText }}</span>\n    <span class="mint-datetime-action mint-datetime-confirm" @click="confirm">{{ confirmText }}</span>\n  </mt-picker>\n</mt-popup>');

});
