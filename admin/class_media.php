<?php
/**
 * App initiated
 */
$uploadMethods='yes';
require_once('../init.php');
defined('BASE_PATH') OR exit('No direct script access allowed');

$pageName='media.php';
$managePage='manage_media.php';
$mainTable=$db->TB_media;

//$db->debug_mode=true;
if(isset($_REQUEST['action']) && $_REQUEST['action']!='') {
	$action=$_REQUEST["action"];
	
	if($action=='add' || $action=='update') {
		$error="";
		$arr["title"]=(isset($_POST["title"]) && trim($_POST["title"])!="")?htmlentities(addslashes(trim($_POST["title"]))):"";
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
				header("Location:$pageName?success=added&media=$id"); 
			} else
				 include_once($pageName);
		break;
		case "update":
			$update_edit=$id=$_REQUEST["media_id"];
			
			if($error=="" && $update_edit!=NULL) {
				$WHERE="media_id=$update_edit";
				
				$trackInfoObj->saveTrack($id, $mainTable);
				
				$db->update($arr,$WHERE,$mainTable); 
				header("Location:$pageName?success=updated&media=$update_edit");
			} else
				include_once($pageName);
		break;
	}  
} else
	header("location:$pageName");
?>