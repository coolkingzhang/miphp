<?php

/********

$mem = CORE::N('memory/memory');
echo $mem->mem('kb');
echo "<br/>";
echo $mem->get();

*////////
class memoryClass
{
	var $memory_start = '';
	var $memory_end = '';
	function start(){
		$this->memory_start = memory_get_usage();
	}
	function end($type = 'kb'){
		$this->memory_end = memory_get_usage();
		if($type == 'kb') {
			$time = ((($this->memory_end) - ($this->memory_start)) / 1024) ."kb";
		} else if($type == 'm'){
			$time = (($this->memory_end) - ($this->memory_start)) / (1024*1024) ."m";
		}
		return number_format($time,2).$type;
	}
	function mem($type = 'kb'){
		if($type == 'kb') {
			$time = memory_get_usage() / 1024 ."kb";
		} else if($type == 'm'){
			$time = memory_get_usage() / (1024*1024) ."m";
		}
		return number_format($time,2).$type;
	}
	function set($v = '100M'){
		ini_set('memory_limit', $v);
	}
	function get(){
		return ini_get('memory_limit');
	}
}
?>