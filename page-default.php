<?php #Template Name:Default ?>
<?php
/**
 * App initiated
 */
$checkAdminLogin='no';
require_once('init.php');
$seoTitle = $pageInfoObj->get_the_title();

defined('BASE_PATH') OR exit('No direct script access allowed');

include_once('includes/header.php');
?>
		<div class="subpage-banner">
			<div class="subpage-bg">
				<div class="container">
					<h2 class="subpage-title"><?php echo $pageInfoObj->get_the_title(); ?></h2>
					<h4><?php $pageInfoObj->the_excerpt(); ?></h4>
				</div>
			</div>
		</div>

		<!-- Page section -->
		<div class="subpage-wrapper">
			<div class="container">
				<?php 
					$children_arr = $pageInfoObj->getPageArr($pageInfoObj->get_page_id());
					foreach($children_arr as $key=>$val){
						$description=html_entity_decode($val['description']);
				?>
				
				<div class="about-ct-section">
					<h3><?php echo $val['title']; ?></h3>
					<div class="hr-line"></div>
					<h4><?php echo $val['short_description']; ?></h4>
					<?php echo $description; ?>
			   </div>
				
				<?php
					}
				?>
				
			</div>
		</div>
		<!--/Page section -->
<?php include_once('includes/footer.php'); ?>
<?php include_once('includes/footer-end.php'); ?>