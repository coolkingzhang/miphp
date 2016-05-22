<?php
/**
 * @file			index.php
 * @CopyRright (c)	1996-2099 zhangzhihe 
 * @Project			鲜橙网 
 * @Author			张志合 <coolkingzhang@163.com>
 * @version 		1.0
 * @Create Date：   	2011-12-15
 * @Modified By：   	张志合/2011-12-15
 * @Brief			网站的单入口文件 
 */

/// 定义程序入口路径    
define('ROOT', dirname(__FILE__));
/// 定义application入口路径 
define('APP', ROOT . '/application');
/// 当前控制器入口文件
define('SELF_INDEX' , 'index.php');
require(APP . '/init.php');
//CORE::route("^user/home\.*","index\.(test)", array('params'=>array(), 'class'=>'index','function'=>'gotobaidu','dir' => ''));
CORE::Run();