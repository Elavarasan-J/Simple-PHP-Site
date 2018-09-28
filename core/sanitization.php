<?php 
defined('BASE_PATH') OR exit('No direct script access allowed');
	 
class Sanitization {
	function __construct() {

	}

	function cleanSQL($str) {
		return mysql_real_escape_string($str);
	}
	function cleanHTML($str) {
		return htmlentities($str, ENT_QUOTES);
	}
	function revertHTML($str) {
		return html_entity_decode($str, ENT_QUOTES);
	}
	function cleanText($str) {
		$s = $str;
		if(!ini_get('magic_quotes_gpc')) {
			$s = addslashes($s);
		}
		return $s;
	}
	function checkLength($str, $acceptableLength) {
		if(strlen($str) >= $acceptableLength) {
			return true;
		}
		else			{
			return false;
		}
	}
}
$sanitizationObj = new Sanitization;
?>