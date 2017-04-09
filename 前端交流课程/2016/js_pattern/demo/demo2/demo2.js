function kele(){
	return {name:"kele"};
}
function xuebi(){
	return {name:"xuebi"};
}
var drink=function(type){
	var cc_obj;
	switch(type){
		case 0:cc_obj=new kele();break;

		case 1:cc_obj=new xuebi();break;
	}
	return cc_obj;
}
$(function(){
	var init_ui=function(val){
		var val=val || "空"
		$(".drink_result").text(val);
	}
	$(".btn").click(function(){
		var $_this=$(this);
		var cc_drink=drink($_this.data("type"));
		init_ui(cc_drink.name);
	});
})


