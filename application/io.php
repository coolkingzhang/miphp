<?php
/**
 * @file			io.php
 * @CopyRright (c)	1996-2099 zhangzhihe 
 * @Project			鲜橙网 
 * @Author			张志合 <coolkingzhang@163.com>
 * @version 		1.0
 * @Create Date：   	2011-12-15
 * @Modified By：   	张志合/2011-12-15
 * @Brief			io操作类
 */
class IO
{
	public function writefile($path,$body)   
    {   
		self::createDir(dirname($path));    
		$handle = fopen($path,'w');    
		fwrite($handle,$body);    
		fclose($handle);   
		return TRUE;   
	}   
	public function createDir($path)
	{    
		if (!file_exists($path))
		{    
			self::createDir(dirname($path));    
			mkdir($path, 0777);    
		}    
	}    
	public function file_del($file_name) 
	{ 
		if(is_file($file_name)) 
		{ 
			@unlink($file_name); 
			return FALSE;
		} 
		else {
			return TRUE;
		}
	} 
	public function myfopen($file_add) 
	{
		$file=@fopen($file_add,"r");
		while(!feof($file)) 
		{
			$body.=fgets($file,4096);	
	    }
		return $body; 
	} 
	public function mkdirs($dir) 
	{
		if(!is_dir($dir)) 
		{
			@mkdir($dir);
			return TRUE;
		}
	}
}
?>
