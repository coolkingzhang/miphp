<?php
/*
 * api 通讯接口类
 */
class apiClass implements api_interface 
{
	protected $socket;
	protected $xml_player;
	protected $xml;
	protected $root 		= 'root';			//xml的根目录名
	protected $record 		= 'record';			//xml的记录集名字
	protected $access_token = '';				//通行证
	public function __construct()
	{    	
		$this->socket  		= CORE::N('socket/php_socket');
		$this->player 		= CORE::N('xml/array2xml');  
		$this->access_token = 'ce27556090c220c220f3c46136f090b7';
	}
	/*
	* 返回取的数据
	*/
	protected function return_data($data)
	{
		$message 		= $this->player->toXml($data);
		//echo $message;	
		$xml 			= $this->socket->wr($message);			     
		if(!empty($xml)) 
		{
			$root 		= $this->player->xml2array($xml);
			$return  	= $root[$this->root];
			if(@count($return['record']) > 0  && @!is_array($return['record'][0])) 
			{
				$list = $return['record'];
				$return['record']  = array();
 				$return['record'][0] = $list;
 			}
 			return $return;
		}
		else{
			return FALSE;
		}   
		//$this->socket->close();	///关闭socket
	}
}

/*
 * api 接口类
 */

interface api_interface {
	protected function return_data($data);
}
?>
