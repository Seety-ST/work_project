<?php
return array(
            array(
                    'name' => '��ѯ����',
                    'acl_root_id'=>1,
                    'list' => array(
                                    array(
                                        'name' => '��ѯ�б�',
                                        'acl_root_id'=>537,
                                        'url' => 'javascript:;',
                                        'file' => array($_SERVER['DOCUMENT_ROOT'].'/admin/consult.php'),
                                        'children_list' => array(
                                            array(
                                                'name' => '��ѯ����<font id="new_not_do"></font>',
                                                'acl_root_id'=>537,
                                                'url' => '/admin/consult.php?action=consult_list',
                                                'file'=> $_SERVER['DOCUMENT_ROOT'].'/admin/consult.php',
                                                'action'=> array('consult_list'),
                                            ),
                                            array(
                                                'name' => '��ѯ��ťת������<font id="new_not_do"></font>',
                                                'acl_root_id'=>537,
                                                'url' => '/admin/consult.php?action=consult_button&consult_type=goods',
                                                'file'=> $_SERVER['DOCUMENT_ROOT'].'/admin/consult.php',
                                                'action'=> array('consult_button'),
                                            ),
                                            array(
                                                'name' => '��Ʒ��ѯ������<font id="new_not_do"></font>',
                                                'acl_root_id'=>537,
                                                'url' => '/admin/goods.php?action=goods_consult_edit',
                                                'file'=> $_SERVER['DOCUMENT_ROOT'].'/admin/goods.php',
                                                'action'=> array('goods_consult_edit'),
                                            ),
                                            array(
                                                'name' => '��Ӱʦ�б�<font id="new_not_do"></font>',
                                                'acl_root_id'=>537,
                                                'url' => '/admin/photographers.php?action=photographers_list',
                                                'file'=> $_SERVER['DOCUMENT_ROOT'].'/admin/photographers.php',
                                                'action'=> array('photographers_list'),
                                            ),
                                            array(
                                                'name' => '�����ҳ����(�����˰񵥺���Ҫ����)<font id="new_not_do"></font>',
                                                'acl_root_id'=>537,
                                                'url' => '/admin/cache.php?action=clear',
                                                'file'=> $_SERVER['DOCUMENT_ROOT'].'/admin/cache.php',
                                                'action'=> array('clear'),
                                            ),
                                        ),
                                    ),
                            ),
                    'file' => array($_SERVER['DOCUMENT_ROOT'].'/admin/consult.php'),
                ),
);
?>