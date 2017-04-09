<?php

class my_test_class extends POCO_TDG
{
	/**
	 * ¹¹Ôìº¯Êı
	 */
	public function __construct()
	{
		$this->setServerId(101);
		$this->setDBName("zixun_db");
        $this->setTableName('zixun_log_tbl');
	}


    public function get_log_list($b_select_count = false, $where_str = '',  $limit = '0,10', $order_by = 'log_id DESC', $fields = '*')
    {

        if ($b_select_count == true)
        {
            $ret = $this->findCount($where_str);
        }
        else
        {
            $ret = $this->findAll($where_str, $limit, $order_by, $fields);
        }
        return $ret;
    }
}
