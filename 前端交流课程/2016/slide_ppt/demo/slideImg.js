/**
 * slideImg
 * version 0.0.1
 * time 2016-04-11
 */


var $ = require("$");
var C = require("common");
/**
 * [initial description]
 * @param  {[type]} opt [description]
 * {
 * 		wrap: 包住的div 选择器
 * 		box：包住图片列表的div 选择器
 *   	handle: 触发show 选择器
 *   	handleClass: 当前选择器 添加的样式
 *   	autoPlay：是否自动播放 默认 false
 *   	time：自动播放时间,
 *   	prev:
 *   	next:
 *      fn_changesrc:改变图片触发的函数
 *      fn_afterload:图片加载完成以后触发的函数
 * }
 * @return {[type]}     [description]
 */
exports.initial = function (opt) {
    return new slide(opt);
}
function slide(opt) {
    this.init(opt);
    this.index = 0;
}
//version 0.0.1 
slide.prototype = {
    init: function (opt) {
        var _this = this;

        _this.getopt(opt);

        _this.$_wrap = $(opt.wrap);
        _this.$_box = _this.$_wrap.find(opt.box);
        _this.$_handle_box = _this.$_wrap.find(opt.handle_box);
        _this.$_handle_box_parent=_this.$_handle_box.parent();
        _this.$_handle = _this.$_wrap.find(opt.handle);
        _this.$_prev = _this.$_wrap.find(opt.prev);
        _this.$_next = _this.$_wrap.find(opt.next);

        _this.$_sub_prev= _this.$_wrap.find(opt.sub_prev);
        _this.$_sub_next= _this.$_wrap.find(opt.sub_next);

        _this.bindEvent();
        _this.swf.init.call(_this);

        _this.fn_changesrc=opt.fn_changesrc;
        _this.fn_afterload=opt.fn_afterload;

        _this.init_handle();
    },
    getopt: function (opt) {
        var _this = this;
        var default_opt = {
            wrap: ".wrap",
            box: ".box",
            handle: ".handle span",
            handleClass: "on",
            time: 2 * 1000,
            autoPlay: true,
            prev: "",
            next: ""
        };

        _this.opt = C.MerageObj(default_opt, opt);
    },
    init_handle: function () {
        var _this = this;
        var cc_opt = _this.opt;
        var cc_width = 0;

        //_this.$_prev.hide();
        //_this.$_next.hide();

        _this.$_handle.each(function () {
            cc_width += $(this).outerWidth(true);
        });

        _this.$_handle_box.css({
            position: "absolute",
            left: "0px",
            top: "0px",
            height: _this.$_handle_box.parent().height(),
            width: cc_width + 'px'
        })

        if(_this.$_handle_box.width()>_this.$_handle_box_parent.width() && _this.$_handle_box.width()-_this.$_handle_box_parent.width()-_this.$_handle.last().outerWidth(true)>=0){
            _this.$_sub_next.css({display:"block"});
            _this.$_sub_prev.css({display:"block"});
        }

        //缩略图加载
        _this.$_handle_box.children().each(function (index, item) {
            var $_this = $(item);
            C.GetLoading($_this, "small");
            C.SetImgSrc($_this.find("img").data("src"), {
                callback: function () {
                    $_this.find("img").attr("src", $(this).attr("src"));
                    C.ImageResize($_this.find("img"));
                    C.CancelLoading($_this);
                }
            });
        });


        //是否显示 prev next 按钮
        //if (_this.$_handle_box.children(":last-child").position().left > _this.$_handle_box.parent().width()) {
        //    _this.$_prev.show();
        //    _this.$_next.show();
        //}
    },
    swf: {
        init: function () {
            var _this = this;//这里的指向需要注意,调用的时候需要指向 prototype
            //_this.$_box.children().hide();
            switch (_this.opt.effect) {
                case "left":
                    _this.$_wrap.css({
                        position: "relative"
                    });
                    _this.$_box.css({
                        position: "absolute",
                        width: _this.$_wrap.width() * _this.$_box.children().length + 'px',
                        height: _this.$_box.parent().height() + 'px',
                        left: "0px",
                        top: "0px",
                        transition: "all linear 0s"
                    });
                    _this.$_box.children().css({
                        display: "block",
                        float: "left",
                        width: _this.$_wrap.width() + 'px',
                        height: "100%",
                        overflow: "hidden"
                    });

                    _this.$_handle.eq(0).trigger("click");
                    break;
                case "fade":
                    _this.$_wrap.css({
                        position: "relative"
                    });
                    _this.$_box.css({
                        position: "relative",
                        width: _this.$_wrap.width(),
                        height: _this.$_box.parent().height() + 'px',
                        overflow: "hidden"
                    });
                    _this.$_box.children().css({
                        display: "block",
                        position: "absolute",
                        left: "100%",
                        top: "0px",
                        width: _this.$_wrap.width(),
                        height: _this.$_box.parent().height() + 'px'
                    });
                    _this.$_handle.eq(0).trigger("click");
                    break;
            }
        },
        show: function (index) {
            var _this = this;
            switch (_this.opt.effect) {
                case "left":
                    _this.$_box.css({
                        transitionDuration: "0.5s",
                        left: 0 - _this.$_wrap.width() * index + "px"
                    });
                    _this.$_handle && _this.$_handle.size && _this.$_handle.eq(index).addClass(_this.opt.handleClass).siblings().removeClass(_this.opt.handleClass);
                    break;
                case "fade":
                    window.win_lazyload && window.win_lazyload.load();
                    _this.$_box.children().eq(index).css({"left": "0px"}).fadeIn(300).siblings().fadeOut(300, function () {
                        $(this).css({"left": "100%"})
                    });
                    _this.$_handle && _this.$_handle.size && _this.$_handle.eq(index).addClass(_this.opt.handleClass).siblings().removeClass(_this.opt.handleClass);
            }
        }
    },
    show: function () {
        var _this = this;
        _this.timer && clearTimeout(_this.timer);
        if (!_this.lock) {
            _this.$_wrap.trigger("event_move");
            C.GetLoading(_this.$_box.children().eq(_this.getIndex()));
            var cc_ast=false;
            C.SetImgSrc(_this.$_box.children().eq(_this.getIndex()).find("img").data("src"), {
                callback: function () {
                    cc_ast && clearTimeout(cc_ast);
                    C.CancelLoading(_this.$_box.children().eq(_this.getIndex()));
                    var cc_img = _this.$_box.children().eq(_this.getIndex()).find("img");
                    cc_img.attr("src", $(this).attr('src'));
                    if (cc_img.height() < cc_img.parent().height()) {
                        cc_img.css({
                            "marginTop": (cc_img.parent().height() - cc_img.height()) / 2 + 'px'
                        });
                    }
                    _this.fn_afterload && _this.fn_afterload.call && _this.fn_afterload.call(_this,_this.$_handle.eq(_this.getIndex()))
                }
            });
            _this.swf.show.call(_this, _this.getIndex());
            cc_ast=setTimeout(function(){
                _this.fn_changesrc && _this.fn_changesrc.call && _this.fn_changesrc.call(_this,_this.$_handle.eq(_this.getIndex()))
            },30)

            if (_this.opt.autoPlay) {
                _this.timer = setTimeout(function () {
                    _this.autoPlay();
                }, _this.opt.time);
            }
        }
    },
    autoPlay: function () {
        var _this = this;
        if (!_this.lock) {
            _this.setIndex((_this.getIndex()) + 1);
            _this.show();
        }
    },
    setIndex: function (num) {
        var _this = this;
        if (0 <= num && num <= _this.$_box.children().length - 1) {
            _this.index = num;
        }
        else if (num < 0) {
            _this.index = _this.opt.autoPlay ? _this.$_box.children().length - 1 : 0;
        } else {
            _this.index = _this.opt.autoPlay ? 0 : _this.$_box.children().length - 1;
        }
    },
    getIndex: function () {
        var _this = this;
        return _this.index;
    },
    lock: false,
    bindEvent: function () {
        var _this = this,
            opt = _this.opt,
            handle_box_parent = _this.$_handle_box.parent(),
            handle_length = _this.$_handle.length,
            slide_lock = false;
        _this.$_wrap.on("click", _this.opt.handle, function (event) {
            event.stopPropagation();
            var $_this = $(this);
            if($_this.index()==_this.getIndex()){  //重复点击不反应
                return false;
            }
            _this.setIndex($_this.index());
            _this.show();
        });

        _this.$_prev.on("click", function (e) {
            e.stopPropagation();
            if (!_this.lock) {
                if (_this.getIndex() == 0) {
                    _this.setIndex(_this.$_handle.length - 1);
                } else {
                    _this.setIndex(_this.getIndex() - 1);
                }
                _this.show();
            }

        });

        _this.$_next.on("click", function (e) {
            e.stopPropagation();
            if (!_this.lock) {
                if (_this.getIndex() >= _this.$_handle.length - 1) {
                    _this.setIndex(0);
                } else {
                    _this.setIndex(_this.getIndex() + 1);
                }
                _this.show();
            }

        });

        //缩略图 滚动
        _this.$_wrap.on("event_move", function (event) {
            var cc_handle=_this.$_handle.eq(_this.getIndex()),
                cc_handle_pos_left=cc_handle.position().left,
                cc_left;
            if (_this.$_handle_box.width() > handle_box_parent.width()+_this.$_handle_box.children().eq(0).width() ) {

                if (_this.getIndex() == 0) {
                    cc_left=0;
                }
                else if (_this.getIndex() == handle_length - 1) {
                    cc_left=handle_box_parent.width() - _this.$_handle_box.width() + parseInt(cc_handle.css("marginRight"))
                }
                else {

                    if (cc_handle_pos_left >= Math.abs(parseInt(_this.$_handle_box.css("left"))) + handle_box_parent.outerWidth(true)) {//在 显示框 右边  next
                        cc_left=parseInt(_this.$_handle_box.css("left")) - cc_handle.outerWidth(true);
                        if(Math.abs(cc_left)+handle_box_parent.width()<cc_handle_pos_left){
                            cc_left=0-cc_handle_pos_left+handle_box_parent.width()-cc_handle.outerWidth();
                        }
                        //console.log("next");
                    }
                    else if (cc_handle_pos_left < Math.abs(parseInt(_this.$_handle_box.css("left"))) + cc_handle.outerWidth(true)) {//在显示框 左边  prev
                        cc_left=parseInt(_this.$_handle_box.css("left")) + cc_handle.outerWidth(true);
                        if(Math.abs(cc_left)+cc_handle.outerWidth(true)>cc_handle_pos_left){
                            cc_left=0-cc_handle_pos_left;
                        }
                        //console.log("prev");
                    }
                }
                if(cc_left!==undefined){
                    _this.lock = true;
                    _this.$_handle_box.stop().animate({
                        left: cc_left+"px"
                    }, 500, function () {
                        _this.lock = false;
                    });
                }

            }
        });
        _this.$_sub_next.on("click",function(e){
            var $_this=$(e.target);
            if(_this.$_handle_box.width()-Math.abs(_this.$_handle_box.position().left)-_this.$_handle_box_parent.width()*2>=0 && !$_this.data("is_lock")){
                $_this.data("is_lock",true);
                _this.$_handle_box.stop().animate({
                    left:_this.$_handle_box.position().left-_this.$_handle_box_parent.width()+"px"
                },500,function(){
                    $_this.data("is_lock",false);
                })
            }else{
                if(!$_this.data("is_lock")){
                    $_this.data("is_lock",true)
                    _this.$_handle_box.stop().animate({
                        left:0-(_this.$_handle_box.width()-_this.$_handle_box_parent.width())+"px"
                    },500,function(){
                        $_this.data("is_lock",false);
                    });
                }

            }
        });
        _this.$_sub_prev.on("click",function(e){
            var $_this=$(e.target);
            if(Math.abs(_this.$_handle_box.position().left)-_this.$_handle_box_parent.width()>=0 && !$_this.data("is_lock")){
                $_this.data("is_lock",true);
                _this.$_handle_box.stop().animate({
                    left:_this.$_handle_box.position().left+_this.$_handle_box_parent.width()+"px"
                },500,function(){
                    $_this.data("is_lock",false);
                });
            }else{
                if(!$_this.data("is_lock")){
                    $_this.data("is_lock",true);
                    _this.$_handle_box.stop().animate({
                        left:"0px"
                    },500,function(){
                        $_this.data("is_lock",false);
                    });
                }

            }
        });
        _this.$_box.on({
            "mouseover": function (e) {
                if (_this.opt.autoPlay) {
                    _this.lock = true;
                }
            },
            "mouseleave": function (e) {
                if (_this.opt.autoPlay) {
                    _this.lock = false;
                    _this.show();
                }
            }
        });
    }
};


