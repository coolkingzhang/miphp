<?php
/**
 * 错误控制类
 * @author zhangzhihe
 * @version 1.0
 * @updated 28-八月-2011 13:35:19
 */
class errorController 
{
	/**
	 * 是否启动代码
	 */
	var $is_show = 1;
	/**
	 * 当控制器不存在的时候print出错误控制器方法
	 * 
	 * @param c
	 */
	public function error_controllersAction($c = '')
	{
		echo "error This <font color=red>$c</font> controllers is null";
	}
	/**
	 * 当控制器方法不存在的时候print 出控制器方法
	 * 
	 * @param c    控制器方法
	 * @param m    fdsfdfsd
	 */
	public function error_methodAction($c = '' ,$m = '') 
	{
		echo "$c controllers <font color=red>".$m."</font> methos is null";
	}
	/**
	 * 当视图不存在的时候print 出视图
	 * 
	 * @param tpl 模板文件名
	 */
	public function error_tplAction($tpl)
	{
		echo "this  <font color=red>$tpl</font>"." is  null ";
	}

	public function is_errorAction()
	{	
		
	}
}
?>