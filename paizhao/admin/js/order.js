$(function () {
    $('.date').datetimepicker({
       language: 'zh-CN',
       weekStart: 1,
       todayBtn: 1,
       autoclose: 1,
       todayHighlight: 1,
       startView: 2,
       minView: 2,
       forceParse: 0
   });

  var keyword = $('#search_keyword_input').attr('name');
  if (keyword.length==0)
  {
    $('#search_keyword_input').attr('name','order_sn');
  }
   $("#search_keyword_option").change(function () {
       var input_name = $(this).children('option:selected').attr("data-val");
       $("#search_keyword_input").attr("name", input_name);
       $("#search_selected").val(input_name);
   });
});
/**
* 以下功能为关闭订单  拒绝退款
* yue_admin/task/mall_bill.php
* @param action reject_refund/close_order
* @param order_sn
*/
function open_dialog(action,order_sn) {
var action_str = 'action='+action+'&order_sn=' + order_sn;
 $("#dialog_submit_form").attr("action",action_str);
 if ( action=='order_reject_refund' )
 {
     $("#dialog-form .modal-title").html("拒绝退款");
     $("#reason_p").html("请确保商家及消费者已同意拒绝退款，拒绝退款后，将按回订单原来流程。");
 }
 if ( action=='order_close_order' )
 {
     $("#dialog-form .modal-title").html("关闭订单");
     $("#reason_p").html("请确保商家及消费者已同意取消订单，取消订单后，已付款将全额退给买家");
 }
 $("#dialog_save").html("提交");
 $('#dialog-form').modal({
   keyboard: false
 })
}
function dialog_submit() {
var reason = $('#reason').val().trim();
if (reason.length<1)
{
 alert("请填写原因！")
 return false;
}
$("#dialog_save").html("正在提交");
var action_str = $("#dialog_submit_form").attr("action");
var reason = $('#reason').val();
$.ajax({
   url: 'order.php?'+action_str,
   type: 'post',
   cache: false,
   dataType: 'json',
   data: {reason: reason},
   success: function (data) {
     $('#dialog-form').modal('hide');
       if (data.result == 1) {
           location.reload();
       }
       else {
           alert("保存失败")
       }
   },
   error: function () {
       alert("异常！");
   }
});
}
/**
* 以下功能为 签到     同意退款  接受订单
* yue_admin/task/mall_bill.php
* @param order_sn
*/
function submit_action(action,order_sn) {
$.get("order.php", { action: action, order_sn: order_sn },
function(data){
 var obj = $.parseJSON(data);
 if (obj.result == 1) {
 location.reload();
}
else {
     alert(obj.message)
}
});
}

function updateTips(t) {
 var tips = $(".validateTips");
 tips.text(t).addClass("ui-state-highlight");
 setTimeout(function () {
     tips.removeClass("ui-state-highlight", 1500);
 }, 500);
}

function search_order() {
 this.form.target = '';
 $('#action').attr('value', 'list');
 this.form.submit();
}
