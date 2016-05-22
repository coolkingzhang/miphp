<?php
/// 站点安装目录 / 为根目录
define('WEB_ROOT','http://www.myorange.cn/');
/// 网站标题
define('WEB_TITLE','鲜橙网-大学生的社闭交流平台');

/// 定义微博来源
define('WEIBO','鲜橙说说');

/// 发邮件来源
define('EMAIL_TITLE','鲜橙网-大学生的社闭交流平台');


define('EMAIL_OUTTIME',259200);   //邮箱没验证，3天的过期时间


//自定义邮箱的网址
function return_email_array()
{
  return array(
            '163'=>'http://mail.163.com/',
            '126'=>'http://mail.126.com/',
            'sina'=>'http://mail.sina.com.cn/',
            'yahoo'=>'http://mail.cn.yahoo.com/',
            'sohu'=>'http://mail.sohu.com/',
            'yeah'=>'http://www.yeah.net/',
            '139'=>'http://mail.10086.cn/',
            'tom'=>'http://mail.tom.com/',
            '21cn'=>'http://mail.21cn.com/',
            'qq'=>'https://mail.qq.com/',
      );  
}

?>