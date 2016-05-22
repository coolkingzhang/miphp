
<?php

/**
* 定义Session类
* 
* @param string $name,$value
* @author wangguoxing 554952580
* @version version 1.0
* @link http://www.a.com
* @copyright Copyright (c) 2010 wgx Inc. {@link http://www.a.com}
* @return array
*/

/*
Session类 方法说明：
Session::set_s()设置session
Session::get_s()获取session
Session::remove_s()移除session
Session::clean()清空session
*/


class sessionClass
{
    
    public function __construct(){
	    /// session_start();
    }
    /**
     * 设置session
     *  @param array $array    $k为session名，$v为session的值      
     * @param string $act      USER表示前台的session，ADMIN表示后的session
     */
    function set_s($array,$act=USER)
    {    
         if(is_array($array) && !$array)
         {
           foreach($array as $k=>$v)
           {
               $_SESSION[$act][$k]=$v;
           }
         }               
    } 
    
    /**
     * 获取session
     * @param string $_name    session名  
     * @param string $act      USER表示前台的session，ADMIN表示后的session
     */
    function get_s($_name='',$act=USER)
    {
        if(!$_name)    //返回前台的session
        {
            return  $_SESSION[$act];   
        }        
        if(isset($_SESSION[$act][$_name]))
        {
            return $_SESSION[$act][$_name];
        }        
     }
    /**
     * 删除session名
     * @param string $_name    session名  
     * @param string $act      USER表示前台的session，ADMIN表示后的session
     */
    function remove_s($_name='',$act=USER)
    {
		if(!$_name)
        {
             unset($_SESSION[$act]);   
        }
        if($_SESSION[$act][$_name])
        {
            unset($_SESSION[$act][$_name]);     
        }
        
    }
    
    /**
     * 清除所有session    
     */
    function clean_s()
    {
		$_SESSION = array();
        session_destroy();
        session_regenerate_id();
        return true;
    }
}

?>