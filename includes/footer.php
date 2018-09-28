	<!-- Footer -->
   <footer class="footer">
		<div class="footer-top">
			<div class="container">
				<div class="row">
					 <div class="col-sm-4 col-md-3">
						<div class="footer-col">
							<h4 class="footer-title">About ACF</h4>
							<ul class="footer-list">
								<li><a href="<?php echo $pageInfoObj->get_the_page_link(1); ?>">About Ann</a></li>
								<li><a href="<?php echo $pageInfoObj->get_the_page_link(1); ?>">Mission, Vision &amp; Values</a></li>
								<li><a href="<?php echo $pageInfoObj->get_the_page_link(1); ?>">Our Sponsors &amp; Partners</a></li>
								<li><a href="<?php echo $pageInfoObj->get_the_page_link(5); ?>">What We Do</a></li>
							</ul>
						</div>
					</div>

					<div class="col-sm-4 col-md-2">
						<div class="footer-col">
							<h4 class="footer-title">Get in Touch</h4>
							<ul class="footer-list">
								<li><a href="<?php echo $pageInfoObj->get_the_page_link(15); ?>">Contact Us</a></li>
								<li><a href="<?php echo $pageInfoObj->get_the_page_link(17); ?>">News &amp; Blog</a></li> 
							</ul> 
							<ul class="social-list">
								<li><a href="https://www.facebook.com/www.anncaresfoundation.org" target="_blank"><i data-feather="facebook"></i></a></li>
								<li><a href="#"><i data-feather="twitter"></i></a></li>
							</ul>
						</div>
					</div>

					<div class="col-sm-4 col-md-3">
						<div class="footer-col">
							<h4 class="footer-title">Get Involved</h4>
							<ul class="footer-list">
								<li><a href="<?php echo $pageInfoObj->get_the_page_link(16); ?>">Donate</a></li>
								<li><a href="<?php echo $pageInfoObj->get_the_page_link(16); ?>">Fundraise</a></li>
								<li><a href="<?php echo $pageInfoObj->get_the_page_link(14); ?>">Volunteer Oportunities</a></li> 
							</ul> 
						</div>
					</div>

					<div class="col-sm-12 col-md-4">
						<div class="footer-col">
							<h4 class="footer-title">Contact Us</h4>
							<address><i data-feather="map-pin"></i>18/39, Cross Street, Kennedy Square, Sembiam, Perambur, Chennai-600011</address>
							<ul class="contact-list">
								<li><i data-feather="phone"></i>+91 98400 96022 / +91 8056698777 </li>
								<li><i data-feather="mail"></i>info@anncaresfoundation.org</li>
							</ul>
						</div>
					</div>
				</div>

				<div class="footer-bottom">
					<div class="fb-table">
						<div class="copyright">
							Copyright All Rights Reserved &copy; 2018. Designed by GtectSystems
						</div>
						<div class="paypal-logo">
							<img src="<?php echo ASSET_PATH; ?>images/paypal.png" alt="Paypal"> 
							<span>Donate Securely &amp;<br>Hassle Free with PayPal</span>
						</div>
					</div>
				</div>

			</div>
		</div>

	</footer>
   <!--/Footer -->

	<script src="<?php echo ASSET_PATH; ?>js/jquery.min.js"></script>

	<script src="<?php echo ASSET_PATH; ?>js/bootstrap.min.js"></script>
	<script src="<?php echo ASSET_PATH; ?>js/feather.min.js"></script>
<?php if(isset($recaptcha) && $recaptcha==1) { ?>
	<script src='https://www.google.com/recaptcha/api.js'></script>
<?php } ?>