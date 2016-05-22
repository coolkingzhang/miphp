<?php
if (!defined('IN_HF')) 
{
    die('Hacking attempt');
}
/**
 * @file			config.php
 * @CopyRright (c)	1996-2099 zhangzhihe 
 * @Project			鲜橙网 
 * @Author			张志合 <coolkingzhang@163.com>
 * @version 		1.0
 * @Create Date：   	2011-12-15
 * @Brief			框架配置文件
 */

/// 构架初始化的配置全局变量
define('V_GLOBAL_NAME','__GG'); 

//后台用户权cookies 和 session 名字
define('ADMIN','admin');

//用户 用户权cookies 和 session 名字
define('USER','user');

///新浪短连接网名,orange网名
define('SINA_WEBNAME','http://t.cn/');
define('ORANGE_WEBNAME','http://myorange.cn/');

/// 静态资源url
$GLOBALS[V_GLOBAL_NAME]['skip'] = 'default';

define('STATIC_JS'		,'http://www.myorange.cn/js');
define('STATIC_IMAGES'	,'http://www.myorange.cn/images');
define('STATIC_FLASH'	,'http://www.myorange.cn/flash');
define('STATIC_CSS'		,'http://www.myorange.cn/css/'.$GLOBALS[V_GLOBAL_NAME]['skip']);

///xheditor编辑器
define('FCK','http://www.myorange.cn/application/plugins/xheditor/xheditor-1.1.13-zh-cn.min.js');
define('FCK_ROOT','http://www.myorange.cn/application/plugins/fckeditor/');
//define('FCK_ROOT','/fckeditor/');


///用户的头像url
define('USER_FACE','http://user.myorange.cn/face');

/// 用户头像默认 50x50
define('STATIC_IMAGES_FACE_ERROR',STATIC_IMAGES.'/face/50x50.png');
/// 活动默认缩略图片
define('STATIC_IMAGES_ACT_ERROR',STATIC_IMAGES.'/face/act_img.jpg');

/*
 * 模板设置
 */

$GLOBALS[V_GLOBAL_NAME]['adapter']['tpl']['type'] = 'TPL';
$GLOBALS[V_GLOBAL_NAME]['TPL'] = array();
/*
 * memcache设置
 */
$GLOBALS[V_GLOBAL_NAME]['adapter']['cache']['type'] = 'memcache';
$GLOBALS[V_GLOBAL_NAME]['adapter']['cache']['memcache']['server'][0] = array('host'=>'192.168.2.31', 'port'=>12000,'persistent'=> TRUE, 'weight'=>1,'timeout'=> 1,'retry_interval'=> 15,'status'=> TRUE);
$GLOBALS[V_GLOBAL_NAME]['adapter']['cache']['memcache']['server'][1] = array('host'=>'192.168.2.31', 'port'=>12001,'persistent'=> TRUE, 'weight'=>1,'timeout'=> 1,'retry_interval'=> 15,'status'=> TRUE);
/*
 * 语言设置 
 */
$GLOBALS[V_GLOBAL_NAME]['language'] = 'gb2312';

///定义opensdk根目录
//define("OPENSDK_DIR",dirname(__FILE__)."/plugins/opensdk/lib/");

/*
 * 163.com 邮箱发送接口
 */
define('EMAIL_USERNAME','');
define('EMAIL_PWD','');
define('EMAIL_SMTP','');
/*
 * 新浪oauth1.0 appkey接口
 */
define( "WB_AKEY_SINA" , '' );
define( "WB_SKEY_SINA" , '' );
define( "WB_CALLBACK_URL_SINA" , 'http://www.myorange.cn/index.php?c=bind.sina_callback' );
/*
 * 腾讯oauth1.0 appkey接口
 */
define( "WB_AKEY_TX" , '' );
define( "WB_SKEY_TX" , '' );
define( "WB_CALLBACK_URL_TX" , 'http://www.myorange.cn/index.php?c=bind.tx_callback' );

/*
 * http 请求接口
 */
$GLOBALS[V_GLOBAL_NAME]['adapter']['http']['type'] 				= 'curl_http';
/*
 * socket 请求接口
 */
$GLOBALS[V_GLOBAL_NAME]['adapter']['socket'][0] = array('host'=>'192.168.2.252','port'=>'35678');
//$GLOBALS[V_GLOBAL_NAME]['adapter']['socket'][1] = array('host'=>'192.168.2.250','port'=>'2222');

/// mysql 账号密码相关设置
$GLOBALS[V_GLOBAL_NAME]['adapter']['db'] = array();
///$GLOBALS[V_GLOBAL_NAME]['adapter']['db']['type'] 			= 'sqlite';
$GLOBALS[V_GLOBAL_NAME]['adapter']['db']['type'] 				= 'mysqli'; 
$GLOBALS[V_GLOBAL_NAME]['adapter']['db']['mysql']['read'][0] 	= array(
	'hostname'=>'192.168.1.6',
	'username'=>'root',
	'password'=>123456,
	'database'=>'kzj_mall',
	'dbdriver'=>'mysql',
	'dbprefix'=>'',
	'pconnect'=>FALSE,
	'db_debug'=>TRUE,
	'cache_on'=>FALSE,
	'cachedir'=>'',
	'char_set'=>'utf8',
	'dbcollat'=>'utf8_general_ci'
);
$GLOBALS[V_GLOBAL_NAME]['adapter']['db']['mysql']['read'][1] = array(
	'hostname'=>'192.168.1.6',
	'username'=>'root',
	'password'=>123456,
	'database'=>'kzj_mall',
	'dbdriver'=>'mysql',
	'dbprefix'=>'',
	'pconnect'=>FALSE,
	'db_debug'=>TRUE,
	'cache_on'=>FALSE,
	'cachedir'=>'',
	'char_set'=>'utf8',
	'dbcollat'=>'utf8_general_ci'
);
$GLOBALS[V_GLOBAL_NAME]['adapter']['db']['mysql']['write'][0] = array(
	'hostname'=>'192.168.1.6',
	'username'=>'root',
	'password'=>123456,
	'database'=>'kzj_mall',
	'dbdriver'=>'mysql',
	'dbprefix'=>'',
	'pconnect'=>FALSE,
	'db_debug'=>TRUE,
	'cache_on'=>FALSE,
	'cachedir'=>'',
	'char_set'=>'utf8',
	'dbcollat'=>'utf8_general_ci'
);
/// sqlite 配置
$GLOBALS[V_GLOBAL_NAME]['adapter']['db']['sqlite']['src'] = ROOT."/test.db";
?>