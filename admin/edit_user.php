<?php
/**
 * App initiated
 */
require_once('../init.php');
defined('BASE_PATH') OR exit('No direct script access allowed');

if(isset($_REQUEST['userid']) || $usersid!='') {
	$gen_pass=1;
	
	include_once("inc/header.php");
	include_once("inc/menu.php");
	
	$randkey=$_GET['key'];
	$user=$passwordObj->decrypt_old($_GET['user'],$randkey);
	$userid=$passwordObj->encrypt($user,$randkey);
?>
					<h2 class="title <?php $utilityObj->title_color(); ?>"><i aria-hidden="true" class="fa fa-lock"></i> Edit User Account</h2>
                    <p>This section allows you to edit an user account. Fields marked with <span class="req">*</span>  are mandatory.</p>
                    
					<?php include_once('inc/form_message.php'); ?><br />
    
<?php
	$userid=(isset($usersid) && $usersid!='')?(int)$usersid:(int)$user;

	$edituser=mysqli_query($db->conn, "SELECT * FROM $db->tablename_user WHERE user_id=$userid");
	if(@mysqli_num_rows($edituser)>0) {
		$fetchuser=mysqli_fetch_array($edituser);
		extract($fetchuser);
		
		$user=$passwordObj->encrypt_old($user,$randkey);
		$userid=$passwordObj->encrypt($user,$randkey);
	
		$password_dummy='xxxxxxxxxxxxxxxxxxxxxx';
?>
                    <form name="adduserform" action="ClassUser.php?action=updateuser&key=<?php echo $randkey; ?>&user=<?php echo $user; ?>&userid=<?php echo $userid;?>" method="post" onsubmit="return adduservalidate();" >
                    	<div class="imp">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="table1 auto" style="margin:0;">
                                <tr>
                                    <td colspan="2"><label for="fullname"><span class="req">*</span> Full Name:</label> <input type="text" name="fullname" id="fullname" value="<?php echo $full_name; ?>" style="width:350px" /></td>  
                                </tr>
                                <tr>
                                    <td colspan="2"><label for="username"><span class="req">*</span> User Name:</label><input type="text" name="username" id="username"  value="<?php echo $user_name; ?>"style="width:350px" /></td>  
                                </tr>
                                <tr>
                                    <td colspan="2"><label for="email"><span class="req">*</span> Email Address:</label><input type="text" name="email"  id="email"  value="<?php echo $email; ?>" style="width:350px"/></td>  
                                </tr>
                                <tr>
                                    <td colspan="2">
                                    	<label for="department_id"><span class="req">*</span> Department:</label>
                                        <select class="chosen" data-placeholder="Select Department" name="department_id" id="department_id" style="width:200px">		
                                        	<option value=""></option>		
                                            <?php $utilityObj->getOptionsList("SELECT * FROM $db->TB_department WHERE status=1 ORDER BY department_name ASC",1,0,$department_id); ?>
                                        </select>
                                    </td> 
                                </tr>
                                <tr>
                                    <td colspan="2"><label for="job_title">Job Title:</label><input type="text" name="job_title" id="job_title"  value="<?php echo $job_title; ?>"style="width:300px" /></td>
                                </tr>
                                <tr>
                                    <td><label for="phone">Contact Number:</label><input type="text" name="phone" id="phone"  value="<?php echo $contact_number; ?>"style="width:300px" /></td>
                                    <td><label for="office_extn">Office Extn:</label><input type="text"  name="office_extn" id="office_extn" value="<?php echo $office_extn; ?>" style="width:100px" /></td>
                                </tr>
                                <tr>
                                    <td colspan="2"><label for="generated_pass">Generate Password: <small><em>(Make a note of your password before save)</em></small></label><input type="text" name="get_pass" id="generated_pass" style="margin:0;" /><a class="button1 green copyPrevText" style="margin-right:20px;" id="usePass">Copy &amp; Use</a><a class="button1 green gen_pass">Generate Password</a> <i class="fa fa-spinner fa-spin" id="gen_pass_imgloader" style="display:none;font-size:22px;line-height:32px;vertical-align:top;animation-duration:1s;"></i></td>
                                </tr>
                                <tr>
                                    <td width="25%"><label for="pass"><span class="req">*</span> Password:</label><input type="password" name="pass" id="pass" value="<?php echo $password_dummy; ?>" style="width:300px" /></td>
									<td width="75%" ><label for="confirm_pass"><span class="req">*</span> Confirm Password:</label><input type="password"  name="confirm_pass" id="confirm_pass" value="<?php echo $password_dummy; ?>" style="width:300px" /></td>
							 	</tr>
                                <tr>
                                    <td colspan="2">
                                    	<label for="status">Account Status: </label><br />
										<select name="status" class="chosen" data-placeholder="Select status">
											<option value="1" <?php if($status==1) echo 'Selected'; ?>>Active</option>
											<option value="0" <?php if($status!=1) echo 'Selected'; ?>>InActive</option>
										</select>
									</td> 
                                </tr>
                                <tr>
                                    <td colspan="2"><button type="submit" name="submit" class="button1"  /><i aria-hidden="true" class="fa fa-pencil"></i> Update User Account</button> &nbsp; <a href="manage_users.php" class="button1 red"><i class="fa fa-ban"></i> Cancel</a></td>
							</table>
						</div>
					</form>

	<?php } ?>
                <div class="notes">
                	<p><strong>Account Activity:</strong></p>
                    <ul>
                        <li> Last logged-in on <?php echo $login_date; ?> from <?php echo $last_login_ip; ?></li>
                    </ul>
                </div>
<!-- start footer -->
<?php include_once("jscript.php"); ?>
<?php
	include_once("inc/footer.php");
?>
<!-- end footer -->
<?php } ?>