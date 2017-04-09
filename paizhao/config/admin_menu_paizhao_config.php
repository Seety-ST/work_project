<?php
return array(
            array(
                    'name' => '咨询管理',
                    'acl_root_id'=>1,
                    'list' => array(
                                    array(
                                        'name' => '咨询列表',
                                        'acl_root_id'=>537,
                                        'url' => 'javascript:;',
                                        'file' => array($_SERVER['DOCUMENT_ROOT'].'/admin/consult.php'),
                                        'children_list' => array(
                                            array(
                                                'name' => '咨询跟进<font id="new_not_do"></font>',
                                                'acl_root_id'=>537,
                                                'url' => '/admin/consult.php?action=consult_list',
                                                'file'=> $_SERVER['DOCUMENT_ROOT'].'/admin/consult.php',
                                                'action'=> array('consult_list'),
                                            ),
                                            array(
                                                'name' => '咨询按钮转化报表<font id="new_not_do"></font>',
                                                'acl_root_id'=>537,
                                                'url' => '/admin/consult.php?action=consult_button&consult_type=goods',
                                                'file'=> $_SERVER['DOCUMENT_ROOT'].'/admin/consult.php',
                                                'action'=> array('consult_button'),
                                            ),
                                            array(
                                                'name' => '商品咨询数管理<font id="new_not_do"></font>',
                                                'acl_root_id'=>537,
                                                'url' => '/admin/goods.php?action=goods_consult_edit',
                                                'file'=> $_SERVER['DOCUMENT_ROOT'].'/admin/goods.php',
                                                'action'=> array('goods_consult_edit'),
                                            ),
                                            array(
                                                'name' => '摄影师列表<font id="new_not_do"></font>',
                                                'acl_root_id'=>537,
                                                'url' => '/admin/photographers.php?action=photographers_list',
                                                'file'=> $_SERVER['DOCUMENT_ROOT'].'/admin/photographers.php',
                                                'action'=> array('photographers_list'),
                                            ),
                                            array(
                                                'name' => '清楚首页缓存(更新了榜单后需要清理)<font id="new_not_do"></font>',
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