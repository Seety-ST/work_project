<?php
/**
* @Desc:     摄影师类
* @User:     wlq<wulq@yueus.com>
* @Date:     2016年10月18日
* @Time:     下午6:30:23
* @Version:  
*/
class pai_paizhao_photographers_class extends POCO_TDG
{

    private $goods_type_config;
    private $page_url_config;
    private $consults_limit = 1000;
    private $consults_replace = '999+';
    public function __construct()
    {
        $this->goods_type_config = pai_mall_paizhao_load_config('paizhao_goods_type');
        $this->page_url_config = pai_mall_paizhao_load_config('page_url');
        $this->setServerId(101);
        $this->setDBName('mall_paizhao_db');
        include('/disk/data/htdocs232/poco/paizhao/include/paizhao.fun.php');
        //$this->query('SET NAMES UTF8');
    }
    
    private function set_mall_photographers_tbl()
    {
        $this->setTableName('mall_photographers_tbl');
    }
	
    private function set_mall_log_tbl()
    {
        $this->setTableName('mall_log_tbl');
    }
    
    private function set_mall_goods_tbl()
    {
        $this->setTableName('mall_goods_tbl');
    }
    
    /**
     * 获取商家信息
     * @param array $id
     */
    public function get_photographers_info($id)
    {
		$return = array(
		                'result'=>-1,
						'message'=>'没有该商家信息',
						);
		$id = (int)$id;
		if(!$id)
		{
			return $return;
		}
        //$where_str = "user_id={$id} AND status=1";
        $where_str = "user_id={$id} AND status=1 AND is_black=0";
		$this->set_mall_photographers_tbl();
		$info = $this->find($where_str);
        if(!$info)
		{
			return $return;
		}
		//$detail = $this->array_iconv(unserialize($info['detail']));
		$detail = $this->array_iconv(unserialize(str_replace(array('\"',"\'"),array('"',"'"),$info['detail'])));
		$detail['goods_num'] = $info['goods_num'];
		$detail['consults'] = ($info['consult_num'] + $info['add_consult_num'])>=$this->consults_limit ? $this->consults_replace : ($info['consult_num'] + $info['add_consult_num']);
		$detail['avatar'] = $info['avatar'];
		$detail['cover'] = $info['cover'];
		$detail['location_name'] = get_location_name_by_location_id($info['location_id']);
		//$detail['location_name'] = $info['location_id'];
		$type_ids = $info['type_ids'];
		$type_info = array();
		if ($type_ids)
		{
		    $type_info[0] = array('type_id'=>0, 'name'=>'所有分类');
		    $type_ids = unserialize($type_ids);
		    foreach ($type_ids as $k=>$v)
		    {
		        $type_info[] = array('type_id'=>$v, 'name'=>$this->goods_type_config[$v]['name']);
		    }
		    
		}
		$detail['type_ids'] = $type_info;
		$return = array(
		                'result'=>1,
						'message'=>'商家信息',
						//'data'=>unserialize($info['detail']),
						'un_data'=>$detail,
						);
		return $return;
    }
	
    /**
     * 转换摄影师数据,并入库
     * @param array $photographers_info
     */
    public function transform_photographers_info($seller_id_str)
    {
        $seller_id_arr = unserialize($seller_id_str);
        $obj = POCO::singleton('pai_mall_seller_class');
        $photographers_info = $obj->get_seller_info_by_sql($seller_id_arr['seller_id']);
		if(!$photographers_info)
		{
			$this->set_mall_log_tbl();
			$this->insert(array('type'=>'seller','detail'=>$seller_id_arr['seller_id'].'no_data', 'create_time'=>time()));
			return true;
		}
        $data = $this->handle_data($photographers_info['seller_data']);
        if ($data === false || !$photographers_info['seller_data'])
        {
            return true;
        }
        if ($data)
        {
            $this->set_mall_photographers_tbl();
            //$sql = "INSERT INTO {$this->_db_name}.{$this->_tbl_name}
            $sql = "INSERT INTO {$this->_db_name}.`mall_photographers_tbl`
                        (`seller_id`,`user_id`,`photographers_type`,`cover`,`seller_name`,`avatar`,
                        `location_id`,`status`,`is_black`,`detail`,`create_time`,`update_time`)
                    VALUES
                        ({$data['seller_id']},{$data['user_id']},{$data['photographers_type']},'{$data['cover']}',
                        '{$data['seller_name']}','{$data['avatar']}','{$data['location_id']}',{$data['status']},
                        {$data['is_black']},'{$data['detail']}',{$data['create_time']},{$data['update_time']})
                    ON DUPLICATE KEY UPDATE
                        `photographers_type`= VALUES(photographers_type),`cover`= VALUES(cover),`seller_name`= VALUES(seller_name),
                        `avatar`= VALUES(avatar),`location_id`= VALUES(location_id),`status`= VALUES(status),
                        `is_black`= VALUES(is_black),`detail`= VALUES(detail),`update_time`= VALUES(update_time)";
			if($data['seller_id']==1)
			{
				$this->set_mall_log_tbl();
				$this->insert(array('type'=>'seller','detail'=>'sql=>'.$sql, 'create_time'=>time()));
			}
			$this->query($sql);
            return true;
        }
		$this->insert(array('type'=>'seller','detail'=>'error_'.$seller_id_arr['seller_id'], 'create_time'=>time()));
        return true;
    }

