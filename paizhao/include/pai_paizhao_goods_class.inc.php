<?php
/**
* @Desc:     paizhao 商品类 
* @User:     wlq<wulq@yueus.com>
* @Date:     2016年10月8日
* @Time:     上午8:57:48
* @Version:  
*/
class pai_paizhao_goods_class extends POCO_TDG
{
    private $goods_type_config;
    private $goods_style_config;
    private $goods_location_config;
    private $goods_fields_num = 10;
    private $consults_limit = 1000;
    private $consults_replace = '999+';
    private $page_url_config;
    private $data_num_config = array(
        'hot_goods' => 19,
        'organization' => 3,
        'photographers' => 4,
        'banner' => 5,
        'custom_recommend' => 3,
        'footer' => 3,
        'package_type' => 7,
        'styles' => 4,
        'wap_banner' => 5,
    );

    public function __construct()
    {
        $this->goods_style_config = pai_mall_paizhao_load_config('paizhao_goods_style');
        $this->goods_type_config = pai_mall_paizhao_load_config('paizhao_goods_type');
        $this->goods_location_config = pai_mall_paizhao_load_config('paizhao_goods_location');
        $this->page_url_config = pai_mall_paizhao_load_config('page_url');
        $this->setServerId(101);
        $this->setDBName('mall_paizhao_db');
		include('/disk/data/htdocs232/poco/paizhao/include/paizhao.fun.php');
//         $this->query('SET NAMES UTF8');
    }
    
    private function set_mall_consult_status_tbl()
    {
        $this->setTableName('mall_consult_status_tbl');
    }
    
    private function set_mall_consult_tbl()
    {
        $this->setTableName('mall_consult_tbl');
    }
    
    private function set_mall_goods_tbl()
    {
        $this->setTableName('mall_goods_tbl');
    }
    
    private function set_mall_advertises_img_tbl()
    {
        $this->setTableName('mall_advertises_img_tbl');
    }
    
    private function set_mall_recommend_photographers_tbl()
    {
        $this->setTableName('mall_recommend_photographers_tbl');
    }
    
    private function set_mall_hot_goods_tbl()
    {
        $this->setTableName('mall_hot_goods_tbl');
    }
    
    private function set_mall_goods_style_tbl()
    {
        $this->setTableName('mall_goods_style_tbl');
    }
    
    private function set_mall_photographers_tbl()
    {
        $this->setTableName('mall_photographers_tbl');
    }
    
    private function set_mall_log_tbl()
    {
        $this->setTableName('mall_log_tbl');
    }
    
    /**
     * 转换商品数据,并入库
     * @param array $goods_info
     */
    public function transform_goods_info($goods_id_str)
    {
		//return false;
		$goods_id_arr = unserialize($goods_id_str);
		$obj = POCO::singleton('pai_mall_goods_class');
		$goods_info = $obj->get_goods_info_by_sql($goods_id_arr['goods_id']);
        $data = $this->handle_data($goods_info);
        if ($data === false || !$goods_info)
        {
            return true;
        }
        if ($data)
        {
            $this->set_mall_goods_tbl(); 
            $sql = "INSERT INTO {$this->_db_name}.mall_goods_tbl
                       (`goods_id`,`seller_id`,`user_id`,`is_show`,`is_black`,`photographers_type`,`type_id`,
                       `type_name`,`content`,`titles`,`images`,`prices`,`location_id`,`detail`,`create_time`,`update_time`)
                   VALUES
                       ({$data['goods']['goods_id']},{$data['goods']['seller_id']},{$data['goods']['user_id']},
                       {$data['goods']['is_show']},{$data['goods']['is_black']},{$data['goods']['photographers_type']},
                       {$data['goods']['type_id']},'{$data['goods']['type_name']}','{$data['goods']['content']}',
                       '{$data['goods']['titles']}','{$data['goods']['images']}','{$data['goods']['prices']}',
                       '{$data['goods']['location_id']}','{$data['goods']['detail']}',{$data['goods']['create_time']},{$data['goods']['update_time']})
                   ON DUPLICATE KEY UPDATE 
                       `is_show`= VALUES(is_show),`is_black`= VALUES(is_black),`photographers_type`= VALUES(photographers_type),
                       `type_id`= VALUES(type_id),`type_name`= VALUES(type_name),`content`= VALUES(content),
                       `titles`= VALUES(titles),`images`= VALUES(images),`prices`= VALUES(prices),
                       `location_id`= VALUES(location_id),`detail`= VALUES(detail),`update_time`= VALUES(update_time)";
			$this->query($sql);
            if ($data['style'])
            {
                $this->set_mall_goods_style_tbl();
                $goods_id = (int)$data['style']['goods_id'];
                $this->delete("goods_id={$goods_id}");
                foreach ($data['style']['style_ids'] as $v)
                {
                    $style_data = array(
                        'goods_id' => $goods_id,
                        'style_id' => (int)$v,
                        'style_name' => iconv('utf-8', 'gbk', $this->goods_style_config[$v]['name']),
                    );
                    $this->insert($style_data);                    
                }
            }
            $this->init_goods_num($data['goods']['user_id']);
            return true;
        }
        $this->set_mall_log_tbl();
        $this->insert(array('type'=>'goods','detail'=>'error_'.$goods_id_arr['goods_id'], 'create_time'=>time()));
        return true;
    }
    
    public function update_consult()
    {
        
    }
    
    /**
     * 进行数据具体转换
     * @param array $data
     * @return array|boolean
     */
    private function transform_data($data)
    {
        $result = array();
        if ( is_array($data) && array_key_exists(1, $data) )
        {
            foreach ($data as $k=>$v)
            {
                $v = $this->check_data($this->handle_data($v['goods_data']));
                if (!$v) continue;
                $result[] = $v;
            }
        }
        else if ( is_array($data) )
        {
            $data = $this->check_data($this->handle_data($data['goods_data']));
            $data ? $result[] = $data : $result = false;
        }
        return $result ? $result : false;
    }

