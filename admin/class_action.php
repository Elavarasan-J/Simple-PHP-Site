<?php
/**
 * App initiated
 */
$uploadMethods='yes';
require_once('../init.php');
defined('BASE_PATH') OR exit('No direct script access allowed');

if(isset($_REQUEST['action'],$_REQUEST['key'],$_REQUEST['id'],$_REQUEST['table'],$_REQUEST['page']) && $_REQUEST['action']!='' && $_REQUEST['key']!='' && $_REQUEST['id'] && $_REQUEST['table']!='' && $_REQUEST['page']!='') {
	$action=$_REQUEST["action"];
	$key=$_REQUEST['key'];
	$id=$_REQUEST['id'];
	$table=$db->{"TB_".$_REQUEST['table']};
	$page=$_REQUEST['page'].'.php';

	switch($action)	{
		case "switchStatus":
			$WHERE="$key=$id";
			$update=$db->main_update("status=CASE WHEN status=0 THEN 1 WHEN status=1 THEN 0 END",$WHERE,$table);
			$trackInfoObj->saveTrack($id, $table);
			$utilityObj->headerLocation("$page?success=updated");
		break;
		case "remove_featured_img":
			$arr['featured_img']=NULL;
			$WHERE="$key=$id";
			$db->update($arr,$WHERE,$table);
			$trackInfoObj->saveTrack($id, $table);
			$utilityObj->headerLocation("$page?success=removed_img&$key=$id");
		break;
		case "trash":
			$msg=$utilityObj->trashRec($key,$id,$table);
			$utilityObj->headerLocation("$page?$msg=trashed");
		break;
		case "restore":
			$msg=$utilityObj->restoreRec($key,$id,$table);
			$utilityObj->headerLocation("$page?$msg=restored");
		break;
		default: break;
	}
}
?>