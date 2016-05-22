<?php 

/*
 * httpsqs 消息队列函数，把请求异步存在httpsqs队列中
 */
class httpsqsClass
{
        public $httpsqs_host;
        public $httpsqs_port;
        public $httpsqs_auth;
        public $httpsqs_charset;
        
        public function __construct($host='127.0.0.1', $port=1218, $auth='', $charset='utf-8') {
                $this->httpsqs_host = $host;
                $this->httpsqs_port = $port;
                $this->httpsqs_auth = $auth;
                $this->httpsqs_charset = $charset;
                return true;
        }

    public function http_get($query)
    {
        $header 	= '';
        $host 		= '';
        $pos_value 	= '';
        $out		= '';
    	$socket = fsockopen($this->httpsqs_host, $this->httpsqs_port, $errno, $errstr, 5);
        if (!$socket)
        {
            return false;
        }
        $out = "GET ${query} HTTP/1.1\r\n";
        $out .= "Host: ${host}\r\n";
        $out .= "Connection: close\r\n";
        $out .= "\r\n";
        fwrite($socket, $out);
        $line = trim(fgets($socket));
        $header .= $line;
        list($proto, $rcode, $result) = explode(" ", $line);
        $len = -1;
        while (($line = trim(fgets($socket))) != "")
        {
            $header .= $line;
            if (strstr($line, "Content-Length:"))
            {
                list($cl, $len) = explode(" ", $line);
 
            }
            if (strstr($line, "Pos:"))
            {
                list($pos_key, $pos_value) = explode(" ", $line);
            }                   
            if (strstr($line, "Connection: close"))
            {
                $close = true;
            }
        }
        if ($len < 0)
        {
            return false;
        }
        
        $body = fread($socket, $len);
        $fread_times = 0;
        while(strlen($body) < $len){
                $body1 = fread($socket, $len);
                $body .= $body1;
                unset($body1);
                if ($fread_times > 100) {
                        break;
                }
                $fread_times++;
        }
        //if ($close) fclose($socket);
                fclose($socket);
                $result_array["pos"] = (int)$pos_value;
                $result_array["data"] = $body;
        return $result_array;
    }

    public function http_post($query, $body)
    {
        $header = '';
        $host = '';
    	$socket = fsockopen($this->httpsqs_host, $this->httpsqs_port, $errno, $errstr, 1);
        if (!$socket)
        {
            return false;
        }
        $out = "POST ${query} HTTP/1.1\r\n";
        $out .= "Host: ${host}\r\n";
        $out .= "Content-Length: " . strlen($body) . "\r\n";
        $out .= "Connection: close\r\n";
        $out .= "\r\n";
        $out .= $body;
        fwrite($socket, $out);
        $line = trim(fgets($socket));
        $header .= $line;
        list($proto, $rcode, $result) = explode(" ", $line);
        $len = -1;
        while (($line = trim(fgets($socket))) != "")
        {
            $header .= $line;
            if (strstr($line, "Content-Length:"))
            {
                list($cl, $len) = explode(" ", $line);
            }
            if (strstr($line, "Pos:"))
            {
                list($pos_key, $pos_value) = explode(" ", $line);
            }                   
            if (strstr($line, "Connection: close"))
            {
                $close = true;
            }
        }
        if ($len < 0)
        {
            return false;
        }
        $body = @fread($socket, $len);
        //if ($close) fclose($socket);
                fclose($socket);
                $result_array["pos"] = (int)$pos_value;
                $result_array["data"] = $body;
        return $result_array;
    }
        
    public function put($queue_name, $queue_data)
    {
        $result = $this->http_post("/?auth=".$this->httpsqs_auth."&charset=".$this->httpsqs_charset."&name=".$queue_name."&opt=put", $queue_data);
                if ($result["data"] == "HTTPSQS_PUT_OK") {
                        return true;
                } else if ($result["data"] == "HTTPSQS_PUT_END") {
                        return $result["data"];
                }
                return false;
    }
    
    public function get($queue_name)
    {
        $result = $this->http_get("/?auth=".$this->httpsqs_auth."&charset=".$this->httpsqs_charset."&name=".$queue_name."&opt=get");
                if ($result == false || $result["data"] == "HTTPSQS_ERROR" || $result["data"] == false) {
                        return false;
                }
        return $result["data"];
    }
        
    public function gets($queue_name)
    {
        $result = $this->http_get("/?auth=".$this->httpsqs_auth."&charset=".$this->httpsqs_charset."&name=".$queue_name."&opt=get");
                if ($result == false || $result["data"] == "HTTPSQS_ERROR" || $result["data"] == false) {
                        return false;
                }
        return $result;
    }   
        
