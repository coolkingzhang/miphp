<?php
/*
 * 用户权限控制器
 */

class acc_controller 
{ 
	/**
	*自定义加载         
	* @return	object
	*/
	function my_load() 
	{         
		$access			= array(		
			'home' 		=> 	array('access'=>array('ALL'),'exclude'=>array('test')),
	 		'user'		=>	array('access'=>array('ALL'),'exclude'=>array()),
			'letter'	=>	array('access'=>array('ALL'),'exclude'=>array()),
			'act'		=>	array('access'=>array('ALL'),'exclude'=>array()),
			'setting'	=>	array('access'=>array('ALL'),'exclude'=>array()),
			'v'			=>	array('access'=>array('ALL'),'exclude'=>array()),
			'show'		=>	array('access'=>array('ALL'),'exclude'=>array()),
			'report'	=>	array('access'=>array('ALL'),'exclude'=>array()),
			'sina'		=>	array('access'=>array('ALL'),'exclude'=>array()),
			'bind'		=>	array('access'=>array('ALL'),'exclude'=>array()),
			//'index'	=>	array('access'=>array('ALL'),'exclude'=>array()),
			'profile'	=>	array('access'=>array('ALL'),'exclude'=>array()),
		); 
		$mc	= Route::makeRoute1();               
		$c 	= $mc['class'];
		$m 	= $mc['function'];
		if('admin' == $c) 
		{
			self::checkpriv();
		} 
		else 
		{
			if(!empty($access[$c]) && in_array('ALL', $access[$c]['access']) && !in_array($m, $access[$c]['exclude']))
			{                            
				self::checklogin();
			} 
			if(!empty($access[$c]) && in_array($m, $access[$c]['access']) && !in_array($m, $access[$c]['exclude'])) 
			{                    
				self::checklogin();
			}
		}
	}
	/**
	 * 检查后台用户权限
	 *
	 * @return void
	 */
	function checkpriv() 
	{   	
	}        
	/*
	 * 检查用户登录权限
	 */            
	function checklogin() 
	{
		/*
		 * p = 0 没有登录
		 * p = 1 已经登录
		 */
		$p = 0;
		if(!empty($_SESSION[USER])) 
		{
			$p = 1;
		} 
		else 
		{
			$ck = CORE::N('cookies/cookies');
			$c = $ck->get(USER);
			if(!empty($c)) 
			{
				$code = authcode($c,'DECODE',520);
				if(!empty($code)) 
				{
					$x = explode('|||',$code);
					if(md5($x[0]) == $x[1] && !empty($x[0]) && !empty($x[1])) 
					{
						$user_mod = CORE::M('user_mod');
						$user_info = $user_mod->show($x[0]);
						set_s($user_info);
						$p = 1;
					}
				}
			}
			unset($ck,$c,$code,$x,$user_mod,$user_info);
			//$ck->set(USER,$md5,(time()+5));
			//$ck->set(USER,authcode($code,'ENCODE',520));
		}
		if($p == 0) 
		{
			CORE::HEADER('login.ulogin');
		}
	}
}
//acc_controller::my_load();
?>