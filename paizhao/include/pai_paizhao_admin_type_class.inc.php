<?php
/**
 * 无限级角色管理
 * 
 * @author ljl
 */

class pai_paizhao_admin_type_class extends POCO_TDG
{
    public $_redis_cache_name_prefix = "G_YUEUS_MALL_PAIZHAO_ACL_TYPE_INFO_";
    
	public function __construct()
	{
		$this->setServerId('101');
		$this->setDBName('mall_db');
	}
	
	private function set_mall_admin_type_tbl()
    {
        $this->setTableName('mall_admin_type_tbl');
    }
    
    
    /**
     * 获取权限详情
     * @param type $id
     * @return boolean
     */
    public function get_admin_type_info($id)
    {
        $id = (int)$id;
        if( ! $id )
        {
            return false;
        }
		$info = POCO::getCache($this->_redis_cache_name_prefix.$id);
        if(!$info || ! is_array($info))
		{
            $this->set_mall_admin_type_tbl();
			$info = $this->find("id='{$id}'");
            POCO::setCache($this->_redis_cache_name_prefix.$id,$info, array('life_time'=>2592000));
		}
       
        return $info;
    }
	
    /**
     * 拿权限详情用sql
     * @param type $id
     * @return boolean
     */
    public function get_admin_type_info_for_sql($id)
    {
        $id = (int)$id;
        if( ! $id )
        {
            return false;
        }
        $this->set_mall_admin_type_tbl();
        $info = $this->find("id='{$id}'");
        return $info;
    }

    /**
     * 获取所有子级
     * @param type $id
     * @param type $res
     * @return type
     */
    public function get_all_childs($id,$res = array())
    {
        $id = (int)$id;
        if( ! $id )
        {
            return false;
        }
        $this->set_mall_admin_type_tbl();
        $data = $this->findAll("parent_id='$id' and status='1' ",'','id ASC','id');
        foreach($data as $v)
        {
			$res[] = $v['id'];
            $res = $this->get_all_childs($v['id'],$res); 
		}
		
		return $res;
    }
    
    public function get_all_parents($id,$res = array())
    {
         $this->set_mall_admin_type_tbl();
         $data = $this->findAll("id='$id' and status=1",'','id asc','parent_id');
         foreach($data as $v)
         {
             $res[] = $v['parent_id'];
             $res = $this->get_all_parents($v['parent_id'],$res);
         }
		 
         return $res;
    }

    /**
     * 获取所有的父级名称
     * @param type $parent_str
     * @return boolean
     */
    public function get_parent_list_name($parent_str = '')
    {
        $parent_str = mall_simple_filter($parent_str);
        if(empty($parent_str))
        {
            return false;
        }
        $parent_ary = explode(',',$parent_str);
        $final_str = '';
        foreach($parent_ary as $k => $v)
        {
            if($v == 0)
            {
                continue;
            }
            $admin_type_info = $this->get_admin_type_info($v);
            $final_str .= $admin_type_info['name'].'->';
            
        }
        
        $final_str = substr($final_str, 0,-2);
        
        return $final_str;
        
    } 
}
