<?php
/**
 * Ȩ�����޼��˵�����
 * 
 * @author ljl
 */

class pai_paizhao_admin_acl_class extends POCO_TDG
{
    public $_redis_cache_name_prefix = "G_YUEUS_MALL_PAIZHAO_ACL";
    
	public function __construct()
	{
		$this->setServerId('101');
		$this->setDBName('mall_db');
	}
	
	private function set_mall_admin_acl_tbl()
    {
        $this->setTableName('mall_admin_acl_tbl');
    }
    
    
    /**
     * Ȩ�޲������޼�����
     * @param type $pid
     * @param type $level
     * @param type $res
     * @return type
     */
    public function get_acl_cate($pid = 0,$level = 0 ,$res = array())
    {
        $pid = (int)$pid;
        $this->set_mall_admin_acl_tbl();
        $sql = "parent_id='$pid' and status='1' ";
		$data = $this->findAll($sql,'','id asc');
        foreach($data as $v)
        {
            $v['level'] = $level;
            $res[] = $v;
            $res = $this->get_acl_cate( $v['id'],$level+1,$res); 
		}
		return $res;
    }
    
    /**
     * ��ȡ�����Ӽ�
     * @param type $id
     * @param type $res
     * @return type
     */
    public function get_all_childs($id,$res = array())
    {
        $this->set_mall_admin_acl_tbl();
        $data = $this->findAll("parent_id='$id' and status='1' ",'','id ASC','id');
        foreach($data as $v)
        {
			$res[] = $v['id'];
            $res = $this->get_all_childs($v['id'],$res); 
		}
		
		return $res;
    }
    
    /**
     * ��ȡ���и���
     * @param type $id
     * @param type $res
     * @return type
     */
    public function get_all_parents($id,$res = array())
    {
         $this->set_mall_admin_acl_tbl();
         $data = $this->findAll("id='$id' and status='1'",'','id asc','parent_id');
         foreach($data as $v)
         {
             $res[] = $v['parent_id'];
             $res = $this->get_all_parents($v['parent_id'],$res);
         }
		 
         return $res;
    }
    
    /**
     * ��ȡȨ������
     * @param type $id
     * @return boolean
     */
    public function get_admin_acl_info($id)
    {
        $id = (int)$id;
        if( ! $id )
        {
            return false;
        }
        $this->set_mall_admin_acl_tbl();
        return $this->find("id='{$id}'");
    }
    
    /**
     *����Ƿ��и���
     * @param type $id
     * @return type
     */
    public function check_have_parent($id)
    {
        $this->set_mall_admin_acl_tbl();
        return $this->find("parent_id='$id' and status='1'");
    }
    
    /**
     * ��ȡval ��md5�����Ӽ�
     * @return type
     */
    public function get_md5_row_and_child_list()
    {
       $md5_child_cache = $this->get_md5_child_cache();
       if( ! empty($md5_child_cache) )
       {
           return $md5_child_cache;
       }
       $this->set_mall_admin_acl_tbl();
       $md5_rs = $rs = $parent_list = $md5_child_rs = array();
       $list = $this->findAll("parent_id='0' and status=1");
       if( ! empty($list) )
       {
           foreach($list as $k => $v)
           {
               $parent_list[] = $v['id'];
           } 
       }
       if( ! empty($parent_list) )
       {
           $where = implode(",",$parent_list);
           $rs = $this->findAll("parent_id in ($where) and status=1");
       } 
       
       if( ! empty($rs) )
       {
           foreach($rs as $k => $v)
           {
               $md5_rs[md5('/disk/data/htdocs232/poco/pai/'.$v['val'])] = $v;
           } 
       }
       if($md5_rs)
       {
            $md5_child_rs = $this->get_child_cate($md5_rs);
            $this->set_md5_child_cache($md5_child_rs,86400);
       }
       
       
       return $md5_child_rs;
       
    }
    
    public function set_md5_child_cache($data,$life_time)
    {
       POCO::setCache($this->_redis_cache_name_prefix, $data, array('life_time'=>$life_time));
    }
    
    public function get_md5_child_cache()
    {
         $return = POCO::getCache($this->_redis_cache_name_prefix);
		 return $return;
    }
    
    public function get_child_cate($ary)
    {
        foreach($ary as $k => $v)
        {
            if( ! empty($v['children_list']) )
            {
                $child_ary = explode(",",$v['children_list']);
                foreach($child_ary as $ck => $cv)
                {
                    $this->set_mall_admin_acl_tbl();
                    $child_one = array();
                    $child_one = $this->find("id='{$cv}'");
                    if( ! $ary[$k]['child_data'][md5($child_one['val'])] )
                    {
                        $ary[$k]['child_data'][md5($child_one['val'])] = $child_one;
                    }
                    
                    if($child_one['children_list'])
                    {
                        $ary[$k]['child_data'] = $this->get_child_cate( $ary[$k]['child_data'] );
                    }
                }
                
            }
        }
        return $ary;
    }         
}
