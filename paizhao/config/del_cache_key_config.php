<?php
//type_id������
$type_id_ary = pai_mall_load_config('certificate_service_type_id');
return array(
			 array(
                 'val'=>'G_YUEUS_MALL_GET_FIRST_CACHE_',
                 'text'=>'��̨������������',
                 'params'=>array(),
             ),
             array(
                 'val'=>'G_YUEUS_MALL_GOODS_TYPE_ATT_SEARCH_TYPE_',
                 'text'=>'ǰ̨������������',
                 'params'=>array('class'=>'pai_mall_goods_type_attribute_class','method'=>'del_property_for_search_get_data'), 
             ),
			 array(
                 'val'=>'G_YUEUS_MALL_SELLER_',
                 'text'=>'�̼���Ϣ����',
                 'params'=>array('class'=>'pai_mall_seller_class','method'=>'del_seller_cache_for_all'), 
             ),
             array(
                 'val'=>'G_YUEUS_MALL_GOODS_ACL_TYPE_INFO_',
                 'text'=>'��ɫȨ�޻���',
                 'params'=>array('class'=>'pai_mall_admin_type_class','method'=>'del_all_admin_type_cache'), //���������������ɾ������ķ���
             ),
             array(
                 'val'=>'G_YUEUS_MALL_GOODS_ACL',
                 'text'=>'��̨Ȩ�޲˵�����',
                 'params'=>array('class'=>'pai_mall_admin_acl_class','method'=>'del_md5_child_cache'), //���������������ɾ������ķ���
             ),
             array(
                 'val'=>'G_YUEUS_MALL_GOODS_ACL_USER_',
                 'text'=>'���к�̨��ԱȨ�޻���',
                 'params'=>array('class'=>'pai_mall_admin_user_class','method'=>'del_all_user_cache'), //���������������ɾ������ķ���
             ),
             array(
                 'val'=>'G_YUEUS_MALL_CMS_NEW_',
                 'text'=>'cms����Ļ���',
                 'params'=>array('class'=>'pai_mall_cms_news_class','method'=>'del_all_cms_cache'), //���������������ɾ������ķ���
             ),
             array(
                 'val'=>'G_YUEUS_MALL_CMS_TYPE',
                 'text'=>'cms����Ļ���',
                 'params'=>array('class'=>'pai_mall_cms_type_class','method'=>'del_first_level_type_id_cache'), //���������������ɾ������ķ���
             ),
             array(
                 'val'=>'G_YUEUS_MALL_PROFESSION',
                 'text'=>'ְҵ��ػ���',
                 'params'=>array('class'=>'pai_mall_profession_class','method'=>'del_all_cache_for_profession'), //���������������ɾ������ķ���
             ),
             array(
                 'val'=>'G_YUEUS_MALL_ADMIN_GROUP_',
                 'text'=>'��������ػ���',
                 'params'=>array('class'=>'pai_mall_admin_group_class','method'=>'del_all_cache'), //���������������ɾ������ķ���
             ),
             
);
?>