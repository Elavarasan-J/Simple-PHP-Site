<?php
/**
 * App initiated
 */
$uploadMethods='yes';
require_once('../init.php');
defined('BASE_PATH') OR exit('No direct script access allowed');

$pageName='board_members.php';
$managePage='manage_board_members.php';

$mainTable=$db->TB_board_members;
$trackTable=$db->TB_track;

//$db->debug_mode=true;
if(isset($_REQUEST['action']) && $_REQUEST['action']!='') {
	$action=$_REQUEST['action'];
	
	if($action=="add" || $action=="update") {
		$error='';
		
		$arr['board_type']=(isset($_POST['board_type']) && $_POST['board_type']!='')?$_POST['board_type']:'';
		$arr['title']=(isset($_POST['title']) && trim($_POST['title'])!='')?$sanitizationObj->cleanHTML($sanitizationObj->cleanText(trim($_POST['title']))):'';
		$arr['position']=(isset($_POST['position']) && trim($_POST['position'])!='')?$sanitizationObj->cleanHTML($sanitizationObj->cleanText(trim($_POST['position']))):'';
		$arr['description']=(isset($_POST['description']) && trim($_POST['description'])!='')?$sanitizationObj->cleanHTML($sanitizationObj->cleanText(trim($_POST['description']))):'';
		$arr['sort_order']=($_POST['sort_order']>0)?trim($_POST['sort_order']):1;
		$arr['featured_img']=($_POST['featured_img']!='')?$_POST['featured_img']:NULL;
		
		$error= $utilityObj->isEmptyfield(array("Title" =>$arr['title']));

		if(isset($_POST['submit'])) {
			$title_error=($arr['title']=='')?'error':'';
		}
	}
	
	switch($action) {
		case "add":
			$arr['status']=1;
			if($error=='') {
				$id=$db->getAutoincrement($mainTable);
				$trackInfoObj->saveTrack($id, $mainTable);
						
				$db->insert($arr,$mainTable);
				
				$utilityObj->headerLocation("$pageName?success=added&board_member_id=$id");
			} else
				include_once($pageName);
		break;
		case "update":
			$board_member_ids=$_REQUEST['board_member_id'];
			$arr['status']=(isset($_POST['status']) && $_POST['status']!=1)?0:1;
			
			if($error=='' && $board_member_ids!='') {
				$trackInfoObj->saveTrack($board_member_ids, $mainTable);
				
				$WHERE="board_member_id=$board_member_ids";
				$db->update($arr,$WHERE,$mainTable);

				$utilityObj->headerLocation("$pageName?success=updated&board_member_id=$board_member_ids");
			}
			else
				include_once($pageName);
		break;
		case "delete_featured_img":
			$id=(int)$_REQUEST['board_member_id'];
			$arr['featured_img']=NULL;
			
			$trackInfoObj->saveTrack($id, $mainTable);
			$WHERE="page_id=$id";
			$db->update($arr,$WHERE,$mainTable);
			$utilityObj->headerLocation("$mainPage?success=deleteimg&page=$id");
		break;
		case "trash_board_member":
			$id=(int)$_REQUEST['board_member_id'];
			$utilityObj->trashRec('board_member_id',$id,$mainTable);
			$utilityObj->headerLocation("$managePage?$msg=trashed");
		break;
		case "restorepage":
			$id=(int)$_REQUEST['board_member_id'];
			$utilityObj->restoreRec('board_member_id',$id,$mainTable);
			$utilityObj->headerLocation("$managePage?$msg=updated");
		break;
		case "delete_board_member":
			$con=(int)$_REQUEST['board_member_id'];
			$track_where="primary_key_id=$con AND table_name='$mainTable'";
			$WHERE="board_member_id=$con";
			$rec=$db->getSingleRec($mainTable,$WHERE,'board_member_id');
			$msg='failed';
			if($rec==true)	{
				mysql_query("DELETE FROM $db->TB_track_detail WHERE $track_where");
				
				$msg=($db->delSingleRec($mainTable,$WHERE))?'success':'fail';
			}
			$utilityObj->headerLocation("$managePage?$msg=deleted");
		break;
    }
} else
	include_once($pageName);
?>