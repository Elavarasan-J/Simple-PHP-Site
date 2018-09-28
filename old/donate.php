
		<?php
			$checkAdminLogin='no';
			require_once('init.php');
			
			defined('BASE_PATH') OR exit('No direct script access allowed');

			$activeMenu = 'Donate';
			$seoTitle = $activeMenu;
	
			include_once('includes/header.php');	
		?>

	   	<!--Banner -->
		<div class="subpage-banner">
			<div class="subpage-bg">
				<div class="container">
					<h2 class="subpage-title">Donate</h2>
					<h4>Your donation matters! Every donation made to the Ann Cares Foundation helps us continue to provide<br class="hidden-xs"> free support services to cancer patients and their loved ones each year.</h4>
				</div>
			</div>
		</div>
	    <!--/Banner -->
	   
	   <!-- Subpage Content -->
	   <div class="subpage-wrapper">
		   <div class="container">
			   <div class="donate-form-wrapper">
					<div class="row row20">
						<div class="col-md-6">
							<div class="donate-content">
								<h2>Make your Contribution,<br> Make a Change</h2>
								<p>A simple act can bring a huge difference in the lives of Kids with terminal illness. Ann Cares Foundation translates every penny it receives into a smile on the face of a cancer survivor kid, educate a bunch of women about the symptoms of cancer, or organize a huge fundraising event for kids affected by terminal illness.</p>
								<p>It's all about the kids, 100% of your generosity goes towards building new found confidence and hope for kids who need them most <strong>"Sometimes the smallest drop in the bucket makes biggest ripples"</strong>  Please give whole heartedly and help make the difference in the lives of children who are not as fortunate as us.</p>
								<p>Ann Cares Foundation Inc  is a 501(c)3 nonprofit organization.</p>
								<ul>
									<li>We are 100% volunteer run and have zero paid employees.</li>
									<li>Donations are 100% tax deductible</li>
									<li><a href="https://bit.ly/2LDkE53" target="_blank">IRS Tax Except Status</a></li>
									<li><a href="javascript:void(0);" data-toggle="modal" data-target="#IRSModal">Favourable Determination Letter issued by IRS</a></li>
								</ul>
								<p>Online donation is the quickest and easiest way to join the enduring efforts of Ann Cares Foundation.</p>
								<p>Please help us continue our lifesaving work by making a contribution today!</p>
							</div>
						</div>

						<div class="col-md-6">
							<div class="donate-form">
								<form>
									<div class="gift-amount-section">
										<div class="donate-head">
											<h3>Choose your donation amount:</h3>
											<p>Your donations will make difference!</p>
										</div>

										<div class="form-group">
											<select class="form-control input-lg gift-amount-type">
												<option value="INR">INR</option>
												<option value="USD">USD</option>
											</select>
										</div>

										<div class="form-group">
											<div class="gift-amount-group clearfix" data-id="INR">
												<div class="select-gift-div">
													<a class="select-gift-amount" data-amount="500">&#8377;500</a>
												</div>
												<div class="select-gift-div">
													<a class="select-gift-amount" data-amount="1000">&#8377;1000</a>
												</div>
												<div class="select-gift-div">
													<a class="select-gift-amount" data-amount="2000">&#8377;2000</a>
												</div>
												<div class="select-gift-div">
													<a class="select-gift-amount" data-amount="5000">&#8377;5000</a>
												</div>
												<div class="select-gift-div">
													<a class="select-gift-amount" data-amount="">Other</a>
												</div>
											</div>
											
											<div class="gift-amount-group clearfix" data-id="USD" style="display: none;">
												<div class="select-gift-div">
													<a class="select-gift-amount" data-amount="25">$25</a>
												</div>
												<div class="select-gift-div">
													<a class="select-gift-amount" data-amount="50">$50</a>
												</div>
												<div class="select-gift-div">
													<a class="select-gift-amount" data-amount="100">$100</a>
												</div>
												<div class="select-gift-div">
													<a class="select-gift-amount" data-amount="200">$200</a>
												</div>
												<div class="select-gift-div">
													<a class="select-gift-amount" data-amount="">Other</a>
												</div>
											</div>
										</div>

										<div class="form-group">
											<input type="text" class="form-control input-lg" id="gift-amount" placeholder="Your Donation Amount">
										</div>
									</div>

									<div class="donor-info-section">
										<div class="donate-head">
											<h3>Your Information:</h3>
											<p>Enter your information in the given fields</p>
										</div>

										<div class="form-group row">
											<div class="col-md-6">
												<input type="text" class="form-control input-lg" id="name" placeholder="Your Name *">
											</div>
											<div class="col-md-6">
												<input type="text" class="form-control input-lg" id="phone" placeholder="Your Phone Number *">
											</div>
										</div>
										<div class="form-group">
											<input type="email" class="form-control input-lg" id="email" placeholder="Your Email *">
										</div>

										<div class="form-group">
											<input type="text" class="form-control input-lg" id="address" placeholder="Address *">
										</div>

										<div class="form-group row">
											<div class="col-md-6">
												<select class="form-control input-lg">
													<option value="">Select Country</option>
													<option value="Afghanistan" data-cid="1">Afghanistan</option>
													<option value="Albania" data-cid="2">Albania</option>
													<option value="Algeria" data-cid="3">Algeria</option>
													<option value="American Samoa" data-cid="4">American Samoa</option>
												</select>
											</div>
											<div class="col-md-6">
												<input type="text" class="form-control input-lg" id="state" placeholder="Your State *">
											</div>
										</div>

										<div class="form-group row">
											<div class="col-md-6">
												<input type="text" class="form-control input-lg" id="city" placeholder="Your City *">
											</div>
											<div class="col-md-6">
												<input type="text" class="form-control input-lg" id="pin-code" placeholder="Pin Code *">
											</div>
										</div>

										<div class="form-group">
											<textarea class="form-control input-lg" rows="3" id="comment" placeholder="Comments"></textarea>
										</div>
										<div class="form-group">
											<div style="padding-top: 10px;">
												<label class="checkbox"><input type="checkbox" id="dedicate-check"> Dedicate my donation in honor or in memory of someone</label>
											</div>
										</div>
										<div class="hradio-group">
											<label class="radio-inline">
											  <input type="radio" name="honor" value="100" checked> In honor of...
											</label>
											<label class="radio-inline">
											  <input type="radio" name="honor" value="250"> In memory of...
											</label>

											<div class="checkbox-field row">
												<div class="col-sm-6 col-md-6">
													<input type="text" class="form-control input-lg" placeholder="Full Name &#42;">
												</div>
												<div class="col-sm-6 col-md-6">
													<input type="text" class="form-control input-lg" placeholder="Email Address &#42;">
												</div>
											</div>
										</div>
									</div>

									<div class="payment-method-section">
										<div class="donate-head">
											<h3>Payment Method:</h3>
											<p>Your payments are secure and encrypted</p>
										</div>

										<div class="form-group">
											<label><img src="<?php echo ASSET_PATH; ?>images/paypal.png" alt="Paypal"></label>
										</div>
									</div>

									<button class="btn btn-primary">Donate Now</button>
								</form>
							</div>
						</div>
					</div>
			   </div>
		   </div>
	   </div>
	   
	   <div class="donate-bottom">
		   <div class="container text-center">
			   The Ann Care Foundation is a  80G nonprofit organization.<br>Contributions will be used in its discretion for charitable purposes and are tax deductible to the extent <br>allowed by law.
		   </div>
	   </div>
	   <!--/Subpage Content -->
	    
	   <!-- Modal -->
		<div class="modal fade" id="IRSModal" tabindex="-1" role="dialog" aria-labelledby="IRSModalLabel">
		  <div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
			  <button type="button" class="close abs-close" data-dismiss="modal" aria-label="Close">
				  <i data-feather="x"></i>
			  </button>
			  <div class="modal-body">
				 <img class="img-responsive" src="<?php echo ASSET_PATH; ?>images/determination_letter.jpg" alt="Determination Letter">
			  </div>
			</div>
		  </div>
		</div>

        
		<?php include_once('includes/footer.php');	?>
		<script src="assets/js/icheck.min.js"></script>
	    <script>
			$(document).ready(function(){
			  $('input').iCheck({
				checkboxClass: 'icheckbox_square',
				radioClass: 'iradio_square',
				increaseArea: '20%' // optional
			  });
				
				$('#dedicate-check').on('ifChecked', function(event){
				  $('.hradio-group').slideDown();
				});

				$('#dedicate-check').on('ifUnchecked', function(event){
				  $('.hradio-group').slideUp();
				});
				
				//Gift Amount
				$('.select-gift-amount').click(function(e){
					e.preventDefault();
					var currAmount;
					
					$('.select-gift-amount').removeClass('active');
					$(this).addClass('active');
					currAmount = $(this).attr('data-amount');
					
					$('#gift-amount').val(currAmount).focus();
				});
				
				$('.gift-amount-type').on('change',function(){
					var currId;
					
					currId = $(this).val();
					$('.gift-amount-group').css('display','none');
					$('.gift-amount-group[data-id="'+currId+'"]').css('display','block');
				});
			});
		</script>
        <?php include_once('includes/footer-end.php');	?>