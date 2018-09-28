	<?php
			$checkAdminLogin='no';
			require_once('init.php');
			
			defined('BASE_PATH') OR exit('No direct script access allowed');

			$recaptcha = 1;
			$activeMenu = 'Contact Us';
			$seoTitle = $activeMenu;
			$formName="contactForm";
			$buttonName="Send Message";
	
			include_once('includes/header.php');	
		?>

	   
	   	<!--Banner -->
		<div class="subpage-banner">
			<div class="subpage-bg">
				<div class="container">
					<h2 class="subpage-title">Contact Us</h2>
					<h4>For any questions you might have about our Organisation or the programs,<br class="hidden-xs"> we are very happy to share more details about what we do.</h4>
				</div>
			</div>
		</div>
	    <!--/Banner -->
	   
	   <!-- Subpage Content -->
	   <div class="subpage-wrapper">
		   <div class="container">
			   <div class="contact-us-top">
				   <address><i data-feather="map-pin"></i><br><strong class="font24">Ann Cares Foundation</strong><br>18/39, Cross Street, Kennedy Square, Sembiam, Perambur, Chennai-600011</address>
				   <p><strong>Phone:</strong> +91 98400 96022 / +91 8056698777<br><strong>Email:</strong> info@anncaresfoundation.org</p>
			   </div>
			   
			   <p class="form-help-text">If you would like to get in touch or wanted to know more about our services,<br>Please fill out the below form.</p>
			   
			   <form class="contct-form" id="<?php echo $formName; ?>">
					<div class="form-group">
						<label for="name">Name <span class="mandatory">*</span></label>
						<input type="text" id="name" name="name" class="form-control input-lg">
					</div>

					<div class="form-group">
						<label for="email">Email Address <span class="mandatory">*</span></label>
						<input type="email" id="email" name="email" class="form-control input-lg">
					</div>

					<div class="form-group">
						<label for="phone">Phone Number</label>
						<input type="tel" id="phone" name="phone" class="form-control input-lg">
					</div>

					<div class="form-group">
						<label for="message">Message</label>
						<textarea class="form-control input-bg" name="message" id="message" rows="5"></textarea>
					</div>
				   
				    <div class="form-group">
						<div class="g-recaptcha" id="recaptcha" data-sitekey="6LcQUxsUAAAAAFHG6GCt3MEGE2nuYp60tNSqNQPv" style="transform:scale(0.96);-webkit-transform:scale(0.96);transform-origin:0 0;-webkit-transform-origin:0 0;"></div>
					</div>
				   
					<div class="form-group text-center">
						<button type="submit" id="submit" class="btn btn-primary"><?php echo $buttonName; ?></button>
					</div>
				   <div class="alert hide"></div>
				</form>
		   </div>
	   </div>
	   <!--/Subpage Content -->
	   
	   <div class="map-responsive">
		   <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d22624.984090988764!2d80.2245387127587!3d13.117551944069934!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3a5265ad63fed25d%3A0x658d52859326fee!2sAnn+Cares+Foundation!5e0!3m2!1sen!2sin!4v1514956645895" width="600" height="450" frameborder="0" style="border:0" allowfullscreen></iframe>
	   </div>
	     
	   
	   <?php include_once('includes/footer.php'); ?>
	   <?php require_once('includes/formScript.php'); ?>
	   <?php include_once('includes/footer-end.php'); ?>