<?php
class pregClass 
{	
	function is_code($str){
	//检查验证码
		if(strlen($str)==4) {
			return TRUE;
		} else {
			return FALSE;
		}
	} 
	function is_user($str){
	//检验用户名
		return preg_match("/^([0-9a-zA-Z_]+)$/", $str);
	}
	function is_email($str){
	//检验email
		return preg_match("/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/", $str);
	}
	function is_url($str){
	//检验网址
		return preg_match("/^http:\/\/[A-Za-z0-9]+\.[A-Za-z0-9]+[\/=\?%\-&_~`@[\]\’:+!]*([^<>\"])*$/", $str);
	}
	function is_qq($str){
	//检验qq
		return preg_match("/^[1-9]\d{4,9}$/", $str);
	}
	function is_zip($str){
	//检验邮编
		return preg_match("/^[1-9]\d{5}$/", $str);
	}
	function is_idcard($str){
	//检验身份证
		return preg_match("/^\d{15}(\d{2}[A-Za-z0-9])?$/", $str);
	}
	function is_chinese($str){
	//检验是否是中文
		return ereg("^[".chr(0xa1)."-".chr(0xff)."]+$",$str);
	}
	function is_english($str){
	//检验是否是英文
		return preg_match("/^[A-Za-z]+$/", $str);
	}
	function is_mobile($str){
	//检验是否是手机
		return preg_match("/^((\(\d{3}\))|(\d{3}\-))?1\d{10}$/", $str);
	}
	function is_phone($str){
	//检验是否为电话
		return preg_match("/^((\(\d{3}\))|(\d{3}\-))?(\(0\d{2,3}\)|0\d{2,3}-)?[1-9]\d{6,7}$/", $str);
	}
	function is_safe($str){
		return (preg_match("/^(([A-Z]*|[a-z]*|\d*|[-_\~!@#\$%\^&\*\.\(\)\[\]\{\}<>\?\\\/\’\"]*)|.{0,5})$|\s/", $str) != 0);
	}
}
?>