<?php

/*
 * 活动接口api 
 * 
 */
//include_once(ROOT_CLASS.'/api.php');
CORE::In('api','class');   

//////	加载api基类
class ActivityClass extends api
{	
	/*
	 * 获取我发起的活动			public function get($uid = '',$utype = '',$count = 5,$page = 1)
	 * $type					1060012
	 * $uid						用户的uid
	 * $utype					用户的类型
	 * $count 					每页的记录数 		默认是 5 
	 * $page    				当前页码			默认值是  1
	 * $access_toen 			通行证
	 */
	public function get($uid = '',$utype = '',$count = 5,$page = 1,$pp = 1)  
	{
		$p['type']				= 1060012;
		$p['uid']				= $uid;
		$p['p']					= $pp;
		$p['count']				= $count;
		$p['page']				= $page;
		$p['utype']				= $utype; 
		$p['access_token']		= $this->access_token;
		return  $this->return_data($p);
	} 
	
	/*
	 * 删除我发起的活动	public function delete($uid = '',$id = '') 
	 * $type	1060021
	 * $uid		用户的uid
	 * $id		活动的id号
	 * $access_toen 	通行证
	 */
	public function delete($uid = '',$id = '') 
	{
		$p['type']				= 1060021;
		$p['uid']				= $uid;
		$p['id']				= $id;
		$p['access_token']		= $this->access_token;
		return	$this->return_data($p); 
	} 
	
	/*
	 * 发起活动	public function create($array = array()) 
	 * $type						1060031
	 * $array['uid']				用户的uid
	 * $array['title']				活动标题
	 * $array['association_id']		所属社团id
	 * $array['start_time']			开始时间
	 * $array['end_time']			结束时间
	 * $array['province_id']		省份id
	 * $array['city_id']			城市id
	 * $array['address']			活动详细地址
	 * $array['content']			活动内容
	 * $array['act_type']			活动类型：1本校活动，2同城活动，3线上活动
	 * $array['fans_list']			要邀请的粉丝的列表, 只有发给自己的粉丝,最多20人,非自己粉丝写了将无效
	 * $array['img']				活动图片的id号
	 * $array['img_src']			活动的图片url
	 * $array['acount']				活动人员数			默认是0
	 * $access_toen 				通行证
	 */
	
	public function create($array = array()) 
	{
		$p['type']				= 1060031;
		$p['uid']				= $array['uid'];
		$p['title']				= $array['title'];
		$p['association_id']	= $array['association_id'];
		$p['start_time']		= $array['start_time'];
		$p['end_time']			= $array['end_time'];
		$p['province_id']		= $array['province_id'];
		$p['city_id']			= $array['city_id'];
		$p['address']			= $array['address'];
		$p['content']			= $array['content'];
		$p['act_type']			= $array['act_type'];
		$p['fans_list']			= $array['fans_list'];
		$p['img']				= $array['img'];
		$p['img_src']			= $array['img_src'];
		$p['acount']			= $array['acount'];
		$p['access_token']		= $this->access_token;
		return  $this->return_data($p);
	} 
	
	/*
	 * 修改我发起活动	public function edit($array = array()) 
	 * $type						1060041
	 * $array['uid']				用户的uid
	 * $array['id']					活动的id号
	 * $array['title']				活动标题
	 * $array['start_time']			开始时间
	 * $array['end_time']			结束时间
	 * $array['province_id']		省份id
	 * $array['city_id']			城市id
	 * $array['address']			活动详细地址
	 * $array['content']			活动内容
	 * $array['fans_list']			要邀请的粉丝的列表, 只有发给自己的粉丝,最多20人,非自己粉丝写了将无效
	 * $array['img']				活动图片的id号
	 * $array['img_src']			活动的图片url
	 * $access_toen 				通行证
	 */
	 
	public function edit($array = array()) 
	{
		$p['type']				= 1060041;
		$p['id']				= $array['id'];
		$p['uid']				= $array['uid'];
		$p['title']				= $array['title'];
		$p['start_time']		= $array['start_time'];
		$p['end_time']			= $array['end_time'];
		$p['province_id']		= $array['province_id'];
		$p['city_id']			= $array['city_id'];
		$p['address']			= $array['address'];
		$p['content']			= $array['content'];
		$p['fans_list']			= $array['fans_list'];
		$p['img']				= $array['img'];
		$p['img_src']			= $array['img_src'];
		$p['access_token']		= $this->access_token;
		return  $this->return_data($p);
	}
	
