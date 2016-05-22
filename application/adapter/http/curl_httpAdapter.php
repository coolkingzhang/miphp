<?php

// +----------------------------------------------------------------------+
// | Copyright (c) 2008-2010 zhangzhihe                  
// +----------------------------------------------------------------------+
// | Authors: Willko zhangzhihe@163.com    
// http request 请求类
// +----------------------------------------------------------------------+

class curl_httpAdapter	
{
	private $_is_temp_cookie = FALSE;
	private $_header;
	private $_body;
	private $_ch;
	private $_proxy;
	private $_proxy_port;
	private $_proxy_type = 'HTTP'; // or SOCKS5
	private $_proxy_auth = 'BASIC'; // or NTLM
	private $_proxy_user;
	private $_proxy_pass;
	
	protected $_cookie;
	protected $_options;
	protected $_url = array ();
	protected $_referer = array ();
	
	public function __construct($options = array()) 
	{
		$defaults = array ();
		$defaults ['timeout'] = 30;
		@$defaults ['temp_root'] = sys_get_temp_dir ();
		$defaults ['user_agent'] = 'Mozilla/5.0 (Windows; U; Windows NT 6.0; zh-CN; rv:1.8.1.20) Gecko/20081217 Firefox/2.0.0.20';
		$this->_options = array_merge ( $defaults, $options );
	}
	
	public function open() {
		$this->_ch = curl_init ();
		curl_setopt ( $this->_ch, CURLOPT_HEADER, TRUE );
		curl_setopt ( $this->_ch, CURLOPT_RETURNTRANSFER, TRUE );
		curl_setopt ( $this->_ch, CURLOPT_USERAGENT, $this->_options ['user_agent'] );
		curl_setopt ( $this->_ch, CURLOPT_CONNECTTIMEOUT, $this->_options ['timeout'] );
		curl_setopt ( $this->_ch, CURLOPT_HTTPHEADER, array('Expect:') ); // for lighttpd 417 Expectation Failed
		
		$this->_header = '';
		$this->_body = '';
		
		return $this;
	}
	
	public function close() {
		if (is_resource ( $this->_ch )) {
			curl_close ( $this->_ch );
		}
		if (isset ( $this->_cookie ) && $this->_is_temp_cookie && is_file ( $this->_cookie )) {
			unlink ( $this->_cookie );
		}
	}
	
	public function cookie() {
		if (! isset ( $this->_cookie )) {
			if (! empty ( $this->_cookie ) && $this->_is_temp_cookie && is_file ( $this->_cookie )) {
				unlink ( $this->_cookie );
			}
			
			$this->_cookie = tempnam ( $this->_options ['temp_root'], 'curl_manager_cookie_' );
			$this->_is_temp_cookie = TRUE;
		}
		curl_setopt ( $this->_ch, CURLOPT_COOKIEJAR, $this->_cookie );
		curl_setopt ( $this->_ch, CURLOPT_COOKIEFILE, $this->_cookie );
		return $this;
	}
	
	public function ssl() {
		curl_setopt ( $this->_ch, CURLOPT_SSL_VERIFYPEER, FALSE );
		return $this;
	}
	
	public function proxy($host = null, $port = null, $type = null, $user = null, $pass = null, $auth = null) {
		$this->_proxy = isset ( $host ) ? $host : $this->_proxy;
		$this->_proxy_port = isset ( $port ) ? $port : $this->_proxy_port;
		$this->_proxy_type = isset ( $type ) ? $type : $this->_proxy_type;
		$this->_proxy_auth = isset ( $auth ) ? $auth : $this->_proxy_auth;
		$this->_proxy_user = isset ( $user ) ? $user : $this->_proxy_user;
		$this->_proxy_pass = isset ( $pass ) ? $pass : $this->_proxy_pass;
	
		if (! empty ( $this->_proxy )) {
			curl_setopt ( $this->_ch, CURLOPT_PROXYTYPE, $this->_proxy_type == 'HTTP' ? CURLPROXY_HTTP : CURLPROXY_SOCKS5 );
			curl_setopt ( $this->_ch, CURLOPT_PROXY, $this->_proxy );
			curl_setopt ( $this->_ch, CURLOPT_PROXYPORT, $this->_proxy_port );
		}
		
		if (! empty ( $this->_proxy_user )) {
			curl_setopt ( $this->_ch, CURLOPT_PROXYAUTH, $this->_proxy_auth == 'BASIC' ? CURLAUTH_BASIC : CURLAUTH_NTLM );
			curl_setopt ( $this->_ch, CURLOPT_PROXYUSERPWD, "[{$this->_proxy_user}]:[{$this->_proxy_pass}]" );
		}
		
		return $this;
	}
	