    /**
     * 对数据进行处理,增加type_id和style_id等
     * @param array $data
     * @return array|boolean
     */
    private function handle_data($data)
    {
        $this->set_mall_log_tbl();
        $this->insert(array('type'=>'goods','detail'=>$data['goods_data']['goods_id'], 'create_time'=>time()));
        $type_config = $this->goods_type_config;
        $style_config = $this->goods_style_config;
        //转换新老类型id
        $old_type_id = $data['goods_data']['att_type_id'];
        $new_type_id = 0;
        foreach ($type_config as $k=>$v)
        {
            if (in_array($old_type_id, $v['include']))
            {
                $new_type_id = $k;
                break;
            }
        }
        if (!$new_type_id)
        {
            return false;
        }
        //转换新老风格id
        $new_style_ids = array();
        foreach ($data['goods_data']['detail'] as $v)
        {
            if ($v['type_id'] == 289)
            {
                foreach ($style_config as $m=>$n)
                {
                    if (in_array($v['data_type_attribute_id'], $n['include']) && !in_array($m, $new_style_ids))
                    {
                        $new_style_ids[] = $m;
                        break;
                    }
                }
            }
        }
        $user_basic_type = $this->get_user_basic_type();
        $time = time();
		//print_r($data);
        $goods_data = array(
            'goods_id' => (int)$data['goods_data']['goods_id'],
            'seller_id' => (int)$data['goods_data']['seller_id'],
            'user_id' => (int)$data['goods_data']['user_id'],
            'is_show' => (int)$data['goods_data']['is_show'],
            'is_black' => (int)$data['goods_data']['is_black'],
            'photographers_type' => $user_basic_type[$data['goods_data']['user_id']] == 'person' ? 1 : 2,
            'type_id' => (int)$new_type_id,
            'type_name' => mall_simple_filter(iconv('utf-8', 'gbk', $type_config[$new_type_id]['name'])),
            'content' => mall_simple_filter(strip_tags($data['goods_data']['content'])),
            'titles' => mall_simple_filter($data['goods_data']['titles']),
            'images' => mall_simple_filter($data['goods_data']['images']),
            'prices' => (float)$data['goods_data']['prices'],
            'location_id' => mall_simple_filter($data['goods_data']['location_id']),
            'detail' => str_replace(array('"',"'"),array('\"',"\'"),serialize($data)),
            //'detail' => serialize($data),
            'create_time' => $time,
            'update_time' => $time,
        );
        $result['goods'] = $goods_data;
        $result['style'] = $new_style_ids ? array('goods_id'=>$data['goods_data']['goods_id'], 'style_ids'=>$new_style_ids) : false;
        return $result;
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
    /**
     * 获取新的type_id或style_id
     * @param int $old_id
     * @param string $type
     * @return int|boolean
     */
    private function get_new_id($old_id, $type='type')
    {
        $data = $type == 'type' ? $this->goods_type_config : $this->goods_style_config;
        foreach ($data as $k=>$v)
        {
            if (in_array($old_id, $v['include'])) return $k;
        }
        return false;
    }

    /**
     * 校验goods字段信息
     * @param array $data
     * @return array|boolean
     */
    private function check_data($data)
    {
        if (!$data || (count($data) != $this->goods_fields_num) || !$data['goods_id'] || !$data['seller_id'] || !$data['style_id'] ||  
            !$data['photographers_type'] || !$data['titles'] || !$data['prices'] || !$data['location_id'] || !$data['type_id'] || !$data['detail'])
        {
            return false;
        }
        else
        {
            $time = time();
            return array(
                (int)$data['goods_id'],
                (int)$data['seller_id'],
                (int)$data['photographers_type'],
                (int)$data['type_id'],
                (int)$data['style_id'],
                "'{$data['titles']}'",
                "'{$data['images']}'",
                (float)$data['prices'],
                (int)$data['location_id'],
                "'{$data['detail']}'",
                $time,
                $time,
            );
        }
    }

    /**
     * 生成热门推荐商品
     */
    public function make_hot_goods()
    {
        $this->set_mall_goods_tbl();
        $hot_goods = $this->findAll('', 'LIMIT 0,20', 'consult_num DESC', '`goods_id`,`titles`');
        if ($hot_goods)
        {
            $values = array();
            $time = time();
            foreach ($hot_goods as $k=>$v)
            {
                $values[] = "({$v['goods_id']},'{$v['titles']}',{$time},{$time})";
            }
            $values = implode(',', $values);
            $this->set_mall_hot_goods_tbl();
            $sql = "INSERT INTO {$this->_db_name}.{$this->_tbl_name}(`goods_id`,`titles`,`create_time`,`update_time`) VALUES{$values}";
            $this->query($sql);
            return array('result'=>1,'message'=>'更新成功');
        }
        return array('result'=>-1, 'message'=>'没有热门商品');
    }
    
    public function edit_hot_goods()
    {
        
    }
    
    public function add_ad($data)
    {
        
    }

    public function edit_ad($edit_info)
    {
        
    }
    
    public function add_consult($data)
    {
        $data = array(
            'name' => $data['name'],
            'mobile' => $data['mobile'],
            'photographers' => $data['photographers']
        );
        $result = $this->insert($data);
        return $result ? array('result'=>1, 'message'=>'添加咨询成功') : array('result'=>-1, 'message'=>'添加失败');
    }
    
    public function edit_consult($edit_info)
    {
        
    }
    
    /**
     * 获取商品列表
     * @param string $is_count
     * @param array $condition = array(
     *                              type_id=>类型id
     *                              order=> 1=>正序,2=>倒序,只对价格有用
     *                              sort=> 1=>人气, 2=>最新, 3=>价格
     *                          );
     * @param string $limit
     * @param string $fields
     * @return array
     */
    public function get_goods_list($is_count = false, $condition = array('t'=>0,  'u'=>0, 'sr'=>0, 'o'=>2), $limit='0,40',$fields = '`goods_id`,`titles`,`images`,`consult_num`,`add_consult_num`,`location_id`,`prices`')
    {
        $where = '1';
        if ($condition['u']) $where .= ' AND user_id=' . (int)$condition['u'];
        if ($condition['t']) $where .= ' AND type_id=' . (int)$condition['t'];
        $sort = $condition['sr'];
        $order = $condition['o'] == 1 ?  'ASC' : 'DESC';
        if ($sort == 1 || $sort == 2 || $sort == 3)
        {
            $sort_filed = $sort == '1' ? "(consult_num+add_consult_num) DESC" : ($sort=='2'?'update_time DESC':'prices ' . $order);
        }
        else
        {
            $sort_filed = '((consult_num*0.5)+(pv*0.05)+(uv*0.45)) DESC';
        }
        // return $where . '---' . $sort_filed;
        if ($is_count)
        {
            return $this->get_goods_list_count($where);
        }
        else
        {
            $limit = preg_match("/^\d+,\d+$/i", $limit) ? $limit : '0,40';
            $return = $this->inner_get_goods_list($where, $limit, $sort_filed, $fields, true);
            if ($sort == 3)
            {
                $return['o'] = $condition['o'] == 2 ? 1 : 2;
            }
            return $return;
        }
    }
    /**
     * 获取商品列表
     * @param string $where
     * @param string $limit
     * @param string $order
     * @param string $fields
     */
    public function inner_get_goods_list($where = '1', $limit = '0,40', $order = '`consult_num` DESC, `update_time` DESC', $fields = '`goods_id`,`titles`,`images`,`consult_num`,`add_consult_num`,`location_id`,`prices`', $transform = false)
    {
        $this->set_mall_goods_tbl();
        $where .= ' AND is_show=1 AND is_black=0';
        $goods_list = $this->array_iconv($this->findAll($where, $limit, $order, $fields));
        if ($transform)
        {
            foreach ($goods_list as $k=>$v)
            {
                $goods_list[$k]['location_name'] = get_location_name_by_location_id($v['location_id']);
                $goods_list[$k]['prices'] = '<span class="money_symbol">¥</span>' . "<span class='money_num'>{$v['prices']}</span>";
                $goods_list[$k]['consults'] = (($v['consult_num'] + $v['add_consult_num'])>=$this->consults_limit ? $this->consults_replace : ($v['consult_num'] + $v['add_consult_num'])) . '人咨询';
                $goods_list[$k]['link_url'] = $this->page_url_config['goods_detail'] . '?goods_id=' . (int)$v['goods_id'];
            }
        }
        $result = array(
            'result' => $goods_list ? 1 : -1,
            'data' => $goods_list,
        );
        return $result;
    }
    
    public function admin_get_goods_list($is_count = false, $where = '1', $limit = '0,40', $order = '`consult_num` DESC, `update_time` DESC', $fields = '`goods_id`,`titles`,`images`,`consult_num`,`add_consult_num`,`location_id`,`prices`')
    {
        $this->set_mall_goods_tbl();
        if ($is_count) return $this->findCount($where);
        $goods_list = $this->array_iconv($this->findAll($where, $limit, $order, $fields));
        $result = array(
            'result' => $goods_list ? 1 : -1,
            'data' => $goods_list,
        );
        return $result;
    }
    public function get_goods_list_count($where = '1')
    {
        $where .= ' AND is_show=1 AND is_black=0';
        $this->set_mall_goods_tbl();
        return $this->findCount($where);
    }
    
    /**
     * 获取商品列表
     * @param string $where
     * @param string $style_where
     * @param string $sort
     * @param string $limit
     */
    private function inner_get_goods_list_with_style($where = '1', $style_where = '1', $sort='goods_id DESC', $limit = '0,40')
    {
        $this->set_mall_goods_tbl();
        $style_sql = $style_where != '1' ? "AND goods_id IN(SELECT goods_id FROM {$this->_db_name}.mall_goods_style_tbl WHERE {$style_where} GROUP BY goods_id)" : '';
        $sql = "SELECT
                    seller_id,user_id,goods_id,(consult_num+add_consult_num) as consults,titles,prices,images,location_id
                FROM
                    {$this->_db_name}.{$this->_tbl_name}
                WHERE
                    {$where} {$style_sql} AND is_show=1 AND is_black=0
                ORDER BY
                    {$sort}
                LIMIT
                    {$limit}
                ";
        $goods_list = $this->array_iconv($this->query($sql));
        $user_ids = array();
        foreach ($goods_list as $v)
        {
            $user_ids[] = (int)$v['user_id'];
        }
        $this->set_mall_photographers_tbl();
        $user_names = array();
        $user_ids = $user_ids ? implode(',', $user_ids) : 0;
        $user_list = $this->array_iconv($this->findAll("user_id IN({$user_ids})", null, null, 'avatar,user_id,seller_name'));
        foreach ($user_list as $v)
        {
            $user_names[$v['user_id']] = array('seller_name'=>$v['seller_name'], 'avatar'=>$v['avatar']);
        }
        foreach ($goods_list as $k=>$v)
        {
            $goods_list[$k]['prices'] = '<span class="money_symbol">¥</span>'."<span class='money_num'>{$v['prices']}</span>";
            $goods_list[$k]['seller_name'] = $user_names[$v['user_id']]['seller_name'];
            $goods_list[$k]['avatar'] = $user_names[$v['user_id']]['avatar'];
            $goods_list[$k]['link_url'] = $this->page_url_config['goods_detail'] . "?goods_id={$v['goods_id']}";
            $goods_list[$k]['seller_link_url'] = $this->page_url_config['photographer_detail'] . "?user_id={$v['user_id']}";
            $goods_list[$k]['consults'] = $v['consults']>=$this->consults_limit ? $this->consults_replace : $v['consults'];
        }
        return $goods_list;
    }
    
    public function get_goods_list_with_style($is_count = false, $condition = array('kw'=>'', 'sr'=>0, 'o'=>2, 'l'=>0,'pt'=>0, 't'=>array(), 's'=>array()), $limit = '0,40')
    {
        $where = '1';
        $style_where = '1';
        $key_word = $condition['kw'];
        if ($key_word)
        {
            $key_word = mall_simple_filter($this->array_iconv($key_word, 'utf-8', 'gbk'));
            $where .= " AND (titles LIKE '%{$key_word}%' OR content LIKE '%{$key_word}%')";
        }
        $type_ids = $condition['t'];
        if ($type_ids)
        {
            if (is_array($type_ids))
            {
                foreach ($type_ids as $v)
                {
                    if (!$v) continue;
                    $t_ids[] = (int)$v;
                }
                $type_ids = $t_ids;
                $t_ids = '(' . implode(',', $t_ids) . ')';
            }
            else
            {
                $t_ids = '(' . (int)$type_ids . ')';
                $type_ids = array();
                $type_ids[] = $t_ids;
            }
            if ($type_ids) $where .= " AND type_id IN {$t_ids}";
        }
        $style_ids = $condition['s'];
        if ($style_ids)
        {
            if (is_array($style_ids))
            {
                foreach ($style_ids as $v)
                {
                    if (!$v) continue;
                    $s_ids[] = (int)$v;
                }
                $style_ids = $s_ids;
                $s_ids = '(' . implode(',', $s_ids) . ')';
            }
            else
            {
                $s_ids = '(' . (int)$style_ids . ')';
                $style_ids = array();
                $style_ids[] = $s_ids;
            }
            if ($style_ids) $style_where .= " AND style_id IN {$s_ids}";
        }
        $photographers_type = $condition['pt'];
        if ($photographers_type == 1 || $photographers_type ==2)
        {
            $photographers_type = (int)$photographers_type;
            $where .= " AND photographers_type={$photographers_type}";
        }
        $location_id = (int)$condition['l'];
        if ($location_id)
        {
            $where .= " AND location_id like '{$location_id}%'";
        }
        $sort = $condition['sr'];
        $order = $condition['o'] == 1 ?  'ASC' : 'DESC';
        if ($sort == 1 || $sort == 2 || $sort == 3)
        {
            $sort_filed = $sort == '1' ? "consult_num DESC" : ($sort=='2'?'update_time DESC':'prices ' . $order);
        }
        else
        {
            $sort_filed = '((consult_num*0.5)+(pv*0.05)+(uv*0.45)) DESC';
        }
        if ($is_count)
        {
            return $this->get_goods_list_count_with_style($where, $style_where);
        }
        else
        {
            $limit = preg_match("/^\d+,\d+$/i", $limit) ? $limit : '0,40';
            return $this->inner_get_goods_list_with_style($where, $style_where, $sort_filed, $limit);
        }
    }
    /**
     * 获取总数
     * @param string $where
     * @param string $style_where
     */
    private function get_goods_list_count_with_style($where = '1', $style_where = '1')
    {
        $this->set_mall_goods_tbl();
        $style_sql = $style_where != '1' ? "AND goods_id IN(SELECT goods_id FROM {$this->_db_name}.mall_goods_style_tbl WHERE {$style_where} GROUP BY goods_id)" : '';
        $sql = "SELECT
                    count(goods_id) as count
                FROM
                    {$this->_db_name}.{$this->_tbl_name}
                WHERE
                    {$where} {$style_sql} AND is_show=1 AND is_black=0
                ";
        $result = $this->query($sql);
        return $result[0]['count'];
    }
    
    /**
     * 获取商品详情
     * @param int $goods_id
     */
    public function get_goods_info($goods_id, $get_user = false, $get_like_goods = false, $type = 'PC')
    {
        $return = array(
            'result'=>-1,
            'message'=>'没有商品信息!',
        );
        $where = 'goods_id=' . (int)$goods_id;
        $this->set_mall_goods_tbl();
        $goods = $this->find($where . ' AND is_show=1 AND is_black=0');
        if (!$goods) return $return;
        $this->set_mall_goods_style_tbl();
        $style = $this->findAll($where);
        $style_str = '';
        $style_ids = array();
        foreach ($style as $v)
        {
            $style_str .= $v['style_name'] . ' ';
            $style_ids[] = $v['style_id'];
        }
        $style_ids = array_unique($style_ids);
        $goods_info = $this->array_iconv(unserialize(str_replace(array('\"',"\'"),array('"',"'"),$goods['detail'])));
        //$goods_info = $this->array_iconv(unserialize($goods['detail']));
        $goods_info['goods_data']['prices'] = '<span class="money_symbol">¥</span>'."<span data-role='money_num' class='money_num'>{$goods['prices']}</span>";
        $goods_info['consults'] = ($goods['consult_num'] + $goods['add_consult_num'])>=$this->consults_limit ? $this->consults_replace : ($goods['consult_num'] + $goods['add_consult_num']);
        $goods_info['new_type_id'] = $goods['type_id'];
        $goods_info['new_type_name'] = $this->array_iconv($goods['type_name']);
        $goods_info['style_ids'] = $style_ids;
        $goods_info['style_name'] = $this->array_iconv($style_str);
//         $goods_info['prices_list'] = unserialize($goods_info['prices_list']);
        if ($type == 'PC')
        {
            $goods_info['detail_introduce'] = "<p>套系类型 : {$goods_info['new_type_name']}</p>";
            $goods_info['detail_introduce'] = $style_str ? $goods_info['detail_introduce'] . "<p>风格场景 : {$goods_info['style_name']}</p>" : $goods_info['detail_introduce']; 
            foreach ($goods_info['system_data'] as $v)
            {
                if ( $v['id'] == 516 && $v['value'] )
                {
                    $goods_info['detail_introduce'] .= "<p>拍摄器材 : {$v['value']}</p>";
                    break;
                }
            }
            $goods_info['detail_introduce'] .= '<p>拍摄地点 : ' . ($goods_info['goods_data']['service_detail'][0]['service_data_detail']['message'] ? $goods_info['goods_data']['service_detail'][0]['service_data_detail']['message'] : $goods_info['goods_data']['service_detail'][0]['service_data_detail']['address']). '</p>';
            $goods_info['format_introduce'] = '<p><b>套系规格: </b></p>';
            foreach ($goods_info['goods_prices_list'] as $v)
            {
                $goods_info['format_introduce'] .= "<p>{$v['name']}: <span class='money_symbol'>¥</span><span class='money_num'>{$v['prices']}</span></p><p>{$v['mess']}</p><p></p>";
            }
        }
        else 
        {
            $goods_info['detail_introduce'][] = array('name'=>'套系类型', 'value'=>$goods_info['new_type_name']);
            if ($style_str) $goods_info['detail_introduce'][] = array('name'=>'风格场景', 'value'=>$goods_info['style_name']);
            foreach ($goods_info['system_data'] as $v)
            {
                if ( $v['id'] == 516 && $v['value'] )
                {
                    $goods_info['detail_introduce'][] = array('name'=>'拍摄器材', 'value'=>$v['value']);
                    break;
                }
            }
            $goods_info['detail_introduce'][] = array('name'=>'拍摄地点', 'value'=>($goods_info['goods_data']['service_detail'][0]['service_data_detail']['message'] ? $goods_info['goods_data']['service_detail'][0]['service_data_detail']['message'] : $goods_info['goods_data']['service_detail'][0]['service_data_detail']['address']));
            $tmp = '';
            foreach ($goods_info['goods_prices_list'] as $v)
            {
               $tmp .= "{$v['name']}: <span class='money_symbol'>¥</span><span class='money_num'>{$v['prices']}</span><br/>" . ($v['mess'] ? "{$v['mess']}<br/>" : '');
            }
            $goods_info['format_introduce'][] = array('name'=>'套系规格', 'value'=>$tmp);
        }
        if ($get_user)
        {
            $user_info = $this->get_photographers_info($goods['user_id'], 'avatar,(consult_num+add_consult_num) as consults,seller_name,user_id,seller_id');
            $user_info['link_url'] = $this->page_url_config['photographer_detail'] . '?user_id=' . (int)$user_info['user_id'];
            $user_info['consults'] = $user_info['consults']>=$this->consults_limit ? $this->consults_replace : $user_info['consults'];
            $result = array(
                'user_info' => $user_info,
            );
        }
        if ($get_like_goods)
        {
            $like_goods_info = $this->get_like_goods(array('goods_id'=>(int)$goods_id, 'user_id'=>$goods['user_id'], 'type_id'=>$goods['type_id'], 'style_ids'=>$style_ids));
            if ($like_goods_info)
            {
                foreach ($like_goods_info as $k=>$v)
                {
                    $like_goods_info[$k]['prices'] = '<span class="money_symbol">¥</span>'."<span class='money_num'>{$v['prices']}</span>";
                    $like_goods_info[$k]['consults'] = $v['consults']>=$this->consults_limit ? $this->consults_replace : $v['consults'];
                    $like_goods_info[$k]['location_name'] = get_location_name_by_location_id($v['location_id']);
                }
            }
            $result['like_goods_info'] = $like_goods_info;
            $result['more_url'] = $this->page_url_config['goods_list'];
        }
        $result['goods_info'] = $goods_info;
        $result['result'] = 1;
        return $result;
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
            return mb_convert_encoding($data, $out_charset, $in_charset);
        }
    }
    
