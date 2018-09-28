<?php #Template Name:News ?>
<?php
/**
 * App initiated
 */
$checkAdminLogin='no';
require_once('init.php');
$seoTitle = $pageInfoObj->get_the_title();

defined('BASE_PATH') OR exit('No direct script access allowed');

$id = (isset($_GET['id']) && $_GET['id']!='')?(int)$_GET['id']:'';
$post_arr=$utilityObj->get_post('',$id);

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
		<div class="subpage-wrapper news-blog">
			<div class="container">
  				<div class="row row20">

			<?php
				$i=0;
				foreach($post_arr as $key=>$val) {
					$description=html_entity_decode($val['description']);
			?>
				   <div class="col-sm-6 col-md-4">
					   <div class="news-col">
						   <img class="img-responsive" src="<?php echo $val['featured_img']; ?>" alt="<?php echo $val['title']; ?>">
						   <div class="news-col-content">
							   <h4><a href="<?php echo SITE_PATH; ?>post/<?php echo $val['cat_slug']; ?>/<?php echo $val['slug']; ?>"><?php echo $val['title']; ?></a></h4>
							   <p><?php echo $val['short_description']; ?></p>
							   <a href="<?php echo SITE_PATH; ?>post/<?php echo $val['cat_slug']; ?>/<?php echo $val['slug']; ?>" class="btn btn-nm btn-primary outline">Read More</a>
							</div>
					   </div>
				   </div>

			<?php
					$i++;
				}
			?>
				</div>
			</div>
		</div>
		<!--/Page section -->
<?php include_once('includes/footer.php'); ?>
<?php include_once('includes/footer-end.php'); ?>