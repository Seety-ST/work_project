
$(function(){
    function test_input(opt){
        this.opt=opt;
        this.init();
    };
    test_input.prototype={
        init:function(){
            var _this=this;
            var $_obj=$(_this.opt.select);
            $_obj.each(function(){
                var $_this=$(this);
                _this.bindEvent($_this);
            });
        },
        test:{
            "isNumber":function(obj){
                var val=obj.val();
                return val && /^\d*$/g.test(val)?true:false;
            },
            "lengthScope":function(obj){
                var val=obj.val();
                return val && val.length>=obj.data("min") && val.length<=obj.data("max")?true:false;
            },
            "default":function(obj){
                var val=obj.val();
                return val && val.length>0?true:false;
            }
        },
        bindEvent:function(obj){
            var _this=this;
            obj.on("blur",function(){
                var $_this=$(this);
                var cc_rule=$_this.data("rule") || "default";

                if(!_this.test[cc_rule]($_this)){
                    _this.opt.callback && $_this.trigger(_this.opt.callback,false);
                }else{
                    _this.opt.callback && $_this.trigger(_this.opt.callback,true);
                }
            });
        }
    };

    var test=new test_input({select:".cc_input",callback:"event_callback"});

    $("body").on("event_callback",function(e,bool){
        var $_this=$(e.target);
        if(bool){
           $_this.css({"border-color":"green"}); 
        }else{
           var error_tips=$_this.attr("placeholder") || "不能为空"
           console.log(error_tips);
           $_this.css({"border-color":"red"}); 
        }
        
    });
});