	public function post($action, $query = array()) {
		if (is_array($query)) {
			foreach ($query as $key => $val) {
				if ($val{0} != '@') {
					$encode_key = urlencode($key);
					if ($encode_key != $key) {
						unset($query[$key]);
					}
					$query[$encode_key] = urlencode($val);
				}
			}
		}
		
		curl_setopt ( $this->_ch, CURLOPT_POST, TRUE );
		curl_setopt ( $this->_ch, CURLOPT_URL, $this->_url [$action] );
		curl_setopt ( $this->_ch, CURLOPT_REFERER, $this->_referer [$action] );
		curl_setopt ( $this->_ch, CURLOPT_POSTFIELDS, $query );
		$this->_requrest ();
		return $this;
	}
	
	public function get($action, $query = array()) {
		$url = $this->_url [$action];
		if (! empty ( $query )) {
			$url .= strpos ( $url, '?' ) === FALSE ? '?' : '&';
			$url .= is_array ( $query ) ? http_build_query ( $query ) : $query;
		}
		curl_setopt ( $this->_ch, CURLOPT_URL, $url );
		curl_setopt ( $this->_ch, CURLOPT_REFERER, $this->_referer [$action] );
		$this->_requrest ();
		return $this;
	}
	
	public function put($action, $query = array()) {
		curl_setopt ( $this->_ch, CURLOPT_CUSTOMREQUEST, 'PUT' );
		return $this->post ( $action, $query );
	}
	
	public function delete($action, $query = array()) {
		curl_setopt ( $this->_ch, CURLOPT_CUSTOMREQUEST, 'DELETE' );
		return $this->post ( $action, $query );
	}
	
	public function head($action, $query = array()) {
		curl_setopt ( $this->_ch, CURLOPT_CUSTOMREQUEST, 'HEAD' );
		return $this->post ( $action, $query );
	}
	
	public function options($action, $query = array()) {
		curl_setopt ( $this->_ch, CURLOPT_CUSTOMREQUEST, 'OPTIONS' );
		return $this->post ( $action, $query );
	}
	
	public function trace($action, $query = array()) {
		curl_setopt ( $this->_ch, CURLOPT_CUSTOMREQUEST, 'TRACE' );
		return $this->post ( $action, $query );
	}
	
	public function connect() {
	}
	
	public function follow_location() {
		preg_match ( '#Location:\s*(.+)#i', $this->header (), $match );
		if (isset ( $match [1] )) {
			$this->set_action ( 'auto_location_gateway', $match [1], $this->effective_url () );
			
			$this->get ( 'auto_location_gateway' )->follow_location ();
		}
		return $this;
	}
	
	public function set_action($action, $url, $referer = '') {
		$this->_url [$action] = $url;
		$this->_referer [$action] = $referer;
		return $this;
	}
	public function header() {
		return $this->_header;
	}
	public function body() {
		return $this->_body;
	}
	public function effective_url() {
		return curl_getinfo ( $this->_ch, CURLINFO_EFFECTIVE_URL );
	}

	public function http_code() {
		return curl_getinfo($this->_ch, CURLINFO_HTTP_CODE);
	}
	private function _requrest() {
		$response = curl_exec ( $this->_ch );
		$errno = curl_errno ( $this->_ch );
		
		if ($errno > 0) {
			throw new Curl_Manager_Exception ( curl_error ( $this->_ch ), $errno );
		}
		$header_size = curl_getinfo ( $this->_ch, CURLINFO_HEADER_SIZE );
		$this->_header = substr ( $response, 0, $header_size );
		$this->_body = substr ( $response, $header_size );
	}
	
	public function __destruct() {
		$this->close ();
	}
}

/*


$manager = new curl_http();

//发起get请求
$manager->set_action('gg', 'http://www.baidu.com', 'http://willko.javaeye.com/'); //设置动作，(动作名称, 动作对应url，来源referer)
$manager->open()->get('gg'); //打开一个请求，进行get操作

//echo $manager->body(); // 获得报文
//echo $manager->header(); // 获得报头(需要自己解析)

//发起ssl请求
$manager->set_action('taobao', 'https://login.taobao.com/member/login.jhtml?f=top&redirectURL=http://www.taobao.com/', 'http://willko.javaeye.com/');
$manager->ssl()->get('taobao'); //使用ssl方法，让当前这个请求支持ssl请求
//echo $manager->body();
//echo $manager->header();

//支持cookie：
$manager->cookie();

//带参数请求，get/post/put/delete等等使用方式一样
$manager->post('action', array('k' => 'v', 'a' => 'b'));
$manager->post('action', 'k=v&a=b');
$manager->post('action', array('k' => 'v', '@a' => '/home/www/avatar.gif')); //上传，需要使用绝对路径，参数名
*/


?>