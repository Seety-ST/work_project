<?php
    //����ģ�ص�
    $property_for_search_ary[31] = array(
        array(
            'text'=>'����',//��������
            'name'=>'detail[458]',
            'data_type'=>1,//1����ϵͳ���� 2������������
            'select_type'=>1,
            'function_param'=>array('type_id'=>31,'parent_id'=>458,'is_have_son'),//ϵͳ���Ը��ݲ���������Ӧ�ķ���ȡ����
        ),
        array(
            'text'=>'���',
            'name'=>'m_height',
            'data_type'=>2,//��������ֱ�ӷ���data����
            'select_type'=>1,
            'data'=>array(
                array('key'=>'150,160','val'=>'150-160cm'),
                array('key'=>'160,170','val'=>'160-170cm'),
                array('key'=>'170,180','val'=>'170-180cm'),
                array('key'=>'180,190','val'=>'180-190cm'),
                array('key'=>'190,200','val'=>'190-200cm'),
                array('key'=>'200','val'=>'200cm����'),
            )
        ),
        array(
            'text'=>'�ֱ�',
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
            'text'=>'�Ա�',
            'name'=>'m_sex',
            'data_type'=>2,
            'select_type'=>1,
            'data'=>array(
                array('key'=>'��','val'=>'��ģ'),
                array('key'=>'Ů','val'=>'Ůģ'),
            )
        ),
        array(
            'text'=>'��Ǯ',
            'name'=>'prices_list',
            'data_type'=>2,
            'select_type'=>1,
            'data'=>array(
                array('key'=>'1','val'=>'100����'),
                array('key'=>'2','val'=>'100-300'),
                array('key'=>'3','val'=>'300-500'),
                array('key'=>'27','val'=>'500-800'),
                array('key'=>'4','val'=>'800-1000'),
                array('key'=>'5','val'=>'1000����'),
            )
        ),
        array(
            'text'=>'�̼�����',
            'name'=>'basic_type',
            'data_type'=>2,
            'select_type'=>1,
            'data'=>array(
                array('key'=>'person','val'=>'����'),
                array('key'=>'company','val'=>'��ҵ'),
            ),
         ),
    );
    
    //���ڻ�ױ��
    $property_for_search_ary[3] = array(
        array(
            'text'=>'����',
            'name'=>'detail[68]',
            'data_type'=>1,
            'select_type'=>1,
            'function_param'=>array('type_id'=>3,'parent_id'=>68,'is_have_son'=>true),
        ),
        array(
            'text'=>'��ױ����',
            'name'=>'detail[152]',
            'data_type'=>1,
            'select_type'=>1,
            'function_param'=>array('type_id'=>3,'parent_id'=>152),
        ),
        array(
            'text'=>'�۸�',
            'name'=>'prices_list',
            'data_type'=>2,
            'select_type'=>1,
            'data'=>array(
                array('key'=>'19','val'=>'300����'),
                array('key'=>'20','val'=>'300-500'),
                array('key'=>'21','val'=>'500-1000'),
                array('key'=>'22','val'=>'1000����'),
            )
        ),
        array(
            'text'=>'�̼�����',
            'name'=>'basic_type',
            'data_type'=>2,
            'select_type'=>1,
            'data'=>array(
                array('key'=>'person','val'=>'����'),
                array('key'=>'company','val'=>'��ҵ'),
            ),
         ),
    );
    
    //����Ӱ����ƾ
     $property_for_search_ary[12] = array(
          array(
            'text'=>'����',
            'name'=>'detail[475]',
            'data_type'=>1,
            'select_type'=>1,
            'function_param'=>array('type_id'=>12,'parent_id'=>475,'is_have_son'=>true),
          ),
          array(
            'text'=>'Ӱ�����',
            'name'=>'name_19',
            'data_type'=>2,
            'select_type'=>1,
            'data'=>array(
                array('key'=>'0,100','val'=>'100ƽ���׺�����'),
                array('key'=>'101,300','val'=>'101-300ƽ����'),
                array('key'=>'301,500','val'=>'301-500ƽ����'),
                array('key'=>'501,1000','val'=>'501-1000ƽ����'),
                array('key'=>'1000,99999999','val'=>'1000ƽ��������'),
            ),
          ),
         array(
            'text'=>'�۸�',
            'name'=>'prices_list',
            'data_type'=>2,
            'select_type'=>1,
            'data'=>array(
                array('key'=>'23','val'=>'300����'),
                array('key'=>'24','val'=>'300-500'),
                array('key'=>'25','val'=>'500-1000'),
                array('key'=>'26','val'=>'1000����'),
            )
          ),
         array(
            'text'=>'�̼�����',
            'name'=>'basic_type',
            'data_type'=>2,
            'select_type'=>1,
            'data'=>array(
                array('key'=>'person','val'=>'����'),
                array('key'=>'company','val'=>'��ҵ'),
            ),
         ),
     );
     //������Ӱ�����
     $property_for_search_ary[40] = array(
         array(
            'text'=>'����',
            'name'=>'detail[90]',
            'data_type'=>1,
            'select_type'=>1,
            'function_param'=>array('type_id'=>40,'parent_id'=>90,'is_have_son'=>true),
          ),
        array(
            'text'=>'�۸�',
            'name'=>'prices_list',
            'data_type'=>2,
            'select_type'=>1,
            'data'=>array(
                array('key'=>'12','val'=>'500����'),
                array('key'=>'13','val'=>'500-1000'),
                array('key'=>'14','val'=>'1000-2000'),
                array('key'=>'15','val'=>'2000-3000'),
                array('key'=>'16','val'=>'3000-5000'),
                array('key'=>'17','val'=>'5000-10000'),
                array('key'=>'18','val'=>'10000����'),
            ),
          ),
         array(
            'text'=>'�̼�����',
            'name'=>'basic_type',
            'data_type'=>2,
            'select_type'=>1,
            'data'=>array(
                array('key'=>'person','val'=>'����'),
                array('key'=>'company','val'=>'��ҵ'),
            ),
         ),
         
     );
     
     //����Լ��ʳ��
     $property_for_search_ary[41] = array(
         array(
            'text'=>'����',
            'name'=>'detail[219]',
            'data_type'=>1,
            'select_type'=>1, 
            'function_param'=>array('type_id'=>41,'parent_id'=>219),
          ),
         array(
            'text'=>'����',
            'name'=>'detail[220]',
            'data_type'=>1,
            'select_type'=>1, 
            'function_param'=>array('type_id'=>41,'parent_id'=>220),
          ),
         array(
            'text'=>'�۸�',
            'name'=>'prices_list',
            'data_type'=>2,
            'select_type'=>1,
            'data'=>array(
                array('key'=>'28','val'=>'100����'),
                array('key'=>'29','val'=>'100-300'),
                array('key'=>'30','val'=>'300-500'),
                array('key'=>'31','val'=>'500-1000'),
                array('key'=>'32','val'=>'1000����'),
            ),
          ),
        array(
           'text'=>'�̼�����',
           'name'=>'basic_type',
           'data_type'=>2,
           'select_type'=>1,
           'data'=>array(
               array('key'=>'person','val'=>'����'),
               array('key'=>'company','val'=>'��ҵ'),
           ),
        ),
     );
     
     //����Լ��Ȥ��
     $property_for_search_ary[43] = array(
         array(
            'text'=>'����',
            'name'=>'detail[278]',
            'data_type'=>1,
            'select_type'=>1, 
            'function_param'=>array('type_id'=>43,'parent_id'=>278,'is_have_son'=>true),
          ),
//         array(
//            'text'=>'�۸�',
//            'name'=>'prices_list', 
//            'data_type'=>2,
//            'select_type'=>1, 
//            'data'=>array(
//                array('key'=>'33','val'=>'100Ԫ����'),
//                array('key'=>'34','val'=>'100-300Ԫ'),
//                array('key'=>'35','val'=>'300-500Ԫ'),
//                array('key'=>'36','val'=>'500-1000Ԫ'),
//                array('key'=>'37','val'=>'1000����'),
//            ),
//          ),
         array(
            'text'=>'�̼�����',
            'name'=>'basic_type',
            'data_type'=>2,
            'select_type'=>1,
            'data'=>array(
                array('key'=>'person','val'=>'����'),
                array('key'=>'company','val'=>'��ҵ'),
            ),
         ),
         
     );
    
     //������Ӱ��ѵ��
     $property_for_search_ary[5] = array(
         array(
            'text'=>'����',
            'name'=>'detail[382]',
            'data_type'=>1,
            'select_type'=>1, 
            'function_param'=>array('type_id'=>5,'parent_id'=>382,'is_have_son'=>true),
          ),
         array(
           'text'=>'��Ǯ',
            'name'=>'prices_list', 
            'data_type'=>2,
            'select_type'=>1, 
            'data'=>array(
                array('key'=>'6','val'=>'100Ԫ����'),
                array('key'=>'7','val'=>'101-1000Ԫ'),
                array('key'=>'8','val'=>'1001-2000Ԫ'),
                array('key'=>'9','val'=>'2001-3000Ԫ'),
                array('key'=>'10','val'=>'3000-4000Ԫ'),
                array('key'=>'11','val'=>'4000����'),
            ),
         ),
         array(
            'text'=>'�̼�����',
            'name'=>'basic_type',
            'data_type'=>2,
            'select_type'=>1,
            'data'=>array(
                array('key'=>'person','val'=>'����'),
                array('key'=>'company','val'=>'��ҵ'),
            ),
         ),
     );
     
    //���ڻ��
     $property_for_search_ary[42] = array(
         array(
            'text'=>'����',
            'name'=>'detail[270]',
            'data_type'=>1,
            'select_type'=>1, 
            'function_param'=>array('type_id'=>42,'parent_id'=>270,'is_have_son'=>true),
         ),
         array(
            'text'=>'��֯��',
            'name'=>'is_official',
            'data_type'=>2,
            'select_type'=>1,
            'data'=>array(
                array('key'=>'1','val'=>'�ٷ�'),
                array('key'=>'0','val'=>'�ǹٷ�'),
            ),
         ),
         array(
           'text'=>'ʱ��',
            'name'=>'front_time', 
            'data_type'=>2,
            'select_type'=>1, 
            'data'=>array(
                array('key'=>'today','val'=>'����'),
                array('key'=>'tomorrow','val'=>'����'),
                array('key'=>'weekend','val'=>'��ĩ'),
                array('key'=>'self','val'=>'�Զ���'),
            ),
         ),
         array(
           'text'=>'��Ǯ',
            'name'=>'prices_list', 
            'data_type'=>1,
            'select_type'=>1, 
            'data'=>array(
                array('key'=>'38','val'=>'100Ԫ����'),
                array('key'=>'39','val'=>'100-200Ԫ'),
                array('key'=>'40','val'=>'200-400Ԫ'),
                array('key'=>'41','val'=>'400-800Ԫ'),
                array('key'=>'42','val'=>'800-1500Ԫ'),
                array('key'=>'43','val'=>'1500Ԫ����'),
            ),
         ),
         array(
            'text'=>'�̼�����',
            'name'=>'basic_type',
            'data_type'=>2,
            'select_type'=>1,
            'data'=>array(
                array('key'=>'person','val'=>'����'),
                array('key'=>'company','val'=>'��ҵ'),
            ),
         ),
         
     );
     
     //ռ������ʦ
     $property_for_search_ary[44] = array(
         array(
            'text'=>'����',
            'name'=>'detail[597]',
            'data_type'=>1,
            'select_type'=>1, 
            'function_param'=>array('type_id'=>44,'parent_id'=>597,'is_have_son'=>true),
         ),
         array(
            'text'=>'�̼�����',
            'name'=>'basic_type',
            'data_type'=>2,
            'select_type'=>1,
            'data'=>array(
                array('key'=>'person','val'=>'����'),
                array('key'=>'company','val'=>'��ҵ'),
            ),
         ),
     );
     
     //�赸/����/�˶�
     $property_for_search_ary[45] = array(
         array(
            'text'=>'����',
            'name'=>'detail[601]',
            'data_type'=>1,
            'select_type'=>1, 
            'function_param'=>array('type_id'=>45,'parent_id'=>601,'is_have_son'=>true),
         ),
         array(
            'text'=>'�̼�����',
            'name'=>'basic_type',
            'data_type'=>2,
            'select_type'=>1,
            'data'=>array(
                array('key'=>'person','val'=>'����'),
                array('key'=>'company','val'=>'��ҵ'),
            ),
         ),
     );
     
     //��ѯ����
     $property_for_search_ary[47] = array(
         array(
            'text'=>'����',
            'name'=>'detail[581]',
            'data_type'=>1,
            'select_type'=>1, 
            'function_param'=>array('type_id'=>47,'parent_id'=>581,'is_have_son'=>true),
         ),
         array(
            'text'=>'�̼�����',
            'name'=>'basic_type',
            'data_type'=>2,
            'select_type'=>1,
            'data'=>array(
                array('key'=>'person','val'=>'����'),
                array('key'=>'company','val'=>'��ҵ'),
            ),
         ),
     );
     
     //�ֹ�����
     $property_for_search_ary[49] = array(
         array(
            'text'=>'����',
            'name'=>'detail[567]',
            'data_type'=>1,
            'select_type'=>1, 
            'function_param'=>array('type_id'=>49,'parent_id'=>567,'is_have_son'=>true),
         ),
         array(
            'text'=>'�̼�����',
            'name'=>'basic_type',
            'data_type'=>2,
            'select_type'=>1,
            'data'=>array(
                array('key'=>'person','val'=>'����'),
                array('key'=>'company','val'=>'��ҵ'),
            ),
         ),
     );
     
     //��ʳDIY��
     $property_for_search_ary[52] = array(
         array(
            'text'=>'����',
            'name'=>'detail[612]',
            'data_type'=>1,
            'select_type'=>1, 
            'function_param'=>array('type_id'=>52,'parent_id'=>612,'is_have_son'=>true),
         ),
         array(
            'text'=>'�̼�����',
            'name'=>'basic_type',
            'data_type'=>2,
            'select_type'=>1,
            'data'=>array(
                array('key'=>'person','val'=>'����'),
                array('key'=>'company','val'=>'��ҵ'),
            ),
         ),
     );
     
     return $property_for_search_ary;
    
?>