	/*
	 * 获取我参与的活动	public function join($array = array()) 
	 * $type					1060052
	 * $uid						用户的uid
	 * $count					每页的记录数		默认是 5
	 * $page					当前页码 			默认值是  1
	 * $atype					活动情况 1 即将开始 2 正在进行 3 已结束 4 全部
	 * $access_toen 				通行证
	 */
	public function join($uid = '',$count = 5,$atype  = '4',$page = 1) {
		$p['type']				= 1060052;
		$p['uid']				= $uid;
		$p['atype']				= $atype;
		$p['page']				= $page;
		$p['count']				= $count;
		$p['access_token']		= $this->access_token;
		return  $this->return_data($p);
	}
	
	/*
	 * 获取我参与的活动的成员信息	 	public function user($uid = '',$id = '',$count = 10,$page = 1)
	 * $uid							用户的uid
	 * $id							活动的id号
	 * $page						当前页 默认是1
	 * $count 						每页记录数，默认是10	
	 * $access_toen 				通行证				
	 */
	public function user($uid = '',$aid = '',$count = 10,$page = 1) 
	{
		$p['type']				= 1060062;
		$p['uid']				= $uid;
		$p['page']				= $page;
		$p['count']				= $count;
		$p['aid']				= $aid;
		$p['access_token']		= $this->access_token;
		return  $this->return_data($p);
	}
	
	/*
	 * 获取活动的详细信息	 		public function about($uid = '',$id = '')
	 * $uid						用户的uid
	 * $id						活动的id号	
	 * $access_toen 			通行证			
	 */
	public function about($uid = '',$id) 
	{
		$p['type']				= 1060072;
		$p['uid']				= $uid;
		$p['id']				= $id;
		$p['access_token']		= $this->access_token;
		return  $this->return_data($p);
	}
	/*
	 * 获取活动的评论	 		public function get_comment($aid = '',$count = 5,$page = 1) 
	 * $type 	true int 		1060082
	 * $aid						活动的aid
	 * $count	true int       	每页记录数   默认 5
	 * $page	true int 		当前页数       默认是1 			
	 */
	public function get_comment($aid = '',$count = 5,$page = 1) 
	{
		$p['type']				= 1060082;
		$p['aid']				= $aid;
		$p['count']				= $count;
		$p['page']				= $page;
		$p['access_token']		= $this->access_token;
		return  $this->return_data($p);
	}
	/*
	 * 删除活动的评论	 		public function delete_comment($aid = '',$id = '',$uid = '') 
	 * $type 	true int 		1060091
	 * $aid		true int		活动的aid
	 * $id		true int		活动的评论id号
	 * $uid		true int 		用户的uid 			
	 */
	public function delete_comment($aid = '',$id = '',$uid = '') 
	{
		$p['type']				= 1060091;
		$p['aid']				= $aid;
		$p['id']				= $id;
		$p['uid']				= $uid;
		$p['access_token']		= $this->access_token;
		return  $this->return_data($p);
	}
	/*
	 * 发起活动的评论	 		public function	comment($aid = '',$uid = '',$content = '') 
	 * $type 	true int 		1060101
	 * $aid		true int		活动的aid
	 * $uid		true int 		用户的uid 
	 * $content true string     评论的内容 		
	 */
	public function comment($aid = '',$uid = '',$content = '') 
	{
		$p['type']				= 1060101;
		$p['aid']				= $aid;
		$p['content']			= $content;
		$p['uid']				= $uid;
		$p['access_token']		= $this->access_token;
		return  $this->return_data($p);
	}
	
	/*
	 * 用户参加活动	 		public function	join_in($aid = '',$uid = '') 
	 * $type 	true int 		1060191
	 * $aid		true int		活动的aid
	 * $uid		true int 		用户的uid 	
	 */
	public function join_in($aid = '',$uid = '') 
	{
		$p['type']				= 1060191;
		$p['aid']				= $aid;
		$p['uid']				= $uid;
		$p['access_token']		= $this->access_token;
		return  $this->return_data($p);
	}
	
	//////////////////////////////	借东风
	
	/*
	 * 发起借东风	public function show_create($array = array()) 
	 * 
	 * $type	 					1060111
	 * $array['uid']				用户的uid 
	 * $array['about_us']			社团简介
	 * $array['target']				活动目标 
	 * $array['title']				借东风标题
	 * $array['plans']				活动目标
	 * $array['start_time']			开始时间
	 * $array['end_time']			结束时间
	 * $array['address']			活动详细地址
	 * $array['content']			活动内容
	 * $array['img_src']			活动封面
	 * $access_toen 				通行证
	 */
	
	public function show_create($array = array()) 
	{
		$p['type']				= 1060111;
		$p['uid']				= $array['uid'];
		$p['title']				= $array['title'];
		$p['about_us']			= $array['about_us'];
		$p['start_time']		= $array['start_time'];
		$p['end_time']			= $array['end_time'];
		$p['address']			= $array['address'];
		$p['content']			= $array['content'];
		$p['plans']				= $array['plans'];
		$p['img_src']			= $array['img_src'];
		$p['target']			= $array['target'];
		$p['access_token']		= $this->access_token;
		return  $this->return_data($p);
	} 
	
