<?php
/**
 * App initiated
 */
$uploadMethods='yes';
require_once('../init.php');
defined('BASE_PATH') OR exit('No direct script access allowed');

$pageName='project.php';
$managePage='manage_project.php';
$mainTable=$db->TB_project;

//$db->debug_mode=true;
if(isset($_REQUEST['action']) && $_REQUEST['action']!='') {
	$action=$_REQUEST["action"];
	
	if($action=='add' || $action=='update') {
		$error="";
		$arr["title"]=(isset($_POST["title"]) && trim($_POST["title"])!="")?htmlentities(addslashes(trim($_POST["title"]))):"";
		$arr["place"]=(isset($_POST["place"]) && trim($_POST["place"])!="")?htmlentities(addslashes(trim($_POST["place"]))):"";

		$arr["from_date"]=(isset($_REQUEST['filter_from']) && $_REQUEST['filter_from']!='')?$timeObj->switch_date_format($_REQUEST['filter_from'],'-'):'';
		$arr["to_date"]=(isset($_REQUEST['filter_to']) && $_REQUEST['filter_to']!='')?$timeObj->switch_date_format($_REQUEST['filter_to'],'-'):'';

		$arr["supported_by"]=(isset($_POST["supported_by"]) && trim($_POST["supported_by"])!="")?htmlentities(addslashes(trim($_POST["supported_by"]))):"";
		$arr["funded_by"]=(isset($_POST["funded_by"]) && trim($_POST["funded_by"])!="")?htmlentities(addslashes(trim($_POST["funded_by"]))):"";

		$arr["description"]=(isset($_POST["description"]) && trim($_POST["description"])!="")?htmlentities(addslashes(trim($_POST["description"]))):"";

		$arr["link_name"]=(isset($_POST["link_name"]) && trim($_POST["link_name"])!="")?htmlentities(addslashes(trim($_POST["link_name"]))):"";
		$arr["link_url"]=(isset($_POST["link_url"]) && trim($_POST["link_url"])!="")?trim($_POST["link_url"]):"";
		$arr["external_link"]=(isset($_POST["external_link"]) && ($_POST["external_link"])!="")?1:0;
		$arr["status"]=(isset($_POST["status"]) && trim($_POST["status"])!="")?trim($_POST["status"]):"";
		
		$arr['featured_img']=(isset($_POST['featured_img']) && $_POST['featured_img']!='')?$_POST['featured_img']:NULL;

		$error= $utilityObj->isEmptyfield(array("Title" =>$utilityObj->trims($arr['title'],'')));
		
		if($arr['title']=='') {
			$title_error='error';
		}
		//echo '<pre>'; print_r($arr); echo '</pre>'; exit;
	}
	
	switch($action)	{
		case "add":
			if($error=="") {
				$id=$db->getAutoincrement($mainTable);
				$trackInfoObj->saveTrack($id, $mainTable);
				
				$db->insert($arr,$mainTable); 
				header("Location:$pageName?success=added&project=$id"); 
			} else
				 include_once($pageName);
		break;
		case "update":
			$update_edit=$id=$_REQUEST["project_id"];
			
			if($error=="" && $update_edit!=NULL) {
				$WHERE="project_id=$update_edit";
				
				$trackInfoObj->saveTrack($id, $mainTable);
				
				$db->update($arr,$WHERE,$mainTable); 
				header("Location:$pageName?success=updated&project=$update_edit");
			} else
				include_once($pageName);
		break;
	}  
} else
	header("location:$pageName");
?>