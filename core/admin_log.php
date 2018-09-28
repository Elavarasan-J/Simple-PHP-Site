<?php
defined('BASE_PATH') OR exit('No direct script access allowed');

class AdminLog {
	function __construct() {
		$this->pathObj = new Path;
		$this->passwordObj = new Password;
		$this->utilityObj = new Utility;
		$this->checkLogin();
	}

	function checkLogin() {
		if( isset($_SESSION[ADMIN_SESSION]['user_id']) &&
			$_SESSION[ADMIN_SESSION]['user_id']!='' &&
			$_SESSION[ADMIN_SESSION]['user_session_id']!=$this->passwordObj->encrypt(SESSION_ID,$_SESSION[ADMIN_SESSION]['user_id']) )
		{
				unset($_SESSION[SESSION]);
				unset($_SESSION[ADMIN_SESSION]);
				$_SESSION[ADMIN_SESSION]['url']=$this->pathObj->url;
				$this->utilityObj->headerLocation(ADMIN_PATH."login.php?expired=1");
				//header("location:login.php?expired=1"); exit;
		}
		else if( !isset($_SESSION[ADMIN_SESSION]['user_id']) || $_SESSION[ADMIN_SESSION]['user_id']=='')
		{
				$_SESSION[ADMIN_SESSION]['url']=$this->pathObj->url;
				$this->utilityObj->headerLocation(ADMIN_PATH."login.php");
				//header("location:login.php"); exit;
		}
	}
}
$adminLogObj = new AdminLog;
?>