<?php
/**
 * App initiated
 */
$checkAdminLogin='no';
require_once('init.php');

defined('BASE_PATH') OR exit('No direct script access allowed');

$redirectURL=$pageInfoObj->pageRedirect();
include_once($redirectURL);
?>