<?php
/**
 * App initiated
 */
$uploadMethods='yes';
require_once('../init.php');
defined('BASE_PATH') OR exit('No direct script access allowed');

$pageName='slider.php';
$managePage='manage_slider.php';
$mainTable=$db->TB_slider;

//$db->debug_mode=true;
if(isset($_REQUEST['action']) && $_REQUEST['action']!='') {
	$action=$_REQUEST["action"];
	
	if($action=='add' || $action=='update') {
		$error="";
		$arr["title"]=(isset($_POST["title"]) && trim($_POST["title"])!="")?htmlentities(addslashes(trim($_POST["title"]))):"";
		$arr["featured_title"]=(isset($_POST["featured_title"]) && trim($_POST["featured_title"])!="")?htmlentities(addslashes(trim($_POST["featured_title"]))):"";
		$arr["description"]=(isset($_POST["description"]) && trim($_POST["description"])!="")?htmlentities(addslashes(trim($_POST["description"]))):"";
		$arr["link_name"]=(isset($_POST["link_name"]) && trim($_POST["link_name"])!="")?htmlentities(addslashes(trim($_POST["link_name"]))):"";
		$arr["link_url"]=(isset($_POST["link_url"]) && trim($_POST["link_url"])!="")?trim($_POST["link_url"]):"";
		$arr["external_link"]=(isset($_POST["external_link"]) && ($_POST["external_link"])!="")?1:0;
		$arr['sort_order']=($_POST['sort_order']>0)?trim($_POST['sort_order']):1;
		$arr["status"]=(isset($_POST["status"]) && trim($_POST["status"])!="")?trim($_POST["status"]):"";
		
		$arr['featured_img']=(isset($_POST['featured_img']) && $_POST['featured_img']!='')?$_POST['featured_img']:NULL;

		$error=($arr['featured_img']=='')?'Please select featured image.':'';
		//echo '<pre>'; print_r($arr); echo '</pre>'; exit;
	}
	
	switch($action)	{
		case "add":
			if($error=="") {
				$id=$db->getAutoincrement($mainTable);
				$trackInfoObj->saveTrack($id, $mainTable);
				
				$db->insert($arr,$mainTable); 
				header("Location:$pageName?success=added&slider=$id"); 
			} else
				 include_once($pageName);
		break;
		case "update":
			$update_edit=$id=$_REQUEST["slider_id"];
			
			if($error=="" && $update_edit!=NULL) {
				$WHERE="slider_id=$update_edit";
				
				$trackInfoObj->saveTrack($id, $mainTable);
				
				$db->update($arr,$WHERE,$mainTable); 
				header("Location:$pageName?success=updated&slider=$update_edit");
			} else
				include_once($pageName);
		break;
	}  
} else
	header("location:$pageName");
?>