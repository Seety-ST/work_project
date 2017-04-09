var discountCash=function(cash,dis){
    dis=dis>1 || dis<=0 ?1:dis;
    return cash*dis;
};
var returnCash=function(cash,re_cash){
    return {cash:cash,re_cash:re_cash};
};

var Computer_cash=function(opt,type){
    var cc_result;

    switch(type){
        case "returnCash": cc_result=returnCash(opt.cash,opt.re_cash);
        break;
        case "discountCash": cc_result=discountCash(opt.cash,opt.dis);
    }
    return cc_result;
};

//Computer_cash({cash:10,re_cash:2},"returnCash");
//Computer_cash({cash:10,dis:0.5},"discountCash");