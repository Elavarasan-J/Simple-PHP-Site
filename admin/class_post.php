<?php
/**
 * App initiated
 */
$uploadMethods='yes';
require_once('../init.php');
defined('BASE_PATH') OR exit('No direct script access allowed');

$mainPage='post.php';
$managePage='manage_posts.php';

//$db->debug_mode=true;
$mainTable=$db->TB_post;
$trackTable=$db->TB_track;

$action=$_REQUEST["action"];

if($action=='add' || $action=='update') {
	$error="";
	
	$arr["post_category_id"]=(isset($_POST["post_category_id"]) && trim($_POST["post_category_id"])!="")?trim($_POST["post_category_id"]):"0";
	
	$arr["title"]=(isset($_POST["title"]) && trim($_POST["title"])!="")?trim($_POST["title"]):"";
	$arr["slug"]=(isset($_POST["slug"]) && trim($_POST["slug"])!="")?$utilityObj->to_slug($_POST["slug"]):$utilityObj->to_slug($arr["title"]);
	$arr['short_description']=(isset($_POST["short_description"]) && trim($_POST["short_description"])!="")?htmlentities(trim($_POST["short_description"])):"";
	$arr['description']=(isset($_POST["description"]) && trim($_POST["description"])!="")?htmlentities(trim($_POST["description"])):"";
	$arr['published_time']=($_POST['published_time']>0)?trim($_POST['published_time']):date("Y-m-d H:i:s");
	$arr["status"]=(isset($_POST["status"]) && trim($_POST["status"])!="")?(int)($_POST["status"]):"";
	
	$error= $utilityObj->isEmptyfield(array("Title" =>$utilityObj->trims($arr['title'],''),"Slug" =>$utilityObj->trims($arr['slug'],'')));
	
	if($arr['title']=='') {
		$title_error='error';
	}
	$arr['featured_img']=(isset($_POST['featured_img']) && $_POST['featured_img']!='')?$_POST['featured_img']:NULL;
	if($arr['post_category_id']=='' || $arr['post_category_id']<1) {
		$error.="<strong>&bull; Category:</strong> Please select a category.";
		$post_category_id_error='error';
	}
}
switch($action)	{
	case "add":
		if($error=="") { 
			$id=$db->getAutoincrement($mainTable);
	
			$slug_arr=$db->getSingleRec($mainTable,"post_id!=$id AND slug='$arr[slug]'");
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
		$update_editids=$id=(isset($_GET['post']) && $_GET['post']!='')?(int)$_GET['post']:'';
		
		$slug_arr=$db->getSingleRec($mainTable,"post_id!=$id AND slug='$arr[slug]'");
		if(is_array($slug_arr) && count($slug_arr)>0) {
			$arr["slug"].="-".$id;
		}
		if($error=="" && $update_editids!=NULL) { 
			$trackInfoObj->saveTrack($id, $mainTable);
			
			$WHERE="post_id=$update_editids";
			$db->update($arr,$WHERE,$mainTable); 
			$utilityObj->headerLocation("$mainPage?success=updated&post=$update_editids");
		} else 
			include_once($mainPage);
	break;
}  
?>