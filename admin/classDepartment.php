<?php
/**
 * App initiated
 */
$uploadMethods='yes';
require_once('../init.php');
defined('BASE_PATH') OR exit('No direct script access allowed');

$managePage="department.php";
$mainTable=$db->TB_department;

// $db->debug_mode=true;
if(isset($_REQUEST['action']) && $_REQUEST['action']!='') {
	$action=$_REQUEST['action'];
	$error='';
	
	if($action=='add' || $action=='update') {
		if(isset($_POST['submit'])) {
			$department_name_error=($_POST['department_name']=='')?'error':'';
			$privileges_error=($_POST['privileges']=='')?'error':'';
		}	
		$arr['department_name']=trim($_POST['department_name']);
		$arr['full_privilege']=(isset($_POST['full_privilege']) && $_POST['full_privilege']!='')?$_POST['full_privilege']:0;
		$arr['privileges']=(isset($_POST['privileges']) && is_array($_POST['privileges']))?$utilityObj->arrayToComma($_POST['privileges']):'';
		$arr['status']=trim($_POST['status']);
		
		if($arr['department_name']=='') {
			$error.="<strong>&bull; Department Name:</strong> You must enter a valid Department Name.<br />";
		}
	}
	switch($action) {
		case "add":
			if($error=='') {
				$id=$db->getAutoincrement($mainTable);
				
				$trackInfoObj->saveTrack($id, $mainTable);
				$db->insert($arr,$mainTable);
				$utilityObj->headerLocation("$managePage?success=added&department=$id");
			} else
				include_once("$managePage");
		break;
		case "update":
			$id=$_REQUEST['department'];
			
			if($error=='' && $id!='') {
				$WHERE="department_id=$id";
				$trackInfoObj->saveTrack($id, $mainTable);
				$db->update($arr,$WHERE,$mainTable);
				$utilityObj->headerLocation("$managePage?success=updated&department=$id");
			} else
				include_once("$managePage");
		break;
		case "switchStatus":
			$id=(int)$_REQUEST['department'];
			$key=(isset($_REQUEST['key']) && $_REQUEST['key']!='')?$_REQUEST['key']:'status';
			$trackInfoObj->saveTrack($id, $mainTable);
			$WHERE="department_id=$id";
			$update=$db->main_update("$key=CASE WHEN $key=0 THEN 1 WHEN $key=1 THEN 0 END",$WHERE,$mainTable); 
			$utilityObj->headerLocation("$managePage?success=updated");
		break;
		case "delete_department":
			$id=(int)$_REQUEST['department'];
			$WHERE="department_id=$id";
			//echo $WHERE;exit;
			$msg=($db->delSingleRec($mainTable,$WHERE))?'success':'fail';
			$utilityObj->headerLocation("$managePage?$msg=deleted");
		break;
    }
} else
	include_once("$managePage");
?>