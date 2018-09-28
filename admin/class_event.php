<?php
/**
 * App initiated
 */
$uploadMethods='yes';
require_once('../init.php');
defined('BASE_PATH') OR exit('No direct script access allowed');

$pageName='event.php';
$managePage='manage_event.php';
$mainTable=$db->TB_event;

//$db->debug_mode=true;
if(isset($_REQUEST['action']) && $_REQUEST['action']!='') {
	$action=$_REQUEST["action"];
	
	if($action=='add' || $action=='update') {
		$error="";
		$arr["title"]=(isset($_POST["title"]) && trim($_POST["title"])!="")?htmlentities(addslashes(trim($_POST["title"]))):"";
		$arr["date"]=(isset($_REQUEST['date']) && $_REQUEST['date']!='')?$timeObj->switch_date_format($_REQUEST['date'],'-'):'';
        $arr["time"]=(isset($_POST["time"]) && trim($_POST["time"])!="")?htmlentities(addslashes(trim($_POST["time"]))):"";
		$arr["location"]=(isset($_POST["location"]) && trim($_POST["location"])!="")?htmlentities(addslashes(trim($_POST["location"]))):"";
		$arr["description"]=(isset($_POST["description"]) && trim($_POST["description"])!="")?htmlentities(addslashes(trim($_POST["description"]))):"";

		$arr["link_name"]=(isset($_POST["link_name"]) && trim($_POST["link_name"])!="")?htmlentities(addslashes(trim($_POST["link_name"]))):"";
		$arr["link_url"]=(isset($_POST["link_url"]) && trim($_POST["link_url"])!="")?trim($_POST["link_url"]):"";
		$arr["external_link"]=(isset($_POST["external_link"]) && ($_POST["external_link"])!="")?1:0;
		$arr["status"]=(isset($_POST["status"]) && trim($_POST["status"])!="")?trim($_POST["status"]):"";
		
		$arr['featured_img']=(isset($_POST['featured_img']) && $_POST['featured_img']!='')?$_POST['featured_img']:NULL;

		$error= $utilityObj->isEmptyfield(array("Title" =>$utilityObj->trims($arr['title'],''),"Date" =>$utilityObj->trims($arr['date'],'')));
		
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
				header("Location:$pageName?success=added&event=$id"); 
			} else
				 include_once($pageName);
		break;
		case "update":
			$update_edit=$id=$_REQUEST["event_id"];
			
			if($error=="" && $update_edit!=NULL) {
				$WHERE="event_id=$update_edit";
				
				$trackInfoObj->saveTrack($id, $mainTable);
				
				$db->update($arr,$WHERE,$mainTable); 
				header("Location:$pageName?success=updated&event=$update_edit");
			} else
				include_once($pageName);
		break;
	}  
} else
	header("location:$pageName");
?>