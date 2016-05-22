<?php 
/**
 * filename: MypageClass.php
 * @package:phpbean
 * @author :feifengxlq<feifengxlq#gmail.com>
 * @copyright :Copyright 2006 feifengxlq
 * @license:version 2.0
 * @create:2006-5-31
 * @modify:2006-6-1
 * @modify:feifengxlq 2006-11-4
 * description:超强分页类，四种分页模式，默认采用类似baidu,google的分页风格。
 * 2.0增加功能：支持自定义风格，自定义样式，同时支持PHP4和PHP5,
 * to see detail,please visit http://www.phpobject.net/blog/read.php
 * example:
 * Four model：
   require_once('../libs/classes/page.class.php');
   $page=new page(array('total'=>1000,'perpage'=>20));
   echo 'mode:1<br>'.$page->show();
   echo '<hr>mode:2<br>'.$page->show(2);
   echo '<hr>mode:3<br>'.$page->show(3);
   echo '<hr>mode:4<br>'.$page->show(4);
   open AJAX：
   $ajaxpage=new page(array('total'=>1000,'perpage'=>20,'ajax'=>'ajax_page','page_name'=>'test'));
   echo 'mode:1<br>'.$ajaxpage->show();
   Use the inherited customizated pagination display model.
   demo:[url=http://www.phpobject.net/blog]http://www.phpobject.net/blog[/url]
 */

class MypageClass 
{
	/**
	* config ,public
	*/
  
	var $page_name="page";//page tag, control the page number,for example, example.php?PB_page=2
	var $next_page='>';//prev page
	var $pre_page='<';//next page
	var $first_page='第一页';//The first page
	var $last_page='最后一页';//The last page
	var $pre_bar='<<';//Prev page bar
	var $next_bar='>>';//Next page bar
	var $format_left='';
	var $format_right=''; 
	var $is_ajax=false;//Support ajax
	var $router = '/index.php/success_case/index/';
	var $html = '';
 
 /**
  * private
  *
  */ 
 var $pagebarnum=10;//number per page.
 var $totalpage=0;//Toal page
 var $ajax_action_name= 'atten_view';//ajax callback
 var $nowindex=1;//current page
 var $url="";//the head of page url
 var $offset=0;
 
 /**
  * constructor
  *
  * @param array $array['total'],$array['perpage'],$array['nowindex'],$array['url'],$array['ajax']...
  */
 function Mypage($array)
 {
  
  if(is_array($array)){
     if(!array_key_exists('total',$array))$this->error(__FUNCTION__,'need a param of total');
     $total=intval($array['total']);
     $perpage=(array_key_exists('perpage',$array)) ? intval($array['perpage']):10;
     $nowindex=(array_key_exists('nowindex',$array)) ? intval($array['nowindex']):'';
     $url=(array_key_exists('url',$array))?$array['url']:'';
	 if (array_key_exists('router',$array)){
	 	$this->router = $array['router'];
	 }
	 if (array_key_exists('pagebarnum',$array)){
	 	$this->pagebarnum = $array['pagebarnum'];
	 }
	 if (array_key_exists('html',$array)){
	 	$this->html = $array['html'];
	 }
	 if(@$array['page_name'] == 'ajax_page') {
	 	@$this->is_ajax = TRUE;
	 }
  }else{
     $total=$array;
     $perpage = COUNT_PER_PAGE_FRONT;
     //$perpage=10;
     $nowindex='';
     $url='';
  }
  if((!is_int($total))||($total<0))$this->error(__FUNCTION__,$total.' is not a positive integer!');
  if((!is_int($perpage))||($perpage<=0))$this->error(__FUNCTION__,$perpage.' is not a positive integer!');
  if(!empty($array['page_name']))$this->set('page_name',$array['page_name']);//设置pagename
  $this->_set_nowindex($nowindex);//set the current page
  $this->_set_url($url);//set the href source
  $this->totalpage=ceil($total/$perpage);
  $this->offset=($this->nowindex-1)*$perpage;
  if(!empty($array['ajax']))$this->open_ajax($array['ajax']);//open ajax mode
 }
 /**
  * 设定类中指定变量名的值，如果改变量不属于这个类，将throw一个exception
  *
  * @param string $var
  * @param string $value
  */
 function set($var,$value)
 {
  if(in_array($var,get_object_vars($this)))
     $this->$var=$value;
  else {
   $this->error(__FUNCTION__,$var." does not belong to PB_Page!");
  }
  
 }
 /**
  * Open ajax mode
  *
  * @param string $action default callback function for ajax.
  */
 function open_ajax($action = 'page_ajax')
 {
  $this->is_ajax=true;
  $this->ajax_action_name=$action;
 }
 /**
  * Fetch the html code for displaying the next page bar. 
  * 
  * @param string $style
  * @return string
  */
 function next_page($style='meneame')
 {
  if($this->nowindex < $this->totalpage){
   return $this->_get_link($this->_get_url($this->nowindex+1),$this->next_page,$style);
  }
  return '<span class="'.$style.'">'.$this->next_page.'</span>';
 }
 
