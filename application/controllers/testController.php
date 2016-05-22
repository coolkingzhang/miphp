<?php
/*
 * test Controller
 */
class testController extends CTRL_BASE 
{
	public function initAction() 
	{
		echo 'this is test pagef';
		TPL::display("test/index");

	} 
}
