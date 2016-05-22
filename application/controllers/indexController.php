<?php

/* 
 * 网站默认控制器  index
 */
class indexController extends CTRL_BASE 
{	
	/*
	public function _before_initAction() 
	{
		echo 'this is _before_ Action<br/>';
	}
	public function _after_initAction() 
	{
		echo '<Br/>this is _after_ Action';
	}
	*/

	public function initAction() 
	{
		echo 'Hello world!';
		//self::testAction();
		TPL::display("indexTpl.php");
	}
	public function testAction()
	{
		echo 'Hello world!ff';
		//self::testAction();
		TPL::display("test");
	}
}