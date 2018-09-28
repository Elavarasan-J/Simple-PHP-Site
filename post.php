		<?php
			$checkAdminLogin='no';
			require_once('init.php');
			
			defined('BASE_PATH') OR exit('No direct script access allowed');

			$post_slug=(isset($_GET['post_id']) && $_GET['post_id']!='')?$_GET['post_id']:'';
			$reqResArr='';
			if($post_slug)	{
				$reqResArr=$db->getSingleRec($db->TB_post,"slug='$post_slug'");
				$description=html_entity_decode($reqResArr['description']);
			}
			 
			$category_arr=$utilityObj->get_post_category();
			$post_arr=$utilityObj->get_post(3);
			
			//echo '<pre>';print_r($reqResArr);echo '</pre>';
			//echo '<pre>';print_r($category_arr);echo '</pre>';

			$seoTitle = $reqResArr['title'];
			include_once('includes/header.php');
		?>

	   	<!--Banner -->
		<div class="subpage-banner">
			<div class="subpage-bg">
				<div class="container">
					<h2 class="subpage-title"><?php echo $reqResArr['title']; ?></h2>
				</div>
			</div>
		</div>
	    <!--/Banner -->
	   
	   <!-- Subpage Content -->
	   <div class="subpage-wrapper blog-detail-page">
		   <div class="container">
			   <div class="row row20">
				   <div class="col-md-8">
					   <div class="blog-content">
						   <?php echo $description; ?>
						   
						   <div id="blog-slider" class="carousel slide" data-ride="carousel">
							  <!-- Indicators -->
							  <ol class="carousel-indicators">
								<li data-target="#blog-slider" data-slide-to="0" class="active"></li>
								<li data-target="#blog-slider" data-slide-to="1"></li>
								<li data-target="#blog-slider" data-slide-to="2"></li>
								<li data-target="#blog-slider" data-slide-to="3"></li>
								<li data-target="#blog-slider" data-slide-to="4"></li>
							  </ol>

							  <!-- Wrapper for slides -->
							  <div class="carousel-inner" role="listbox">
								<div class="item active">
								  <img src="<?php echo ASSET_PATH; ?>images/blog/large/blog1.jpg" alt="">
								</div>
								<div class="item">
								  <img src="<?php echo ASSET_PATH; ?>images/blog/large/blog1-2.jpg" alt="">
								</div>
								<div class="item">
								  <img src="<?php echo ASSET_PATH; ?>images/blog/large/blog1-3.jpg" alt="">
								</div>
								<div class="item">
								  <img src="<?php echo ASSET_PATH; ?>images/blog/large/blog1-4.jpg" alt="">
								</div>
								<div class="item">
								  <img src="<?php echo ASSET_PATH; ?>images/blog/large/blog1-5.jpg" alt="">
								</div>
							  </div>
							   
							</div>
						   
					   </div>
				   </div>
				   <div class="col-md-4">
					   <div class="right-sidebar">
						   <div class="post-category">
							   <h4 class="blog-title">Categories</h4>
								<ul class="cat-list">
								<?php 
									foreach($category_arr as $key=>$val){ 
										$cat_post_arr=$utilityObj->get_post('',$val['post_category_id']);
										if($cat_post_arr!=0){
								?>
									<li class="<?php echo ($val['post_category_id'] == $reqResArr['post_category_id'])?'active':''; ?>">
										<a href="<?php echo SITE_PATH; ?>post_category/<?php echo $val['slug']; ?>"><?php echo $val['title']; ?></a>
									</li>
								<?php 
										}
									}
								?>
								</ul>
						   </div>
						   
						   <!--<div class="post-category">
							   <h4 class="blog-title">Archives</h4>
								<ul class="cat-list">
									<li><a href="#">2015</a></li>
									<li><a href="#">2016</a></li>
									<li><a href="#">2017</a></li>
									<li><a href="#">2018</a></li>
								</ul>
						   </div>-->
						   
						   <div class="popular-post">
							   <h4>Recent Post</h4>
						   <?php
								foreach($post_arr as $key=>$val){
						   ?>
								<div class="post">
								   <div class="img">
									   <a href="<?php echo SITE_PATH; ?>post/<?php echo $val['cat_slug']; ?>/<?php echo $val['slug']; ?>">
										   <img class="img-responsive" src="<?php echo $val['featured_img']; ?>" alt="<?php echo $val['title']; ?>">
									   </a>
								   </div>
								   <div class="post-con">
									   <h6><a href="<?php echo SITE_PATH; ?>post/<?php echo $val['cat_slug']; ?>/<?php echo $val['slug']; ?>"><?php echo $val['title']; ?></a></h6>
									   <a href="javascript:void(0);"><i class="post-date-icon" data-feather="calendar"></i> <?php echo date('M d, Y', strtotime($val['published_time'])); ?></a>
								   </div>
							   </div>
						   <?php
								}
						   ?>
							</div>
						   
					   </div>
				   </div>
			   </div>
		   </div>
	   </div>
	   <!--/Subpage Content -->
	   
	   <?php include_once('includes/footer.php');	?>
	   <?php include_once('includes/footer-end.php');	?>