    /**
     * 获取首页数据
     * @return array
     */
    public function get_index_data($type='PC')
    {
        $data = get_index_data();
        if ($type =='test') return $data;
        foreach ($data as $k=>$v)
        {
            if (strpos($k, 'organization_') !== false)
            {
                $organization = $this->patch_data($v, $k);
                if ($type == 'WAP')
                {
                    $wap_or = $organization[0];
                    $wap_or['goods_id'] = $organization[1] ? $organization[1]['goods_id'] : 0;
                    $wap_or['img_url'] = $organization[1] ? $organization[1]['img_url'] : '';
//                     $wap_or['link_url'] = $organization[1] ? $organization[1]['link_url'] : '';
                }
                $data['organization'][] = $wap_or ? $wap_or : $organization;
                unset($data[$k]);
            }
            else
            {
                if ($k == 'banner')
                {
                    foreach ($v as $m=>$n)
                    {
                        $v[$m]['img_url'] = paizhao_resize_act_img_url($n['img_url']);
                    }
                }
                $data[$k] = $this->patch_data($v, $k);
                if ($k == 'package_type' && $type == 'WAP')
                {
                    unset($data[$k][0]);
                }
            }
            
        }
        return $data;
    }
    
    /**
     * 对首页数据进行处理,拼凑
     * @param array $data
     * @param string $key
     * @return array
     */
    private function patch_data($data, $key)
    {
        $tmp = array();
        $result = array();
        $place_numbers = array();
        $pattern  = '/http:\/\/((yue)?(seller)?){1}-icon-[de]\.yueus\.com/';
        switch ($key)
        {
            case 'hot_goods':
                $goods_id = array();
                foreach ($data as $k=>$v)
                {
                    $goods_id[] = (int)$v['goods_id'];
                    $tmp[$v['goods_id']] = array(
                        'img_url' => $v['img_url'],
                        'link_url' => $v['link_url'],
                        'title' => $v['title'],
                        'place_number' => $v['place_number'],
                    );
                    if ( count($goods_id) >= $this->data_num_config[$key] ) break;
                }
                $goods_id = $goods_id ? '(' . implode(',', $goods_id) . ')' : '(0)';
                $goods_list = $this->inner_get_goods_list("goods_id IN{$goods_id} AND is_show=1 AND is_black=0", '0,40', '', 'goods_id,images,titles,type_id,type_name,prices,(consult_num+add_consult_num) as consults');
                foreach ($goods_list['data'] as $k=>$v)
                {
                    $result[] = array(
                        'goods_id' => $v['goods_id'],
                        'img_url' => !preg_match($pattern, $tmp[$v['goods_id']]['img_url']) ? $tmp[$v['goods_id']]['img_url'] : $v['images'],
                        'link_url' => $this->page_url_config['goods_detail'] . '?goods_id=' . (int)$v['goods_id'],
                        'title' => $tmp[$v['goods_id']]['title'] ? $tmp[$v['goods_id']]['title'] : $v['titles'],
                        'place_number' => $tmp[$v['goods_id']]['place_number'],
                        'type_id' => $v['type_id'],
                        'type_name' => '['.$v['type_name'].']',
                        'prices' => '<span class="money_symbol">¥</span>'."<span class='money_num'>{$v['prices']}</span>",
                        'consults' => ($v['consults']>=$this->consults_limit ? $this->consults_replace : $v['consults']) . '人咨询',
                    );
                    $place_numbers[] = $tmp[$v['goods_id']]['place_number'];
                }
                array_multisort($place_numbers, SORT_ASC, $result);
                break;
            case 'organization_1':
            case 'organization_2':
            case 'organization_3':
                $user_id = '';
//                 $remark = '';
                $goods_id = array();
                foreach ($data as $k=>$v)
                {
                    if (!$user_id)
                    {
                        $user_id = (int)$v['user_id'];
//                         $remark = $v['remark'];
                        continue;
                    }
                    $goods_id[] = (int)$v['goods_id'];
                    $tmp[$v['goods_id']] = array(
                        'img_url' => $v['img_url'],
                        'link_url' => $v['link_url'],
                        'place_number' => $v['place_number'],
                    );
                    if ( count($goods_id) >= $this->data_num_config['organization'] ) break;
                }
                $goods_id = $goods_id ? '(' . implode(',', $goods_id) . ')' : '(0)';
                $goods_list = $this->inner_get_goods_list("goods_id IN{$goods_id} AND is_show=1 AND is_black=0", '0,40', '', 'goods_id,images,prices');
                foreach ($goods_list['data'] as $k=>$v)
                {
                    $result[] = array(
                        'prices' => '<span class="money_symbol">¥</span>'."<span class='money_num'>{$v['prices']}</span>",
                        'goods_id' => $v['goods_id'],
                        'img_url' => !preg_match($pattern, $tmp[$v['goods_id']]['img_url']) ? $tmp[$v['goods_id']]['img_url'] : $v['images'],
                        'link_url' => $this->page_url_config['goods_detail'] . '?goods_id=' . (int)$v['goods_id'],
                        'place_number' => $tmp[$v['goods_id']]['place_number'],
                    );
                    $place_numbers[] = $tmp[$v['goods_id']]['place_number'];
                }
                array_multisort($place_numbers, SORT_ASC, $result);
                $photographers_info = $this->get_photographers_info($user_id);
                $good_at = $this->array_iconv($this->get_good_at($user_id));
                array_unshift($result, array(
                    'seller_id' => $photographers_info['seller_id'],
                    'user_id' => $photographers_info['user_id'],
                    'avatar' => $photographers_info['avatar'],
                    'link_url' => $this->page_url_config['photographer_detail'] . "?user_id={$user_id}",
                    'remark' => $good_at,
                    'seller_name' => $photographers_info['seller_name']
                ));
                break;
            case 'photographers':
                $user_ids = array();
                foreach ($data as $k=>$v)
                {
                    $user_ids[] = (int)$v['user_id'];
                    $tmp[$v['user_id']] = array(
                        'img_url' => $v['img_url'],
                        'link_url' => $v['link_url'],
                        'place_number' => $v['place_number'],
                    );
                    if ( count($user_ids) >= $this->data_num_config[$key] ) break;
                }
                
                $user_ids = $user_ids ? '(' . implode(',', $user_ids) . ')' : '(0)';
                $greate_goods_list = $this->get_greate_goods("user_id IN{$user_ids} AND is_show=1 AND is_black=0");
                $user_goods_info = array();
                foreach ($greate_goods_list as $k=>$v)
                {
                    $user_goods_info[$v['user_id']] = array('images'=>$v['images'], 'goods_id'=>$v['goods_id']);
                }
                $photographers_list = $this->get_photographers_list("user_id IN{$user_ids} AND status=1 AND is_black=0", '0,40', '', 'seller_id,user_id,seller_name,avatar,cover,goods_num');
                $photographers_list = $this->array_iconv($photographers_list);
                foreach ($photographers_list as $k=>$v)
                {
                    $result[] = array(
                        'seller_id' => $v['seller_id'],
                        'user_id' => $v['user_id'],
                        'seller_name' => $v['seller_name'],
                        'img_url' => !preg_match($pattern, $tmp[$v['user_id']]['img_url']) ? $tmp[$v['user_id']]['img_url'] : ($user_goods_info[$v['user_id']]['images'] ? $user_goods_info[$v['user_id']]['images'] :  $v['cover']),
                        'link_url' => $this->page_url_config['photographer_detail'] . '?user_id=' . (int)$v['user_id'],
                        'goods_num' => $v['goods_num'],
                        'goods_info' => '摄影套系:',
                        'avatar' => $v['avatar'],
                        'place_number' => $tmp[$v['user_id']]['place_number'],
                    );
                    $place_numbers[] = $tmp[$v['user_id']]['place_number'];
                }
                array_multisort($place_numbers, SORT_ASC, $result);
                break;
            default:
                foreach ($data as $k=>$v)
                {
                    $result[] = $v;
                    if ( count($result) >= $this->data_num_config[$key] ) break;
                }
                break;
        }
        return $result;
    }
    
