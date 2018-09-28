		<?php
			$checkAdminLogin='no';
			require_once('init.php');
			
			defined('BASE_PATH') OR exit('No direct script access allowed');

			$seoTitle = $pageInfoObj->get_the_title();
			$formName = "newsletterForm";
			$buttonName = "Submit";

			include_once('includes/header.php');

			$post_arr=$utilityObj->get_post(3);
		?>
		
	   	<!--Banner -->
		<div id="fs-carousel" class="carousel fs-carousel slide" data-ride="carousel">
			<div class="carousel-inner" role="listbox">
				<div class="item active" style="background:url('<?php echo ASSET_PATH; ?>images/slider1.jpg');">
					<div class="fs-banner-table">
						<div class="fs-banner-cell">
							<div class="fs-banner-bg">
								<div class="container">
									<div class="fs-banner-content">
										<span class="divider"></span>
										<h2>Caring, Nurturing and Filling Happiness<br>in the Lives of Kids with Terminal Illnesses.</h2>
										<p>Ann Cares Foundation (ACF) India is a nonprofit organization serving families<br>with terminally ill children in Chennai, India, and surrounding districts.</p>
										<span class="divider"></span>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	    <!--/Banner -->
	   
	   <!-- Our Vision -->
	   <div class="our-vision-section">
		   <div class="container">
			   <div class="row row20">
				   <div class="col-md-6">
					   <div class="our-vision-top">
						   <h2>What We Do</h2>
						   <p>Spreading hope and celebrating life. We are commited and engaged in making a difference in the 
lives of under privileged children with terminal illness.</p>
						   <p>ACF india is a non profit organisation operating in chennai and surrounding districts. The foundation ensure quality of life and holistic care for the child with terminal stages of 
cancer, and his family through his journey.</p>
							<a href="<?php echo SITE_PATH; ?>what-we-do.php" class="btn btn-primary outline">Learn More</a>
					   </div>
				   </div>
				   <div class="col-md-6">
					   <img class="img-responsive center-block" src="<?php echo ASSET_PATH; ?>images/kids.png" alt="Kids">
				   </div>
			   </div>
		   </div>
	   </div>
	   <!--/Our Vision -->
	   
	   <!-- Flickr -->
	   <ul class="flickr-list">
		   <li>
			   <a target="_blank" href="https://www.flickr.com/photos/127810982@N02/with/15667806740/"><img src="<?php echo ASSET_PATH; ?>images/flickr1.jpg" alt=""></a>
		   </li>
		   <li>
			   <a target="_blank" href="https://www.flickr.com/photos/127810982@N02/with/15667806740/"><img src="<?php echo ASSET_PATH; ?>images/flickr2.jpg" alt=""></a>
		   </li>
		   <li>
			   <a target="_blank" href="https://www.flickr.com/photos/127810982@N02/with/15667806740/"><img src="<?php echo ASSET_PATH; ?>images/flickr3.jpg" alt=""></a>
		   </li>
		   <li>
			   <a target="_blank" href="https://www.flickr.com/photos/127810982@N02/with/15667806740/"><img src="<?php echo ASSET_PATH; ?>images/flickr4.jpg" alt=""></a>
		   </li>
		   <li>
			   <a target="_blank" href="https://www.flickr.com/photos/127810982@N02/with/15667806740/"><img src="<?php echo ASSET_PATH; ?>images/flickr5.jpg" alt=""></a>
		   </li>
		   <li>
			   <a target="_blank" href="https://www.flickr.com/photos/127810982@N02/with/15667806740/"><img src="<?php echo ASSET_PATH; ?>images/flickr6.jpg" alt=""></a>
		   </li>
		   <li>
			   <a target="_blank" href="https://www.flickr.com/photos/127810982@N02/with/15667806740/"><img src="<?php echo ASSET_PATH; ?>images/flickr7.jpg" alt=""></a>
		   </li>
		   <li>
			   <a target="_blank" href="https://www.flickr.com/photos/127810982@N02/with/15667806740/"><img src="<?php echo ASSET_PATH; ?>images/flickr8.jpg" alt=""></a>
		   </li>
	   </ul>
	   <!--/Flickr -->
	   
	   <!-- News -->
	   <div class="news-section">
		   <div class="container">
			   <div class="head-section">
				   <h3>From Our Blog</h3>
				   <p>The Latest News and Updates on the progress of Ann Cares Foundation</p>
			   </div>
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
								   <h4><a href="<?php echo SITE_PATH; ?>news-detail.php?id=<?php echo $val['post_id']; ?>"><?php echo $val['title']; ?></a></h4>
								   <p><?php echo $val['short_description']; ?></p>
								   <a href="<?php echo SITE_PATH; ?>news-detail.php?id=<?php echo $val['post_id']; ?>" class="btn btn-nm btn-primary outline">Read More</a>
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
	   <!--/News -->
	   
	   <!--Donate -->
	   <div class="donate-link-wrapper">
		   <div class="container">
			   <div class="donate-link-inner">
				   <h3>Support Ann Cares Foundation a 501(c)3 nonprofit organization.</h3>
				   <p>A simple act can bring a huge difference in the lives of Kids with terminal illness.</p>
				   <div class="">
					   <a href="<?php echo SITE_PATH; ?>donate.php" class="btn btn-default outline fill">Donate Now</a>
				   </div>
			   </div>
		   </div>
	   </div>
	   <!--/Donate -->
	   
	   <!--Newsletter Signup -->
	   <div class="newsletter-signup">
		   <div class="container">
			   <form class="newsletter-form" id="<?php echo $formName; ?>">
				   <div class="head-section">
					   <h3>Sign up our Newsletter</h3>
					   <p>Subscribe to our mailing list to receive updates on our progress!</p>
				   </div>
				   <div class="alert hide"></div>
				   <div class="row row5">
					   <div class="col-sm-4 col-md-4 form-group">
						   <input type="text" class="form-control input-lg" id="name" name="name" placeholder="Name *">
					   </div>
					   <div class="col-sm-5 col-md-5 form-group">
						   <input type="email" class="form-control input-lg" id="email" name="email" placeholder="Email Address *">
					   </div>
					   <div class="col-sm-3 col-md-3">
						   <button class="btn btn-primary btn-block" id="submit"><?php echo $buttonName; ?></button>
					   </div>
				   </div>
				</form>
		   </div>
		   
	   </div>
	   <!--/Newsletter Signup -->

	<?php include_once('includes/footer.php');	?>
	<script>
		$('.fs-carousel').carousel({
		  interval: 6000,
		  pause: 'hover'
		});
	</script>
	<?php require_once('includes/formScript.php'); ?>
	<?php include_once('includes/footer-end.php');	?>