    /**
     * 对数据进行处理,增加type_id和style_id等
     * @param array $data
     * @return array|boolean
     */
    private function handle_data($data)
    {
        if (!$data['seller_id'] || !$data['user_id']) return false;
        $this->set_mall_log_tbl();
        $this->insert(array('type'=>'seller','detail'=>$data['user_id'], 'create_time'=>time()));
        $time = time();
        $photographers = array(
            'seller_id' => (int)$data['seller_id'],
            'user_id' => (int)$data['user_id'],
            'photographers_type' => $data['basic_type'] == 'person' ? 1 : 2,
            'cover' => mall_simple_filter($data['profile'][0]['cover']),
            'seller_name' => mall_simple_filter($data['name']),
            'avatar' => mall_simple_filter($data['profile'][0]['avatar']),
            'location_id' => mall_simple_filter($data['profile'][0]['location_id']),
            //'goods_num' => $data['goods_num'],
            'status' => (int)$data['status'],
            'is_black' => (int)$data['is_black'],
            'detail' => str_replace(array('"',"'"),array('\"',"\'"),serialize($data)),
            'create_time' => $time,
            'update_time' => $time,
        );
        return $photographers;
    }

    private function get_user_basic_type()
    {
        $user_basic_type = POCO::getCache('paizhao_user_basic_type');
        if (!$user_basic_type)
        {
            $sql = "SELECT `user_id`, `basic_type` FROM mall_db.mall_seller_tbl GROUP BY `user_id`";
            $info = $this->query($sql);
            foreach ($info as $k=>$v)
            {
                $user_basic_type[$v['user_id']] = $v['basic_type'];
            }
            POCO::setCache('paizhao_user_basic_type', $user_basic_type);
        }
        return $user_basic_type;
    }
    
    private function array_iconv($data, $in_charset='gbk', $out_charset='utf-8')
    {
        if(is_array($data))
        {
            foreach($data as $k => $v)
            {
                $data[$k] = $this->array_iconv($v);
            }
            return $data;
        }
        else
        {
            if(is_string($data))
            {
                return mb_convert_encoding($data, $out_charset, $in_charset);
            }
            else
            {
                return $data;
            }
        }
    }
    
