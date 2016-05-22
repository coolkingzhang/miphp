<?php

/**************************************************
*  Created:  2010-06-08
*
*  memcached缓存
*
*  
*  @Author zhangzhihe <coolkingzhang@163.com>
*
***************************************************/

class memcacheAdapter
{
	private $mem ;
	public function memcacheAdapter() {
		$this->mem = new Memcache(); 
		if(count($GLOBALS[V_GLOBAL_NAME]['adapter']['cache']['memcache']['server'])> 0 ) {
			foreach($GLOBALS[V_GLOBAL_NAME]['adapter']['cache']['memcache']['server'] as $key => $server) {
				$this->mem->addServer($server['host'],$server['port'],$server['persistent'],$server['weight'],$server['timeout'],$server['retry_interval'],$server['status']); 
			}
		}
		return TRUE;
	}
	
	/*
	 * flag   // Use MEMCACHE_COMPRESSED to store the item compressed (uses zlib). 
	 * expire 过期时间 最大不能过30天  expire最大值是 2592000 (30 days). 
	 * 
	 */
	public function set($key,$value,$expire = 360,$flag = MEMCACHE_COMPRESSED) {
		return $this->mem->set($key, $value,$flag,$expire);
	}
	public function get($key) 
	{
		return $this->mem->get($key);
	}
	public function delete($key) 
	{
		return $this->mem->delete($key, 0);
	}	
	public function getExtendedStats() {
		return $this->mem->getExtendedStats();
	}
	public function increment($key,$value) {
		return $this->mem->increment($key,$value);
	}
}


/*
 * 		$memcachehost = '192.168.2.31';
		$memcacheport = 12000;
		$memcachelife = 60;
		$memcache = new Memcache;
		$memcache->connect($memcachehost,$memcacheport) or die ("Could not connect");
		$memcache->set('cctv','eee',MEMCACHE_COMPRESSED, 50);
		echo $memcache->get('cctv');
		exit;
 * 
 * 
 */

?>
