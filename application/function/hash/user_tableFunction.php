<?php

/*
 * 哈希函数                    
 * 调用例子         echo CORE::F('get_hash','usre','http://www.baid.com');
 */

function user_table($userid = 1,$n = 5000) {   
	return  ceil($userid/$n);
}   
  
?>