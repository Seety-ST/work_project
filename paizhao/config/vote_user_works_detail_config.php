<?php 
//选手作品其它项序列化,配置项  //input 1单选 2多选 3输入框 4文本框 5在线编辑器 6日期 7日期时间 8地区 9隐藏域
// 添加活动专题时读取配置并存到数据库，之后都从数据库读取配置，不读此文件 2016-8-4 by lcx
// 配置参考注释里内容
return array(
	/*
        array(
           'name'  =>'作品是否独立完成',
           'key'   =>'unique_finish',
           'input'  =>1,
           'child_data'=>array(
               array('name'=>'是','val'=>'1'),
               array('name'=>'否','val'=>'2'),
           ), 
           'is_show'=>1,  //1代表显示 2 代表不显示
        ),
        array(
           'name'  =>'作品价格',
           'key'   =>'work_price',
           'input'  =>3,
           'is_show'=>1,
        ),
        array(
           'name'  =>'何时创作',
           'key'   =>'creat_time',
           'input'  =>6,
           'is_show'=>1,
        ),
        array(
            'name'=>'作品风格',
            'key'=>'style',
            'input'=>2,
            'child_data'=>array(
                array('name'=>'清新','val'=>1,'form_name'=>'style'),
                array('name'=>'温情','val'=>2,'form_name'=>'style'),
                array('name'=>'大自然','val'=>3,'form_name'=>'style'),
                array('name'=>'亲子','val'=>4,'form_name'=>'style'),
            ),
            'is_show'=>1,
        ),
    */    array(
           'name'  =>'团队介绍',
           'key'   =>'team_introduce',
           'input'  =>4,
           'is_show'=>1,
        ),
	
);




