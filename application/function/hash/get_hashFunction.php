<?php

/*
 * 哈希函数                    
 * 调用例子         echo CORE::F('get_hash','usre','http://www.baid.com');
 */

function get_hash($table, $userid) {   
	$str = crc32($userid);   
 	if($str < 0) {   
 		$hash = "0".substr(abs($str), 0, 1);   
 	}
 	else {   
  		$hash = substr($str, 0, 2);   
 	}   
	return $table."_".$hash;   
}   
  
//echo get_hash_table('message','zhang');     //结果为message_10   
//echo get_hash_table('message','中国人民无可厚非魂牵梦萦');    //结果为message_13   
?>