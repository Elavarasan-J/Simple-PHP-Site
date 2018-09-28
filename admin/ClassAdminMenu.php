<?php
/**
 * App initiated
 */
require_once('../init.php');
defined('BASE_PATH') OR exit('No direct script access allowed');

// $db->debug_mode=true;
if(isset($_REQUEST['action']) && $_REQUEST['action']!='') {
	$action=$_REQUEST['action'];
	
	if($action=='add_menu' || $action=='update_menu') {
		if(isset($_POST['submit_navigation'])) {
			$menu_name_error=($_POST['menu_name']=='')?'error':'';
			$menu_link_error=($_POST['menu_link']=='')?'error':'';
		}	
	}
	$managePage="manage-admin-menu.php";
	$mainTable=$db->TB_admin_menu;
	
	switch($action) {
		case "add_menu":
			$userid=$_SESSION[ADMIN_SESSION]['user_id'];
			$error='';
			$track=array();
			$arr['parent_menu_id']=$parent_menu_id=trim($_POST['parent_menu_id']);
			$arr['menu_name']=trim($_POST['menu_name']);
			$arr['menu_link']=trim($_POST['menu_link']);
			$arr['class_name']=trim($_POST['class_name']);
			$arr['active_page']=trim($_POST['active_page']);
			$arr['menu_order_num']=($_POST['menu_order_num']>0)?trim($_POST['menu_order_num']):1;
			$arr['menu_target']=trim($_POST['menu_target']);
			$arr['menu_status']=trim($_POST['menu_status']);
			if($arr['parent_menu_id']!='' && $arr['parent_menu_id']>0) {
				$parent_menu_rec=$db->getSingleRec($mainTable,"menu_id=$parent_menu_id && menu_status=1",'menu_id');
				if($parent_menu_rec==FALSE || $parent_menu_rec['menu_id']=='' || $parent_menu_rec['menu_id']<1) {
					$error.="Please select a valid Parent Menu.<br />";
				}
			}
			if($arr['menu_name']=='') {
				$error.="<strong>&bull; Menu Name:</strong> You must enter a valid Menu Name.<br />";
			}
			if($arr['menu_link']=='') {
				$error.="<strong>&bull; Menu Link:</strong> You must enter a valid Menu Link.<br />";
			}
			if($error=='') {
				$db->insert($arr,$mainTable);
				
				$WHERE='';
				if($_SESSION[ADMIN_SESSION]['full_privilege']!=1)	{
					$query1=$utilityObj->subqueryIncomma("SELECT privileges FROM $db->TB_department WHERE user_id=".$_SESSION[ADMIN_SESSION]['department_id']." AND status=1",'privileges');
					
					$WHERE=" WHERE menu_id IN ($query1)";
				}
				$query="SELECT menu_id FROM $mainTable $WHERE";
				$db->query($query);
				if($db->recordcount>0)	{
					$menu_arr=array();
					while($fetch=$db->getrec()) {
						$menu_arr[].=$fetch['menu_id'];
					}
					$menu_arr[].=1;
					$_SESSION[ADMIN_SESSION]['USER_MODULE_LIST']=$menu_arr;
				}
				$utilityObj->headerLocation("$managePage?success=added");
			} else
				include_once("$managePage");
		break;
		case "update_menu":
			$error='';
			$track=array();
			$arr['parent_menu_id']=$parent_menu_id=trim($_POST['parent_menu_id']);
			$arr['menu_name']=trim($_POST['menu_name']);
			$arr['menu_link']=trim($_POST['menu_link']);
			$arr['class_name']=trim($_POST['class_name']);
			$arr['active_page']=trim($_POST['active_page']);
			$arr['menu_order_num']=($_POST['menu_order_num']>0)?trim($_POST['menu_order_num']):1;
			$arr['menu_target']=trim($_POST['menu_target']);
			$arr['menu_status']=trim($_POST['menu_status']);
			if($arr['parent_menu_id']!='' && $arr['parent_menu_id']>0) {
				$parent_menu_rec=$db->getSingleRec($mainTable,"menu_id=$parent_menu_id",'menu_id');
				if($parent_menu_rec==FALSE || $parent_menu_rec['menu_id']=='' || $parent_menu_rec['menu_id']<1) {
					$error.="Please select a valid Parent Menu.<br />";
				}
			}
			if($arr['menu_name']=='') {
				$error.="<strong>&bull; Menu Name:</strong> You must enter a valid Menu Name.<br />";
			}
			if($arr['menu_link']=='') {
				$error.="<strong>&bull; Menu Link:</strong> You must enter a valid Menu Link.<br />";
			}
			$contentids=$_SESSION['EDIT_MENU'];
			//$db->debug_mode=true;
			if($error=='' && $contentids!='') {
				$WHERE="menu_id=$contentids";
				$db->update($arr,$WHERE,$mainTable);
				$sesObj->delete($_SESSION['EDIT_MENU']);
				$utilityObj->headerLocation("$managePage?success=updated&menu=$contentids");
			} else
				include_once("$managePage");
		break;
		case "switchStatus":
			$id=$_REQUEST['menu'];
			$WHERE="menu_id=$id";
			$update=$db->main_update("menu_status=CASE WHEN menu_status=0 THEN 1 WHEN menu_status=1 THEN 0 END",$WHERE,$mainTable);
			$trackInfoObj->saveTrack($id, $mainTable);
			$utilityObj->headerLocation("$managePage?success=updated");
		break;
		case "delete_menu":
			$con=(int)$_REQUEST['menu'];
			$WHERE="menu_id=$con";
			//echo $WHERE;exit;
			$msg=($db->delSingleRec($mainTable,$WHERE))?'success':'fail';
			$utilityObj->headerLocation("$managePage?$msg=deleted");
		break;
    }
} else
	include_once("$managePage");
?>