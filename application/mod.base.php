<?php
/**
 * @file			mod.base.php
 * @CopyRright (c)	1996-2099 zhangzhihe 
 * @Project			鲜橙网 
 * @Author			张志合 <coolkingzhang@163.com>
 * @version 		1.0
 * @Create Date：   	2011-12-15
 * @Modified By：   	张志合/2011-12-15
 * @Brief			模型基础类
 */

if (!defined('IN_HF'))
{
    die('Hacking attempt');
}
/**
 * 模型基础类 MOD_BASE
 */
class MOD_BASE implements mod_interface 
{
	/** 
	 * function Getdb()
	 * 返回数据库操作类
	 * @param string
	 */	
	public function Getdb() 
	{
		return CORE::Adp('db');
	}
	/** 
	 * function Getcache()
	 * 返回cache操作类
	 * @param string
	 */	
	public function Getcache() 
	{
		return CORE::Adp('cache');
	}
}


/*
 * 模型接口类  实际上接口类说白了，就是一个类的模板,一个清单 
 * 
 * PHP 类是单继承，也就是不支持多继承，当一个类需要多个类的功能时，继承就无能为力了
 * 为此 PHP 引入了类的接口技术。
如果一个抽象类里面的所有方法都是抽象方法，且没有声明变量，而且接口里面所有的成员都是 public 权限的
那么这种特殊的抽象类就叫 接口 。
接口使用关键字 interface 来定义，并使用关键字 implements 来实现接口中的方法，且必须完全实现。 
 */

interface mod_interface 
{
	public function Getcache(); 
	public function Getdb(); 
}
?>