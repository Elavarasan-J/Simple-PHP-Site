<?php
defined('BASE_PATH') OR exit('No direct script access allowed');

/**
 * SESSION
 * 
 * Methods: start, destroy, delete
 */
class Session {
	function __construct() {
		$this->start();

		define("SESSION", "ANNCARES");
		define("ADMIN_SESSION", "ANNCARES_ADMIN");
		define("USER_SESSION", "ANNCARES_USER");

		if (!isset($_SESSION[SESSION]['SESS_CREATED'])) {
			$_SESSION[SESSION]['SESS_CREATED'] = time();	// assiging session created time
		} else if ((time()-$_SESSION[SESSION]['SESS_CREATED']) < 1800) {
			$_SESSION[SESSION]['SESS_CREATED'] = time();	// extend expiration if website in use within 30 minutes
		} else if ((time() - $_SESSION[SESSION]['SESS_CREATED']) > 1800) {
			// session started more than 30 minutes ago
			session_regenerate_id(true);    				// change session ID for the current session and invalidate old session ID
			$_SESSION[SESSION]['SESS_CREATED'] = time();	// update creation time
		}
		
		$sessionID=session_id();
		define("SESSION_ID",$sessionID,TRUE);
	}

	private function start() {
		session_start();
	}

	function destroy() {
		session_destroy();
	}

	function delete($sesVar) {
		if(is_array($sesVar) && count($sesVar)>0) {
			foreach($sesVar as $key=>$val) {
				unset($sesVar[$key]);
			}
		} else if($sesVar!='')
			unset($sesVar);
	}
}
$sesObj = new Session;
?>