    /**
     * 获取摄影师列表
     * @param array $condition
     * @param string $limit
     */
    public function get_photographers_list($is_count = false, $condition = array('kw'=>'','sr'=>'','pt'=>0,'l'=>0), $limit = '0,40', $type = 'PC')
    {
        $where = 'status=1 AND is_black=0';
        $key_word = $condition['kw'];
        if ($key_word)
        {
            $key_word = mall_simple_filter($this->array_iconv($key_word, 'utf-8', 'gbk'));
            $where .= " AND seller_name LIKE '%{$key_word}%'";
        }
        $sort = $condition['sr'];
        if ($sort == 1)
        {
            $sort_filed = 'consult_num DESC';
        }
        else
        {
            $sort_filed = '((consult_num*0.5)+(pv*0.05)+(uv*0.25)+(goods_num*0.2)) DESC';
        }
        $photographers_type = $condition['pt'];
        if ($photographers_type == 1 || $photographers_type == 2)
        {
            $where .= ' AND photographers_type=' . (int)$photographers_type;
        }
        $location_id = (int)$condition['l'];
        if ($location_id)
        {
            $where .= " AND location_id LIKE '{$location_id}%'";
        }
        $this->set_mall_photographers_tbl();
        if ($is_count)
        {
            return $this->findCount($where);
        }
        $limit = preg_match("/^\d+,\d+$/i", $limit) ? $limit : '0,40';
        $photographers_list = $this->array_iconv($this->findAll($where, $limit, $sort_filed, 'seller_id,user_id,cover,avatar,goods_num,consult_num,add_consult_num,seller_name'));
        $user_ids = array();
        foreach ($photographers_list as $v)
        {
            $user_ids[] = (int)$v['user_id'];
        }
        $user_ids = $user_ids ? implode(',', $user_ids) : 0;
        if ($type == 'WAP' && $user_ids)
        {
            $user_goods_info = $this->get_user_goods_info($user_ids);
        }
        $sql = "SELECT
                    user_id, type_id, MAX(type_count) 
                FROM 
                    (SELECT 
                        user_id,type_id,count(type_id) as type_count 
                    FROM 
                        {$this->_db_name}.`mall_goods_tbl` 
                    WHERE
                        user_id IN ({$user_ids})
                    GROUP BY 
                        user_id, type_id) as tmp 
                GROUP BY 
                    user_id";
        $goods_info = $this->query($sql);
        $goods_at = array();
        foreach ($goods_info as $v)
        {
            $goods_at[$v['user_id']] = $v['type_id'];
        }
        foreach ($photographers_list as $k=>$v)
        {
            $photographers_list[$k]['consults'] = ($v['consult_num'] + $v['add_consult_num'])>=$this->consults_limit ? $this->consults_replace : ($v['consult_num'] + $v['add_consult_num']);
            $photographers_list[$k]['goods_at'] = $this->goods_type_config[$goods_at[$v['user_id']]]['name'];
            $photographers_list[$k]['link_url'] = $this->page_url_config['photographer_detail'] . "?user_id={$v['user_id']}";
            $photographers_list[$k]['goods_info'] = $user_goods_info[$v['user_id']] ? $user_goods_info[$v['user_id']] : array();
        }
        return $photographers_list;
    }
    
    /**
     * 获取摄影师总数
     * @param int $photographer_type
     */
    public function get_photographers_list_count($where = '1')
    {
        $this->set_mall_photographers_tbl();
        return $this->findCount($where);
    }
    
    public function admin_get_photographers_list($where = '1', $limit = '0,40', $sort = 'consult DESC', $fields = '*')
    {
        $this->set_mall_photographers_tbl();
        return $this->findAll($where, $limit, $sort, $fields);
    }
    /**
     * 更新商品、摄影师的pv uv
     */
    public function update_p_u_v()
    {
        $obj = POCO::singleton('pai_yue_pai_class');
        $info = $obj->get_pai_info();
        $goods_info = $info['goods'];
        $user_info = $info['user'];
        $time = time();
        //将pv uv置零并更新
        $this->set_mall_goods_tbl();
        $this->update(array('uv'=>0, 'pv'=>0, 'update_time'=>$time), ' 1 ');
        if ($goods_info)
        {
            foreach ($goods_info as $v)
            {
                $this->update(array('uv'=>(int)$v['uv'], 'pv'=>(int)$v['pv']), 'goods_id='.(int)$v['goods_id']);
            }
        }
        //将pv uv置零并更新
        $this->set_mall_photographers_tbl();
        $this->update(array('uv'=>0, 'pv'=>0, 'update_time'=>$time), ' 1 ');
        if ($user_info)
        {
            foreach ($user_info as $v)
            {
                $this->update(array('uv'=>(int)$v['uv'], 'pv'=>(int)$v['pv']), 'user_id='.(int)$v['user_id']);
            }
        }
        return $info;
    }
    
    /**
     * 获取摄影师前3商品,根据咨询数和创建时间排序
     * @param number $user_ids
     */
    private function get_user_goods_info($user_ids = 0)
    {
        $this->set_mall_goods_tbl();
        $goods_info = $this->findAll("is_show=1 AND is_black=0 AND user_id IN ({$user_ids})", '', 'consult_num DESC, create_time DESC', 'goods_id,user_id,images,prices');
        $user_goods_info = array();
        foreach ($goods_info as $v)
        {
            if (count($user_goods_info[$v['user_id']]) < 3)
            {
                $user_goods_info[$v['user_id']][] = array('goods_id'=>$v['goods_id'],'images'=>$v['images'], 'prices'=>$v['prices'], 'link_url'=>$this->page_url_config['goods_detail'] . '?goods_id=' . $v['goods_id']);
            }
        }
        return $user_goods_info;
    }
}