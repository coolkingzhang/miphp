<?php

/**
 * @file			TPL.php
 * @CopyRright (c)	1996-2099 zhangzhihe 
 * @Project			鲜橙网 
 * @Author			张志合 <coolkingzhang@163.com>
 * @version 		1.0
 * @Create Date：   	2011-12-15
 * @Modified By：   	张志合/2011-12-15
 * @Brief			模块tpl文件 
 */
define('CACHE_TYPE','redis');
class TPL 
{	
	/**
	 * TPL::reset();
	 * 重置模板变量列表
	 * @return 无返回值
	 */
	public function reset() 
	{
		$GLOBALS[V_GLOBAL_NAME]['TPL'] = array();
	}
	/**
	 * TPL::assign($k,$v=null);
	 * 给模板变量赋值，类似SMARTY
	 * 使用实例：
	 * TPL::assign('var_name1','var'); 在模板中可以使用  $var_name1 变量
	 * TPL::assign(array('var_name2'=>'var')); 在模板中可以使用  $var_name2 变量
	 * @param $k	当  $k 为字串时 在模板中 可使用以 $k 命名的变量 其值 为 $v
	 * @param 		当  $k 为关联数组时 在模板中可以使用 $k 的所有索引为变量名的变量
	 * @param $v	当  $k 为字符串时 其值 即为 模板中 以  $k 为名的变量的值
	 * @return 无返回值
	 */
	public function assign($k,$v=null)
	{
		if ( !isset($GLOBALS[V_GLOBAL_NAME]['TPL']) || !is_array($GLOBALS[V_GLOBAL_NAME]['TPL']) ) 
		{
			$GLOBALS[V_GLOBAL_NAME]['TPL'] = array();
		}
		if (!is_array($k))
		{
			$GLOBALS[V_GLOBAL_NAME]['TPL'][$k] = $v;
		}
		else
		{
			TPL::assignExtract($k);
		}
	}
	public function get($k) 
	{
		return @$GLOBALS[V_GLOBAL_NAME]['TPL'][$k];
	}
	public function LO($params = '') 
	{
		$p = func_get_args();
		$kk = $p[0];
		array_shift($p);
		if(is_array($p)) 
		{
			foreach($p as $k => $v) 
			{
				$p[$k] = "'".$v."'";
			}
		}
		$pa = implode(',',$p);
		if(count($p) > 0) 
		{
			eval("printf(\$GLOBALS[V_GLOBAL_NAME]['TPL'][\$kk],$pa);");
			unset($pa);
			unset($p);
			unset($kk);
		} 
		else 
		{
			eval("printf(\$GLOBALS[V_GLOBAL_NAME]['TPL'][\$kk]);");
			unset($pa);
			unset($p);
			unset($kk);
		}
	}
	
	/**
	 * TPL::assignExtract($data);
	 * 给模板变量赋值
	 * @param $data	关联数组
	 * @return 无返回值
	 */
	public function assignExtract($data)
	{
		if ( !isset($GLOBALS[V_GLOBAL_NAME]['TPL']) || !is_array($GLOBALS[V_GLOBAL_NAME]['TPL']) ) 
		{
			$GLOBALS[V_GLOBAL_NAME]['TPL'] = array();
		}
		foreach($data as $k => $v)
		{
			$GLOBALS[V_GLOBAL_NAME]['TPL'][$k] = $v;
		}
	}
	public function exists($_tpl,$type = CACHE_TYPE) 
	{
		$file = md5($_tpl);
		$file = substr($file,0,2).'/'.substr($file,2,2).'/'.substr($file,4,2).'/'.$file;
		$cache_name = CACHE_ROOT.'/'.$file.EXT_TPL.EXT_FILE;
		if($type == 'file') 
		{
			if(file_exists($cache_name))
			{
				return TRUE;
			} else {
				return FALSE;
			}
		} 
		elseif($type == 'redis') 
		{
			$redis = new Redis();
			$redis->connect('192.168.1.6', 6379, 2); // 2 sec timeout.
			if($redis->exists($_tpl)) 
			{
				return TRUE;
			}
			else 
			{
				return FALSE;
			}
		}
	}
	/**
	 * display($_tpl,  $cacheing = FALSE,$_langs=array(),$_ttl=0,$_baseSkin = TRUE)
	 * 用法如                               TPL::display('index',FALSE,300);
	 * 显示一个模板
	 * @param $_tpl		模板路由
	 * @param $_ttl		缓存时间 单位秒 （ 未实现 ）
	 * @param $cacheing 是否启动 cache
	 * @param $type     缓冲的类型 
	 * @return 无返回值
	 */
	public static function display($_tpl,$cacheing = FALSE,$type = CACHE_TYPE,$_ttl = 10)
	{
		$file = md5($_tpl);
		$file = substr($file,0,2).'/'.substr($file,2,2).'/'.substr($file,4,2).'/'.$file;
		$cache_name = CACHE_ROOT.'/'.$file.EXT_TPL.EXT_FILE;
		/*
		 * 如果不启动cache 则直接include　模板
		 */
		if($cacheing == FALSE)
		{
			CORE::IN($_tpl,'tpl');
		}
		/*
		 * 如果启动模板则引用cache
		 */ 
		else 
		{	
			if($type == 'file' ) 
			{
				$limit = microtime(true) - @filemtime($cache_name) - $_ttl;
				if(!file_exists($cache_name) || $limit > 0)
				{
					ob_start();
					CORE::IN($_tpl,'tpl');
					$str = ob_get_contents();
					ob_end_clean();
					IO::writefile($cache_name , $str);
					echo $str;
				} 
				else 
				{
					CORE::IN($cache_name);
				}
			} 
			elseif($type == 'redis') 
			{	
				$redis = new Redis();
				$redis->connect('192.168.1.6', 6379, 2); // 2 sec timeout.
				/*
				 * redis cache
				 */	
				if($redis->exists($_tpl))
				{
					echo $redis->get($_tpl);
				} 
				else 
				{
					ob_start();
					CORE::IN($_tpl,'tpl');
					$str = ob_get_contents();
					ob_end_clean();
					$redis->setex($_tpl ,$_ttl, $str); 
					echo $str;
				}
			}
		}
	}
	/**
	 * TPL::fetch($_tpl,$cacheing = FALSE);
	 * 获取一个模板解释完后的内容
	 * @param $tpl		模板路由
	 * @return 模板解释完后的内容，字符串
	 */
	public function fetch($_tpl,$cacheing = FALSE)
	{
		ob_start();
		self::display($_tpl);
		$data = ob_get_clean();
		return $data;
	}
	/*
	 * 调用
	 * //TPL::ctrl('user/','home','action',array(10,20));
	 */
	public function ctrl($dir = '',$class = '',$function = '',$params = array()) 
	{
		CORE::RUN(array('dir'=>$dir,'class'=>$class,'function'=>$function,'params'=>$params));
	}
}
?>