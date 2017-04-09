<?php 
//职业详细 序列化,配置项  //input 1单选 2多选 3输入框 4文本框 5在线编辑器 6日期 7日期时间 8地区 9隐藏域
// 配置参考注释里内容
return array(
		array(
           'name'  =>'工作年限',
           'key'   =>'year',
           'input'  =>1,
           'child_data'=>array(
               array('name'=>'0~2年','val'=>'1'),
               array('name'=>'2~3年','val'=>'2'),
               array('name'=>'3~4年','val'=>'3'),
               array('name'=>'4年以上','val'=>'4'),
           ), 
           
        ),
		array(
           'name'  =>'描述',
           'key'   =>'desc',
           'input'  =>4,
           
        ),
        array(
           'name'  =>'性别',
           'key'   =>'sex',
           'input'  =>1,
           'child_data'=>array(
               array('name'=>'男','val'=>'1'),
               array('name'=>'女','val'=>'2'),
           ), 
            
        ),
        array(
           'name'  =>'qq号码',
           'key'   =>'qq_number',
           'input'  =>3,
           'is_show'=>1,
        ),
        array(
           'name'  =>'出生年月日',
           'key'   =>'birth_day',
           'input'  =>6,
           
        ),
        array(
            'name'=>'兴趣爱好',
            'key'=>'hobby',
            'input'=>2,
            'child_data'=>array(
                array('name'=>'游泳','val'=>1,'form_name'=>'detail[hobby][]'),
                array('name'=>'唱K','val'=>2,'form_name'=>'detail[hobby][]'),
                array('name'=>'上网','val'=>3,'form_name'=>'detail[hobby][]'),
            ),
            
        )
	
);




