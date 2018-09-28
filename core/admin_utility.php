<?php
defined('BASE_PATH') OR exit('No direct script access allowed');

class AdminUtility {
	function __construct() {
		$this->db = new DB;
		$this->passwordObj = new Password;
	}

	function info($obj) {
		if($obj!='') {
			switch($obj) {
				case "user_name" : echo $_SESSION[ADMIN_SESSION]['user_name']; break;
			}
		}
	}
	function get_info($obj) {
		if($obj!='') {
			switch($obj) {
				case "user_name" : return (isset($_SESSION[ADMIN_SESSION]['user_name']) && $_SESSION[ADMIN_SESSION]['user_name']!='')?$_SESSION[ADMIN_SESSION]['user_name']:'-'; break;
			}
		}
	}

#####################################################################################################################
	function checkUserEmail($email,$tablename)	{
		$selectemail=mysqli_query($this->db->conn, "SELECT email FROM $tablename WHERE email='$email'");
		$numselectemail=@mysqli_num_rows($selectemail);
		return ($numselectemail>0)?true:false;
	}

	function checkUserExist($userName)	{
		global $tablename_user;

		$selectusername=mysqli_query($this->db->conn, "SELECT * FROM $tablename_user WHERE user_name='$userName'");
		$numselectusername=@mysqli_num_rows($selectusername);
		return ($numselectusername>0)?true:false;
	}

	function checkUserId($userId,$tablename_user)	{
		$selectusername=mysqli_query($this->db->conn, "SELECT * FROM $tablename_user WHERE rep_id='$userId'");
		$numselectusername=@mysqli_num_rows($selectusername);
		return ($numselectusername>0)?true:false;
	}

	function checkUserWhereDynamic($tablename,$where)	{
		$memberRes=mysqli_query($this->db->conn, "SELECT * FROM $tablename WHERE $where");
		$memberRows=@mysqli_num_rows($memberRes);
		return ($memberRows>0)?true:false;
	}
#####################################################################################################################

	//	check security (
	function checkSecurity($user_name,$password) {
		$sel='';
		$user_name=addslashes($user_name);
		$password=addslashes($password);
		
		$memberdetails=$this->db->getSingleRec($this->db->TB_security,"user_name='$user_name'",'user_id');
		
		if($user_name!='' && $password!='' && $memberdetails==true)	{
			$password_detail=$this->passwordObj->encrypt($password,$memberdetails['user_id']);
			
			$sql="SELECT * FROM ".$this->db->TB_security." WHERE BINARY user_name='$user_name' AND BINARY password='$password_detail' AND status=1";
			$this->db->query($sql);
			if($this->db->recordcount>0)	{
				return $this->db->getrec();
			} else
				return false;
		} else {
			return false;
		}
	}
	//	) check security

	//	validate security (
	function validateSecurity($user_name,$password) {
		$sel='';
		$user_name=addslashes($user_name);
		$password=addslashes($password);
		
		$memberdetails=$this->db->getSingleRec($this->db->TB_security,"user_name='$user_name' AND password='$password'");
		
		if($user_name!='' && $password!='' && $memberdetails==true)	{
			return $memberdetails;
		} else {
			return false;
		}
	}
	//	) check security

	//	User Privileges
	function userPrivileges($department_id) {
		$WHERE='';
		$arr=array();
		if($department_id!=1)	{
			//$query1=subqueryIncomma("SELECT privileges FROM $this->db->TB_department WHERE department_id=$department_id AND status=1",'privileges');
			$this->db->query("SELECT privileges FROM ".$this->db->TB_department." WHERE department_id=$department_id AND status=1");
			$query1=implode(',',$this->db->getrec('array'));
			$WHERE=" WHERE menu_id IN ($query1)";
		}
		$WHERE=($WHERE!='')?$WHERE." AND menu_status=1":"WHERE menu_status=1";
		$query="SELECT menu_id FROM ".$this->db->TB_admin_menu." $WHERE";
		$this->db->query($query);
		if($this->db->recordcount>0)	{
			while($fetch=$this->db->getrec()) {
				$arr[].=$fetch['menu_id'];
			}
			$arr[].=1;
			$arr=array_unique($arr);
			$_SESSION[ADMIN_SESSION]['USER_MODULE_LIST']=$arr;
			return $arr;
		}	//return false;
	}
	//	User Privileges

