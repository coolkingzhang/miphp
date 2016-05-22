<?php


/*
 * 取得微博的类型	 @他		#主题#	[表情]
 */


function get_type($str = '',$type = '@') {
	switch ($type) {
		case "@" : preg_match_all('/@[^\s]+\s+/is',$str,$m);
		break;
		case "#" : preg_match_all('/#[^#]+#+/is',$str,$m);
		break;
		case "[]" : preg_match_all('/\[[^\]]+\]/',$str,$m);
		break;
		default:;
	}
	return $m;	
}


?>