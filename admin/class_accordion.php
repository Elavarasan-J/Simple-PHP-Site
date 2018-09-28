<?php
/**
 * App initiated
 */
$uploadMethods='yes';
require_once('../init.php');
defined('BASE_PATH') OR exit('No direct script access allowed');

$mainPage='accordion.php';
$managePage='manage_accordion.php';

//$db->debug_mode=true;
$mainTable=$db->TB_page;
$trackTable=$db->TB_track;
$pageAccordionTable=$db->TB_page_accordion;

$action=$_REQUEST["action"];

if($action=='add' || $action=='update') {
	$error="";
	$newPageId=(isset($_POST["page_id"]) && trim($_POST["page_id"])!="")?trim($_POST["page_id"]):"0";
	
	//	Accordion
	if(isset($_POST['accordion_title']) && count($_POST['accordion_title'])>0) {
		foreach($_POST['accordion_title'] as $key=>$val) {
			if(trim($_POST['accordion_title'][$key])!='' && trim($_POST['accordion_desc'][$key])!=='') {
				if(isset($_POST['accordion_id'][$key]) && $_POST['accordion_id'][$key]!='') {
					$accordion[$key]['accordion_id']=$_POST['accordion_id'][$key];
				}
				$accordion[$key]["page_id"]=$newPageId;
				$accordion[$key]['accordion_title']=htmlentities(trim($_POST['accordion_title'][$key]));
				$accordion[$key]['accordion_desc']=htmlentities(trim($_POST['accordion_desc'][$key]));
				$accordion[$key]['accordion_sort_order']=$_POST['accordion_sort_order'][$key];
				$accordion[$key]['accordion_status']=$_POST['accordion_status'][$key];
			}
		}
	}
	//	Accordion
}
//printR($accordion,1,1);
switch($action)	{
	case "add":
		if($error=="") {
			if(is_array($accordion) && count($accordion)>0) {
				foreach($accordion as $key=>$val) {
					$accordion_id=$db->getAutoincrement($pageAccordionTable);
					$trackInfoObj->saveTrack($accordion_id, $pageAccordionTable);
					$db->insert($val,$pageAccordionTable);
				}
			}
			
			$utilityObj->headerLocation("$mainPage?success=added&page=$newPageId");
		} else 
			 include_once($mainPage);
	break;
	case "update":
		$update_editids=$id=(isset($_GET['page']) && $_GET['page']!='')?(int)$_GET['page']:'';
		
		if(is_array($accordion) && count($accordion)>0) {
			foreach($accordion as $key=>$val) {
				if(isset($val['accordion_id']) && $val['accordion_id']!='') {
					$accordion_id=$val['accordion_id'];
					$trackInfoObj->saveTrack($accordion_id, $pageAccordionTable);
					$WHERE="accordion_id=$accordion_id";
					$db->update($val,$WHERE,$pageAccordionTable);
				} else {
					$accordion_id=$db->getAutoincrement($pageAccordionTable);
					$trackInfoObj->saveTrack($accordion_id, $pageAccordionTable);
					$db->insert($val,$pageAccordionTable);
				}
			}
		}
		if($error=="" && $update_editids!=NULL) {
			$utilityObj->headerLocation("$mainPage?success=updated&page=$newPageId");
		} else 
			include_once($mainPage);
	break;
	case "delete_accordion":
		$id=(int)$_REQUEST['id'];
		$page_id=(int)$_REQUEST['page_id'];
		
		$WHERE="accordion_id=$id";
		$msg='failed';
		$rec=$db->getSingleRec($pageAccordionTable,$WHERE,'accordion_id');
		if($rec==true && is_array($rec))	{
			$msg=delSingleRec($pageAccordionTable,$WHERE)?'success':'fail';
		}
		$trackInfoObj->deleteTrack($id, $pageAccordionTable);
		$utilityObj->headerLocation("$mainPage?$msg=updated&page=$page_id");
	break;
}  
?>