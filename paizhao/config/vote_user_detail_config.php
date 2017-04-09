<?php 
//选手其它项序列化,配置项  //input 1单选 2多选 3输入框 4文本框 5在线编辑器 6日期 7日期时间 8地区 9隐藏域
// 添加活动专题时读取配置并存到数据库，之后都从数据库读取配置，不读此文件 2016-8-4 by lcx
// 配置参考注释里内容
return array(
		array(
           'name'  =>'类别',
           'key'   =>'type',
           'input'  =>1,
           'child_data'=>array(
               array('name'=>'主题餐厅','val'=>'1'),
               array('name'=>'文艺场所','val'=>'2'),
               array('name'=>'手工体验','val'=>'3'),
               array('name'=>'其它','val'=>'4'),
           ), 
           'is_show'=>1,  //1代表显示 2 代表不显示
        ),
		array(
           'name'  =>'描述',
           'key'   =>'desc',
           'input'  =>4,
           'is_show'=>1,
        ),
	/*
        array(
           'name'  =>'性别',
           'key'   =>'sex',
           'input'  =>1,
           'child_data'=>array(
               array('name'=>'男','val'=>'1'),
               array('name'=>'女','val'=>'2'),
           ), 
           'is_show'=>1,  //1代表显示 2 代表不显示
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
           'is_show'=>1,
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
            'is_show'=>1,
        )
	*/
);




