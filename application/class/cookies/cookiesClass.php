<?php

/**
**/
class cookiesClass 
{
	
	function cookies($a = '') {
		
	}
	// 判断Cookie是否存在
    static function is_set($name) {
        return isset($_COOKIE[$name]);
    }

	// 设置cookies
	function set($name = 'cookies', $value = '', $time = 0, $path = '/', $domain = 'myorange.cn', $httponly = 0){
		$time = empty($time) ? (time()+3600) : $time;
		if(@setcookie($name,$value,$time,$path,$domain,$httponly)){
			return true;
		} else {
			return false;
		}

	}
	// 读取某个cookies值
	function get($name) {
		return  !empty($name) ? @$_COOKIE[$name] : false;
	}

	function change($new,$value){
        return $_COOKIE[$new] = $value;
    }

	// 删除某个Cookie值
    static function delete($name) {
        self::set($name,'',time()-3600,'/');
        unset($_COOKIE[$name]);
    }

	// 清空Cookie值
    static function clear() {
        foreach($_COOKIE as $key=>$item) {
			setcookie($key,"",time()-10,"/");
		}
		unset($_COOKIE);
    }
    function test() {
    	echo 'test';
    }
}
?>