    private function get_photographers_list($where = '', $limit = '0,40', $order = '`consult_num` DESC, `update_time` DESC', $fields = '*')
    {
        $this->set_mall_photographers_tbl();
        return $this->findAll($where, $limit, $order, $fields);
    }
    
    private function get_photographers_info($user_id, $fields = '*')
    {
        $this->set_mall_photographers_tbl();
        $user_id = (int)$user_id;
        return $this->array_iconv($this->find("user_id={$user_id} AND status=1 AND is_black=0", null, $fields));
    }
    
    public function add_consult_num($goods_id, $type, $num)
    {
        $this->set_mall_goods_tbl();
        if (is_array($goods_id) && $type == 'add_rand_num' && is_array($num))
        {
            $min = (int)min($num);
            $max = (int)max($num) - $min;
            foreach ($goods_id as $v)
            {
                $ids[] = (int)$v;
            }
            $ids = $ids ? '(' . implode(',', $ids) . ')' : '(0)';
            $where = "goods_id IN {$ids}";
            $sql = "UPDATE {$this->_db_name}.{$this->_tbl_name} SET add_consult_num=add_consult_num+FLOOR({$min}+RAND()*{$max}) WHERE {$where}";
            $update = $this->query($sql);
            $sql = "SELECT
                        user_id,sum(consult_num) as consult_num, sum(add_consult_num) as add_consult_num
                    FROM
                        {$this->_db_name}.{$this->_tbl_name}
                    WHERE
                        is_show=1 AND is_black=0 AND {$where}
                    GROUP BY
                        user_id";
            $user_consult_info = $this->query($sql);
            if ($user_consult_info[0])
            {
                foreach ($user_consult_info as $v)
                {
                    $this->synchronize_photographers_consult($v);
                }
            }
            return true;
        }
        else if (is_numeric($goods_id) && $type=='add_num')
        {
            $where = 'goods_id=' . (int)$goods_id;
            $num = (int)$num;
            POCO_TRAN::begin($this->getServerId());
            $update = $this->incrField($where, 'add_consult_num', $num);
            if (!$update)
            {
                POCO_TRAN::rollback($this->getServerId());
                return false;
            }
            $user = $this->find("goods_id={$goods_id}", null, 'user_id');
            if (!$user)
            {
                POCO_TRAN::rollback($this->getServerId());
                return false;
            }
            $update = $this->synchronize_photographers_add_consults($user['user_id'], $num);
            if (!$update)
            {
                POCO_TRAN::rollback($this->getServerId());
                return false;
            }
            POCO_TRAN::commmit($this->getServerId());
            return true;
        }
    }
    
