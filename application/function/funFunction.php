<?php

/**
 * 方法库
 *
 */
/*
 * upload_curl_pic 远程上传文件如图片
 * 
 * params $file 本地文件名
 * params $url  远程图片上传的地址
 * params $type 图片类型       common 普通  weibo 微博图  album 相册  activity 活动 avatar 头像
 * 根据图片类型设置参数提交函数
 * params $thumb 是否生成缩略图  0 不生成 1 生成
 * 
 * 客户端
 * $file = 'F:\myorange.cn\xx.gif'; //要上传的文件
 * $src = upload_curl_pic($file);
 * echo $src;
 *
 * 服务端 
 * $uploaddir = "F:/myorange.cn/cc";
 * $uploadfile = $uploaddir . $_FILES['f']['name'];
 * if(move_uploaded_file($_FILES['f']['tmp_name'], $uploadfile))
 * { 
 *      echo $uploadfile;
 *      //echo "File is valid, and was successfully uploaded.\n";
 * }
 * else
 * {
 *		echo '0';
 *     	//echo "Possible file upload attack!\n";
 *     	//echo 'Here is some more debugging info:';
 * }
 */


function upload_curl_pic($file,$url = 'http://www.myorange.cn/uploadfile.php',$type = 'common',$thumb = 0)
{
    $fields['file'] = '@'.$file;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url );
    curl_setopt($ch, CURLOPT_POST, 1 );
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields );
    ob_start();
    curl_exec($ch);
    $result = ob_get_contents();
    ob_end_clean();
    curl_close($ch);
    return $result;
}
/************************
*    HttpVisit($ip, $host, $url) 函数用途：同一域名对应多个IP时，获取指定服务器的远程网页内容
*    
*    参数说明：
*    $ip   服务器的IP地址
*    $host   服务器的host名称
*    $url   服务器的URL地址（不含域名）
*	   返回值：
*    获取到的远程网页内容
*    false   访问远程网页失败
*    
*    //调用方法：
* 	 $server_info1 = HttpVisit("72.249.146.213", "blog.s135.com", "/abc.php");
*    $server_info2 = HttpVisit("72.249.146.214", "blog.s135.com", "/abc.php");
*    $server_info3 = HttpVisit("72.249.146.215", "blog.s135.com", "/abc.php");
*  
************************/
function HttpVisit($ip, $host, $url)   
{   
    $errstr = '';   
    $errno = '';
    $fp = fsockopen ($ip, 80, $errno, $errstr, 90);
    if (!$fp)   
    {   
         return false;   
    }   
    else  
    {   
        $out = "GET {$url} HTTP/1.1\r\n";
        $out .= "Host:{$host}\r\n";   
        $out .= "Connection: close\r\n\r\n";
        fputs ($fp, $out);   
        while($line = fread($fp, 4096)){
           $response .= $line;
        }
        fclose( $fp );
        //去掉Header头信息
        $pos = strpos($response, "\r\n\r\n");
        $response = substr($response, $pos + 4);
        return $response;   
    }   
}

/*
	*	Utf-8、gb2312都支持的汉字截取函数
	*	cut_str(字符串, 截取长度, 开始长度, 编码);
	*	编码默认为 utf-8
	*	开始长度默认为 0
	*	例子
	*	$str = "abcd需要截取的字符串";
	*	echo cut_str($str,0,10);
*/
 
function cut_str($string,$start = 0,$sublen, $code = 'UTF-8')
{
    if($code == 'UTF-8')
    {
        $pa = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/";
        preg_match_all($pa, $string, $t_string);
 
        if(count($t_string[0]) - $start > $sublen) return join('', array_slice($t_string[0], $start, $sublen))."...";
        return join('', array_slice($t_string[0], $start, $sublen));
    }
    else
    {
        $start = $start*2;
        $sublen = $sublen*2;
        $strlen = strlen($string);
        $tmpstr = '';
 
        for($i=0; $i< $strlen; $i++)
        {
            if($i>=$start && $i< ($start+$sublen))
            {
                if(ord(substr($string, $i, 1))>129)
                {
                    $tmpstr.= substr($string, $i, 2);
                }
                else
                {
                    $tmpstr.= substr($string, $i, 1);
                }
            }
            if(ord(substr($string, $i, 1))>129) $i++;
        }
        if(strlen($tmpstr)< $strlen ) $tmpstr.= " ......";
        return $tmpstr;
    }
}

