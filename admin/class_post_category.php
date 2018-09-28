<?php
/**
 * App initiated
 */
require_once('../init.php');
defined('BASE_PATH') OR exit('No direct script access allowed');

$mainPage='post_category.php';
$managePage='post_category.php';

//$db->debug_mode=true;
$mainTable=$db->TB_post_category;
$trackTable=$db->TB_track;

$action=$_REQUEST["action"];

if($action=='add' || $action=='update') {
	$error="";
	
	$arr["title"]=(isset($_POST["title"]) && trim($_POST["title"])!="")?trim($_POST["title"]):"";
	$arr["slug"]=(isset($_POST["slug"]) && trim($_POST["slug"])!="")?$utilityObj->to_slug($_POST["slug"]):$utilityObj->to_slug($arr["title"]);
	$arr['description']=(isset($_POST["description"]) && trim($_POST["description"])!="")?htmlentities(trim($_POST["description"])):"";
	$arr["status"]=(isset($_POST["status"]) && trim($_POST["status"])!="")?(int)($_POST["status"]):"";
	
	$error= $utilityObj->isEmptyfield(array("Title" =>$utilityObj->trims($arr['title'],''),"Slug" =>$utilityObj->trims($arr['slug'],'')));
	
	if($arr['title']=='') {
		$title_error='error';
	}
	if($arr['slug']=='') {
		$slug_error='error';
	}
	$arr['featured_img']=(isset($_POST['featured_img']) && $_POST['featured_img']!='')?$_POST['featured_img']:NULL;
}
switch($action)	{
	case "add":
		if($error=="") { 
			$id=$db->getAutoincrement($mainTable);
	
			$slug_arr=$db->getSingleRec($mainTable,"post_category_id!=$id AND slug='$arr[slug]'");
			if(is_array($slug_arr) && count($slug_arr)>0) {
				$arr["slug"].="-".$id;
			}
				
			$trackInfoObj->saveTrack($id, $mainTable);
			
			$db->insert($arr,$mainTable); 
			$utilityObj->headerLocation("$mainPage?success=added");
		} else 
			 include_once($mainPage);
	break;
	case "update":
		$update_editids=$id=(isset($_GET['post_category']) && $_GET['post_category']!='')?(int)$_GET['post_category']:'';
		
		$slug_arr=$db->getSingleRec($mainTable,"post_category_id!=$id AND slug='$arr[slug]'");
		if(is_array($slug_arr) && count($slug_arr)>0) {
			$arr["slug"].="-".$id;
		}
		if($error=="" && $update_editids!=NULL) { 
			$trackInfoObj->saveTrack($id, $mainTable);
			
			$WHERE="post_category_id=$update_editids";
			$db->update($arr,$WHERE,$mainTable); 
			$utilityObj->headerLocation("$mainPage?success=updated&post_category=$update_editids");
		} else 
			include_once($mainPage);
	break;
}  
?>