    public function status($queue_name)
    {
        $result = $this->http_get("/?auth=".$this->httpsqs_auth."&charset=".$this->httpsqs_charset."&name=".$queue_name."&opt=status");
                if ($result == false || $result["data"] == "HTTPSQS_ERROR" || $result["data"] == false) {
                        return false;
                }
        return $result["data"];
    }
        
    public function view($queue_name, $queue_pos)
    {
        $result = $this->http_get("/?auth=".$this->httpsqs_auth."&charset=".$this->httpsqs_charset."&name=".$queue_name."&opt=view&pos=".$pos);
                if ($result == false || $result["data"] == "HTTPSQS_ERROR" || $result["data"] == false) {
                        return false;
                }
        return $result["data"];
    }
        
    public function reset($queue_name)
    {
        $result = $this->http_get("/?auth=".$this->httpsqs_auth."&charset=".$this->httpsqs_charset."&name=".$queue_name."&opt=reset");
                if ($result["data"] == "HTTPSQS_RESET_OK") {
                        return true;
                }
        return false;
    }
        
    public function maxqueue($queue_name, $num)
    {
        $result = $this->http_get("/?auth=".$this->httpsqs_auth."&charset=".$this->httpsqs_charset."&name=".$queue_name."&opt=maxqueue&num=".$num);
                if ($result["data"] == "HTTPSQS_MAXQUEUE_OK") {
                        return true;
                }
        return false;
    }
        
    public function status_json($queue_name)
    {
        $result = $this->http_get("/?auth=".$this->httpsqs_auth."&charset=".$this->httpsqs_charset."&name=".$queue_name."&opt=status_json");
                if ($result == false || $result["data"] == "HTTPSQS_ERROR" || $result["data"] == false) {
                        return false;
                }
        return $result["data"];
    }

    public function synctime($num)
    {
        $result = $this->http_get("/?auth=".$this->httpsqs_auth."&charset=".$this->httpsqs_charset."&name=httpsqs_synctime&opt=synctime&num=".$num);
                if ($result["data"] == "HTTPSQS_SYNCTIME_OK") {
                        return true;
                }
        return false;
    }
}


/***
 * 
 * 
 * httpsqs 用法例子

include_once("httpsqs_client.php");   
$httpsqs = new httpsqs($httpsqs_host, $httpsqs_port, $httpsqs_auth, $httpsqs_charset);   
   
/*  
1. 将文本信息放入一个队列（注意：如果要放入队列的PHP变量是一个数组，需要事先使用序列化、json_encode等函数转换成文本） 
    如果入队列成功，返回布尔值：true  
    如果入队列失败，返回布尔值：false  
*/   
//$result = $httpsqs->put($queue_name, $queue_data);   
   
/*  
2. 从一个队列中取出文本信息 
    返回该队列的内容 
    如果没有未被取出的队列，则返回文本信息：HTTPSQS_GET_END 
    如果发生错误，返回布尔值：false  
*/   
//$result = $httpsqs->get($queue_name);   
  
/*  
3. 从一个队列中取出文本信息和当前队列读取点Pos 
    返回数组示例：array("pos" => 7, "data" => "text message") 
    如果没有未被取出的队列，则返回数组：array("pos" => 0, "data" => "HTTPSQS_GET_END") 
    如果发生错误，返回布尔值：false 
*/   
//$result = $httpsqs->gets($queue_name);  
  
/*  
4. 查看队列状态（普通方式） 
*/   
//$result = $httpsqs->status($queue_name);  
  
/*  
5. 查看队列状态（JSON方式） 
    返回示例：{"name":"queue_name","maxqueue":5000000,"putpos":130,"putlap":1,"getpos":120,"getlap":1,"unread":10} 
*/   
//$result = $httpsqs->status_json($queue_name);  
   
/*  
6. 查看指定队列位置点的内容 
    返回指定队列位置点的内容。 
*/   
//$result = $httpsqs->view($queue_name, $queue_pos);  
   
/*  
7. 重置指定队列 
    如果重置队列成功，返回布尔值：true  
    如果重置队列失败，返回布尔值：false  
*/   
//$result = $httpsqs->reset($queue_name);  
   
/*  
8. 更改指定队列的最大队列数量 
   如果更改成功，返回布尔值：true 
   如果更改操作被取消，返回布尔值：false 
*/   
//$result = $httpsqs->maxqueue($queue_name, $num);  
  
/* 
9. 修改定时刷新内存缓冲区内容到磁盘的间隔时间 
   如果更改成功，返回布尔值：true 
   如果更改操作被取消，返回布尔值：false 
*/  
//$result = $httpsqs->synctime($num);  
   

?>