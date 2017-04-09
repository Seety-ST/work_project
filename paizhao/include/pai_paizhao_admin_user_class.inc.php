<?php
/**
 * 权限用户管理
 * 
 * @author ljl
 */

class pai_paizhao_admin_user_class extends POCO_TDG
{
    public $_redis_cache_name_prefix = "G_YUEUS_MALL_GOODS_ACL_USER_";
    
	public function __construct()
	{
		$this->setServerId('101');
		$this->setDBName('mall_db');
	}
	
	private function set_mall_admin_acl_tbl()
    {
        $this->setTableName('mall_admin_acl_tbl');
    }
    
    private function set_mall_admin_tbl()
    {
        $this->setTableName('mall_admin_tbl');
    }
    
    
    /**
     * 获取详情
     * @param type $user_id
     * @return array
     */
    public function get_user_info($user_id)
    {
        $user_id = (int)$user_id;
        if( ! $user_id )
        {
            return false;
        }
        $this->set_mall_admin_tbl();
        return $this->find("user_id='{$user_id}'");
    }
    
    /**
     * 获取详情
     * @param type $id
     * @return boolean
     */
    public function get_one($id)
    {
        $id = (int)$id;
        if( ! $id )
        {
            return false;
        }
        $this->set_mall_admin_tbl();
        return $this->find("id='{$id}'");
    }
    
    /**
     * 设定缓存
     * @param type $user_id
     * @return boolean
     */
    public function set_acl_user_cache($user_id)
    {
        $user_id = (int)$user_id;
        if( ! $user_id )
        {
            return false;
        }
        $this->set_mall_admin_tbl();
        //$user_one = $this->find("user_id='$user_id' and status='1'");
        $user_one = $this->find("user_id='$user_id'");
        if( ! empty($user_one) )
        {
            POCO::setCache($this->_redis_cache_name_prefix."{$user_id}", $user_one, array('life_time'=>30*86400));
			return $user_one;
        }
		else
        {
            return false;
        }
            
    }
	
    
    /**
     * 更新登录session
     * @param int $user_id
     * @param str $session_id
     * @param bool $type
     * @return bool
     */
    public function update_user_session($user_id,$session_id,$type=true)
    {
		$user_id = (int)$user_id;
		if(!$user_id)
		{
			return false;
		}
		$this->set_mall_admin_tbl();
		$s_id = md5($session_id."|".mall_get_ip());
		$data = array(
		              's_id'=>$s_id,
					  'login_time'=>date('Y-m-d H:i:s'),
					  );
		if(!$type)
		{
			$data = array(
			              'n_s_id'=>$s_id,
			              'login_time'=>date('Y-m-d H:i:s'),						  
						  );
		}
		$this->update($data,"user_id='{$user_id}'");
		$this->del_acl_user_cache($user_id);
		return true;
    }
    
    /**
     * 读缓存
     * @param type $user_id
     * @return boolean
     */
    public function get_acl_user_cache($user_id)
    {
        $user_id = (int)$user_id;
        if( ! $user_id )
        {
            return false;
        }
        $cache_one = array();
        $cache_one = POCO::getCache($this->_redis_cache_name_prefix."{$user_id}");
        if(!$cache_one)
        {
            $cache_one = $this->set_acl_user_cache($user_id);
        }
		return $cache_one;
    }    
}
