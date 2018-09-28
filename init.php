<?php
/**
 * ENVIRONMENT = development/testing/production
 */
define('ENVIRONMENT', 'development');

/*
 *---------------------------------------------------------------
 * ERROR REPORTING
 *---------------------------------------------------------------
 *
 * Different environments will require different levels of error reporting.
 * By default development will show errors but production will hide them.
 */
switch (ENVIRONMENT)
{
	case 'development':
		error_reporting(-1);
		ini_set('display_errors', 1);

		define('IP',getHostByName(getHostName()));
	break;

	case 'testing':
		error_reporting(-1);
		ini_set('display_errors', 1);

		define('IP',$_SERVER["REMOTE_ADDR"]);
	break;

	case 'production':
		ini_set('display_errors', 0);
		if (version_compare(PHP_VERSION, '5.3', '>='))
		{
			error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT & ~E_USER_NOTICE & ~E_USER_DEPRECATED);
		}
		else
		{
			error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_USER_NOTICE);
		}

		define('IP',$_SERVER["REMOTE_ADDR"]);
	break;

	default:
		header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
		echo 'The application environment is not set correctly.';
		exit(1); // EXIT_ERROR
}

/**
 * Define the root path
 */
defined('BASE_PATH') OR define('BASE_PATH', dirname(__FILE__).DIRECTORY_SEPARATOR);
defined('CORE_BASE_PATH') OR define('CORE_BASE_PATH', dirname(__FILE__).DIRECTORY_SEPARATOR.'core'.DIRECTORY_SEPARATOR);
defined('ADMIN_BASE_PATH') OR define('ADMIN_BASE_PATH', dirname(__FILE__).DIRECTORY_SEPARATOR.'admin'.DIRECTORY_SEPARATOR);

if(file_exists(CORE_BASE_PATH.'session.php')) { require_once(CORE_BASE_PATH.'session.php'); }
if(file_exists(CORE_BASE_PATH.'path.php')) { require_once(CORE_BASE_PATH.'path.php'); }
if((!isset($dbConn) || (isset($dbConn) && $dbConn!='no')) && file_exists(CORE_BASE_PATH.'db_config.php')) { require_once(CORE_BASE_PATH.'db_config.php'); }
if(file_exists(CORE_BASE_PATH.'sanitization.php')) { require_once(CORE_BASE_PATH.'sanitization.php'); }
if(file_exists(CORE_BASE_PATH.'time.php')) { require_once(CORE_BASE_PATH.'time.php'); }
if(file_exists(CORE_BASE_PATH.'password.php')) { require_once(CORE_BASE_PATH.'password.php'); }
if((!isset($adminUtility) || (isset($adminUtility) && $adminUtility!='no')) && file_exists(CORE_BASE_PATH.'admin_utility.php')) { require_once(CORE_BASE_PATH.'admin_utility.php'); }
if(file_exists(CORE_BASE_PATH.'track_info.php')) { require_once(CORE_BASE_PATH.'track_info.php'); }
if(file_exists(CORE_BASE_PATH.'utility.php')) { require_once(CORE_BASE_PATH.'utility.php'); }
if(file_exists(CORE_BASE_PATH.'validate.php')) { require_once(CORE_BASE_PATH.'validate.php'); }
if((!isset($checkAdminLogin) || (isset($checkAdminLogin) && $checkAdminLogin!='no')) && file_exists(CORE_BASE_PATH.'admin_log.php')) { require_once(CORE_BASE_PATH.'admin_log.php'); }
if(file_exists(CORE_BASE_PATH.'pagination.php')) { require_once(CORE_BASE_PATH.'pagination.php'); }
if(file_exists(CORE_BASE_PATH.'page_info.php')) { require_once(CORE_BASE_PATH.'page_info.php'); }
if((isset($uploadMethods) && $uploadMethods=='yes') && file_exists(CORE_BASE_PATH.'upload_methods.php')) { require_once(CORE_BASE_PATH.'upload_methods.php'); }
if((isset($phpMailer) && $phpMailer=='yes') && file_exists(CORE_BASE_PATH.'phpmailer/class.phpmailer.php')) { require_once(CORE_BASE_PATH.'phpmailer/class.phpmailer.php'); }
?>