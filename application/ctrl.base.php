<?php

if (!defined('IN_HF'))
{
    die('Hacking attempt');
}

/**
 * @file			index.php
 * @CopyRright (c)	1996-2099 zhangzhihe 
 * @Project			鲜橙网 
 * @Author			张志合 <coolkingzhang@163.com>
 * @version 		1.0
 * @Create Date：   	2011-12-15
 * @Modified By：   	张志合/2011-12-15
 * @Brief			控制器基础父类
 */

class CTRL_BASE implements ctrl_interface 
{
	function __construct() 
	{ 
	}
	public function Gethttp() 
	{
		return CORE::Adp('http');
	}
	public function Getdb() 
	{
		return CORE::Adp('db');
	}
	public function Getcache() 
	{
		return CORE::Adp('cache');
	}
	public function Getparms() 
	{
		$p = CORE::Get_Request_Route();
		return $p['params'];
	}
	public function Getroute() 
	{
		$r = CORE::Get_Request_Route();
		return $r;
	}
	public function Tpl() 
	{
		return CORE::Adp('tpl');
	}
}


interface ctrl_interface 
{ 
	public function Gethttp(); 
	public function Getdb(); 
	public function Getcache();
	public function Getparms(); 
	public function Getroute();
	public function Tpl();
}
?>