	// admin module access (
	function admin_module_access($userid,$role=1) {
		$WHERE='';

		if($role!=2)	{
			$query1=subqueryIncomma("SELECT modules FROM $this->db->tablename_user WHERE user_id=$userid AND status=1",'modules');
			$WHERE=" WHERE menu_id IN ($query1)";
		}
		$WHERE=($WHERE!='')?$WHERE." AND menu_status=1":"WHERE menu_status=1";
		$query="SELECT menu_id FROM ".$this->db->TB_admin_menu." $WHERE";
		
		$this->db->query($query);
		if($this->db->numRows>0)	{
			while($fetch=$this->db->getrec()) {
				$arr[].=$fetch['menu_id'];
			}
			$arr[].=1;
			$_SESSION[ADMIN_SESSION]['USER_MODULE_LIST']=$arr;
			return $arr;
		}	//return false;
	}
	// ) admin module access

	// Get options 3 level menu structure display for all tables
	function getOptions3LevelMenu($current_menu_id='',$text=array(0),$value=0,$sel=NULL,$all=1,$default='All Websites'){
		// Level 1
		$WHERE = ($current_menu_id!='')?" AND menu_id!=".$current_menu_id:"";
		$query1=mysqli_query($this->db->conn, "SELECT * FROM ".$this->db->TB_admin_menu." WHERE parent_menu_id<1".$WHERE." ORDER BY menu_order_num ASC");
		$select=(!$all)?'<option value="0">'.$default.'</option>':'';
		while($r1=mysqli_fetch_row($query1)){
			$selected=(strtolower(trim($sel))==strtolower(trim($r1[$value])))?('selected="selected"'):('');
			$val='';
			if(is_array($text)) {
				foreach($text as $key=>$text_val) {
					if($val!='')
						$val.=" - ";
					$val.=html_entity_decode($r1[$text_val]);
				}
			} else {
				$val=html_entity_decode($r1[$text]);
			}
			$select.='<option value="'.$r1[$value].'" '.$selected.' >'.$val.'</option>';
			
			// Level 2
			$query2=mysqli_query($this->db->conn, "SELECT * FROM ".$this->db->TB_admin_menu." WHERE parent_menu_id=".$r1[$value].$WHERE." ORDER BY menu_order_num ASC");
			while($r2=mysqli_fetch_row($query2)){
				$selected=(strtolower(trim($sel))==strtolower(trim($r2[$value])))?('selected="selected"'):('');
				$val='';
				if(is_array($text)) {
					foreach($text as $key=>$text_val) {
						if($val!='')
							$val.=" - ";
						$val.=html_entity_decode($r2[$text_val]);
					}
				} else {
					$val=html_entity_decode($r2[$text]);
				}
				$select.='<option value="'.$r2[$value].'" '.$selected.' >&mdash; '.$val.'</option>';
				
				
				// Level 3
				$query3=mysqli_query($this->db->conn, "SELECT * FROM ".$this->db->TB_admin_menu." WHERE parent_menu_id=".$r2[$value].$WHERE." ORDER BY menu_order_num ASC");
				while($r3=mysqli_fetch_row($query3)){
					$selected=(strtolower(trim($sel))==strtolower(trim($r3[$value])))?('selected="selected"'):('');
					$val='';
					if(is_array($text)) {
						foreach($text as $key=>$text_val) {
							if($val!='')
								$val.=" - ";
							$val.=html_entity_decode($r3[$text_val]);
						}
					} else {
						$val=html_entity_decode($r3[$text]);
					}
					$select.='<option value="'.$r3[$value].'" '.$selected.' >&mdash; &mdash; '.$val.'</option>';
					
				}
			}
		}
		echo  $select;
	}
}
$adminUtilityObj = new AdminUtility;
?>