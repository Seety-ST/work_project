/**
 * Created by pocouser on 2015/11/2.
 */
/**
 * Created by pocouser on 2015/11/2.
 */
var $=require("$");
var C=require("common");
var base=require("poco_x_base");
var wui_banner_post=require("wui_banner_post");
var dialog=require("dialog");
var api=require("api");

var v={};
v.id="base_index";
v.isfirst=true;
//加载页面的时候加载
exports.init=function(){
    init_params();
    if(v.isfirst){
        exports.ready();
    }
    init_page();
    init_event();
};
//加载JS的时候必定加载，加载一次JS有且只加载一次
exports.ready=function(){

}
//初始化变量
var init_params=function(){
    v.$_baseobj=$("#"+ v.id);
    v.$_view_quan_right= v.$_baseobj.find(".view_quan_right");
    v.$_view_quan_left= v.$_baseobj.find(".view_quan_left");
    v.$_view_quan_right_item= v.$_baseobj.find(".view_quan_right_item");
    v.$_view_quan_left_item= v.$_baseobj.find(".view_quan_left_item");
    v.$_view_player_box= v.$_baseobj.find(".view_player_box");
    v.$_view_player_items= v.$_baseobj.find(".view_player_items");
    v.$_view_player_left= v.$_baseobj.find(".view_player_left");
    v.$_view_player_right= v.$_baseobj.find(".view_player_right");
    v.$_view_user_attention= v.$_baseobj.find(".view_user_attention");

    v.cc_player_items_length=v.$_view_player_items.size();
    v.cc_lock=false;

    v.cc_data= v.$_baseobj.data();

}

//初始化页面
var init_page=function(){
    var $_wui_banner_post=$(wui_banner_post.getid());
    $(window).resize(function(){
        $_wui_banner_post.trigger("module_resize");
    });
    slide({
        wrap:".view_player",
        box:".view_player_box",
        content:".view_player_items",
        children:".view_player_item",
        prev:".view_player_left",
        next:".view_player_right"
    });
    v.$_baseobj.find(".view_user_desc").each(function(index,item){
        var $_item=$(item);
        if(C.Trim($_item.text())==""){
            $_item.text("暂无内容");
        }
    });
}
//这个是一个一个滚动，视图有多个的
/*
 *slide 左右滑动*opt
 * {
 *   wrap
 *   box
 *   content
 *   children
 *   timer
 *   prev
 *   next
 * }
 * */
var slide=function(opt)
{
    var $slide_wrap=$(opt.wrap),
        $slide_box=$(opt.box),
        $slide_content=$(opt.content),
        $slide_children=$slide_content && $slide_content.size?$slide_content.find(opt.children):false,
        slide_timer=opt.timer || 300,
        slide_lock=false;
    $slide_children.css({
        float:"left"
    });
    var cc_width= 0,
        cc_height= 0;
    $slide_children.each(function()
    {
        var $_this=$(this);
        cc_width+=$_this.outerWidth(true);
    })
    $slide_box.css({
        position:"relative"
    })
    $slide_content.css({
        width:cc_width,
        position:"absolute",
        left:"0px",
        top:"0px"
    }).data("is_index",0);
    if($slide_content.width()>$slide_box.width()+$slide_children.last().outerWidth(true)){
        opt.next && $(opt.next).show();
    }
    var move=function(wrap,children){
        wrap.stop().animate({
            left:0-children.position().left+"px"
        },slide_timer,function(){
            slide_lock=false;
        });
    }
    $slide_wrap.on("click",opt.prev,function(e)
    {
        e.stopPropagation();
        var $_this=$(e.currentTarget);
        var is_index=parseInt($slide_content.data("is_index"));
        if(is_index>0 && !slide_lock)
        {
            $slide_content.data("is_index",is_index-1);
            move($slide_content,$slide_children.eq(is_index-1));
            slide_lock=true;
        }
        if(is_index-1 == 0){
            $_this.hide();
            $(opt.next).show();
        }
    });
    $slide_wrap.on("click",opt.next,function(e)
    {
        e.stopPropagation();
        var $_this=$(e.currentTarget);
        var is_index=parseInt($slide_content.data("is_index"));
        if($slide_content.width()-Math.abs($slide_content.position().left)-$slide_box.width()>=$slide_children.eq(is_index).outerWidth(true) && !slide_lock)
        {
            $slide_content.data("is_index",is_index+1);
            move($slide_content,$slide_children.eq(is_index+1));
            slide_lock=true;
        }
        if($slide_content.width()-Math.abs($slide_content.position().left-$slide_box.width()<$slide_children.eq(is_index).outerWidth(true))){
            $_this.hide();
            $(opt.prev).show();
        }
    })
}
//初始化事件
var init_event=function(){
    v.$_baseobj.on("event_quan_show",function(e,index){
        var $_item= v.$_view_quan_left_item.eq(index),
            $_img= v.$_view_quan_left_item.eq(index).find("a img"),
            $_handle= v.$_view_quan_right_item.eq(index);
        $_handle.addClass("on").siblings().removeClass("on");
        //console.log(index);
        if($_item.is(":visible")){
            return false;
        }
        if($_img.data("is_load")){
            v.$_view_quan_left_item.stop().fadeOut().eq(index).fadeIn();
            return false;
        }
        C.SetImgSrc($_img.attr("bsrc"),{
            callback:function(){
                $_img.attr("src",this.src).data({is_load:true}).removeAttr("bsrc");
                C.ImageResize($_img);
                v.$_view_quan_left_item.stop().fadeOut().eq(index).fadeIn();
            },
            loading: v.$_view_quan_left
        });
    });
    v.$_baseobj.on("mouseover",".view_quan_right_item",function(e){
        var $_this=$(e.currentTarget);
        v.$_baseobj.trigger("event_quan_show",$_this.index());
    });

    //关注、取消关注
    v.$_view_user_attention.on("click",function(e){
        var $_this=$(this),
            $_view_player_item=$_this.parents(".view_player_item");
        var cc_act="";
        if(!v.cc_data.login_id>0){
            dialog.alert(window.Config.alert.system.no_login,1);
            return false;
        }
        if($_this.data("is_follow")){
            cc_act="unfollow";
        }
        else{
            cc_act="follow";
        }
        if(!$_this.data("is_lock")){
            C.GetLoading($_this,"little");
            api.Ajax_attention({
                data:{
                    act:cc_act,
                    uid:$_view_player_item.data("user_id")
                },
                callback:function(){
                    if($_this.data("is_follow")){
                        $_this.data("is_follow",0);
                        $_this.removeClass("on").find("span").text("加关注");
                    }else{
                        $_this.data("is_follow",1);
                        $_this.addClass("on").find("span").text("已关注");
                    }
                },
                complete:function(){
                    C.CancelLoading($_this);
                    $_this.data("is_lock",0);
                }
            })
        }
    });
    //“关注按钮”变化
    v.$_view_user_attention.on({
        "mouseover":function(e){
            var $_this=$(this);
            if($_this.hasClass("on")){
                $_this.find(".view_cancel_text").css({"display":"block"});
            }
        },
        "mouseleave":function(e){
            var $_this=$(this);
            $_this.find(".view_cancel_text").css({"display":"none"});
        }
    })
    v.$_baseobj.trigger("event_quan_show",0);
}


