<?php
/**
 * @file			/application/init.php
 * @CopyRright (c)	1996-2099 zhangzhihe 
 * @Project			鲜橙网 
 * @Author			张志合 <coolkingzhang@163.com>
 * @version 		1.0
 * @Create Date：   	2011-12-15
 * @Modified By：   	张志合/2011-12-15
 * @Brief			初始化文件
 */

/// 定义网站权限标识
define('IN_HF',TRUE);

/// 调用常用定义
require(APP.'/define.php');

/// 调用基础模型类
require(APP.'/mod.base.php');

/// 调用基础控制器类
require(APP.'/ctrl.base.php');

/// 调用配置文件 
require(APP.'/config.php');

/// 调用框架核心配置文件
require(APP.'/cfg.php');

/// 调用路由类 
require(APP.'/Route.php');

/// 调用框架核心类库
require(APP.'/core.php');

/// 调用模板框架类
require(APP.'/TPL.php');

/// 调用io操作类 
require(APP.'/io.php');

///调用身份验证类
require(APP.'/acc_controller.php');

///调用数据库表定义
require(APP.'/db.def.php');

?>
