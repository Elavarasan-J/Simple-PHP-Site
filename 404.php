<?php
/**
 * App initiated
 */
$checkAdminLogin='no';
require_once('init.php');
defined('BASE_PATH') OR exit('No direct script access allowed');

$seoTitle = $pageInfoObj->get_the_title();

require_once(BASE_PATH.'includes/header.php');
?>

		<div class="subpage-banner">
			<div class="subpage-bg">
				<div class="container">
					<h2 class="subpage-title"><?php echo $pageInfoObj->get_the_title(); ?></h2>
				</div>
			</div>
		</div>

		<!-- Page section -->
		<div class="subpage-wrapper" style="padding:100px 0;">
			<div class="container">
				<h3 class="text-center" style="margin-bottom:0;"><strong>Sorry!</strong></h3><br>
				<h4 class="text-center">
					The Page you looking for could not be found. <br><br><br><a href="#">Back to Home</a>
				</h4>
			</div>
		</div>
		<!--/Page section -->
<?php
require_once(BASE_PATH.'includes/footer.php');
?>