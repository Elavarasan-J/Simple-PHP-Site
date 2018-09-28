<?php
/**
 * App initiated
 */
require_once('../init.php');
defined('BASE_PATH') OR exit('No direct script access allowed');

$password_dummy='xxxxxxxxxxxxxxxxxxxxxx';

class Security {
	function __construct() {
		$this->db = new DB;
	}
	function set_user($fields) {
		return $this->db->insert($fields,$this->db->tablename_user);
	}
	function update_user($fields,$usersid) {
		$WHERE="user_id=".$usersid;
		return $this->db->update($fields,$WHERE,$this->db->tablename_user);
	}
	function delete_user($usersid) {
		return mysqli_query($db->conn, "DELETE FROM ".$this->db->tablename_user." WHERE user_id=$usersid AND user_id!='$_SESSION[user_id]'");
	}
}
$securityobj=new Security();

if($_REQUEST['action']) {
	$action=$_REQUEST['action'];
	
	if($action=="adduser" || $action=="updateuser") {
		//	Start Error Class
			$fullname_error=($_POST['fullname']=='')?'error':'';
			$username_error=($_POST['username']=='')?'error':'';
			$email_error=($_POST['email']=='')?'error':'';
			$department_id_error=($_POST['department_id']=='')?'error':'';
			
			$pass_error=($_POST['pass']=='' || strlen($_POST['pass'])<5)?'error':'';
			$confirm_pass_error=($_POST['confirm_pass']=='' || strlen($_POST['confirm_pass'])<5)?'error':'';
			if($_POST['pass']!=$_POST['confirm_pass']) {
				$pass_error='error';
				$confirm_pass_error='error';
			}
		//	End Error Class
		
			$ip=IP;
			$createdby=$adminUtilityObj->get_info('user_name');// Needs to change
			$date = $timeObj->sqlDateTime;
			
			$error='';
			$full_name=trim($_POST['fullname']);
			$user_name=trim($_POST['username']);
			$email=trim($_POST['email']);
			$job_title=trim($_POST['job_title']);
			$contact_number=trim($_POST['phone']);
			$office_extn=trim($_POST['office_extn']);
			$password=trim($_POST['pass']);
			$confirmapass=trim($_POST['confirm_pass']);
			
			if($action=="adduser") {
				$status=1;
			} else if($action=="updateuser") {
				$status=(int)$_POST['status'];
			}
			$department_id=((int)$_POST['department_id']>0)?(int)$_POST['department_id']:'';
			
			$error= $utilityObj->isEmptyfield(array("Full Name" =>$full_name, "User Name" => $user_name));
			$error.=$invalid_email=$validateObj->verifyEmail($email,'','<strong>&bull; Email Address:</strong> You must enter a valid Email Address.<br/>');
			
			if($invalid_email!='') {
				$email_error='error';
			}
			$error.=$utilityObj->isEmptyfield(array("Department" =>$department_id));
			if($password=='' ||  $confirmapass=='' || $password!=$confirmapass || strlen($password)<5) {
				$error.='<strong>&bull; Password:</strong> Your password and its retyped copy must be identical and atleast 5 charcters.<br/>';
				$pass_error='error';
				$confirm_pass_error='error';
			}
	}
	
	switch($action) {
		case "adduser":
			if($error=='' && $adminUtilityObj->checkUserExist($user_name)==true)
				$error.='<strong>&bull; Username</strong> Username already exists. Please select another username.<br/>';
				
			$userid=$db->getAutoincrement($db->tablename_user);
			
			$fields = array(
				'full_name'=>$full_name,
				'user_name'=>$user_name,
				'email'=>$email,
				'department_id'=>$department_id,
				'job_title'=>$job_title,
				'contact_number'=>$contact_number,
				'office_extn'=>$office_extn,
				'password'=>$passwordObj->encrypt($password,$userid),
				'status'=>$status,
				'created_by'=>$createdby,
				'created_date'=>$date,
				'created_ip'=>$ip
			);
			if($error=='') {
				$securityobj->set_user($fields);
				header("Location:add_user.php?success=added");
			} else
				include_once('add_user.php');
		break;
		case "updateuser":
			$randkey=$_GET['key'];
			$user=$passwordObj->decrypt_old($_GET['user'],$randkey);
			$userid=$passwordObj->encrypt($user,$randkey);
			
			$usersid=$user;
			
			$fields = array(
				'full_name'=>$full_name,
				'email'=>$email,
				'department_id'=>$department_id,
				'job_title'=>$job_title,
				'contact_number'=>$contact_number,
				'office_extn'=>$office_extn,
				'status'=>$status,
				'modified_by'=>$createdby,
				'modified_date'=>$date,
				'modified_ip'=>$ip
			);
			if($password!=$password_dummy && $password==$confirmapass)	{
				$fields['password']=$passwordObj->encrypt($password,$usersid);
			}
			if($error=='') {
				$user=$passwordObj->encrypt_old($user,$randkey);
				$userid=$passwordObj->encrypt($user,$randkey);
				
				$securityobj->update_user($fields,$usersid);
				header("Location:edit_user.php?success=updated&key=$randkey&user=$user&userid=$userid");
			} else
				include_once('edit_user.php');
		break;
		case 'deleteuser':
			$randkey=$_GET['key'];
			$user=$passwordObj->decrypt_old($_GET['user'],$randkey);
			
			$usersid=$user;  //this should be relative
			if($securityobj->delete_user($usersid)) {
				header("Location:manage_users.php?success=deleted");
			}
		break;
    }
}
?>