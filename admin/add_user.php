<?php
/**
 * App initiated
 */
require_once('../init.php');
defined('BASE_PATH') OR exit('No direct script access allowed');

	$gen_pass=1;
	
    $fullname = (isset($_POST['fullname']) && $_POST['fullname']!='')?$_POST['fullname']:'';
    $username = (isset($_POST['username']) && $_POST['username']!='')?$_POST['username']:'';
    $email = (isset($_POST['email']) && $_POST['email']!='')?$_POST['email']:'';
    $job_title = (isset($_POST['job_title']) && $_POST['job_title']!='')?$_POST['job_title']:'';
    $phone = (isset($_POST['phone']) && $_POST['phone']!='')?$_POST['phone']:'';
    $office_extn = (isset($_POST['office_extn']) && $_POST['office_extn']!='')?$_POST['office_extn']:'';

	include_once("inc/header.php");
	include_once("inc/menu.php");
?>
						<h2 class="title <?php $utilityObj->title_color(); ?>"><i aria-hidden="true" class="fa fa-lock"></i> Add User Account</h2>
                        
						<p>This section allows you to add new user account. Fields marked with <span class="req">*</span> are mandatory.</p>
                    
						<?php include_once('inc/form_message.php'); ?><br />
                        
						<form name="adduserform" action="ClassUser.php?action=adduser" method="post" onsubmit="return adduservalidate();" >
                            <div class="imp">
                                <table width="100%" border="0" cellspacing="0" cellpadding="0" class="table1 auto" style="margin:0;">
                                    <tr>
                                        <td colspan="2" class="<?php echo ((isset($fullname_error))?$fullname_error:''); ?>"><label for="fullname"><span class="req">*</span> Full Name:</label> <input type="text" name="fullname" id="fullname" value="<?php echo ((isset($fullname))?$fullname:''); ?>" style="width:350px" /></td>  
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="<?php echo ((isset($username_error))?$username_error:''); ?>"><label for="username"><span class="req">*</span> User Name:</label><input type="text" name="username" id="username" value="<?php echo ((isset($username))?$username:''); ?>" style="width:350px" /></td>  
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="<?php echo ((isset($email_error))?$email_error:''); ?>"><label for="email"><span class="req">*</span> Email Address:</label><input type="text" name="email" id="email" value="<?php echo ((isset($email))?$email:''); ?>" style="width:350px"/></td>  
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="<?php echo ((isset($department_id_error))?$department_id_error:''); ?>">
                                            <label for="department_id"><span class="req">*</span> Department:</label>
                                            <select class="chosen" data-placeholder="Select Department" name="department_id" id="department_id" style="width:200px">		
                                                <option value=""></option>		
                                                <?php $utilityObj->getOptionsList("SELECT * FROM $db->TB_department WHERE status=1 ORDER BY department_name ASC",1,0,((isset($department_id))?$department_id:'')); ?>
                                            </select>
                                        </td> 
                                    </tr>
                                    <tr>
                                        <td colspan="2"><label for="job_title">Job Title:</label><input type="text" name="job_title" id="job_title"  value="<?php echo ((isset($job_title))?$job_title:''); ?>"style="width:300px" /></td>
                                    </tr>
                                    <tr>
                                        <td><label for="phone">Contact Number:</label><input type="text"  name="phone" id="phone" value="<?php echo ((isset($phone))?$phone:''); ?>" style="width:300px" /></td>
                                        <td><label for="office_extn">Office Extn:</label><input type="text"  name="office_extn" id="office_extn" value="<?php echo ((isset($office_extn))?$office_extn:''); ?>" style="width:100px" /></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"><label for="generated_pass">Generate Password: <small><em>(Make a note of your password before save)</em></small></label><input type="text" name="get_pass" id="generated_pass" style="margin:0;" /><a class="button1 green copyPrevText" style="margin-right:20px;" id="usePass">Copy &amp; Use</a><a class="button1 green gen_pass">Generate Password</a> <i class="fa fa-spinner fa-spin" id="gen_pass_imgloader" style="display:none;font-size:22px;line-height:32px;vertical-align:top;animation-duration:1s;"></i></td>
                                    </tr>
                                    <tr>
                                        <td width="25%" class="<?php echo ((isset($pass_error))?$pass_error:''); ?>"><label for="pass"><span class="req">*</span> Password:</label><input type="password" name="pass" id="pass" style="width:300px" /></td>
                                        <td width="75%" class="<?php echo ((isset($confirm_pass_error))?$confirm_pass_error:''); ?>"><label for="confirm_pass"><span class="req">*</span> Confirm Password:</label><input type="password"  name="confirm_pass" id="confirm_pass" style="width:300px" /></td>
    
                                    </tr>
                                    <tr>
                                        <td colspan="2"><button type="submit" name="submit" class="button1"  /><i aria-hidden="true" class="fa fa-pencil"></i> Create User Account</button> &nbsp; <a href="manage_users.php" class="button1 red"><i class="fa fa-ban"></i> Cancel</a></td>
                                    </tr>
                                </table>
                                <div class="clear"></div>
                            </div>                    
                    	</form>
                    	<div class="notes">
                			<p><strong>Notes :</strong></p>
                        	<ul>
                        		<li> All users created will be Active by default and you can make a user inactive by using the edit option.</li>
                            </ul>
                        </div>
<?php include_once("jscript.php"); ?>
<?php include_once("inc/footer.php"); ?>