	/*
	 * 编辑借东风	public function show_edit($array = array()) 
	 * 
	 * $type						1060121
	 * $array['uid']				用户的uid 
	 * $array['id']					借东风的id号
	 * $array['about_us']			社团简介
	 * $array['target']				活动目标 
	 * $array['title']				借东风标题
	 * $array['plans']				活动目标
	 * $array['start_time']			开始时间
	 * $array['end_time']			结束时间
	 * $array['address']			活动详细地址
	 * $array['content']			活动内容
	 * $array['img_src']			活动封面
	 * $access_toen 				通行证
	 */
	
	public function show_edit($array = array()) 
	{
		$p['type']				= 1060121;
		$p['id']				= $array['id'];
		$p['uid']				= $array['uid'];
		$p['title']				= $array['title'];
		$p['about_us']			= $array['about_us'];
		$p['start_time']		= $array['start_time'];
		$p['end_time']			= $array['end_time'];
		$p['address']			= $array['address'];
		$p['content']			= $array['content'];
		$p['plans']				= $array['plans'];
		$p['img_src']			= $array['img_src'];
		$p['target']			= $array['target'];
		$p['access_token']		= $this->access_token;
		return  $this->return_data($p);
	} 
	
	/*
	 * 删除我发起的借东风 		public function show_delete($uid = '',$id = '') 
	 * $type					1060131
	 * $uid						用户的uid
	 * $id						借东风的id号
	 * $access_toen 			通行证
	 */
	
	public function show_delete($uid = '',$id = '') 
	{
		$p['type']				= 1060131;
		$p['uid']				= $uid;
		$p['id']				= $id;
		$p['access_token']		= $this->access_token;
		return	$this->return_data($p); 
	} 
	
	/*
	 * 获取借东风的详细信息	 	public function show_detail($uid = '',$id = '')
	 * $type					1060142
	 * $uid						用户的uid
	 * $id						借东风的id号	
	 * $access_toen 			通行证			
	 */
	public function show_detail($uid = '',$id) 
	{
		$p['type']				= 1060142;
		$p['uid']				= $uid;
		$p['id']				= $id;
		$p['access_token']		= $this->access_token;
		return  $this->return_data($p);
	}
	
	
	/*
	 * 获取借东风的评论	 		public function show_get_comment($aid = '',$count = 5,$page = 1) 
	 * $type 	true int 		1060152
	 * $aid						借东风的aid
	 * $count	true int       	每页记录数   默认 5
	 * $page	true int 		当前页数       默认是1 			
	 */
	public function show_get_comment($aid = '',$count = 5,$page = 1) 
	{
		$p['type']				= 1060152;
		$p['aid']				= $aid;
		$p['count']				= $count;
		$p['page']				= $page;
		$p['access_token']		= $this->access_token;
		return  $this->return_data($p);
	}
	/*
	 * 删除借东风的评论	 		public function show_delete_comment($aid = '',$id = '',$uid = '') 
	 * $type 	true int 		1060161
	 * $aid		true int		借东风的aid
	 * $id		true int		借东风的评论id号
	 * $uid		true int 		用户的uid 			
	 */
	public function show_delete_comment($aid = '',$id = '',$uid = '') 
	{
		$p['type']				= 1060161;
		$p['aid']				= $aid;
		$p['id']				= $id;
		$p['uid']				= $uid;
		$p['access_token']		= $this->access_token;
		return  $this->return_data($p);
	}
	/*
	 * 发起活动的评论	public function	show_comment($aid = '',$uid = '',$content = '') 
	 * $type 			true int 		1060171
	 * $aid				true int		借东风的aid
	 * $uid				true int 		用户的uid 
	 * $content 		true string     评论的内容 	
	 * $access_token	false string	通行证	
	 */
	public function show_comment($aid = '',$uid = '',$content = '') 
	{
		$p['type']				= 1060171;
		$p['aid']				= $aid;
		$p['content']			= $content;
		$p['uid']				= $uid;
		$p['access_token']		= $this->access_token;
		return  $this->return_data($p);
	}
	
	/*
	 * 获得借东风列表	public function	show_list($uid = '',$count = 5,$page = 1) 
	 * 
	 * $type			true int 		1060182
	 * $page			true int		当前页 默认是 1
	 * $uid				true int 		用户的uid 
	 * $count 			true string     每页记录数  默认 5 	
	 * $access_toke		false	string  通行证
	 */
	public function show_list($uid = '',$count = 5,$page = 1) 
	{
		$p['type']				= 1060182;
		$p['page']				= $page;
		$p['count']				= $count;
		$p['uid']				= $uid;
		$p['access_token']		= $this->access_token;
		return  $this->return_data($p);
	}
}  
?>