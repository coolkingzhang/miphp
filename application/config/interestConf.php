<?php

/*
 * 用户的兴趣标签
 * 
 * 
 * $array 				最终返回兴趣的数组
 * $array['list'] 		所有兴趣的列表
 * $array['category']	兴趣分类
 * $array['verb']		推荐的兴趣
 * 
 * verb = 1 是推荐  verb = 0 是不推荐
 */

$array['category']['运动'][101] =  array('id'=>101,'name'=>'篮球','verb'=>1);
$array['category']['运动'][102] =  array('id'=>102,'name'=>'足球','verb'=>1);
$array['category']['运动'][103] =  array('id'=>103,'name'=>'绘画','verb'=>0);
$array['category']['运动'][104] =  array('id'=>104,'name'=>'棋牌','verb'=>0);

$array['category']['科技'][201] =  array('id'=>201,'name'=>'电子','verb'=>0);
$array['category']['科技'][202] =  array('id'=>202,'name'=>'互联网','verb'=>1);
$array['category']['科技'][203] =  array('id'=>203,'name'=>'电子商务','verb'=>1);

$array['category']['艺术'][301] =  array('id'=>301,'name'=>'摄像','verb'=>0);
$array['category']['艺术'][302] =  array('id'=>302,'name'=>'书法','verb'=>1);
$array['category']['艺术'][303] =  array('id'=>303,'name'=>'音乐','verb'=>0);

///所有兴趣列表
foreach($array['category'] as $key => $val) {
	foreach($val as $k => $v) {
		$array['list'][$k] = $v;
		if($v['verb'] == 1) {
			$array['verb'][$k] = $v;
		}
	}
}
return $array;


?>