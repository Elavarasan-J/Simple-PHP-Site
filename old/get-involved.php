		<?php
			$checkAdminLogin='no';
			require_once('init.php');
			
			defined('BASE_PATH') OR exit('No direct script access allowed');

			$activeMenu = 'Get Involved';
			$seoTitle = $activeMenu;
			$recaptcha = 1;
			$formName="volunteerForm";
			$buttonName="Submit";

			include_once('includes/header.php');	
		?>
	
	   	<!--Banner -->
		<div class="subpage-banner">
			<div class="subpage-bg">
				<div class="container">
					<h2 class="subpage-title">Get Involved</h2>
					<h4>We want your brilliant smile and helping hands to help create a child's<br class="hidden-xs"> hope for love and belonging.  </h4>
				</div>
			</div>
		</div>
	    <!--/Banner -->
	   
	   <!-- Subpage Content -->
	   <div class="subpage-wrapper">
		   <div class="container">
				<div class="get-involved-wrapper">
					<h3>Volunteer Application</h3>
					<p>Please complete the below enrolment form and we will get back to you soon.</p>
				 
					<form class="volunteer-form" id="<?php echo $formName; ?>">
						<div class="form-group row">
							<div class="col-sm-6 col-md-6">
								<label for="fname">First Name <span class="mandatory">*</span></label>
								<input type="text" class="form-control input-lg" name="fname" id="fname">
							</div>
							<div class="col-sm-6 col-md-6">
								<label for="lname">Last Name</label>
								<input type="text" class="form-control input-lg" name="lname" id="lname">
							</div>
						</div>

						<div class="form-group">
							<label for="address">Address </label>
							<input type="text" class="form-control input-lg" name="address" id="address">
						</div>

						<div class="form-group row">
							<div class="col-sm-6 col-md-6">
								<label for="city">City </label>
								<input type="text" class="form-control input-lg" name="city" id="city">
							</div>
							<div class="col-sm-6 col-md-6">
								<label for="postal">Postal Code</label>
								<input type="text" class="form-control input-lg" name="postal" id="postal">
							</div>
						</div>

						<div class="form-group row">
							<div class="col-sm-6 col-md-6">
								<label for="email">Email <span class="mandatory">*</span></label>
								<input type="text" class="form-control input-lg" name="email" id="email">
							</div>
							<div class="col-sm-6 col-md-6">
								<label for="phone">Phone Number <span class="mandatory">*</span></label>
								<input type="text" class="form-control input-lg" name="phone" id="phone">
							</div>
						</div>

						<div class="form-group">
							<label>Which days can you volunteer? </label>
							<div class="checkbox-group">
								<label><input type="checkbox" name="days[]" value="Monday"> Monday</label>
								<label><input type="checkbox" name="days[]" value="Tuesday"> Tuesday</label>
								<label><input type="checkbox" name="days[]" value="Wednesday"> Wednesday</label>
								<label><input type="checkbox" name="days[]" value="Thursday"> Thursday </label>
								<label><input type="checkbox" name="days[]" value="Friday"> Friday</label>
								<label><input type="checkbox" name="days[]" value="Saturday"> Saturday</label>
								<label><input type="checkbox" name="days[]" value="Sunday"> Sunday</label>
							</div>
						</div>

						<div class="form-group">
							<label>Hours you will able to volunteer? </label>
							<div class="row">
								<div class="col-xs-6 col-sm-4 col-md-3">
									<select class="form-control input-lg" name="hours">
										<option value="1">1</option>
										<option value="2">2</option>
										<option value="3">3</option>
										<option value="4">4</option>
										<option value="5">5</option>
										<option value="6">6</option>
										<option value="7">7</option>
										<option value="8">8</option>
										<option value="9">9</option>
										<option value="10">10</option>
									</select>
								</div>
							</div>
						</div>

						<div class="form-group">
							<label for="comments">Please let us know any comments or questions that you might have. </label>
							<textarea class="form-control input-lg" name="comments" id="comments" rows="4"></textarea>
						</div>
						<div class="form-group">
							<div class="g-recaptcha" id="recaptcha" data-sitekey="6LcQUxsUAAAAAFHG6GCt3MEGE2nuYp60tNSqNQPv" style="transform:scale(0.96);-webkit-transform:scale(0.96);transform-origin:0 0;-webkit-transform-origin:0 0;"></div>
						</div>
						<br>
 						<div class="form-group vsubmit-button">
							<button class="btn btn-primary" id="submit" type="submit"><?php echo $buttonName; ?></button>
						</div>
						<div class="alert hide"></div>
					</form>
			   </div>
		   </div>
	   </div>
	   <!--/Subpage Content -->
	   
	    
		<?php include_once('includes/footer.php');	?>
		<?php require_once('includes/formScript.php'); ?>
		<script src="<?php echo ASSET_PATH; ?>js/icheck.min.js"></script>
		<script>
			$(document).ready(function(){
				$('input').iCheck({
					checkboxClass: 'icheckbox_square',
					radioClass: 'iradio_square',
					increaseArea: '20%' // optional
				});
			});
		</script>
		<?php include_once('includes/footer-end.php');	?>