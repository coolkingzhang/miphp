<?php

/*
 * php_socke操作类
 */
class php_socketClass 
{
    private $host;				//连接socket的主机
    private $port;				//socket的端口号
    private $error = array();
    private $socket = null; 	//socket的连接标识
    private $queryStr = "";		//发送的数据
    public function __construct($host = '',$port = '') 
    {
        if(!extension_loaded("sockets"))
        {
        	exit("请打开socket扩展 ");
        }
        //if(empty($host)) 						exit("请输入目标地址");
        //if(empty($port)) 						exit("请输入有效的端口号");
        $socket_list 	= $GLOBALS[V_GLOBAL_NAME]['adapter']['socket'];
        $max 			= count($socket_list) - 1;
        $socket_me 		= rand(0,$max);
        $this->host 	= $GLOBALS[V_GLOBAL_NAME]['adapter']['socket'][$socket_me]['host'];
        $this->port 	= $GLOBALS[V_GLOBAL_NAME]['adapter']['socket'][$socket_me]['port'];
        $this->CreateSocket();					//创建连接    
    }
   	/// CreateSocket()	创建socket
   	
    private function CreateSocket() 
    {
        $this->socket=socket_create(AF_INET, SOCK_STREAM, SOL_TCP);//创建socket
		//socket_set_nonblock($this->socket);
        socket_set_option($this->socket,SOL_SOCKET,SO_RCVTIMEO,array("sec"=>3, "usec"=>0 ) );
		socket_set_option($this->socket,SOL_SOCKET,SO_SNDTIMEO,array("sec"=>3, "usec"=>0 ) );
		///设置$socket 发送超时2秒，接收超时2秒：
		//echo	$this->host,$this->port;
		$r=socket_connect($this->socket,$this->host,$this->port);
        if($r)
        {
            return $r;
        }
        else
        {
            $this->error[]=socket_last_error($this->socket);
            echo 'socket error :',var_dump( $this->error); 
            return 	FALSE;
        }
    }
    /// 向socket服务器写入数据并读取
    public function wr($contents) 
    {
        $this->queryStr = "";
        $this->queryStr = $contents;
        !$this->socket && $this->CreateSocket();
        $contents=$this->fliterSendData($contents);
        $result=socket_write($this->socket,$contents,strlen($contents));
        if(!intval($result))
        {
            $this->error[]=socket_last_error($this->socket);
            return FALSE;
        }
        $data = "";
       
       	//$data = socket_read($this->socket,102400);  /// 100kb
		$all = "";
		$times = 20; 		///最多10次
		$size = 5120;		///每次读的记录数	5kb
		$i = 1;
		while(1) 
		{
			$data = socket_read($this->socket,$size);
        	$all = $all.$data;
        	if(preg_match('/<\/root>/is',$all)) {
        		break;
        	} 
        	if($times < $i) {
        		break;
        	}
        	$i++;
		}
		
        
		/*
       	while(($buffer = socket_read($this->socket,10240,PHP_NORMAL_READ))!==false)
		{
			$data .= $buffer;
		}        
        while(true)
        { 
			$buf = @socket_read($this->socket,10240);            
           	if($buf == '')
            {
                break;
            }
            else
            {
				$data .= $buf;    
      		}
		} 
		*/   
		if(FALSE === $all){
            $this->error[] = socket_last_error($this->socket);
            return FALSE;
        }
        return $all;
    }
    //对发送的数据进行过滤
    private function fliterSendData($contents) 
    {
        //对写入的数据进行处理
        return $contents;
    }
    //所有错误信息
    public function getError() {
        return $this->error;
    }
    //最后一次错误信息
    public function getLastError() {
        return $this->error(count($this->error));
    }
    //获取最后一次发送的消息
    public function getLastMsg() {
        return $this->queryStr;
    }	     
    public function getHost() {
        return $this->host;
    }
     
    public function getPort() {	       
    	return $this->port;
    }
    //关闭socket连接
    private function close() {
        $this->socket&&socket_close($this->socket);//关闭连接
        $this->socket=null;//连接资源初始化
    }
}
?>