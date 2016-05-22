<?php 

class googleClass
{  
    public $opts = array("text" => "", "language_pair" => "en|it");  
    public $out = "";     
   	function __construct() {}  
    function setOpts($opts) {  
        if($opts["text"] != "") $this->opts["text"] = $opts["text"];  
        if($opts["language_pair"] != "") $this->opts["language_pair"] = $opts["language_pair"];  
    }  
   
    function translate() 
    {  
        $this->out = "";  
        $google_translator_url = "http://google.com/translate_t?langpair=";  
        $google_translator_url .= urlencode($this->opts["language_pair"])."&";  
        $google_translator_url .= "text=".urlencode($this->opts["text"]);  
        $gphtml = $this->getPage(array("url" => $google_translator_url));  
		
		//echo $gphtml;
        //exit;
		preg_match('/\'\">(.*?)<\/span>/is', $gphtml, $out);  
		//print_r($out);

        $this->out = utf8_encode($out[1]);  
        return $this->out;  
    }  
   
    function getPage($opts) 
    {  
        $html = "";  
        if($opts["url"] != "") {  
            $ch = curl_init($opts["url"]);  
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  
            curl_setopt($ch, CURLOPT_HEADER, 0);  
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);  
            curl_setopt($ch, CURLOPT_TIMEOUT, 15);  
            $html = curl_exec($ch);  
            if(curl_errno($ch)) {  
                $html = "";  
            }  
            curl_close ($ch);  
        }  
        return $html;  
    }  
}
?>