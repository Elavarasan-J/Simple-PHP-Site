<?php
    defined('BASE_PATH') OR exit('No direct script access allowed');
?>
			<div id="container">
<!-- start menu -->
				<div class="menubar menu scrollbars">
<?php //echo LeftpanelCategory(); ?>

<?php	
	$querys1="SELECT * FROM $db->TB_admin_menu WHERE parent_menu_id<1 AND menu_id IN ($user_modules_list) AND menu_status=1 ORDER BY menu_order_num ASC";
	$sql1=mysqli_query($db->conn,$querys1);
	$no=1;
	if(@mysqli_num_rows($sql1)>0) {
		echo '<ul id="menu">';
		while($fetchs1=mysqli_fetch_array($sql1))	{
			//extract($fetch);
			$parent_menu_id1=$fetchs1[0];
			//$menu_rec=$db->getSingleRec($db->TB_admin_menu,"menu_id=$parent_menu_id1",'menu_name');
			$active_page_arr=($fetchs1['active_page']!='')?explode(",",$fetchs1['active_page']):array();
			$icon=($fetchs1['class_name']!='')?'<i aria-hidden="true" class="fa fa-'.$fetchs1['class_name'].'"></i> ':'';
			$children_rec=$db->getSingleRec($db->TB_admin_menu,"parent_menu_id=".$fetchs1['menu_id']." AND menu_status=1","parent_menu_id");
			$pending_orders_count=(ucfirst($fetchs1['menu_name'])=='Orders' && $order_rec['total_pending']>0)?' <span class="highlight">'.$order_rec['total_pending'].'</span> ':'';
			$subnav_arrow=($children_rec==TRUE && $children_rec['parent_menu_id']>0)?' <span class="fa arrow"></span>':'';
			$target1=$utilityObj->target_array[$fetchs1['menu_target']];
?>
                            <li <?php if(in_array($fetchs1['menu_id'], $active_menu_arr)) { echo 'class="active current"'; } ?> ><a href="<?php echo $fetchs1['menu_link']; ?>" target="<?php echo $target1; ?>"><?php echo $icon.ucfirst($fetchs1['menu_name']).$pending_orders_count.$subnav_arrow; ?></a>
<?php
			$no++;
			
			$querys2="SELECT * FROM $db->TB_admin_menu WHERE parent_menu_id=$parent_menu_id1 AND menu_status=1 ORDER BY menu_order_num ASC";
			$sql2=mysqli_query($db->conn,$querys2);
			if(@mysqli_num_rows($sql2)>0) {
				echo "<ul>";
				while($fetchs2=mysqli_fetch_array($sql2)) {
					//extract($fetch);
					$parent_menu_id1=$fetchs2[0];
					//$menu_rec=$db->getSingleRec($db->TB_admin_menu,"menu_id=$parent_menu_id1",'menu_name');
					$active_page_arr=($fetchs2['active_page']!='')?explode(",",$fetchs2['active_page']):array();
					$icon=($fetchs2['class_name']!='')?'<i aria-hidden="true" class="fa fa-'.$fetchs2['class_name'].'"></i> ':'';
					$children_rec=$db->getSingleRec($db->TB_admin_menu,"parent_menu_id=".$fetchs2['menu_id']." AND menu_status=1","parent_menu_id");
					$subnav_arrow=($children_rec==TRUE && $children_rec['parent_menu_id']>0)?' <span class="fa arrow"></span>':'';
					$target2=$utilityObj->target_array[$fetchs2['menu_target']];
					
					if($_SESSION[ADMIN_SESSION]['user_id']==30 && $fetchs1[0]==65) { // hide info for westin user
						if($fetchs2['menu_link']=='manage_register.php?init=1') {
	?>
							<li <?php if(in_array($fetchs2['menu_id'], $active_menu_arr)) { echo 'class="active current"'; } ?> ><a href="<?php echo $fetchs2['menu_link']; ?>" target="<?php echo $target2; ?>"><?php echo $icon.ucfirst($fetchs2['menu_name']).$subnav_arrow; ?></a></li>
	<?php
						}
					} else {
	?>
                            	<li <?php if(in_array($fetchs2['menu_id'], $active_menu_arr)) { echo 'class="active current"'; } ?> ><a href="<?php echo $fetchs2['menu_link']; ?>" target="<?php echo $target2; ?>"><?php echo $icon.ucfirst($fetchs2['menu_name']).$subnav_arrow; ?></a>
	<?php
						$no++;
					
						$querys3="SELECT * FROM $db->TB_admin_menu WHERE parent_menu_id=$parent_menu_id1 AND menu_status=1 ORDER BY menu_order_num ASC";
						$sql3=mysqli_query($db->conn,$querys3);
						
						if(@mysqli_num_rows($sql3)>0) {
							echo "<ul>";
							while($fetchs3=mysqli_fetch_array($sql3)) {
								//extract($fetch);
								$parent_menu_id1=$fetchs3[0];
								//$menu_rec=$db->getSingleRec($db->TB_admin_menu,"menu_id=$parent_menu_id1",'menu_name');
								$active_page_arr=($fetchs3['active_page']!='')?explode(",",$fetchs3['active_page']):array();
								$icon=($fetchs3['class_name']!='')?'<i aria-hidden="true" class="fa fa-'.$fetchs3['class_name'].'"></i> ':'';
								//$icon='';
								$children_rec=$db->getSingleRec($db->TB_admin_menu,"parent_menu_id=".$fetchs3['menu_id']." AND menu_status=1","parent_menu_id");
								$subnav_arrow=($children_rec==TRUE && $children_rec['parent_menu_id']>0)?' <span class="fa arrow"></span>':'';
								$target3=$utilityObj->target_array[$fetchs3['menu_target']];
		?>
                            		<li <?php if(in_array($fetchs3['menu_id'], $active_menu_arr)) { echo 'class="active current"'; } ?> ><a href="<?php echo $fetchs3['menu_link']; ?>" target="<?php echo $target3; ?>"><?php echo $icon.ucfirst($fetchs3['menu_name']).$subnav_arrow; ?></a></li>
		<?php
								$no++;
							}
							echo '</ul>';
						}
						echo '</li>';
					}
				}
				echo '</ul>';
			}
			echo '</li>';
		}
		echo '</ul>';
	}	
