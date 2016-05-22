<?php

/*
 * mysql数据库操作类
 * 
 * $mysql = CORE::ADP('db');
 *	$record = $mysql->find('select * from org_log');
 *	print_r($record);
 *	$record = $mysql->find('select * from org_log where id = 2');
 *	print_r($mysql->query_list);
 *
 *
 * 写例子
 * 
 * $mysql = CORE::ADP('db');
 * $mysql->connect('write');
 * for($j=0;$j<10;$j++) {
 *		for($i=0; $i < 2000;$i++) {
 *			$row[] = array('uid'=>rand(10,30),'ip'=>'192.168.2.252');
 *		}
 * $mysql->save_multi('org_log',$row);
 *		unset($row);
 *	}
 */

GLOBAL $db_config;
$db_config = $GLOBALS[V_GLOBAL_NAME]['adapter']['db']['sqlite'];

class sqliteAdapter
{
    static private $_instance = NULL;
	private $conn;
    public $query_list = array();
    public $query_count = 0;
    private $db;
    public function __construct()
    {
    	GLOBAL $db_config;
    	$this->db = $db_config['src'];
        self::connect('read');
    }
	/*
     * 单例入口
     */
	public function getInstance()
    {
        if(!self::$_instance instanceof self)
        {
            self::$_instance = new self;
        }
        else
        {
        }
        return self::$_instance;
    }
    function connect($link_type='read')
    {
        $this->conn = sqlite_open($this->db,0666,$sqliteerror);
    }
    public function query($sql)	{
        $stime = microtime(TRUE);
        $result = sqlite_query($this->conn,$sql);
        $this->query_count ++;
        if($result === FALSE){
            throw new Exception(sqlite_error_string()." in SQL: $sql");
        }
        $etime = microtime(TRUE);
        $time = number_format(($etime - $stime) * 1000, 2).'ms';
        $this->query_list[] = $time . ' ' . $sql;
        return $result;
    }
    public function get($sql)
    {
        $result = $this->query($sql);
        if($row = sqlite_fetch_object($result))
        {
            return $row;
        }else{
            return null;
        }
    }
	public function find_assoc($sql, $key=null)
	{
        $data = array();
        $result = $this->query($sql);
        while($row = sqlite_fetch_assoc($result))    
        {
            if(!empty($key)){
                $data[$row->{$key}] = $row;
            }else{
                $data[] = $row;
            }
        }
        return $data;
    }
	public function find_row($sql, $key=null)
	{
        $data = array();
        $result = $this->query($sql);
        while($row = sqlite_fetch_row($result))    
        {
            if(!empty($key)){
                $data[$row->{$key}] = $row;
            }else{
                $data[] = $row;
            }
        }
        return $data;
    }
	public function find_array($sql, $key=null)
	{
        $data = array();
        $result = $this->query($sql);
        while($row = sqlite_fetch_array($result))    
        {
            if(!empty($key)){
                $data[$row->{$key}] = $row;
            }else{
                $data[] = $row;
            }
        }
        return $data;
    }
    public function find_object($sql, $key=null)
    {
        $data = array();
        $result = $this->query($sql);
        while($row = sqlite_fetch_object($result))    
        {
            if(!empty($key)){
                $data[$row->{$key}] = $row;
            }else{
                $data[] = $row;
            }
        }
        return $data;
    }
    public function last_insert_id()    
    {
        return sqlite_insert_id($this->conn);
    }
    public function count($sql){
        $result = $this->query($sql);
        if($row = sqlite_fetch_array($result))    
        {
            return (int)$row[0];
        }else{
            return 0;
        }
    }
    public function begin(){
        sqlite_query('begin');
    }
    public function commit(){
        sqlite_query('commit');
    }
    public function rollback(){
        sqlite_query('rollback');
    }
    public function load($table, $id, $field='id')    
    {
        $sql = "select * from `{$table}` where `{$field}`='{$id}'";
        $row = $this->get($sql);
        return $row;
    }
    public function save($table, &$row) 
    {
        $sqlA = '';
        foreach($row as $k=>$v) 
        {
            $sqlA .= "`$k` = '$v',";
        }
        $sqlA = substr($sqlA, 0, strlen($sqlA)-1);
        $sql  = "insert into `{$table}` set $sqlA";
        $this->query($sql);
        if(is_object($row)) {
            $row->id = $this->last_insert_id();
        }else if(is_array($row)) {
            $row['id'] = $this->last_insert_id();
        }
    }
    function save_multi($table, &$row) {
        $fields = array_keys($row[0]);
        $fields = '`'.join($fields,'`,`').'`';
        $values_list = '';
        foreach($row as $k => $v) {
            $values = array_values($v);
            $values = join($values,"','");
            $values_list = $values_list."('".$values."'),";
        }
        $values_list = rtrim($values_list,',');
        $sql = "insert into $table($fields) values$values_list ";
        echo $sql;
        $this->query($sql);
        exit;
        if(is_object($row)) {
            $row->id = $this->last_insert_id();
        }else if(is_array($row)) {
            $row['id'] = $this->last_insert_id();
        }
    }
   
    public function update($table, &$row, $field = 'id') {
        $sqlA = '';
        foreach($row as $k=>$v){
            $sqlA .= "`$k` = '$v',";
        }

        $sqlA = substr($sqlA, 0, strlen($sqlA)-1);
        if(is_object($row)){
            $id = $row->{$field};
        }else if(is_array($row)){
            $id = $row[$field];
        }
        $sql  = "update `{$table}` set $sqlA where `{$field}`='$id'";
        return $this->query($sql);
    }
    public function remove($table, $id, $field='id'){
        $sql  = "delete from `{$table}` where `{$field}`='{$id}'";
        return $this->query($sql);
    }

    public function escape(&$val){
        if(is_object($val) || is_array($val)){
            $this->escape_row($val);
        }
    }
    public function escape_row(&$row){
        if(is_object($row)){
            foreach($row as $k=>$v){
                $row->$k = sqlite_real_escape_string($v);
            }
        }else if(is_array($row)){
            foreach($row as $k=>$v){
                $row[$k] = sqlite_real_escape_string($v);
            }
        }
    }
    public function escape_like_string($str){
        $find = array('%', '_');
        $replace = array('\%', '\_');
        $str = str_replace($find, $replace, $str);
        return $str;
    }
}
?>