 /**
  * Fetch the html code for displaying the prev page bar.
  *
  * @param string $style
  * @return string
  */
 function pre_page($style='meneame')
 {
  if($this->nowindex>1){
   return $this->_get_link($this->_get_url($this->nowindex-1),$this->pre_page,$style);
  }
  return '<span class="'.$style.'">'.$this->pre_page.'</span>';
 }
 
 /**
  * Fetch the html code for displaying bar which lead to the first page.
  *
  * @return string
  */
 function first_page($style='meneame')
 {
  if($this->nowindex==1){
      return '<span class="'.$style.'">'.$this->first_page.'</span>';
  }
  return $this->_get_link($this->_get_url(1),$this->first_page,$style);
 }
 
 /**
  * Fetch the html code for displaying bar which lead to the last page.
  *
  * @return string
  */
 function last_page($style='meneame')
 {

  if($this->nowindex==$this->totalpage){
      return '<span class="'.$style.'">'.$this->last_page.'</span>';
  }
  return $this->_get_link($this->_get_url($this->totalpage),$this->last_page,$style);
 }
 
 function nowbar($style='meneame',$nowindex_style='meneame')
 {
  $plus=ceil($this->pagebarnum/2);
  if($this->pagebarnum-$plus+$this->nowindex>$this->totalpage)$plus=($this->pagebarnum-$this->totalpage+$this->nowindex);
  $begin=$this->nowindex-$plus+1;
  $begin=($begin>=1)?$begin:1;
  $return='';
  for($i=$begin;$i<$begin+$this->pagebarnum;$i++)
  {
   if($i<=$this->totalpage){
    if($i!=$this->nowindex)
        $return.=$this->_get_text($this->_get_link($this->_get_url($i),$i,$style));
    else 
        $return.=$this->_get_text('<span class="'.$nowindex_style.'">'.$i.'</span>');
   }else{
    break;
   }
   $return.="\n";
  }
  unset($begin);
  return $return;
 }
 /**
  * Create the select form bar.
  *
  * @return string
  */
 function select()
 {
$return = '';	
  return $return;
 }
  function select2()
 {
   $return='<select name="PB_Page_Select">';
  for($i=1;$i<=$this->totalpage;$i++)
  {
   if($i==$this->nowindex){
    $return.='<option value="'.$i.'" selected>'.$i.'</option>';
   }else{
    $return.='<option value="'.$i.'">'.$i.'</option>';
   }
  }
  unset($i);
  $return.='</select>';
  return $return;
 }
 
 /**
  * Get the value for limit option offset in mysql sentence.
  *
  * @return string
  */
 function offset()
 {
  return $this->offset;
 }
 