?>
<?php /* ?>
                    <ul id="menu">
                        <li <?php if($pagename == 'home') { echo 'class="current"'; } ?> ><a href="index.php" class="menulink" ><i aria-hidden="true" class="fa fa-dashboard"></i> Dashboard</a> </li>                      
                        <li <?php if($pagename == 'security') { echo 'class="active current"'; } ?> ><a href="#"><i aria-hidden="true" class="fa fa-lock"></i> Security <span class="fa arrow"></span></a>
                            <ul>
                                <li><a href="add_user.php">Add User Account</a></li>                        
                                <li><a href="manage_users.php?init=1">Manage User Accounts</a></li>                        
                            </ul>
                        </li>                        
                        <li <?php if($pagename == 'orders') { echo 'class="active current"'; } ?> ><a href="#"><i aria-hidden="true" class="fa fa-signal"></i> Orders <span class="highlight">111</span> <span class="fa arrow"></span></a>
                            <ul>
                                <li><a href="manage_orders.php">Manage for Orders</a></li>                        
                            </ul>
                        </li>                       
                        <li <?php if($pagename == 'skin') { echo 'class="active current"'; } ?> ><a href="#"><i aria-hidden="true" class="fa fa-eye"></i> Skin Conditions <span class="fa arrow"></span></a>
                            <ul>
                                <li><a href="skin_condition.php">Add a Skin Condition</a></li>                        
                                <li><a href="manage_skin_condition.php?init=1">Manage Skin Conditions</a></li>                        
                            </ul>
                        </li>                        
                        <li <?php if($pagename == 'product-categories') { echo 'class="active current"'; } ?> ><a href="#"><i aria-hidden="true" class="fa fa-sitemap"></i> Product Categories <span class="fa arrow"></span></a>
                            <ul>
                                <li><a href="product_category.php">Add a Product Category</a></li>                        
                                <li><a href="manage_product_category.php?init=1">Manage Product Categories</a></li>                        
                            </ul>
                        </li>
                        <li <?php if($pagename == 'products') { echo 'class="active current"'; } ?> ><a href="#"><i aria-hidden="true" class="fa fa-cubes"></i> Products <span class="fa arrow"></span></a>
                            <ul>
                                <li><a href="add_product.php">Add a Product</a></li>                        
                                <li><a href="manage_product.php?init=1">Manage Products</a></li>                        
                            </ul>
                        </li>                        
                        <li <?php if($pagename == 'page') { echo 'class="current"'; } ?> ><a href="menu_content.php" ><i aria-hidden="true" class="fa fa-pagelines"></i> Page Content</a></li>                        
                        <li <?php if($pagename == 'pdf') { echo 'class="current"'; } ?> ><a href="managepdf.php"><i aria-hidden="true" class="fa fa-file-pdf-o"></i> Manage PDF</a></li>                        
                        <li <?php if($pagename == 'coupons') { echo 'class="active current"'; } ?> ><a href="#"><i aria-hidden="true" class="fa fa-tag"></i> Coupons <span class="fa arrow"></span></a>
                            <ul>
                                <li><a href="add_coupon.php?start=1">Add a Coupon</a></li>                        
                                <li><a href="manage_coupons.php">Manage Coupons</a></li>                        
                            </ul>
                        </li>
                        <li <?php if($pagename == 'taxes') { echo 'class="active current"'; } ?> ><a href="#"><i aria-hidden="true" class="fa fa-money"></i> Taxes <span class="fa arrow"></span></a>
                            <ul>
                                <li><a href="add_tax.php">Add Tax</a></li>
                                <li><a href="manage_taxes.php">Manage Tax</a></li>
                            </ul>
                        </li>
                        <li <?php if($pagename == 'shipping') { echo 'class="active current"'; } ?> ><a href="#"><i aria-hidden="true" class="fa fa-truck"></i> Setup Shipping <span class="fa arrow"></span></a>
                            <ul>
                                <li><a href="add_shipping.php">Add Shipping</a></li>
                                <li><a href="manage_shipping.php">Manage Shipping</a></li>
                            </ul>
                        </li>
                        <li <?php if($pagename == 'discounts') { echo 'class="current"'; } ?> ><a href="manage_discounts.php"><i aria-hidden="true" class="fa fa-tags"></i> Order Discounts</a></li>
                        <li <?php if($pagename == 'luxe') { echo 'class="active current"'; } ?> ><a href="#" ><i aria-hidden="true" class="fa fa-users"></i> Luxe Membership <span class="fa arrow"></span></a>
                            <ul>
                                <li><a href="luxe_membership.php">Add Membership</a></li>
                                <li><a href="manage_luxe_member.php?init=1">Manage Membership</a></li>
                            </ul>
                        </li>
                        <li <?php if($pagename == 'members') { echo 'class="active current"'; } ?> ><a href="#"><i aria-hidden="true" class="fa fa-users"></i> Members <span class="fa arrow"></span></a>
                            <ul>
                                <li><a href="add_members.php">Add Member</a></li>
                                <li><a href="manage_members.php?init=1">Manage Members</a></li>
                            </ul>
                        </li>
                        <li <?php if($pagename == 'sales') { echo 'class="active current"'; } ?> ><a href="#"><i aria-hidden="true" class="fa fa-bar-chart"></i> Sales Report <span class="fa arrow"></span></a>
                            <ul>
                                <li><a href="sales_client.php?init=1">Sales by Client</a></li>
                                <li><a href="sales_country.php?init=1">Sales by Country</a></li>
                                <li><a href="sales_state.php?init=1">Sales by State</a></li>
                                <li><a href="sales_product.php?init=1">Sales by Product</a></li>
                                <li><a href="sales_coupons.php?init=1">Sales by Coupon</a></li>
                                <li><a href="sales_date.php?init=1">Sales by Date</a></li>
                                <li><a href="sales_taxes.php?init=1">Sales by Tax</a></li>
                            </ul>
                        </li>
                        <li <?php if($pagename == 'auto') { echo 'class="current"'; } ?> ><a href="responder.php"><i aria-hidden="true" class="fa fa-envelope"></i> Autoresponder</a></li>
                        <li <?php if($pagename == 'events') { echo 'class="active current"'; } ?> ><a href="#"><i aria-hidden="true" class="fa fa-calendar"></i> Events <span class="fa arrow"></span></a>
                            <ul>
                                <li><a href="event.php">Add Event</a></li>
                                <li><a href="manage_event.php?init=1">Manage Events</a></li>
                            </ul>
                        </li>
                        <li <?php if($pagename == 'qa') { echo 'class="active current"'; } ?> ><a href="#"><i aria-hidden="true" class="fa fa-question"></i> Q&amp;As <span class="fa arrow"></span></a>
                            <ul>
                                <li><a href="qa.php">Add Q&amp;A</a></li>
                                <li><a href="manage_qa.php">Manage Q&amp;A</a></li>
                            </ul>
                        </li>
                        <li <?php if($pagename == 'testi') { echo 'class="active current"'; } ?> ><a href="#">Testimonials <span class="fa arrow"></span></a>
                            <ul>
                                <li><a href="testimonials.php">Add Testimonial</a></li>
                                <li><a href="manage_testimonials.php">Manage Testimonials</a></li>
                            </ul>
                        </li>
                        <li <?php if($pagename == 'silent') { echo 'class="current"'; } ?>><a href="silent_auction.php" >Silent Auction</a></li>
                        <li <?php if($pagename == 'meet') { echo 'class="active current"'; } ?>><a href="Javascript:void(0);">Meet the Team <span class="fa arrow"></span></a>
                            <ul>
                                <li><a href="spateam.php">Add Team</a></li>
                                <li><a href="manage_spateams.php?init=1">Manage Teams</a></li>
                                <li><a href="order-team.php">Order Teams</a></li>
                            </ul>
                        </li>
                        <li <?php if($pagename == 'morris') { echo 'class="active current"'; } ?>><a href="Javascript:void(0);">Morris Register/Waiver <span class="fa arrow"></span></a>
                            <ul>
                                <li><a href="add_register.php?init=1">Add Register</a></li>
                                <li><a href="manage_register.php?init=1">Manage Register</a></li>
                                <li><a href="add_waiver.php?init=1">Add Waiver</a></li>
                                <li><a href="manage_waiver.php?init=1">Manage Waiver</a></li>
                            </ul>
                        </li>
                        <li <?php if($pagename == 'therapist') { echo 'class="active current"'; } ?>><a href="Javascript:void(0);">Therapist <span class="fa arrow"></span></a>
                            <ul>
                                <li><a href="add_therapist.php?init=1">Add Therapist</a></li>
                                <li><a href="manage_therapist.php?init=1">Manage Therapist</a></li>
                                <li><a href="therapist_report.php?init=1">Report</a></li>
                            </ul>
                        </li>
                    </ul>
<?php */ ?>
				</div>
<!-- #end menu -->
				<div class="main">
                					