<?php
/**
 * App initiated
 */
$uploadMethods='yes';
require_once('../init.php');
defined('BASE_PATH') OR exit('No direct script access allowed');

$mainPage='gallery.php';
$managePage='manage_gallery.php';

//$db->debug_mode=true;
$mainTable=$db->TB_gallery;
$trackTable=$db->TB_track;

$action=$_REQUEST["action"];

if($action=='add' || $action=='update') {
	$error="";
	$arr["title"]=(isset($_POST["title"]) && trim($_POST["title"])!="")?htmlentities($_POST["title"]):"";
	$arr["gallery_items"]=(isset($_POST["gallery_items"]) && is_array($_POST["gallery_items"]) && count($_POST["gallery_items"])>0)?implode(',',$_POST["gallery_items"]):"";
	$arr['featured_img']=(isset($_POST['featured_img']) && $_POST['featured_img']!='')?$_POST['featured_img']:NULL;
	$arr["status"]=(isset($_POST["status"]) && trim($_POST["status"])!="")?(int)($_POST["status"]):"";
	
	$error= $utilityObj->isEmptyfield(array("Title" =>trim($arr['title'],'')));
	
	if($arr['title']=='') {
		$title_error='error';
	}
}

switch($action)	{
	case "add":
		if($error=="") {
			$gallery_id=$db->getAutoincrement($mainTable);
			$trackInfoObj->saveTrack($gallery_id, $mainTable);
			$db->insert($arr,$mainTable);
			
			$utilityObj->headerLocation("$mainPage?success=added&gallery=$gallery_id");
		} else 
			 include_once($mainPage);
	break;
	case "update":
		$gallery_id=(isset($_GET['gallery']) && $_GET['gallery']!='')?(int)$_GET['gallery']:'';
		
		if($error=="" && $gallery_id!=NULL) {
			$WHERE="gallery_id=$gallery_id";
			$db->update($arr,$WHERE,$mainTable);
			
			$utilityObj->headerLocation("$mainPage?success=updated&gallery=$gallery_id");
		} else 
			include_once($mainPage);
	break;
	case "delete_gallery":
		$gallery_id=(int)$_REQUEST['gallery'];
		
		$WHERE="gallery_id=$gallery_id";
		$msg='failed';
		$rec=$db->getSingleRec($mainTable,$WHERE,'gallery_id');
		if($rec==true && is_array($rec))	{
			$msg=delSingleRec($mainTable,$WHERE)?'success':'fail';
		}
		$trackInfoObj->deleteTrack($id, $mainTable);
		$utilityObj->headerLocation("$mainPage?$msg=updated&gallery=$gallery_id");
	break;
}
?>