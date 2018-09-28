<?php
defined('BASE_PATH') OR exit('No direct script access allowed');

class Password {
	private $str,$swapped_word;

	function __construct() {
	}
	
	function generatePassword() {
		$str_arr[0] = range('a','z');
		$str_arr[1] = range('A','Z');
		$str_arr[2] = range('0','9');
		$str_arr[3] = explode(',',"!,@,#,$,^,_,-,~");
		$length=rand(10,16);
		while($length--) {
			$key1=array_rand($str_arr);
			$key2=rand(0,(count($str_arr[$key1])-1));
			$this->str.=$str_arr[$key1][$key2];
		}
		return $this->str;
	}

	function encrypt($string, $key) {
		$result = '';
		$string=trim($string);
		$string=strrev($string);
		$strlen=strlen($string);
		for($i=0;$i<$strlen;$i+=2) {
			$this->swapped_word.=substr($string,$i+1,1).substr($string,$i,1);
		}
		for($i=0; $i<$strlen; $i++) {
			$char = substr($string, $i, 1);
			$rchar = substr($this->swapped_word, $i, 1);
			$keychar = substr($key, ($i % strlen($key))-1, 1);
			$char = hash('sha256', $keychar.$char.$keychar.$rchar);
			$result.=$char;
		}
		return base64_encode(hash('sha256', $result));
	}

	function encrypt_old($string, $key) {
		$result = '';
		for($i=0; $i<strlen($string); $i++) {
			$char = substr($string, $i, 1);
			$keychar = substr($key, ($i % strlen($key))-1, 1);
			$char = chr(ord($char)+ord($keychar));
			$result.=$char;
		}
		return base64_encode($result);
	}
	function decrypt_old($string, $key) {
		$result = '';
		$string = base64_decode($string);
		for($i=0; $i<strlen($string); $i++) {
			$char = substr($string, $i, 1);
			$keychar = substr($key, ($i % strlen($key))-1, 1);
			$char = chr(ord($char)-ord($keychar));
			$result.=$char;
		}
		return $result;
	}
}
$passwordObj = new Password;
?>