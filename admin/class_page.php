<?php
/**
 * App initiated
 */
$uploadMethods='yes';
require_once('../init.php');
defined('BASE_PATH') OR exit('No direct script access allowed');

$mainPage='page.php';
$managePage='manage_page.php';

//$db->debug_mode=true;
$mainTable=$db->TB_page;
$trackTable=$db->TB_track;

$action=$_REQUEST["action"];

if($action=='add' || $action=='update') {
	$error="";
	
	$arr["parent_id"]=(isset($_POST["parent_id"]) && trim($_POST["parent_id"])!="")?trim($_POST["parent_id"]):"0";
	
	if($arr["parent_id"]<=0) {
		$arr["level"]=0;
	} else {
		$parent_level=$db->getSingleRec($db->TB_page,"page_id=$arr[parent_id]","level");
		$arr["level"]=(isset($parent_level['level']) && $parent_level['level']>=0)?($parent_level['level']+1):0;
	}
	
	$arr["page_template"]=(isset($_POST["page_template"]) && trim($_POST["page_template"])!="")?trim($_POST["page_template"]):"";
	$arr["title"]=(isset($_POST["title"]) && trim($_POST["title"])!="")?htmlentities($_POST["title"]):"";
	$arr["subtitle"]=(isset($_POST["subtitle"]) && trim($_POST["subtitle"])!="")?trim($_POST["subtitle"]):"";
	$arr["slug"]=(isset($_POST["slug"]) && trim($_POST["slug"])!="")?$utilityObj->to_slug(strip_tags($_POST["slug"])):$utilityObj->to_slug(strip_tags($_POST["title"]));
	
	$arr['remove_link']=(isset($_POST['remove_link']) && $_POST['remove_link']!='')?$_POST['remove_link']:0;
	
	$arr['short_description']=(isset($_POST["short_description"]) && trim($_POST["short_description"])!="")?htmlentities(trim($_POST["short_description"])):"";
	$arr['description']=(isset($_POST["description"]) && trim($_POST["description"])!="")?htmlentities(trim($_POST["description"])):"";
	
	$arr['button_text']=(isset($_POST['button_text']) && trim($_POST['button_text'])!='')?trim($_POST['button_text']):NULL;
	$arr['button_link_page']=(isset($_POST["button_link_page"]) && trim($_POST["button_link_page"])!="")?trim($_POST["button_link_page"]):NULL;
	
	$arr['sort_order']=(isset($_POST['sort_order']) && $_POST['sort_order']>0)?trim($_POST['sort_order']):0;
	$arr["status"]=(isset($_POST["status"]) && trim($_POST["status"])!="")?(int)($_POST["status"]):"";
	
	$error= $utilityObj->isEmptyfield(array("Title" =>trim($arr['title'],'')));
	
	if($arr['title']=='') {
		$title_error='error';
	}
	$arr['featured_img']=(isset($_POST['featured_img']) && $_POST['featured_img']!='')?$_POST['featured_img']:NULL;
}
switch($action)	{
	case "add":
		if($error=="") { 
			$id=$db->getAutoincrement($mainTable);
	
			$slug_arr=$db->getSingleRec($mainTable,"page_id!=$id AND slug='$arr[slug]'");
			if(is_array($slug_arr) && count($slug_arr)>0) {
				$arr["slug"].="-".$id;
			}
				
			$trackInfoObj->saveTrack($id, $mainTable);
			
			$db->insert($arr,$mainTable); 
			$utilityObj->headerLocation("$mainPage?success=added&page=$id");
		} else 
			 include_once($mainPage);
	break;
	case "update":
		$update_editids=$id=(isset($_GET['page']) && $_GET['page']!='')?(int)$_GET['page']:'';
		
		$slug_arr=$db->getSingleRec($mainTable,"page_id!=$id AND slug='$arr[slug]'");
		if(is_array($slug_arr) && count($slug_arr)>0) {
			$arr["slug"].="-".$id;
		}
		if($error=="" && $update_editids!=NULL) { 
			$trackInfoObj->saveTrack($id, $mainTable);
			
			$WHERE="page_id=$update_editids";
			$db->update($arr,$WHERE,$mainTable); 
			$utilityObj->headerLocation("$mainPage?success=updated&page=$update_editids");
		} else 
			include_once($mainPage);
	break;
}  
?>