var obj=[
    {
        key:"menu1",
        val:'menu1_text',
        child:[
            {
                key:"child1",
                val:"child1_text",
                child:[ 
                    {
                        key:"test",
                        val:"test_text"
                    }
                ]
            },
            {
                key:"child2",
                val:'child2_text'
            }
        ]
    },
    {
        key:"menu2",
        val:"menu2_text"
    }
];
//目标数据结构
var obj2={
    "menu1":{
        "child1":"child1",
        "child2":{
            "test":"test"
        }
    },
    "menu2":"menu2"
};

function fn_parse_data(arr){
    var cc_obj={};
    for(var i=0,len=arr.length;i<len;i++){
        if(arr[i]["child"] && arr[i]["child"].length>0){
            cc_obj[arr[i]["key"]]=fn_parse_data(arr[i]["child"]);
        }else{
            cc_obj[arr[i]["key"]]=arr[i]["key"];
        }
    }
    return cc_obj;
}

console.log(JSON.stringify(fn_parse_data(obj)));