<?php
/**
 * @file			cfg.php
 * @CopyRright (c)	1996-2099 zhangzhihe 
 * @Project			鲜橙网 
 * @Author			张志合 <coolkingzhang@163.com>
 * @version 		1.0
 * @Create Date：   	2011-12-15
 * @Modified By：   	张志合/2011-12-15
 * @Brief			框架配置类 系统基本优化配置,设制度配置
 */
if (!defined('IN_HF')) 
{
    die('Hacking attempt');
}

/// ini_set初始化设置 

///ini_set('session.auto_start','0');
//ini_set('session.cookie_domain', '.myorange.cn');
//$session_save_path = "tcp://192.168.2.31:12000?persistent=1&weight=2&timeout=2&retry_interval=10,tcp://192.168.2.31:12001?persistent=1&weight=2&timeout=2&retry_interval=10";
//ini_set('session.save_handler', 'memcache');
//ini_set('session.save_path', $session_save_path);

/* 
ini_set('memory_limit',          '100M');
ini_set('session.cache_expire',  180);
ini_set('session.use_trans_sid', 0);
ini_set('session.use_cookies',   1);
ini_set('session.auto_start',    0);
ini_set('display_errors',        1);

*/

/// 是否在系统初始化的时候执行 session_start(); 
define('IS_SESSION_START', TRUE);
if (defined('IS_SESSION_START') && IS_SESSION_START)
{
	session_start();
}
/// 是不是启动调试 根据调试状态打开错误信息

if (!defined('IS_DEBUG')) 
{
   define('IS_DEBUG', '1');	  
}
if (defined('IS_DEBUG') && IS_DEBUG)
{
	if (version_compare(PHP_VERSION,'5.0','>='))
	{
		error_reporting(E_ALL &~ E_STRICT);	
	}
	else
	{
		error_reporting(E_ALL);	
	}
	ini_set('display_errors', 1);
}
else
{
	error_reporting(0); //? E_ERROR | E_WARNING | E_PARSE
	ini_set('display_errors', 0);
}

/// php环境变量长短名 
/*
if(PHP_VERSION <'4.1.0') 
{
	$_GET	= &$HTTP_GET_VARS;
	$_POST 	= &$HTTP_POST_VARS;
	$_COOKIE= &$HTTP_COOKIE_VARS;
	$_SERVER= &$HTTP_SERVER_VARS;
	$_ENV 	= &$HTTP_ENV_VARS;
	$_FILES = &$HTTP_POST_FILES;
}*/

/// 设置时区 设置为东 8区 

define('APP_TIMEZONE_OFFSET',8);
if(function_exists('date_default_timezone_set')) 
{
	date_default_timezone_set('Etc/GMT'.(APP_TIMEZONE_OFFSET > 0 ? '-' : '+').(abs(APP_TIMEZONE_OFFSET)));
} 
else 
{
	putenv('Etc/GMT'.(APP_TIMEZONE_OFFSET > 0 ? '-' : '+').(abs(APP_TIMEZONE_OFFSET)));
}

/// 取得 $_POST 数据

/*
if( (function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc()) || ini_get('magic_quotes_sybase') ) {	if(count($_POST) > 0) {
		foreach($_POST as $key => $val){
			if(!is_array($val)) {
				$_POST[$key] = stripslashes($val);
				//addslashes
			}
		}
	}
}
*/

/*
 * 对表单内容进行实体化
 */
foreach($_POST as $key => $val)
{
	if(!is_array($val)) 
	{
		$_POST[$key] = htmlspecialchars($val);
		///htmlspecialchars_decode
	}
}

/// 验证当前 路径
if (__FILE__== '') 
{
    die('Fatal error code: 0');
}

/// 设置 include_path
if (DIRECTORY_SEPARATOR == '\\') 
{
	// 如果是在win下
    @ini_set('include_path', '.;' . ROOT);
    @set_include_path(OPENSDK_DIR);
}
else 
{
    @ini_set('include_path', '.:' . ROOT);
    @set_include_path(OPENSDK_DIR);
}


/// 是否启用模块 Action hook 
define('ENABLE_ACTION_HOOK',	TRUE);

///  后置模块HOOK 前缀 ， ACTION_AFTER_PREFIX+模块方法名 命名的成员方法 将在模块执行完成后被执行 
define('ACTION_BEFORE_PREFIX',	"_before_");
define('ACTION_AFTER_PREFIX',	"_after_");

/// 控制器方法后缀
define('ACTION','Action');
/// 文件扩展名
define('EXT_FILE','.php');

/// 配置文件扩展名
define('EXT_CONF',			"Conf");
/// 适配器文件扩展名
define('EXT_ADP',			"Adapter");
/// 扩展函数文件扩展名
define('EXT_FUNC',			"Function");
/// 控制器文件扩展名
define('EXT_CTRL',			"Controller");
/// 扩展类文件扩展名
define('EXT_CLASS',			"Class");
/// 系统模块文件扩展名
define('EXT_MOD',			"Model");
/// 系统语言文件扩展名
define('EXT_LAN',			"Lan");
/// 系统模板文件扩展名
define('EXT_TPL',			"Tpl");
/// 数据组件文件扩展名
define('EXT_COM',			"Com");
/// pagelets 组件文件扩展名
define('EXT_PLS',			"Pls");
/// 缓存文件扩展名
define('EXT_CACHE',			"Cache");
/// 配置文件的存放目录
define('ROOT_CONF',			APP.'/config');
/// 语言包存放目录
define('ROOT_LAN',			APP.'/language');
/// 函数存放目录
define('ROOT_FUNC',			APP.'/function');
/// 类文件的存放目录
define('ROOT_CLASS',		APP.'/class');
/// controllers 控制器目录
define('ROOT_CTRL',			APP.'/controllers');
/// adapter 配置器
define('ROOT_ADP',			APP.'/adapter');
/// pagelets 块
define('ROOT_PLS',			APP.'/pagelets');
/// modules 模块
define('ROOT_MOD',			APP.'/modules');
/// modules 模块
define('ROOT_COM',			APP.'/com');


/// 系统皮肤文件的存放目录
define('ROOT_TEMPLATES', 		ROOT.'/templates');
/// 模板模块目录
define('ROOT_TEMPLATES_COM',	ROOT."/templates/modules");
/// 默认模板目录
define('TEMPLATES_DEFAULT',	"default");
define('TEMPLATES_DEFAULT_ROOT',ROOT_TEMPLATES."/".TEMPLATES_DEFAULT);
define('ROOT_TPL',TEMPLATES_DEFAULT_ROOT);
/// 后台管理模板目录
define('TEMPLATES_ADMIN',	"admin");


/// 缓存目录
define('CACHE_ROOT',ROOT.'/var/cache');

/// var 目录
define('VAR_ROOT',ROOT.'/var');

/// tmp 目录
define('VAR_TMP',ROOT.'/var/tmp');
/// face目录
define('VAR_FACE',ROOT.'/var/face');

/// user目录
define('VAR_USER',ROOT.'/var/user');

/// 路由配置定义路由类型      1 : 常规带参数    2 : rewrite 方式    3 $_SERVER['PATH_INFO']
define('ROUTE_TYPE', '1'); 
define('ROUTE_M', 'c');
define('ROUTE_S', '.');
 
/// 定义路由类型 当为rewrite时
define('ROUTE_RWRITE_S', '/');
define('ROUTE_RWRITE_G', '-');

/// 路由默认控制器里的函数
define('ROUTE_FUNCTION', 'init');
/// 路由默认控制器类
define('ROUTE_CLASS',	'index');

/// 默认html后缀
define('html','.html'); 

?>