/*
 * 过滤非数字的
 */
function get_int($status = 2,$default = 1) {
	$status = preg_replace('/[^0-9]+/is','',$status);
	if($status == '') {
		$status = $default;
	}
	return $status;
}
/*
 * 中文分词接口
 */

function httpcws($text,$t = 'UTF-8') {
	$http = CORE::ADP('http');
	if($t == 'UTF-8') {
		$text = iconv("UTF-8", "GBK//IGNORE", $text);
	}
	$text = urlencode($text);
	$http->set_action('httpcws', 'http://192.168.2.252:1985?w='.$text, ''); 
	$http->open()->get('httpcws'); 
	$body =  $http->body();
	if($t == 'UTF-8') {
		$body = iconv("GBK", "UTF-8//IGNORE", $body);
	}
	return $body;
}


/*
 * 新浪长连接(即使是原始连接)转成短连接
 */

function shortenSinaUrl($long_url)
{
	$apiKey = WB_AKEY_SINA;
	$apiUrl='http://api.t.sina.com.cn/short_url/shorten.json?source='.$apiKey.'&url_long='.urlencode($long_url);
	$curlObj = curl_init();
	curl_setopt($curlObj, CURLOPT_URL, $apiUrl);
	curl_setopt($curlObj, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curlObj, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($curlObj, CURLOPT_HEADER, 0);
	curl_setopt($curlObj, CURLOPT_HTTPHEADER, array('Content-type:application/json'));
	$response = curl_exec($curlObj);
	curl_close($curlObj);
	$json = json_decode($response);
	
	$shourt = $json[0]->url_short;
	$shourt = str_replace(SINA_WEBNAME,ORANGE_WEBNAME,$shourt);
	return $shourt;
}


/*
 * 新浪短接转成长连接
 */
function expandSinaUrl($short_url){
	$short_url = str_replace(ORANGE_WEBNAME,SINA_WEBNAME,$short_url);
	$apiKey=WB_AKEY_SINA;
	$apiUrl='http://api.t.sina.com.cn/short_url/expand.json?source='.$apiKey.'&url_short='.urlencode($short_url);
	$curlObj = curl_init();
	curl_setopt($curlObj, CURLOPT_URL, $apiUrl);
	curl_setopt($curlObj, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curlObj, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($curlObj, CURLOPT_HEADER, 0);
	curl_setopt($curlObj, CURLOPT_HTTPHEADER, array('Content-type:application/json'));
	$response = curl_exec($curlObj);
	curl_close($curlObj);
	$json = json_decode($response);
	
	$long_url = $json[0]->url_long;
	
	return $long_url;
}
/*
 *  模板显示方法
 */
function LO($params = '') {
	$p = func_get_args();
	$kk = $p[0];
	array_shift($p);
	if(is_array($p)) {
		foreach($p as $k => $v) {
			$p[$k] = "'".$v."'";
		}
	}
	$pa = implode(',',$p);
	if(count($p) > 0) {
		@eval("printf(\$GLOBALS[V_GLOBAL_NAME]['TPL'][\$kk],$pa);");
		unset($pa);
		unset($p);
		unset($kk);
	} else {
		@eval("printf(\$GLOBALS[V_GLOBAL_NAME]['TPL'][\$kk]);");
		unset($pa);
		unset($p);
		unset($kk);
	}
                    
}
/**
 * 返回一段 Javascript 跳转的脚本
 *
 * @param string $url
 * @param string $msg
 * @return string
 */
function jsLocation($url, $msg = '') {
	$js = '<script language="javascript" type="text/javascript">';
	if ($msg) {
		$js .= 'alert("' . $msg . '");';
	}
	return $js . 'location = "' . $url . '";</script>';
}

/**
 * 强化加密字符串
 *
 * @param string $string
 * @param string $operation ENCODE/DECODE
 * @param string $key 密钥
 * @param int $expiry 密码有效期
 * @return string
 */
function authpw($string, $operation = 'DECODE', $key = '', $expiry = 0) {
	if ($operation == 'ENCODE') {
		$string = authcode($string, $operation, $key, $expiry);
	}
	$pos = array(1, 3, 6, 11, 17);
	$string = str_split($string);
	foreach ($pos as $p) {
		$t = $string[$p];
		$string[$p] = $string[$p + 1];
		$string[$p + 1] = $t;
	}
	$string = join('', $string);
	if ($operation == 'DECODE') {
		$string = authcode($string, $operation, $key, $expiry);
	}
	return $string;
}



/**
 * 字符串加密
 *
 * @param string $string
 * @param string $operation ENCODE/DECODE
 * @param string $key
 * @param int $expiry
 * @return string
 */
function authcode($string, $operation = 'DECODE', $key = '', $expiry = 0) {

	$ckey_length = 6;	// 随机密钥长度 取值 0-32;
				// 加入随机密钥，可以令密文无任何规律，即便是原文和密钥完全相同，加密结果也会每次不同，增大破解难度。
				// 取值越大，密文变动规律越大，密文变化 = 16 的 $ckey_length 次方
				// 当此值为 0 时，则不产生随机密钥

	$key = md5($key ? $key : 'zz');
	$keya = md5(substr($key, 0, 16));
	$keyb = md5(substr($key, 16, 16));
	$keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';

	$cryptkey = $keya.md5($keya.$keyc);
	$key_length = strlen($cryptkey);

	$string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
	$string_length = strlen($string);

	$result = '';
	$box = range(0, 255);
        
	$rndkey = array();
	for($i = 0; $i <= 255; $i++) {
		$rndkey[$i] = ord($cryptkey[$i % $key_length]);
	}

	for($j = $i = 0; $i < 256; $i++) {
		$j = ($j + $box[$i] + $rndkey[$i]) % 256;
		$tmp = $box[$i];
		$box[$i] = $box[$j];
		$box[$j] = $tmp;
	}

	for($a = $j = $i = 0; $i < $string_length; $i++) {
		$a = ($a + 1) % 256;
		$j = ($j + $box[$a]) % 256;
		$tmp = $box[$a];
		$box[$a] = $box[$j];
		$box[$j] = $tmp;
		$result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
	}

	if($operation == 'DECODE') { 
		if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
			return substr($result, 26);
		} else {
			return '';
		}
	} else { 
		return $keyc.str_replace(array('='), '', base64_encode($result));
	}
}

/**
 * IP字符串到整形的互转
 *
 * @param string/int $ip
 * @param ENCODE/DECODE $operation
 * @return false/string
 */
function ipconv($ip, $operation = 'DECODE') {
	if ('ENCODE' == $operation) {
		if (!is_ip($ip)) {
			return false;
		}
		$ip = explode('.', $ip);
		$covip = '';
		foreach ($ip as $i) {
			$covip .= substr('000' . $i, -3, 3);
		}
		return $covip;
	}
	if ('DECODE' == $operation) {
		if (! preg_match("/^[0-9]{9,12}$/iU", $ip)) {
			return false;
		}
		$len = strlen($ip);
		$result = intval(substr($ip, $len - 9, 3)) . '.' . intval(substr($ip, $len - 6, 3)) . '.' . intval(substr($ip, $len - 3, 3));
		return intval(substr($ip, -$len, $len - 9)) . '.' . $result;
	}
}

/**
 * 判断是否IP
 *
 * @param string $ip
 * @return boolean
 */
function is_ip($ip){
	$chars = "/^(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])$/";
	if (preg_match($chars, $ip)) {
        return true;
    } else {
        return false;
    }
}

/**
 * 替换字符串中间的字符
 * 
 * */
function replace_inner_letter($str, $start = 2, $leftintheend = 2, $repalce = "*", $middleleght = 6) {
	$length = mb_strlen($str,"utf-8");
	$alllength = ($start+$leftintheend);
	if ($length <= $alllength){
		return $str;
	} else {
		$repeadleght = $length-$alllength;
		if ($repeadleght > $middleleght) {
			$repeadleght = $middleleght;
		}
		return mb_substr($str,0,$start,"utf-8").str_repeat($repalce,$repeadleght).mb_substr($str,$length-$leftintheend,$leftintheend,"utf-8");
	}
}

/**
 * 判断字符串是否是日期格式
 * $dateTimeStr string
 * $m 可选模式： NULL/Ymd/His。 对应匹配：日期时间/日期/时间 格式
 * $t 日期的分隔符，默认为任意字符
 * return true/false
 */
function isdate($dateTimeStr, $m = NULL, $t = '.') {
	switch ($m) {
		case 'Ymd':
			if (ereg('^[0-9]{4}' . $t . '[0-9]{2}' . $t . '[0-9]{2}', $dateTimeStr)) {
				return true;
			}
			
		case 'His':
			if (ereg('[0-9]{2}' . $t . '[0-9]{2}' . $t . '[0-9]{2}', $dateTimeStr)) {
				return true;
			}
		
		default :
			if (ereg('^[0-9]{4}' . $t . '[0-9]{2}' . $t . '[0-9]{2}' . $t . '[0-9]{2}' . $t . '[0-9]{2}' . $t . '[0-9]{2}', $dateTimeStr)) {
				return true;
			}
	}
	return false;
}

/**
 * 把日期时间转换成秒数
 *
 * @param string $mysql_time
 * @return int
 */
function datetotime($mysql_time) {
	if (!preg_match('/^(\\d{4})-(\\d{2})-(\\d{2}) (\\d{2}):(\\d{2}):(\\d{2})$/', $mysql_time, $matches)) {
		return 0;
	}
	return mktime($matches[4], $matches[5], $matches[6], $matches[2], $matches[3], $matches[1]);
}
/**
 *
 * @param <type> $len  生成字符长度
 * @return <type> varchar 返回字符串
 */
function randomstring($len)
{
    $chars = array(
        "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k",
        "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v",
        "w", "x", "y", "z", "A", "B", "C", "D", "E", "F", "G",
        "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R",
        "S", "T", "U", "V", "W", "X", "Y", "Z", "0", "1", "2",
        "3", "4", "5", "6", "7", "8", "9"
    );
    $charsLen = count($chars) - 1;
    shuffle($chars);    // 将数组打乱
    $output = "";
    for ($i=0; $i<$len; $i++)
    {
        $output .= $chars[mt_rand(0, $charsLen)];
    }
    return $output;
}
/**
 * 生成密码度低的字符串
 */
function randomshortstr($len)
{
  $chars = array(
         "0", "1", "2", "3", "4", "5", "6",
        "7", "8", "9"
    );
    $charsLen = count($chars) - 1;
    shuffle($chars);    // 将数组打乱
    $output = "";
    for ($i=0; $i<$len; $i++)
    {
        $output .= $chars[mt_rand(0, $charsLen)];
    }
    return $output;
}

/**
 *  生成密码度一般的字符串
 */
function randmidstr($len)
{
    $chars = array(
        "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k",
        "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v",
        "w", "x", "y", "z", "0", "1", "2",
        "3", "4", "5", "6", "7", "8", "9"
    );
    $charsLen = count($chars) - 1;
    shuffle($chars);    // 将数组打乱
    $output = "";
    for ($i=0; $i<$len; $i++)
    {
        $output .= $chars[mt_rand(0, $charsLen)];
    }
    return $output;
}


/**
 * 检查数组的元素是否唯一
 *
 * @param array $arr
 * @return boolean 唯一返回true
 */
function array_is_unique($arr) {
	$count = count($arr);
	for ($i = 0; $i < $count - 1; $i++) {
		for ($j = $i + 1; $j < $count; $j++) {
			if ($arr[$i] == $arr[$j]) {
				return false;
			}
		}
	}
	return true;
}

function utf8_gb2312($str, $default='gb2312')
{
    $str = preg_replace("/[x01-x7f]+/", "", $str);
    if (empty($str)) return $default;

    $preg =  array(
        "gb2312" => "/^([xa1-xf7][xa0-xfe])+$/", //正则判断是否是gb2312
        "utf-8" => "/^[x{4e00}-x{9fa5}]+$/u",      //正则判断是否是汉字(utf8编码的条件了)，这个范围实际上已经包含了繁体中文字了
    );

    if ($default == 'gb2312') {
        $option = 'utf-8';
    } else {
        $option = 'gb2312';
    }

    if (!preg_match($preg[$default], $str)) {
        return $option;
    }
    $str = @iconv($default, $option, $str);

    //不能转成 $option, 说明原来的不是 $default
    if (empty($str))
    {
        return $option;
   }
}
   /**
    * utf8字符串长度,中文和其它字符都占一个字符，返回总数
    */
   function strlen_utf8($str)
   { 
        $i = 0;
        $count = 0;
        $len = strlen ($str);
        while ($i < $len) {
            $chr = ord ($str[$i]);
            $count++;
            $i++;
            if($i >= $len) break;
            if($chr & 0x80) {
                $chr <<= 1;
                    while ($chr & 0x80){
                        $i++;
                        $chr <<= 1;
                    }
            }
        }
      return $count;
  }
  /**
   * 处理字符，中文占两个字符，其它是一个字符
   */
  function getcharlen($str)
  {
        $i = 0;
        $count = 0;
        $len = strlen ($str);
        while ($i < $len) {
            $chr = ord ($str[$i]);
            $count++;
            $i++;
            if($i >= $len) break;
            if($chr & 0x80){
                $chr <<= 1;
                while ($chr & 0x80){
                    $i++;
                    $chr <<= 1;
                }                
                 $count++;
            }                                       
        }       
       return $count;
  }

 /**
  *
  * @param  $url
  * @param  $second  失效时间
  * @return 
  */
function getbuffer($url, $second = 8)
{
	    $ch= curl_init();
	    curl_setopt($ch,CURLOPT_URL,$url);
	    curl_setopt($ch,CURLOPT_HEADER,0);
	    curl_setopt($ch,CURLOPT_TIMEOUT,$second);
	    curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
	    $content    = curl_exec($ch);
	    curl_close($ch);
	    return $content;
}

 /**
  * @param <type> $strXml
  * @return <type> 
  */
 function getXmlData($strXml) {
		$pos = strpos($strXml, 'xml');
		if ($pos) {
			$xmlCode = simplexml_load_string($strXml,'SimpleXMLElement', LIBXML_NOCDATA);
			$arrayCode = get_object_vars_final($xmlCode);
			return $arrayCode ;
		} else {
			return 0;
		}
	}
    
 function get_object_vars_final($obj){
		if(is_object($obj)){
			$obj = get_object_vars($obj);
		}
		if(is_array($obj)){
			foreach ($obj as $key=>$value){
				$obj[$key] = get_object_vars_final($value);
			}
		}
		return $obj;
	}

//签名函数
function createSign ($paramArr) {
    global $appSecret;
    $sign = $appSecret;
    ksort($paramArr);
    foreach ($paramArr as $key => $val) {
       if ($key !='' && $val !='') {
           $sign .= $key.$val;
       }
    }
   return $sign = strtoupper(md5($sign));  //Hmac方式
}
 //组参函数
function createStrParam ($paramArr) {
    $strParam = '';
    foreach ($paramArr as $key => $val) {
       if ($key != '' && $val !='') {
           $strParam .= $key.'='.urlencode($val).'&';
       }
    }
    return $strParam;
}

function page($array,$pagesize,$current){
	// print_r($array);exit;
	$_return=array();    
	$total=ceil(Count($array)/$pagesize);
	$prev=(($current-1)<=0 ? "1":($current-1));
	$next=(($current+1)>=$total ?   $total:$current+1);
 	$current=($current>($total)?($total):$current);
 	$start=($current-1)*$pagesize;
	for($i=$start;$i<($start+$pagesize);$i++){
		if(!empty($array[$i])){
			array_push($_return,$array[$i]);
		}
 	}
	return $_return;
}

function messagenum($content,$limit)
{
    if($limit)
    {
       $count =floor(strlen_utf8($content)/$limit);    
       if((strlen_utf8($content)/$limit) != $count){
           return $count+1;
       }else{
           return $count;
       }
    }
}

/**
 * 验证是否邮件格式
 * return bool
 */
function check_email($email)
{
   if(preg_match('/^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+((\.[a-zA-Z0-9_-]{2,3}){1,2})$/',$email))
   {
     return TRUE;  
   }
   return TRUE;
}

/**
 * 验证是否是手机号
 * return bool
 */
function check_phone($phone)
{
   if(preg_match('/^((((13[0-9]{1})|15[0-9]{1})|18[0-9]{1})+\d{8})$/',$phone))
   {
     return true;  
   }
   return false;
}

/**
 * 分页函数
 */
function get_page_result($array=array(),$type = 1)
{
    $pagebarStr 			= '';
    $page_player 			= CORE::N('page/Mypage',$array);
	$pagebarStr 			= ' '.$page_player->show(2).'';
	if($type == 2) {
		$pagebarStr 			= ' '.$page_player->show(1).'';
	}
	return $pagebarStr;
}

/**
     * 用户密码加密,返回密码和校检码
     * @param string $string
     * @return array 
     */
function encodepsd($psd)
 {        
     $arr['code']=randomstring(1)?randomstring(1):0;         
     $arr['psd']=md5(md5($psd).$arr['code']);
     return  $arr;
 } 
function utf8() 
{
	header('Content-Type:text/html charset=utf-8');
} 

/**
 * 设置session
 *  @param array $array    $k为session名，$v为session的值      
 * @param string $act      USER表示前台的session，ADMIN表示后的session
 */
function set_s($array,$act=USER)
{      
     if(is_array($array) && $array)
     {   
       foreach($array as $k=>$v)
       {
           $_SESSION[$act][$k]=$v;
       }
     }
    
    
}   
/**
 * 获取session
 * @param string $_name    session名  
 * @param string $act      USER表示前台的session，ADMIN表示后的session
 */
function get_s($_name='',$act=USER)
{ 
    if(!$_name)    //返回前台的session
    {
        return  @$_SESSION[$act];   
    }     
    if(isset($_SESSION[$act][$_name]))
    {
        return $_SESSION[$act][$_name];
    }        
 }
/**
 * 删除session名
 * @param string $_name    session名  
 * @param string $act      USER表示前台的session，ADMIN表示后的session
 */
function remove_s($_name='',$act=USER)
{
    if(!$_name)
    {
         unset($_SESSION[$act]);   
    }
    if($_SESSION[$act][$_name])
    {
        unset($_SESSION[$act][$_name]);     
    }
}    
/**
 * 清除所有session    
 */
function clean_s()
{
    $_SESSION = array();
    session_destroy();
   // session_regenerate_id();
    return true;
}

/**
 * 时间转换
 * $time 为unix时间
 */
function transform_time($time)
{
    $ltime=time()-$time;
   if($ltime >=86400 || date('H:i',$time)>date('H:i',time()))
   {
       return date('m',$time)."月".date('d',$time)."日".date('H:i',$time);    
   }elseif($ltime >3600 && date('H:i',$time)<=date('H:i',time()) ){
       return '今天 '.date('H:i',$time); 
   }else{
       return ceil($ltime/60)." 分钟前";
   }
}
/*
 * 生成一个用户头像 50x50 
 */
function user_face($uid = '',$display = TRUE,$type = 50) 
{
	if($type == 50) 
	{
		$face_name = STATIC_IMAGES_FACE_ERROR;
		$temp_name = VAR_FACE.'/'.$uid.'_middle_avatar.jpg';
		if(file_exists($temp_name)) 
		{
			$face_name = USER_FACE.'/'.$uid.'_middle_avatar.jpg';
		} 
		else 
		{
			$temp_name = VAR_FACE.'/'.$uid.'_middle_avatar.png';
			if(file_exists($temp_name)) 
			{
				$face_name = USER_FACE.'/'.$uid.'_middle_avatar.png';
			} 
			else 
			{
				$temp_name = VAR_FACE.'/'.$uid.'_middle_avatar.gif';
				if(file_exists($temp_name)) 
				{
					$face_name = USER_FACE.'/'.$uid.'_middle_avatar.gif';
				}
			}
		}
		if($display == TRUE) 
		{
			echo "<img height=50 width=50 src='".$face_name."' />";
		} 
		else 
		{
			return "<img height=50 width=50 src='".$face_name."' />";
		}
	}

	if($type == 'act') 
	{
		$headers = get_headers($uid);
		if($headers[0] == 'HTTP/1.1 404 Not Found' || empty($uid)) 
		{
			$uid = STATIC_IMAGES_ACT_ERROR;
		}
		echo "<img src='".$uid."' />";
	}
	
}

/* 
 * 上传图片 
 */
function upload($name = '',$type = 'act',$uid = '',$ext = '',$size = '1') 
{
	if(!is_uploaded_file($_FILES[$name]['tmp_name'])) {
		return '';
		//break;
	}
	$ext_arr = array("gif", "jpg", "jpeg", "png", "bmp", "txt", "zip", "rar","doc");
	$temp_arr = explode(".", $_FILES[$name]["name"]);
	$file_ext = array_pop($temp_arr);
	$file_ext = trim($file_ext);
	$file_ext = strtolower($file_ext);
	
	$uploaddir = VAR_USER.'/'.$type.'/';
	$file_name = time().'.'.$file_ext;
	$file_name_title = time().'_title.'.$file_ext;
	$uploadfile = $uploaddir . $file_name;
	$uploadfile_title = $uploaddir. $file_name_title;
	$src = 'http://user.myorange.cn/user/'.$type.'/'.$file_name_title;
	
	//检查扩展名
	if (in_array($file_ext, $ext_arr) === FALSE) {
		//exit("上传文件扩展名是不允许的扩展名。");
		return '';
		//break;
	}
	if($_FILES[$name]["size"] > ($size*1024*1024)) {
		return '';
		//break;
	}
	
	if(move_uploaded_file($_FILES[$name]['tmp_name'], $uploadfile))
	{ 
		$t = CORE::N('image/thumbhandler');
		$t->setSrcImg($uploadfile);
		$t->setDstImg($uploadfile_title);
		//$t->setMaskWord("love");
		// 指定固定宽高
		if($type == 'act') {
			$t->createImg(100,100);
		} else {
			$t->createImg(100,100);
		}
		return	$src;
		//break;      
		//echo "File is valid, and was successfully uploaded.\n";
	}
	else
	{
	 	return	'';
	 	//break;
	    //echo "Possible file upload attack!\n";
	    //echo 'Here is some more debugging info:';
	}
}
function weibo_content($content) {
	
	/*
	 * 替换表情
	 */
	$emotion = CORE::CONF('emotion');
	//print_r($emotion);
	foreach($emotion as $key => $val) {
		$content = str_replace($key,$val,$content);	 
	}
	return $content;
}
/*
 * 转换日期
 */
function change_date($date) 
{
	if(date('Y-m-d',$date) == date('Y-m-d',time()))
	{
		$t = time() - $date;
		$h  = floor($t/3600);
		if(empty($h)) 
		{
			$h = '';
		} 
		else 
		{
			$h = $h.'小时';
		}
		$dateline = $h.floor(($t/60)%60).'分钟前';
	} 
	else 
	{
		$dateline = date('m月d日H:i',$date);
	}
	return $dateline;
}

?>
