<?php
/**
 * App initiated
 */
require_once('../init.php');
defined('BASE_PATH') OR exit('No direct script access allowed');

include_once("inc/header.php");
include_once("inc/menu.php");
//$utilityObj->printAny($_SESSION);
?>
			 			<div class="tables full">
                        	<div class="tables"><h1>Please use left side navigation menu to navigate.</h1></div>
<?php /*?>
                        	<h3 class="border member"><i aria-hidden="true" class="fa fa-bullhorn"></i> Active Customer Information</h3>
                            <?php include_once("../customer_info.php"); ?>
<?php */?>
                        </div>
<?php
include_once("inc/footer.php");
?>