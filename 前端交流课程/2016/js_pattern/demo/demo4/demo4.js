var module=(function(){
    var my={};
    var private_var=0;
    function privatefn()
    {
        //code  私有方法
    };
    my.publishfn=function(){
        //code 公共方法 
    };
    my.publish_var=1;
    return my;
})();
console.log(module);

var module2=(function(module){
    var private_id=3;
    function get_id(){
        return private_id;
    }
    module.get_id=get_id;
    return module;
})({name:"test"});
console.log(module2);

var module3=(function(){
    var info={
        name:"name_value",
        age:20,
        job:'job_value',
        status:0
    };

    var getinfo=function(){
        return info;
    };

    return {
        getinfo:getinfo
    };
})();
console.log(module3.getinfo());