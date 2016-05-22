<?php

/**
 * @file			mod.base.php
 * @CopyRright (c)	1996-2099 zhangzhihe 
 * @Project			鲜橙网 
 * @Author			张志合 <coolkingzhang@163.com>
 * @version 		1.0
 * @Create Date：   	2011-12-15
 * @Modified By：   	张志合/2011-12-15
 * @Brief			路由类
 */

class Route 
{
	// 路由格式　　http://test.frame.com/test.hello/id-12/r-3　　能过 $_GET['id'] $_GET['r']; 得到这两个的值
	
	public static function makeRoute2() 
	{ 
		$url = substr(CORE::V('S:REQUEST_URI',''),1);
		//$ss = $_SERVER['REDIRECT_URL'];
		$url = str_replace(html,'',$url);
		$p = explode(ROUTE_RWRITE_S, $url);
		$count = count($p);
		$params = array();
		$dir = '';
		
		/*
		 * params
		 */
		if($count > 1) 
		{
			for($i = 1;$i < $count; $i++ ) 
			{
				$tmp = explode(ROUTE_RWRITE_G, $p[$i]);
				$params[$tmp[0]] 	= @$tmp[1];
				$_GET[$tmp[0]] 		= @$tmp[1];
			}
		}
		//print_r($params);
		
		//echo $count;
		
		//if(count($d) == 1)
		//{
			/// 如果不是目录路由
			
			$c_array 	= explode(ROUTE_S,$p[0]);
			//print_r($c_array);
			$function 	= count($c_array) > 1 ? $c_array[count($c_array)-1] : ROUTE_FUNCTION; 
			//echo $function;
			$class		= isset($c_array[0]) ? $c_array[0] : ROUTE_CLASS;
		/*
		} else {
			/// 目录路由
			for($i = 0;$i < (count($d)-1); $i++) { 
				$dir.= $d[$i]."/";
			}
			$c_array 	= explode(ROUTE_S,$d[count($d)-1]);
			$function 	= count($c_array) > 1 ? $c_array[count($c_array)-1] : ROUTE_FUNCTION;
			$class		= isset($c_array[0]) ? $c_array[0] : ROUTE_CLASS;
		}
		*/
		/*	
		if(!empty($c[1])) {
			$split = split(ROUTE_RWRITE_G,$c[1]);
			$count = count($split);
			if(is_array($split)) {
				for($i = 0 ;$i < $count;$i = $i+2)
				{
					$_GET[$split[$i]] = $split[$i+1];
				}
			}
		}*/
		
		return array('params'=>$params, 'class'=>$class, 'function'=>$function , 'dir' => $dir );
	}
	public static function makeRoute2_bak() 
	{ 
		$ss = trim(CORE::V('S:REQUEST_URI',''),'/');
		$ss = rtrim($ss,html);
		$c = explode(ROUTE_RWRITE_S, $ss);
		$d = explode('/', $c[0]);
		$dir = '';
		if(count($d) == 1)
		{
			/// 如果不是目录路由
			$c_array 	= explode(ROUTE_S,$c[0]);
			$function 	= count($c_array) > 1 ? $c_array[count($c_array)-1] : ROUTE_FUNCTION; 
			$class		= isset($c_array[0]) ? $c_array[0] : ROUTE_CLASS;
		} else {
			/// 目录路由
			for($i = 0;$i < (count($d)-1); $i++) { 
				$dir.= $d[$i]."/";
			}
			$c_array 	= explode(ROUTE_S,$d[count($d)-1]);
			$function 	= count($c_array) > 1 ? $c_array[count($c_array)-1] : ROUTE_FUNCTION;
			$class		= isset($c_array[0]) ? $c_array[0] : ROUTE_CLASS;
		}
		if(!empty($c[1])) {
			$split = split(ROUTE_RWRITE_G,$c[1]);
			$count = count($split);
			if(is_array($split)) {
				for($i = 0 ;$i < $count;$i = $i+2)
				{
					$_GET[$split[$i]] = $split[$i+1];
				}
			}
		}
		return array('path'=>$_GET, 'class'=>$class, 'function'=>$function , 'dir' => $dir );
	}
	public static function makeRoute1() 
	{  
		$_GET[ROUTE_M] = isset($_GET[ROUTE_M]) ? $_GET[ROUTE_M] : ROUTE_CLASS;
			$route = CORE::V('G:'.ROUTE_M);
			$c = $route; 
			$d = explode('/', $route);
			$dir = '';
			$count = count($d);
			if($count == 1)
			{
				/// 如果不是目录路由
				$c_array 	= explode(ROUTE_S,$route);
				$function 	= count($c_array) > 1 ? $c_array[count($c_array)-1] : ROUTE_FUNCTION; 
				$class		= isset($c_array[0]) ? $c_array[0] : ROUTE_CLASS;
			} 
			else 
			{
				/// 目录路由
				for($i = 0;$i < (count($d)-1); $i++) 
				{ 
					$dir.= $d[$i]."/";
				}
				$c_array 	= explode(ROUTE_S,$d[count($d)-1]);
				$function 	= count($c_array) > 1 ? $c_array[count($c_array)-1] : ROUTE_FUNCTION;
				$class		= isset($c_array[0]) ? $c_array[0] : ROUTE_CLASS;
			}
			unset($c_array);
			unset($count);
			unset($d);
			unset($route);
			$params = $_GET;
			unset($params[ROUTE_M]); 
			return array('params'=>$params, 'class'=>$class, 'function'=>$function , 'dir' => $dir ,'c'=>$c);
	}
	public function makeRoute3() 
	{ 
		$controller = ROUTE_CLASS;
		$operate 	= ROUTE_FUNCTION;
		$params 	= array();
		$dir 		= '';
		if (isset($_SERVER['PATH_INFO'])) {
			$query_string = substr(str_replace(array(html,'.html','.htm', '.asp', '//'), '',$_SERVER['PATH_INFO']),1);
		} else {
			$query_string = str_replace($_SERVER['SCRIPT_NAME'], '',$_SERVER['PHP_SELF']);
			$query_string = rtrim($query_string,html);
		}
		$d = strpos($query_string,'.');
		/*
		 * 单层控制器
		 */
		if(empty($d)) {
			$temp = explode('/', $query_string);
			if(!empty($temp[0])) {
				$controller = $temp[0];
			}
			if(!empty($temp[1])) {
				$operate = $temp[1];
			}
			if(count($temp) > 2) {
				for($i = 2;$i < count($temp);$i++) {
					$params[] = $temp[$i];
				}
			}
		} else {		
			/*
			 * 多层控制器
			 */
			$q = substr($query_string,0,$d);
			$temp = explode('/', $q);
			$count = count($temp);
			$str = '';
			if($count == 1) {
				$dir = '';
				$controller = $temp[0];
			}
			if($count > 1) {
				for($i = 0;$i<($count-1);$i++) {
					$str.=$temp[$i].'/';
				}
				$dir = $str;
				/// $dir = rtrim($str,'/');
				$controller = $temp[$count - 1];
			}
			
			$h = substr($query_string,($d+1));
			$temp = explode('/', $h);
			if(!empty($temp[0])) {
				$operate = $temp[0];
			}
			if(count($temp) > 1) {
				for($i = 1;$i < count($temp);$i++) {
					$params[] = $temp[$i];
				}
			}
		}
		return array('dir'=> $dir,"class" => $controller,"function" => $operate,"params" => $params);
	}
}
?>