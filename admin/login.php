<?php
/**
 * App initiated
 */
$checkAdminLogin='no';
require_once('../init.php');
defined('BASE_PATH') OR exit('No direct script access allowed');

//require_once('inc/countryValidate.php');  

// Logout condition
if( isset($_REQUEST['logout']) && $_REQUEST['logout']=='success') {
	if(isset($_SESSION[ADMIN_SESSION]['user_name']) && $_SESSION[ADMIN_SESSION]['user_name']!='') {
		$temp['name']=$_SESSION[ADMIN_SESSION]['user_name'];
		$temp['name1']=$_SESSION[ADMIN_SESSION]['password'];
		$temp['date']=$timeObj->sqlDateTime;
		$temp['ip']=IP;
		$temp['status']='logout';
		$temp['referrer']=$utilityObj->get_referrer();
		$db->insert($temp,$db->TB_temp);
	}
	if(isset($_SESSION))
		foreach($_SESSION as $key=>$val)
			unset($_SESSION[$key]);	
	session_destroy();
}
// Login
else if( isset($_REQUEST['login']) && $_REQUEST['login']==1) {
	$pass=$_POST['password'];
	$user=$_POST['username'];
	
	$fetchmember=$adminUtilityObj->checkSecurity($user,$pass);
//	$utilityObj->printAny($fetchmember);
// Check username and password  for member
	if($fetchmember) {
		$_SESSION[ADMIN_SESSION]['user_id']=$fetchmember['user_id'];
		$_SESSION[ADMIN_SESSION]['user_name']=$fetchmember['user_name'];
		$_SESSION[ADMIN_SESSION]['full_name']=$fetchmember['full_name'];
		$_SESSION[ADMIN_SESSION]['password']=$fetchmember['password'];
		$_SESSION[ADMIN_SESSION]['user_session_id']=$passwordObj->encrypt(SESSION_ID,$fetchmember['user_id']);
		$full_privilege_arr=$db->getSingleRec($db->TB_department,"department_id=$fetchmember[department_id]","full_privilege");
		$_SESSION[ADMIN_SESSION]['full_privilege']=$full_privilege_arr['full_privilege'];
		$_SESSION[ADMIN_SESSION]['department_id']=$fetchmember['department_id'];
		$user_privileges_arr=$adminUtilityObj->userPrivileges($fetchmember['department_id']);
		
		//admin_module_access($fetchmember['user_id'],$roles);
		//$db->debug_mode=true;
		$db->update(array('login_date'=>$timeObj->sqlDateTime,'last_login_ip'=>IP),"user_id='$fetchmember[user_id]'",$db->tablename_user);
		
		$temp['name']=$fetchmember['user_name'];
		$temp['name1']=$fetchmember['password'];
		$temp['date']=$timeObj->sqlDateTime;
		$temp['ip']=IP;
		$temp['status']='success';	
		$temp['referrer']=$utilityObj->get_referrer();
		$db->insert($temp,$db->TB_temp);
		
		if(isset($_SESSION[ADMIN_SESSION]['url'])) {
			$utilityObj->headerLocation($_SESSION[ADMIN_SESSION]['url']);
		} else {
			$utilityObj->headerLocation(ADMIN_PATH."index.php");
		}			
	} else	{
		$temp['name']=$user;
		$temp['name1']=$pass;
		$temp['date']=$timeObj->sqlDateTime;
		$temp['ip']=IP;
		$temp['status']='failed';
		$temp['referrer']=$utilityObj->get_referrer();
		$db->insert($temp,$db->TB_temp);
			
		if(isset($_SESSION))
			foreach($_SESSION as $key=>$val)
				unset($_SESSION[$key]);
		
		$utilityObj->headerLocation(ADMIN_PATH.'login.php?fail=1');
	}
} 
// Session maintainence
else if(isset($_SESSION[ADMIN_SESSION]['user_id']) && $_SESSION[ADMIN_SESSION]['user_id']!='' && $_SESSION[ADMIN_SESSION]['user_name']!='' && $_SESSION[ADMIN_SESSION]['password']!='' && $_SESSION[ADMIN_SESSION]['user_session_id']==$passwordObj->encrypt(SESSION_ID,$_SESSION[ADMIN_SESSION]['user_id'])) {
	$fetchmember=$adminUtilityObj->validateSecurity($_SESSION[ADMIN_SESSION]['user_name'],$_SESSION[ADMIN_SESSION]['password']);
	
// Check username and password  for member
	if($fetchmember) {
		$_SESSION[ADMIN_SESSION]['user_id']=$fetchmember['user_id'];
		$_SESSION[ADMIN_SESSION]['user_name']=$fetchmember['user_name'];
		$_SESSION[ADMIN_SESSION]['full_name']=$fetchmember['full_name'];
		$_SESSION[ADMIN_SESSION]['password']=$fetchmember['password'];
		$_SESSION[ADMIN_SESSION]['user_session_id']=$passwordObj->encrypt(SESSION_ID,$fetchmember['user_id']);
		$full_privilege_arr=$db->getSingleRec($db->TB_department,"department_id=$fetchmember[department_id]","full_privilege");
		$_SESSION[ADMIN_SESSION]['full_privilege']=$full_privilege_arr['full_privilege'];
		$_SESSION[ADMIN_SESSION]['department_id']=$fetchmember['department_id'];
		$user_privileges_arr=$adminUtilityObj->userPrivileges($fetchmember['department_id']);
		
		$db->update(array('login_date'=>$timeObj->sqlDateTime,'last_login_ip'=>IP),"user_id='$fetchmember[user_id]'",$tablename_user);
		header("location:index.php");
	} else	{
		if(isset($_SESSION))
			foreach($_SESSION as $key=>$val)
				unset($_SESSION[$key]);
		header("location:login.php");
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
        <link rel="shortcut icon" href="<?php echo ASSET_PATH.'images/'; ?>favicon.png">
		<title><?php echo SITE_NAME; ?> | Content Management System</title>
		<link rel="stylesheet" type="text/css" href="<?php echo ADMIN_PATH.'style/'; ?>login.css" />
        <link rel="stylesheet" href="<?php echo ADMIN_PATH.'style/'; ?>font-awesome.css" />
    <link href='http://fonts.googleapis.com/css?family=PT+Sans+Caption:400,700' rel='stylesheet' type='text/css'>
	</head>
	<body>
		<div id="wrap" >
			<table cellspacing="0" cellpadding="0" class="table">
				<tr>
					<td>
						<?php
                            if( isset($_REQUEST['logout']) && $_REQUEST['logout']=='success')
                                echo '<div class="success"><i class="fa fa-check" aria-hidden="true"></i> You have successfully logged out.</div>';
                
                            else if( isset($_REQUEST['fail']) && $_REQUEST['fail']==1)
                                echo '<div class="error"> <i class="fa fa-warning" aria-hidden="true"></i> Incorrect Username and Password</div>';
								
							else if(isset($_REQUEST['expired']) && $_REQUEST['expired']==1)
								echo '<div class="error"> <i class="fa fa-warning" aria-hidden="true"></i> Session Expired. Please Login to Continue.</div>';
                                
                        ?>
						<form action="login.php?login=1" method="post">
							<h1>Admin Login</h1>
                            <div class="logbox shadow">
                                <img src="<?php echo ADMIN_PATH.'images/logo/logo.png'; ?>" alt="Ann Cares" />
                                <p class="field"><i aria-hidden="true" class="fa fa-user"></i><input type="text" class="text1" name="username" id="username"  /></p>
                                <p class="field"><i aria-hidden="true" class="fa fa-key"></i><input type="password" class="text1" name="password" id="password" /></p>
                                <p><input type="submit" class="button1" value="Log In" /></p>
                                <p class="align-right"><small><i class="fa fa-key"></i> <a href="reset_password.php">Reset Password?</a></small></p>
                            </div>

						</form>
					</td>
                </tr>
            </table>
		</div>
	</body>
</html>