    /**
     * 同步添加虚拟咨询数
     * @param int $user_id
     * @param int $num
     */
    private function synchronize_photographers_add_consults($user_id, $num)
    {
        $this->set_mall_photographers_tbl();
        $user_id = (int)$user_id;
        $num = (int)$num;
        return $this->incrField("user_id={$user_id}", 'add_consult_num', $num);
    }
    
    /**
     * 同步摄影师实际、虚拟咨询数量
     * @param unknown $data
     */
    private function synchronize_photographers_consult($data)
    {
        $user_id = (int)$data['user_id'];
        $consult_num = (int)$data['consult_num'];
        $add_consult_num = (int)$data['add_consult_num'];
        $this->set_mall_photographers_tbl();
        return $this->update(array('consult_num'=>$consult_num, 'add_consult_num'=>$add_consult_num), "user_id={$user_id}");
    }
    
    /**
     * 获取擅长套系
     * @param int $user_id
     */
    private function get_good_at($user_id)
    {
        $this->set_mall_goods_tbl();
        $user_id = (int)$user_id;
        $sql = "SELECT 
                    count(type_id) as num, type_name
                FROM 
                    {$this->_db_name}.{$this->_tbl_name}
                WHERE 
                    user_id ={$user_id}  
                GROUP BY 
                    type_id
                ORDER BY 
                    num DESC
                limit 1 ";
        $result = $this->query($sql);
        return $result[0]['type_name'];
    }
    
