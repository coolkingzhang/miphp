<?php
/*
 *
 *邮件类
 */
class emailModel extends MOD_BASE
{
    /**
     * 发送邮件 
     */
	private $email;
	public function __construct() 
	{
		$this->email = CORE::N('email/phpmailer');
	}
    function send_message($email = '',$subject = '',$body = '',$fronName = EMAIL_TITLE )
    {      
		$this->email->IsSMTP();      
		$this->email->Host 			= EMAIL_SMTP;   
		$this->email->SMTPAuth 		= TRUE;
		$this->email->Username 		= EMAIL_USERNAME;  
		$this->email->Password 		= EMAIL_PWD;
		$this->email->From 			= EMAIL_USERNAME;
		$this->email->FromName 		= $fronName;
		$this->email->AddAddress($email);
		$this->email->WordWrap 		= 50;           
		$this->email->IsHTML(TRUE);   
		$this->email->Subject 		= $subject; 			
		$this->email->Body  		= $body; 
		if($this->email->Send())
		{
			return TRUE;
		} 
		else 
		{	
		   	return FALSE;
		}      
    }
    function test() 
    {
    	echo '<br/><br/>hello<br/>';
    }
}
?>
