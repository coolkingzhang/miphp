<?php

/*
 * 核心框架
 */
if(!defined('IN_HF')) 
{
    die('Hacking attempt');
} 
/**
 * @file			/application/core.php 
 * @CopyRight		鲜橙网
 * @Project			Henry
 * @Author			zhangzhihe <coolkingzhang@163.com>
 * @Create Date:	2010-06-08
 * @Modified By:	zhangzhihe/2010-11-19
 * @Brief			框架核心文件
 * 默认控制器加载的一些功能或者常用类库
  */
class CORE 
{ 
	function __construct() {                       
	} 	
	/**
	 * CORE::Change_Route();
	 * 路由重写规则
	 */
	public function Change_Route() {
		$array['admin/backend.login'] = array('path'=>array('c'=>'admin/admin.default_index','id'=> 9999),'function'=>'default_index','class'=>'admin','dir'=>'admin/');
		return $array; 
	}
	/**
	 * CORE::Header($url,$type = 1,$array = array());
	 * 设置网页头信息
	 * @param string url 跳转的url
	 * @param int type 跳转的类型  1 为普通 location跳转 2 为控制器跳转
	 * @param array $array 转跳的参数和值
	 */
	public function Header($url,$type = 2,$array = array()) 
	{
		switch ($type) 
		{
			case 1:
			foreach($array as $key => $val) 
			{
				$str.="&".$key."=".$val;
			}
			header("Location: $url".$str); 
			break;
			case 2:
			$route = self::Make_Route($url,$array);
			header("Location: $route"); 
			break;
			case 3:
			header('Content-Type:text/html charset='.$url);
			break;
			default:
		}
		exit; 
	}
	/**
	 * CORE::Make_Route($url,$type = 1,$array = array());
	 * 生成路由规则 
	 * 普通路由  http://www.abc.com/index.php?c=admin/test.index&id=100&name=mm
	 * rewrite路由  http://www.abc.com/admin/test.index/-id-100-name-mm.html	
	 * rewrite 第三种 http://www.abc.com/admin/test.index/100.shtml
	 * @param string route 路由地址
	 * @param array $array 路由参数
	 * @param int $type 类型
	 */	
	public function Make_Route($route,$array = array()) 
	{ 
		switch (ROUTE_TYPE) 
		{
			case 1:
			/// http://www.abc.com/index.php?c=admin/index.test&name=11&id=12
			$str = "";
			foreach($array as $key => $val)
			{
				$str.="&".$key."=".$val;
			}
			return WEB_ROOT.SELF_INDEX."?".ROUTE_M."=".$route.$str;
			break;
			
			case 2:
			/// c=admin/index.test/-name-11-id-12
			$pa = "";
			$str = "";
			if(count($array) > 0 ) 
			{
				foreach($array as $key => $val)
				{
					$str.=$key.ROUTE_RWRITE_G.$val.ROUTE_RWRITE_G;
				}
				$str = rtrim($str,ROUTE_RWRITE_G);
				$pa = ROUTE_RWRITE_S.$str;
			}
			$str = rtrim($str,ROUTE_RWRITE_G);
			return WEB_ROOT.$route.$pa.html;
			break;
			case 3:
			/// http://www.abc.com/index.php/index.sina/16/35.shtml
			/// http://www.abc.com/index.php/index.sina/16/35
			$str = "";
			foreach($array as $key => $val) {
				$str.= '/'.$val;
			}
			//echo WEB_ROOT.SELF_INDEX."/".$route.$str;
			if($type == 0) {
				return WEB_ROOT.SELF_INDEX.'/'.$route.$str.html; 
			} else {
				return WEB_ROOT.SELF_INDEX.'/'.$route.$str; 
			}
			default:
		}
	}
	/**
	 * CORE::Get_Request_Route();
	 * 获取路由规则 
	 */ 
	public static function Get_Request_Route() 
	{
		switch (ROUTE_TYPE) {
			case 1:               
		    return Route::makeRoute1();
			break;	
			case 2:
			return Route::makeRoute2();
			break;
			case 3:
			return Route::makeRoute3();
			default :
		}
	} 
	/**
	 * CORE::Lan();
	 * 引用语言包  
	 */ 
	public function Lan($url) {
		$url = ROOT_LAN.'/'.$GLOBALS[V_GLOBAL_NAME]['language'].'/'.$url.EXT_LAN.EXT_FILE;
		if(file_exists($url)) 
		{
			include_once($url);
			TPL::assign($GLOBALS['LAN']);
			return TRUE;
		} else {
			return FALSE;
		}
	}
	/**
	 * CORE::Conf();
	 * 引用配置文件  
	 */ 
	public function Conf($url,$type = 'array') 
	{
		if($type == 'array') 
		{
			$url = ROOT_CONF.'/'.$url.EXT_CONF.EXT_FILE;
			if(file_exists($url)) 
			{
				return include($url);
			} 
		} else if($type == 'json') {
			$url = ROOT_CONF.'/'.$url.EXT_CONF.'.json';
			$str = file_get_contents($url);
			return json_decode($str,TRUE);
		} else if($type == 'xml') {
			$url = ROOT_CONF.'/'.$url.EXT_CONF.'.xml';
			$str = file_get_contents($url);
			$xml = CORE::N('xml/array2xml');
			return $xml->xml2array($str);
		}
	}
	/**
	 * CORE::In();
	 * 调用文件，当$url存在则包含进来，返回true，不存在则返回false
	 */ 
	public static function In($url,$type = 'all') 
	{
		$in['all'] 			= '';
		$in['adp']['dir'] 	= ROOT_ADP;
		$in['adp']['ext'] 	= EXT_ADP.EXT_FILE;
		$in['func']['dir'] 	= ROOT_FUNC;
		$in['func']['ext'] 	= EXT_FUNC.EXT_FILE;
		$in['ctrl']['dir'] 	= ROOT_CTRL;
		$in['ctrl']['ext'] 	= EXT_CTRL.EXT_FILE;
		$in['pls']['dir'] 	= ROOT_PLS;
		$in['pls']['ext'] 	= EXT_PLS.EXT_FILE;
		$in['com']['dir'] 	= ROOT_COM;
		$in['com']['ext'] 	= EXT_COM.EXT_FILE;
		$in['mod']['dir'] 	= ROOT_MOD;
		$in['mod']['ext'] 	= EXT_MOD.EXT_FILE;
		$in['lang']['dir'] 	= ROOT_LAN;
		$in['lang']['ext'] 	= EXT_LAN.EXT_FILE;
		$in['class']['dir'] = ROOT_CLASS;
		$in['class']['ext'] = EXT_CLASS.EXT_FILE;
		$in['tpl']['dir'] 	= ROOT_TPL;
		$in['tpl']['ext'] 	= EXT_TPL.EXT_FILE;
		if($type == 'all') 
		{
		} 
		else 
		{
			if($type == 'lang') 
			{
				$url = $in[$type]['dir'].'/'.$url.'/common'.$in[$type]['ext'];
			} 
			else 
			{
				$url = $in[$type]['dir'].'/'.$url.$in[$type]['ext'];
			}
		}
		if(file_exists($url))
		{	
			include_once($url);
			return TRUE;
		} 
		else 
		{
			return FALSE;
		}
	}
	/**
	 * 执行控制器
	 * CORE::Ctrl($r) 
	 * @param $r  $r为路由的数组
	 */
	public function Ctrl($r) 
	{
		self::Run($r);
	}
	/**
	 * CORE::Run();
	 * 运行入口 
	 * @param $r 运行的数组  
	 * $r = array('dir'=>$dir,'class'=>$class,'function'=>$function,'params'=>$params)
	 * $params为数组
	 */
	 