 /**
  * Manage the style of the pagination bar. Can customize the style.
  *
  * @param int $mode
  * @return string
  */
 function show($mode=1)
 {
  switch ($mode)
  {
  	case '1':
    $this->next_page='下一页';
    $this->pre_page='上一页';
    return $this->pre_page().' '.$this->nowbar().' '.$this->next_page().' '.''.$this->select().'';
   	break;
  
   	//// float left
   case '2':
    $this->next_page='下一页';
    $this->pre_page='上一页';
    return $this->pre_page().' '.$this->nowbar().' '.$this->next_page().' '.'';
    break;
    
   	//// float right
    case '8':
    $this->next_page='下一页';
    $this->pre_page='上一页';
    print_r($this->nowbar());
    print_r(explode('\n',$this->nowbar()));
    return $this->next_page().' '.$this->nowbar().' '.$this->pre_page();
    break;
    
   case '12':
    $this->next_page='Next';
    $this->pre_page='Prev';
    $this->first_page='Home';
    $this->last_page='Last Page';
    return $this->first_page().$this->pre_page().'[No:'.$this->nowindex.']'.$this->next_page().$this->last_page().'Page:'.$this->select().'';
    break;
   case '3':
    $this->next_page='Next';
    $this->pre_page='Prev';
    $this->first_page='Home';
    $this->last_page='Last Page';
    return $this->first_page().$this->pre_page().$this->next_page().$this->last_page();
    break;
   case '4':
    $this->next_page='Next';
    $this->pre_page='Prev';
    return $this->pre_page().$this->nowbar().$this->next_page();
    break;
   default:
    return $this->first_page().$this->pre_page().'[No:'.$this->nowindex.']'.$this->next_page().$this->last_page().'Page:'.$this->select().'';
    break;
  }
  
 }
/*----------------private function -----------------------------------------------------------*/
 /**
  * Set the head of url
  * 
  * @param: String $url
  * @return boolean
  */
 function _set_url($url="")
 {
  $this->url= '';
 }
 
 function _set_url2($url="")
 {
  if(!empty($url)){
      //Manual setting
   $this->url=$url.((stristr($url,'?'))?'&':'?').$this->page_name."=";
  }else{
      //Automatic fetch
   if(empty($_SERVER['QUERY_STRING'])){
       //When QUERY_STRING isn't existed.
    $this->url=$_SERVER['REQUEST_URI']."?".$this->page_name."=";
   }else{
       //
    if(stristr($_SERVER['QUERY_STRING'],$this->page_name.'=')){
        //地址存在页面参数
     $this->url=str_replace($this->page_name.'='.$this->nowindex,'',$_SERVER['REQUEST_URI']);
     $last=$this->url[strlen($this->url)-1];
     if($last=='?'||$last=='&'){
         $this->url.=$this->page_name."=";
     }else{
         $this->url.='&'.$this->page_name."=";
     }
    }else{
        //
     $this->url=$_SERVER['REQUEST_URI'].'&'.$this->page_name.'=';
    }//end if    
   }//end if
  }//end if
 }
 
 /**
  * Set the page number for current page
  *
  */
 function _set_nowindex($nowindex)
 {
  	$this->nowindex=intval($nowindex);
 }
 function _set_nowindex2($nowindex)
 {
  if(empty($nowindex)){
   //系统获取
   
   if(isset($_GET[$this->page_name])){
    $this->nowindex=intval($_GET[$this->page_name]);
   }
  }else{
      //手动设置
   //$this->nowindex=intval($nowindex);
  	$this->nowindex=intval($nowindex);
  }
 }
  
 /**
  * 为指定的页面返回地址值
  *
  * @param int $pageno
  * @return string $url
  */
 function _get_url($pageno=1)
 {
  return $this->url.$pageno;
 }
 
 /**
  * 获取分页显示文字，比如说默认情况下_get_text('<a href="">1</a>')将返回[<a href="">1</a>]
  *
  * @param String $str
  * @return string $url
  */ 
 function _get_text($str)
 {
  return $this->format_left.$str.$this->format_right;
 }
 
 /**
   * 获取链接地址
 */
 function _get_link($url='',$text,$style=''){
  $style=(empty($style))?'':'class="'.$style.'"';
  if($this->is_ajax){
      //如果是使用AJAX模式
   return '<a '.$style.' href="javascript:'.$this->ajax_action_name.'(\''.$url.'\')">'.$text.'</a>';
  }else{
  $router = $this->router;
   return '<a '.$style.' href="'.$router.$url.$this->html.'">'.$text.'</a>';
  }
 }
 /**
   * Handle errors 
 */
 function error($function,$errormsg)
 {
     die('Error in file <b>'.__FILE__.'</b> ,Function <b>'.$function.'()</b> :'.$errormsg);
 }
}

?>