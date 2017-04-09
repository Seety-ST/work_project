<?php
    //关于模特的
    $property_for_search_ary[31] = array(
        array(
            'text'=>'类型',//属性名称
            'name'=>'detail[458]',
            'data_type'=>1,//1代表系统属性 2代表自有属性
            'select_type'=>1,
            'function_param'=>array('type_id'=>31,'parent_id'=>458,'is_have_son'),//系统属性根据参数调用相应的方法取属性
        ),
        array(
            'text'=>'身高',
            'name'=>'m_height',
            'data_type'=>2,//自有属性直接返回data数据
            'select_type'=>1,
            'data'=>array(
                array('key'=>'150,160','val'=>'150-160cm'),
                array('key'=>'160,170','val'=>'160-170cm'),
                array('key'=>'170,180','val'=>'170-180cm'),
                array('key'=>'180,190','val'=>'180-190cm'),
                array('key'=>'190,200','val'=>'190-200cm'),
                array('key'=>'200','val'=>'200cm以上'),
            )
        ),
        array(
            'text'=>'罩杯',
            'name'=>'m_cup',
            'data_type'=>2,
            'select_type'=>1,
            'data'=>array(
                array('key'=>'A','val'=>'A'),
                array('key'=>'B','val'=>'B'),
                array('key'=>'C','val'=>'C'),
                array('key'=>'D','val'=>'D'),
                array('key'=>'E','val'=>'E+'),
            )
        ),
        array(
            'text'=>'性别',
            'name'=>'m_sex',
            'data_type'=>2,
            'select_type'=>1,
            'data'=>array(
                array('key'=>'男','val'=>'男模'),
                array('key'=>'女','val'=>'女模'),
            )
        ),
        array(
            'text'=>'价钱',
            'name'=>'prices_list',
            'data_type'=>2,
            'select_type'=>1,
            'data'=>array(
                array('key'=>'1','val'=>'100以下'),
                array('key'=>'2','val'=>'100-300'),
                array('key'=>'3','val'=>'300-500'),
                array('key'=>'27','val'=>'500-800'),
                array('key'=>'4','val'=>'800-1000'),
                array('key'=>'5','val'=>'1000以上'),
            )
        ),
        array(
            'text'=>'商家类型',
            'name'=>'basic_type',
            'data_type'=>2,
            'select_type'=>1,
            'data'=>array(
                array('key'=>'person','val'=>'个人'),
                array('key'=>'company','val'=>'企业'),
            ),
         ),
    );
    
    //关于化妆的
    $property_for_search_ary[3] = array(
        array(
            'text'=>'类型',
            'name'=>'detail[68]',
            'data_type'=>1,
            'select_type'=>1,
            'function_param'=>array('type_id'=>3,'parent_id'=>68,'is_have_son'=>true),
        ),
        array(
            'text'=>'跟妆类型',
            'name'=>'detail[152]',
            'data_type'=>1,
            'select_type'=>1,
            'function_param'=>array('type_id'=>3,'parent_id'=>152),
        ),
        array(
            'text'=>'价格',
            'name'=>'prices_list',
            'data_type'=>2,
            'select_type'=>1,
            'data'=>array(
                array('key'=>'19','val'=>'300以下'),
                array('key'=>'20','val'=>'300-500'),
                array('key'=>'21','val'=>'500-1000'),
                array('key'=>'22','val'=>'1000以上'),
            )
        ),
        array(
            'text'=>'商家类型',
            'name'=>'basic_type',
            'data_type'=>2,
            'select_type'=>1,
            'data'=>array(
                array('key'=>'person','val'=>'个人'),
                array('key'=>'company','val'=>'企业'),
            ),
         ),
    );
    
    //关于影棚租凭
     $property_for_search_ary[12] = array(
          array(
            'text'=>'类型',
            'name'=>'detail[475]',
            'data_type'=>1,
            'select_type'=>1,
            'function_param'=>array('type_id'=>12,'parent_id'=>475,'is_have_son'=>true),
          ),
          array(
            'text'=>'影棚面积',
            'name'=>'name_19',
            'data_type'=>2,
            'select_type'=>1,
            'data'=>array(
                array('key'=>'0,100','val'=>'100平方米含以下'),
                array('key'=>'101,300','val'=>'101-300平方米'),
                array('key'=>'301,500','val'=>'301-500平方米'),
                array('key'=>'501,1000','val'=>'501-1000平方米'),
                array('key'=>'1000,99999999','val'=>'1000平方米以上'),
            ),
          ),
         array(
            'text'=>'价格',
            'name'=>'prices_list',
            'data_type'=>2,
            'select_type'=>1,
            'data'=>array(
                array('key'=>'23','val'=>'300以下'),
                array('key'=>'24','val'=>'300-500'),
                array('key'=>'25','val'=>'500-1000'),
                array('key'=>'26','val'=>'1000以上'),
            )
          ),
         array(
            'text'=>'商家类型',
            'name'=>'basic_type',
            'data_type'=>2,
            'select_type'=>1,
            'data'=>array(
                array('key'=>'person','val'=>'个人'),
                array('key'=>'company','val'=>'企业'),
            ),
         ),
     );
     //关于摄影服务的
     $property_for_search_ary[40] = array(
         array(
            'text'=>'类型',
            'name'=>'detail[90]',
            'data_type'=>1,
            'select_type'=>1,
            'function_param'=>array('type_id'=>40,'parent_id'=>90,'is_have_son'=>true),
          ),
        array(
            'text'=>'价格',
            'name'=>'prices_list',
            'data_type'=>2,
            'select_type'=>1,
            'data'=>array(
                array('key'=>'12','val'=>'500以下'),
                array('key'=>'13','val'=>'500-1000'),
                array('key'=>'14','val'=>'1000-2000'),
                array('key'=>'15','val'=>'2000-3000'),
                array('key'=>'16','val'=>'3000-5000'),
                array('key'=>'17','val'=>'5000-10000'),
                array('key'=>'18','val'=>'10000以上'),
            ),
          ),
         array(
            'text'=>'商家类型',
            'name'=>'basic_type',
            'data_type'=>2,
            'select_type'=>1,
            'data'=>array(
                array('key'=>'person','val'=>'个人'),
                array('key'=>'company','val'=>'企业'),
            ),
         ),
         
     );
     
     //关于约美食的
     $property_for_search_ary[41] = array(
         array(
            'text'=>'类型',
            'name'=>'detail[219]',
            'data_type'=>1,
            'select_type'=>1, 
            'function_param'=>array('type_id'=>41,'parent_id'=>219),
          ),
         array(
            'text'=>'环境',
            'name'=>'detail[220]',
            'data_type'=>1,
            'select_type'=>1, 
            'function_param'=>array('type_id'=>41,'parent_id'=>220),
          ),
         array(
            'text'=>'价格',
            'name'=>'prices_list',
            'data_type'=>2,
            'select_type'=>1,
            'data'=>array(
                array('key'=>'28','val'=>'100以下'),
                array('key'=>'29','val'=>'100-300'),
                array('key'=>'30','val'=>'300-500'),
                array('key'=>'31','val'=>'500-1000'),
                array('key'=>'32','val'=>'1000以上'),
            ),
          ),
        array(
           'text'=>'商家类型',
           'name'=>'basic_type',
           'data_type'=>2,
           'select_type'=>1,
           'data'=>array(
               array('key'=>'person','val'=>'个人'),
               array('key'=>'company','val'=>'企业'),
           ),
        ),
     );
     
     //关于约有趣的
     $property_for_search_ary[43] = array(
         array(
            'text'=>'类型',
            'name'=>'detail[278]',
            'data_type'=>1,
            'select_type'=>1, 
            'function_param'=>array('type_id'=>43,'parent_id'=>278,'is_have_son'=>true),
          ),
//         array(
//            'text'=>'价格',
//            'name'=>'prices_list', 
//            'data_type'=>2,
//            'select_type'=>1, 
//            'data'=>array(
//                array('key'=>'33','val'=>'100元以下'),
//                array('key'=>'34','val'=>'100-300元'),
//                array('key'=>'35','val'=>'300-500元'),
//                array('key'=>'36','val'=>'500-1000元'),
//                array('key'=>'37','val'=>'1000以上'),
//            ),
//          ),
         array(
            'text'=>'商家类型',
            'name'=>'basic_type',
            'data_type'=>2,
            'select_type'=>1,
            'data'=>array(
                array('key'=>'person','val'=>'个人'),
                array('key'=>'company','val'=>'企业'),
            ),
         ),
         
     );
    
     //关于摄影培训的
     $property_for_search_ary[5] = array(
         array(
            'text'=>'类型',
            'name'=>'detail[382]',
            'data_type'=>1,
            'select_type'=>1, 
            'function_param'=>array('type_id'=>5,'parent_id'=>382,'is_have_son'=>true),
          ),
         array(
           'text'=>'价钱',
            'name'=>'prices_list', 
            'data_type'=>2,
            'select_type'=>1, 
            'data'=>array(
                array('key'=>'6','val'=>'100元以下'),
                array('key'=>'7','val'=>'101-1000元'),
                array('key'=>'8','val'=>'1001-2000元'),
                array('key'=>'9','val'=>'2001-3000元'),
                array('key'=>'10','val'=>'3000-4000元'),
                array('key'=>'11','val'=>'4000以上'),
            ),
         ),
         array(
            'text'=>'商家类型',
            'name'=>'basic_type',
            'data_type'=>2,
            'select_type'=>1,
            'data'=>array(
                array('key'=>'person','val'=>'个人'),
                array('key'=>'company','val'=>'企业'),
            ),
         ),
     );
     
    //关于活动的
     $property_for_search_ary[42] = array(
         array(
            'text'=>'类型',
            'name'=>'detail[270]',
            'data_type'=>1,
            'select_type'=>1, 
            'function_param'=>array('type_id'=>42,'parent_id'=>270,'is_have_son'=>true),
         ),
         array(
            'text'=>'组织者',
            'name'=>'is_official',
            'data_type'=>2,
            'select_type'=>1,
            'data'=>array(
                array('key'=>'1','val'=>'官方'),
                array('key'=>'0','val'=>'非官方'),
            ),
         ),
         array(
           'text'=>'时间',
            'name'=>'front_time', 
            'data_type'=>2,
            'select_type'=>1, 
            'data'=>array(
                array('key'=>'today','val'=>'今天'),
                array('key'=>'tomorrow','val'=>'明天'),
                array('key'=>'weekend','val'=>'周末'),
                array('key'=>'self','val'=>'自定义'),
            ),
         ),
         array(
           'text'=>'价钱',
            'name'=>'prices_list', 
            'data_type'=>1,
            'select_type'=>1, 
            'data'=>array(
                array('key'=>'38','val'=>'100元以下'),
                array('key'=>'39','val'=>'100-200元'),
                array('key'=>'40','val'=>'200-400元'),
                array('key'=>'41','val'=>'400-800元'),
                array('key'=>'42','val'=>'800-1500元'),
                array('key'=>'43','val'=>'1500元以上'),
            ),
         ),
         array(
            'text'=>'商家类型',
            'name'=>'basic_type',
            'data_type'=>2,
            'select_type'=>1,
            'data'=>array(
                array('key'=>'person','val'=>'个人'),
                array('key'=>'company','val'=>'企业'),
            ),
         ),
         
     );
     
     //占卜算命师
     $property_for_search_ary[44] = array(
         array(
            'text'=>'类型',
            'name'=>'detail[597]',
            'data_type'=>1,
            'select_type'=>1, 
            'function_param'=>array('type_id'=>44,'parent_id'=>597,'is_have_son'=>true),
         ),
         array(
            'text'=>'商家类型',
            'name'=>'basic_type',
            'data_type'=>2,
            'select_type'=>1,
            'data'=>array(
                array('key'=>'person','val'=>'个人'),
                array('key'=>'company','val'=>'企业'),
            ),
         ),
     );
     
     //舞蹈/健身/运动
     $property_for_search_ary[45] = array(
         array(
            'text'=>'类型',
            'name'=>'detail[601]',
            'data_type'=>1,
            'select_type'=>1, 
            'function_param'=>array('type_id'=>45,'parent_id'=>601,'is_have_son'=>true),
         ),
         array(
            'text'=>'商家类型',
            'name'=>'basic_type',
            'data_type'=>2,
            'select_type'=>1,
            'data'=>array(
                array('key'=>'person','val'=>'个人'),
                array('key'=>'company','val'=>'企业'),
            ),
         ),
     );
     
     //咨询顾问
     $property_for_search_ary[47] = array(
         array(
            'text'=>'类型',
            'name'=>'detail[581]',
            'data_type'=>1,
            'select_type'=>1, 
            'function_param'=>array('type_id'=>47,'parent_id'=>581,'is_have_son'=>true),
         ),
         array(
            'text'=>'商家类型',
            'name'=>'basic_type',
            'data_type'=>2,
            'select_type'=>1,
            'data'=>array(
                array('key'=>'person','val'=>'个人'),
                array('key'=>'company','val'=>'企业'),
            ),
         ),
     );
     
     //手工匠人
     $property_for_search_ary[49] = array(
         array(
            'text'=>'类型',
            'name'=>'detail[567]',
            'data_type'=>1,
            'select_type'=>1, 
            'function_param'=>array('type_id'=>49,'parent_id'=>567,'is_have_son'=>true),
         ),
         array(
            'text'=>'商家类型',
            'name'=>'basic_type',
            'data_type'=>2,
            'select_type'=>1,
            'data'=>array(
                array('key'=>'person','val'=>'个人'),
                array('key'=>'company','val'=>'企业'),
            ),
         ),
     );
     
     //美食DIY者
     $property_for_search_ary[52] = array(
         array(
            'text'=>'类型',
            'name'=>'detail[612]',
            'data_type'=>1,
            'select_type'=>1, 
            'function_param'=>array('type_id'=>52,'parent_id'=>612,'is_have_son'=>true),
         ),
         array(
            'text'=>'商家类型',
            'name'=>'basic_type',
            'data_type'=>2,
            'select_type'=>1,
            'data'=>array(
                array('key'=>'person','val'=>'个人'),
                array('key'=>'company','val'=>'企业'),
            ),
         ),
     );
     
     return $property_for_search_ary;
    
?>