	public static function Run($r = '') 
	{
		$route = empty($r) ? self::Get_Request_Route() : $r;
		self::In($GLOBALS[V_GLOBAL_NAME]['language'],'lang');
		
		if(self::In($route['dir'].$route['class'],'ctrl'))
		{
			$class = $route['class'].EXT_CTRL;
			$run = new $class;
			if(ENABLE_ACTION_HOOK == TRUE)
			{
				$c = ACTION_BEFORE_PREFIX.$route['function'].ACTION;
				if(method_exists($run,$c))
				{
					$run->$c();
				}
			}
			if(method_exists($run,$route['function'].ACTION)){
				
				foreach($route['params'] as $key =>$val) {
					$route['params'][$key] = "'$val'";
				}
				eval("\$run->".$route['function'].ACTION."(".join(",",$route['params']).");");
				//$run->$route['function'].ACTION();
				//echo "\$run->".$route['function'].ACTION."(".join(",",$route['params']).");";
				//exit;
			} else {
				self::In('error','ctrl');
				errorController::error_methodAction($route['dir'].$class,$route['function'].ACTION);
			}
			if(ENABLE_ACTION_HOOK == TRUE)
			{
				$c = ACTION_AFTER_PREFIX.$route['function'].ACTION;
				if(method_exists($run,$c)){
					$run->$c();
				}
			}
		} else {
			self::In('error','ctrl');
			errorController::error_controllersAction($route['dir'].$route['class'].EXT_CTRL);
		}
	}
	/*
	 * 
	 * 返回内置对象 CORE::V('P:abc') CORE::V('R:abc') CORE::V('G:abc')
	 */
	public static function V($r,$tt = array()) 
	{
		$v_array 	= explode(':',$r);
		$info 		= array('C'=>$_COOKIE,'G'=>$_GET,'P'=>$_POST,'R'=>$_REQUEST,'F'=>$_FILES,'S'=>$_SERVER,'SE'=>$_SESSION,'E'=>$_ENV, '-' => $GLOBALS['__GG'],'t' => $tt);
		if(count($v_array) > 1)
		{
			$vr 	= explode('/',$v_array[1]);
			$str 	= "";
			if(is_array($vr)) 
			{
				foreach($vr as $k => $v)
				{
					$str .= "['".$v."']";
				}
			}
			eval("\$r = @\$info['$v_array[0]']$str;");
		} 
		else 
		{
			$r = $info[$v_array[0]];
		}
		return $r; 
	}
	/**
	 * 
	 * CORE::F($fRoute);
	 * 执行 $fRoute 指定的函数 第二个以及以后的参数 将传递给此函数
	 * 例：CORE::F('test/test',1,2); 表示执行  test/testFunction.php文件的test函,参数第一个值为1 第二个值为2 
	 * @param $fRoute 函数路由，规则与模块规则一样
	 * @return 函数执行结果
	 * 
	 */
	public function F($fRoute) 
	{
		if(empty($fRoute)) 
		{
			exit();
		}
		self::In($fRoute,'func');
		$e = explode('/',$fRoute);
		$count = count($e);
		$p = func_get_args();
		array_shift($p);
		if($count > 0 )
		{
			return call_user_func_array($e[$count-1],$p);
			unset($count,$e,$p);
		} 
		else  
		{
			unset($count,$e,$p);
			return false;
		}
	}
	/**
	 * CORE::Adp ($name, $is_single = FALSE, $cfg = FALSE);
	 * 根据配置，获取一个适配器实例，使用配置信息初始化
	 * @param $name			适配器名称如： db 类型由配置文件中确定
	 * @param $is_single	是否获取单例
	 * @param $cfg			初始化此适配器的配置数据，默认从配置中取
	 * @return 相应的适配器实例
	 */
	public function Adp($name, $is_single = FALSE,$cfg = FALSE)
	{
		self::In($name."/".$GLOBALS[V_GLOBAL_NAME]['adapter'][$name]['type'],'adp');
		$adp = $GLOBALS[V_GLOBAL_NAME]['adapter'][$name]['type'].EXT_ADP;
		
		//echo "\$adp = ".$adp."::getInstance();";
		//self::In($name."/".$GLOBALS[V_GLOBAL_NAME]['adapter'][$name]['type'],'adp');
		//exit;
		
		if($is_single == FALSE) 
		{
			$adp = new $adp;
		} 
		else 
		{	
			eval("\$adp = ".$adp."::getInstance();");
		}
		return $adp;
	}
	/**
	 * 
	 *  CORE::N('cookies/cookies',100)
	 * @param $oRoute
	 */
	public function N($oRoute)
	{
		$retClass = FALSE;
		$p = func_get_args();
		$count = count($p);
		$p2 = $p;
		if($count==0)
		{
			return FALSE;
		} 
		else 
		{
			$n_array 	= explode("/",$p[0]);
			$classname = !empty($n_array[count($n_array)-1]) ? $n_array[count($n_array)-1] : $n_array[0];
			
			$include = ROOT_CLASS."/".$oRoute.EXT_CLASS.EXT_FILE;
			if(self::In($include)) 
			{
				if($count==1) 
				{
					$classname = $classname.EXT_CLASS;
					$c = new $classname;
					return $c;
				}
				if($count > 1)
				{
					array_shift($p2);
					$prm = array();
					foreach($p2 as $i=>$v)
					{
						$prm[] = "\$p2[".$i."]";
					}
					eval("\$retClass =  new ".$classname." (".implode(",",$prm).");");
					return $retClass;
				}	
			}
		}
	}
	/**
	 * 
	 * CORE::M($oRoute);
	 * 根据类路由 和 类初始化参数获取一个模块类实例
	 * 第二个以及以后的参数 将传递给类的构造函数
	 * 如： CORE::M('test/classname','a','b'); 实例化时执行的是 new classname('a','b');
	 * @param $oRoute 类路由，规则与模块规则一样
	 * @return 类实例
	 * 
	 */
	public function M($oRoute)
	{
		$retClass = FALSE;
		$p = func_get_args();
		$count = count($p);
		$p2 = $p;
		if($count==0)
		{
			return FALSE;
		} 
		else 
		{
			$n_array 	= explode("/",$p[0]);
			$classname = !empty($n_array[count($n_array)-1]) ? $n_array[count($n_array)-1] : $n_array[0];
			$include = ROOT_MOD."/".$oRoute.EXT_MOD.EXT_FILE;		
			if(self::In($include)) 
			{
				if($count == 1) 
				{
					$classname = $classname.EXT_MOD;
					$c = new $classname;
					return $c;
				}
				if($count > 1) {
					array_shift($p2);
					$prm = array();
					foreach($p2 as $i=>$v){
						$prm[] = "\$p2[".$i."]";
					}
					eval("\$retClass = new ".$classname." (".implode(",",$prm).");");
					return $retClass;
				}	
			}
		}
	}
	/**
	 * 
	 * CORE::C($oRoute);
	 * 根据类路由 和 类初始化参数获取一个模块类实例
	 * 第二个以及以后的参数 将传递给类的构造函数
	 * 如： CORE::C('test/classname','a','b'); 实例化时执行的是 new classname('a','b');
	 * @param $oRoute 类路由，规则与模块规则一样
	 * @return 类实例
	 * 
	 */
	public function C($oRoute)
	{
		$retClass = false;
		$p = func_get_args();
		$count = count($p);
		$p2 = $p;
		if($count==0){
			return false;
		} else {
			$n_array 	= explode("/",$p[0]);
			$classname = !empty($n_array[count($n_array)-1]) ? $n_array[count($n_array)-1] : $n_array[0];
			$include = ROOT_CTRL."/".$oRoute.EXT_CTRL;
			
			if(self::In($include)) {
				if($count==1) {
					$c = new $classname;
					return $c;
				}
				if($count > 1) {
					array_shift($p2);
					$prm = array();
					foreach($p2 as $i=>$v){
						$prm[] = "\$p2[".$i."]";
					}
					eval("\$retClass = new ".$classname." (".implode(",",$prm).");");
					return $retClass;
				}	
			}
		}
	}
	/// 路由分析器 $ro为当前路由规则,$e为排除的规则,$rr为运行的路由
	public function Route($ro = '',$e = '',$rr = array()) 
	{
		$r = self::Get_Request_Route();
		if(preg_match("/$ro/is",$r['c']) && !preg_match("/$e/is",$r['c'])) 
		{
			self::Ctrl($rr);
		}
	}
	/**
	* 
	*初使化加载方法 
	*/ 
	static function add_fun()
	{
		self::In('fun','func');		//初使化加载方法库
	}
}
function M($oRoute) 
{
	CORE::M($oRoute);
}
function N($oRoute) 
{
	CORE::N($oRoute);
}
function Lan($oRoute) 
{
	CORE::Lan($oRoute);
}
CORE::add_fun(); ///初使加载方法库


?>