    /**
     * 获取用户综合评分最高的
     * @param string $where
     * @param string $group
     */
    private function get_greate_goods($where = '1', $group = 'user_id')
    {
        $this->set_mall_goods_tbl();
        $sql = "SELECT
                    MAX(0.5*consult_num+0.45*uv+0.05*pv) as overall_hot,images,goods_id,user_id
                FROM
                    {$this->_db_name}.{$this->_tbl_name}
                WHERE
                    {$where}
                GROUP BY
                    {$group}
                ";
        return $this->query($sql);
    }
    
    /**
     * 套系详情中的还喜欢商品信息
     * @param array $where
     * @return array
     */
    public function get_like_goods($where = array('goods_id'=>0, 'style_ids'=>array(),'type_id'=>0,'user_id'=>0))
    {
        $this->set_mall_goods_tbl();
        $result = array();
        $goods_ids = array();
        $style_ids = $where['style_ids'] ? '(' . implode(',', $where['style_ids']) . ')' : '(0)';
        $user_id = (int)$where['user_id'];
        $type_id = (int)$where['type_id'];
        $w_goods_id = (int)$where['goods_id'];
        $goods_ids[0] = $w_goods_id;
        $sql = "SELECT
                    location_id, type_id,user_id,goods.goods_id,images,prices,titles,(consult_num+add_consult_num) as consults
                FROM
                    {$this->_db_name}.{$this->_tbl_name} goods
                LEFT JOIN
                    {$this->_db_name}.mall_goods_style_tbl style
                ON
                    is_show=1 AND is_black=0 AND goods.goods_id!={$w_goods_id} AND user_id={$user_id} AND style.style_id IN {$style_ids} AND goods.goods_id=style.goods_id
                WHERE
                    style_id IN {$style_ids}
                GROUP BY
                    goods.goods_id
                ORDER BY
                    consult_num DESC
                LIMIT
                    0,4
                ";
        $style_like_goods = $this->query($sql);
        foreach ($style_like_goods as $k=>$v)
        {
            $goods_id = (int)$v['goods_id'];
            $goods_ids[] = $goods_id;
            $style_like_goods[$k]['link_url'] = $this->page_url_config['goods_detail'] . '?goods_id=' . $goods_id;
        }
        $style_count = count($style_like_goods);
        $result = $style_count > 0 ? $result+$style_like_goods : $result;
        $count = 4;
        //空或不足,需另取
        if ( $style_count < $count )
        {
            $goods_id_where = $goods_ids ? ' AND goods_id NOT IN(' . implode(',', $goods_ids) . ")" : '';
            
            $limit = '0,' . ($count-$style_count);
            $tyle_like_gooods = $this->findAll("is_show=1 AND is_black=0 AND type_id={$type_id} AND user_id={$user_id} {$goods_id_where}", $limit, 'consult_num DESC', 'location_id,goods_id,user_id,type_id,images,prices,titles,(consult_num+add_consult_num) as consults');
            foreach ($tyle_like_gooods as $k=>$v)
            {
                $goods_id = (int)$v['goods_id'];
                $goods_ids[] = $goods_id;
                $v['link_url'] = $this->page_url_config['goods_detail'] . '?goods_id=' . $goods_id;
                $result[] = $v;
            }
            $type_count = count($tyle_like_gooods);
            $result = $type_count > 0 ? $result + $tyle_like_gooods : $result;
            
            if ( ($type_count+$style_count) < $count )
            {
                $goods_id_where = $goods_ids ? ' AND goods_id NOT IN(' . implode(',', $goods_ids) . ')' : '';
                $limit = '0,' . ($count-$type_count-$style_count);
                $like_goods = $this->findAll("is_show=1 AND is_black=0 AND user_id={$user_id} {$goods_id_where}",$limit,'consult_num DESC', 'location_id,goods_id,user_id,type_id,images,prices,titles,(consult_num+add_consult_num) as consults');
                foreach ($like_goods as $k=>$v)
                {
                    $v['link_url'] = $this->page_url_config['goods_detail'] . '?goods_id=' . (int)$v['goods_id'];
                    $result[] = $v;
                }
            }
        }
        return $this->array_iconv($result);
    }
    
