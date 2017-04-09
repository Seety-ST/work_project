/*
bug:
1.需要检查 maincell 跟  titlecell 的 size 是否对应的上
*/
(function(globar){
var util={
	//判断类型 工厂
	testType:function(type_str){
		var cc_fn=function(arg){
		 	return 	Object.prototype.toString.call(arg)=="[object "+type_str+"]";
		}
		return cc_fn;
	},
	//是否数组
	isArray:function(arg){
		var cc_test_fn=util.testType("Array");
		return cc_test_fn(arg);
	},
	//是否兑现
	isObject:function(arg){
		var cc_test_fn=util.testType("Object");
		return cc_test_fn(arg);
	},
	//是否函数
	isFunction:function(arg){
		var cc_test_fn=util.testType("Function");
		return cc_test_fn(arg);
	},
	//对象覆盖，obj2 覆盖 obj1
	objAssign:function(obj1,obj2){
		var init_obj=function(arg){
			if(!arg || !util.isObject(arg)){
				return {};
			}else{
				return arg;
			}
		};

		obj1=init_obj(obj1);
		obj2=init_obj(obj2);

		for(k in obj2){
			if(util.isObject(obj2[k])){
				obj1[k]=util.objAssign({},obj2[k]);
			}else{
				obj1[k]=obj2[k];
			}
		}
		return obj1;
	},
	//图片预加载
	imgPrevLoad:function(src,opt){
		var cc_img=new Image();
		if(!src){
			console.log("src 不存在");
			return false;
		}
		cc_img.src=src;

		opt &&　opt.before && opt.before.call && opt.before.call();

		cc_img.onload=function(){
			opt && opt.callback && opt.callback.call && opt.callback.call(cc_img);
		};

		cc_img.onerror=function(){
			opt && opt.error && opt.error.call && opt.error.call();
		}
	}
};
var slide=function(opt){
	this.opt=opt;
	this.index=0;

	this.todo();
};
slide.prototype={
	//初始化
	todo:function(){
		var _this=this;
		_this.getOpt();
		_this.initDom();
		_this.bindEvent();

		// _this.show(_this.$_wrap.find("[main_index="+_this.index+"]"));
	},
	//初始化 dom
	initDom:function(){
		var _this=this;

		_this.$_wrap=$(_this.opt.wrap);
		_this.$_mainCell=_this.$_wrap.find(_this.opt.mainCell);
		_this.$_titleCell=_this.$_wrap.find(_this.opt.titleCell);
		_this.$_prev=_this.$_wrap.find(_this.opt.prev);
		_this.$_next=_this.$_wrap.find(_this.opt.next);

		//这里 对内容 以及 指定按钮 绑定对于的 index，考虑到之后加上循环的时候需要 对内容进行clone
		_this.$_mainCell.each(function(index,item){
			var $_item=$(item);
			$_item.attr("main_index",index);
		});
		_this.$_titleCell.each(function(index,item){
			var $_item=$(item);
			$_item.attr("title_index",index);
		});
		//初始前
		_this.opt && _this.opt.init_before && _this.opt.init_before.call && _this.opt.init_before.call(_this);
		//初始化
		if(_this.opt.custom_swf && util.isFunction(_this.opt.custom_swf["init"]) && _this.opt.custom_swf["show"]){
			_this.opt.custom_swf.init.call(this);
		}else{
			_this.swf[_this.opt.effect].init.call(_this);
		}
		//初始后
		_this.opt &&　_this.opt.init_after &&　_this.opt.init_after.call && _this.opt.init_after.call(_this);
	},
	//对于 1 屏显示多个的情况  进行 宽度计算
	competer_visit:function(){
		var _this=this,
			cc_width=0;
		if(_this.opt.visit>1){
			cc_width=(_this.$_s_wrap_main.parent().width())/_this.opt.visit-(_this.$_s_wrap_main.outerWidth(true)-_this.$_s_wrap_main.width());
			_this.$_mainCell.css({
				width:cc_width+'px'
			});
		}
	},
	//初始化 配置
	getOpt:function(){
		var default_obj={
			wrap:'.slide',
			mainCell:'.bd li',
			titleCell:'.hd li',
			title_class:'on',
			effect:'left',
			time:500,
			prev:'',
			next:'',
			differ:1,
			visit:1,
			init_bafore:function(){},
			init_after:function(){},
			custom_event:{},
			custom_swf:{}
		};
		var _this=this;
		if(_this.opt && _this.opt.time && isNaN(parseInt(_this.opt.time))){
			_this.opt.time=500;
		}
		_this.opt=util.objAssign(default_obj,_this.opt);
		//每次移动的 item 数不能超过 可视个数
		if(_this.opt.differ > _this.opt.visit){
			_this.opt.differ = _this.opt.visit;
		}
	},
	//轮播图效果切换
	show:function(){
		var _this=this;
		if(_this.opt.custom_swf && util.isFunction(_this.opt.custom_swf["init"]) && _this.opt.custom_swf["show"]){
			_this.opt.custom_swf.show.apply(this,arguments);
		}
		else if(_this.opt.visit>1){
			_this.swf["left"] && _this.swf["left"].show &&_this.swf["left"].show.apply(_this,arguments);
		}
		else{
			_this.swf[_this.opt.effect] && _this.swf[_this.opt.effect].show &&_this.swf[_this.opt.effect].show.apply(_this,arguments);
		}
	},
	//监听自定义对象
	addCustomEvent:function(){
		var _this=this;
		if(_this.opt.custom_event && util.isObject(_this.opt.custom_event)){
			for(k in _this.opt.custom_event){
				if(util.isObject(_this.opt.custom_event[k])){
					for(kk in _this.opt.custom_event[k]){
						_this.$_wrap.on(kk,k,_this.opt.custom_event[k][kk]);
					}
				}
			}
		}
	},
	//绑定事件
	bindEvent:function(){ //只定义基本 事件
		var _this=this;
		//上一个
		_this.$_wrap.on("click",_this.opt.prev,function(){
			if(_this.$_wrap.data('is_lock')) return false;

			if(_this.index==0){
				_this.index=_this.$_mainCell.size()-_this.opt.visit;
			}else{
				if(_this.index-_this.opt.differ<0){
					_this.index=0;
				}else{
					_this.index-=_this.opt.differ;
				}
			}
			_this.show(_this.$_wrap.find("[main_index="+_this.index+"]"));
			_this.$_wrap.data("is_lock",true);
		});
		//下一个
		_this.$_wrap.on("click",_this.opt.next,function(){
			if(_this.$_wrap.data('is_lock')) return false;

			if(_this.index>=_this.$_mainCell.size()-_this.opt.visit){
				_this.index=0;
			}else{
				if(_this.index+_this.opt.differ>_this.$_mainCell.size()-_this.opt.differ){
					_this.index=_this.$_mainCell.size()-_this.opt.differ;
				}else{
					_this.index+=_this.opt.differ;
				}
			}
			_this.show(_this.$_wrap.find("[main_index="+_this.index+"]"));
			_this.$_wrap.data("is_lock",true);
		});
		//索引点击
		_this.$_wrap.on("click",_this.opt.titleCell,function(e){
			var $_this=$(e.target);
			_this.index=$_this.attr("title_index");

			_this.$_titleCell.removeClass(_this.opt.title_class);
			$_this.addClass(_this.opt.title_class);
			_this.show(_this.$_wrap.find("[main_index="+_this.index+"]"));
		});

		_this.addCustomEvent();
	},
	//动画对象
	swf:{
		//滚动
		left:{
			init:function(){
				var _this=this;  //这里的 this 会通过 call 指向 slide
				var m_wrap_info,t_wrap_info;

				//获取 整体的宽，高
				var get_info=function(ele){
					var cc_obj={w:0,h:0};
					$(ele).each(function(index,item){
						var $_item=$(item);
						cc_obj.w+=$_item.outerWidth(true);
						if(cc_obj.h<$_item.height()){
							cc_obj.h=$_item.height();
						}
					});
					return cc_obj;
				}
				if(!_this){
					return false;
				}

				_this.$_mainCell.wrapAll("<div class='s_wrap_main'></div>");
				_this.$_s_wrap_main=_this.$_wrap.find(".s_wrap_main");

				m_wrap_info=get_info(_this.$_mainCell);
				t_wrap_info=get_info(_this.$_titleCell);
				_this.$_mainCell.css({
					float:"left"
				});
				_this.$_s_wrap_main.css({
					width:m_wrap_info.w+'px',
					height:m_wrap_info.h+'px',
					position:'absolute',
					left:'0px',
					top:'0px'
				});

				_this.competer_visit();

				
				if(_this.$_s_wrap_main.parent().css("position") =="static"){
					_this.$_s_wrap_main.parent().css("position","relative");
				}
				
				if(t_wrap_info.w>_this.$_titleCell.parent().width()){
					_this.$_titleCell.wrapAll("<div class='s_wrap_title'></div>");
					_this.$_s_wrap_title=_this.$_wrap.find(".s_wrap_title");

					_this.$_s_wrap_title.css({
						width:t_wrap_info.w+'px',
						height:t_wrap_info.h+'px',
						position:'absolute',
						left:'0px',
						top:'0px'
					});
					if(_this.$_s_wrap_title.parent().css("position") == "static"){
						_this.$_s_wrap_title.parent().css("position","relative");
					}
				};
				_this.$_titleCell.eq(0).addClass(_this.opt.title_class);

			},
			show:function(ele,is_title){
				var _this=this,
					$_ele=$(ele);
				if(!_this.$_wrap.data('is_lock')){
					_this.$_s_wrap_main.data('is_animate_on','true');
					_this.$_wrap.find("[title_index="+_this.index+"]").addClass(_this.opt.title_class).siblings().removeClass(_this.opt.title_class);
					_this.$_s_wrap_main.stop().animate({
						left:0-$_ele.position().left+"px"
					},_this.opt.time,"swing",function(){
						_this.$_wrap.find("._thisClass").removeClass("_thisClass");
						$_ele.addClass("_thisClass");
						_this.$_wrap.data('is_lock',false);
					});
				}
			}
		},
		//渐变
		fade:{
			init:function(){
				var _this=this;
				_this.$_mainCell.css({
					display:"none"
				});
				_this.$_mainCell.eq(0).fadeIn(_this.opt.time);

			},
			show:function(ele){
				var _this=this;
					$_ele=$(ele);

				if(!_this.$_wrap.data('is_lock')){
					_this.$_mainCell.fadeOut(_this.opt.time);
					_this.$_mainCell.find("_thisClass").removeClass("_thisClass");
					_this.$_wrap.removeClass(_this.opt.title_class).find("[title_index="+_this.index+"]").addClass(_this.opt.title_class);
					$_ele.fadeIn(_this.opt.time,function(){
						$_ele.addClass("_thisClass");
						_this.$_wrap.data('is_lock',false);
					})
				}
			}
		}
	}
}
/*
{
	wrap:".wrap"  								//slide 盒子
	mainCell:".bd a"   							// 轮播内容 选择器
	titleCell:".hd a"  							// 索引 选择器
	title_class:"on" 							//  索引当前class
	effect:"left"  								//动画  例如  left || fade
	time:500    								//动画时间  norml:500
	prev:'.btn_prev'   							//上一个按钮  选择器
	next:'.btn_next'  							//下一个按钮选择器
	differ:1  									//切换的 内容 个数
	visit:2   									//可视的 内容 个数
	init_bafore:function(){}  					//初始化前  执行函数
	init_after:function(){} 					//初始化后 执行函数
	custom_event:{//自定义 事件
		".bd a":{
			"click":function(e){
				console.log(e.target);
			}
		}
	} 
												* init 跟 show 的 this 会指定到 slide 对象， 
	custom_swf:{								//自定义 动画，必须定义 init  show,
		init:function(){
	
		},
		show:function(){
	
		}
	}  
};
*/
globar.slide=function(opt){
	if(!opt || !util.isObject(opt)){
		return false;
	}
	return new slide(opt);
}
})(window);
