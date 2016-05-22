<?php
class desCryptClass {

	function encrypt($encrypt,$key="") {
		$iv = mcrypt_create_iv ( mcrypt_get_iv_size ( MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB ), MCRYPT_RAND );
		$passcrypt = mcrypt_encrypt ( MCRYPT_RIJNDAEL_256, $key, $encrypt, MCRYPT_MODE_ECB, $iv );
		$encode = base64_encode ( $passcrypt );
		return $encode;
	}

	//解密函数：decrypt
	function decrypt($decrypt,$key="") {
		$decoded = base64_decode ( $decrypt );
		$iv = mcrypt_create_iv ( mcrypt_get_iv_size ( MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB ), MCRYPT_RAND );
		$decrypted = mcrypt_decrypt ( MCRYPT_RIJNDAEL_256, $key, $decoded, MCRYPT_MODE_ECB, $iv );
		return $decrypted;
	}

}
/*
$new = encrypt("wo shi old","111");
$old = decrypt($new,"111");
echo $new."的明文是："."<br>";
echo $old;
*/ 


?>