    /**
     * 初始化摄影师的摄影商品数量
     * @param string $init_type
     */
    public function init_goods_num($user_id = 0)
    {
        $this->set_mall_goods_tbl();
        $user_id = (int)$user_id;
        $sql = "SELECT
                    COUNT(*) AS goods_num
                FROM
                    {$this->_db_name}.{$this->_tbl_name}
                WHERE
                    user_id={$user_id} AND is_show=1 AND is_black=0
                ";
        $user_goods = $this->query($sql);
        $this->set_mall_photographers_tbl();
        $goods_num = $user_goods[0]['goods_num'] ? $user_goods[0]['goods_num'] : 0;
        $this->update(array('goods_num'=>$goods_num, 'update_time'=>time()), "user_id={$user_id}");
        return $this->init_type($user_id);
    }
    
    /**
     * 初始化摄影师的商品类型集合
     * @param string $init_goods
     */
    public function init_type($user_id)
    {
        $this->set_mall_goods_tbl();
        $user_id = (int)$user_id;
        $sql = "SELECT
                    GROUP_CONCAT(DISTINCT type_id) as type_ids
                FROM
                    {$this->_db_name}.{$this->_tbl_name}
                WHERE
                    user_id={$user_id} AND is_show=1 AND is_black=0
                ";
        $user_types = $this->query($sql);
        $this->set_mall_photographers_tbl();
        if ($user_types[0]['type_ids'])
        {
            $type_ids = explode(',', $user_types[0]['type_ids']);
            sort($type_ids);
            $type_ids = serialize($type_ids);
        }
        else
        {
            $type_ids = null;
        }
        return $this->update(array('type_ids'=>$type_ids, 'update_time'=>time()), "user_id={$user_id}");
    }
    
    public function get_location_info()
    {
        $this->set_mall_goods_tbl();
        return $this->findAll('is_show=1 AND is_black=0', null, null, 'DISTINCT location_id');
    }
}