<?php
/**
 * Validate 	email|phone
 */
class Validate {
	function __construct() {

	}

	//	email validator (
	function verifyEmailLi($email,$fld='',$msg='') {
		if($msg=='')
			$msg="<li><strong>$fld:</strong> You must enter a valid $fld.</li>";
		
		return (filter_var($email, FILTER_VALIDATE_EMAIL))?'':$msg;
	}

	function verifyEmail($email,$fld='',$msg='') {
		if($msg=='')
			$msg="<strong>&bull; $fld:</strong> You must enter a valid $fld.<br />";
		
		return (filter_var($email, FILTER_VALIDATE_EMAIL))?'':$msg;
	}
	//	) email validator

	//	phone number validator (
	function verifyPhoneLi($phone,$fld='',$msg='') {
		if($msg=='')
			$msg="<li><strong>$fld:</strong> You must enter a valid $fld.</li>";
		return !preg_match("/^(\d{3})-(\d{3})-(\d{4})$/", $phone)?$msg:'';
		//return !preg_match("/^([0-9+-_]{6,15})$/", $phone)?$msg:'';
	}

	function verifyPhone($phone,$fld='',$msg='') {
		if($msg=='')
			$msg="<strong>&bull; $fld:</strong> You must enter a valid $fld.<br />";
		return !preg_match("/^(\d{3})-(\d{3})-(\d{4})$/", $phone)?$msg:'';
		//return !preg_match("/^([0-9+-_]{6,15})$/", $phone)?$msg:'';
	}
	//	) phone number validator

	// is empty
	function isEmpty($str,$openTag='',$closeTag='') {
		$returnstring='';
		print_r($str);
		if(count($str)>0) {
			foreach($str as $fieldname=>$fieldvalue)
				if(trim($fieldvalue)==NULL)
					$returnstring.='<strong>'.$fieldname.':</strong> You must enter a valid '.$fieldname;
				else
					$returnstring.='';
			$returnstring=($openTag!='' && $closeTag!='')?($openTag.$returnstring.$closeTag):$returnstring;
		}
		return $returnstring;
	}
	// /is empty
}
$validateObj = new Validate;
?>