<?php
/**
 * App initiated
 */
$uploadMethods='yes';
require_once('../init.php');
defined('BASE_PATH') OR exit('No direct script access allowed');

$addPageName='add_media_library.php';
$editPageName='edit_media_library.php';
$managePageName='manage_media_library.php';

################################## Multiple File upload exclusive for media library photos ###################
function multipleFileUpload($filename) {
	global $db,$uploadMethodsObj,$pathObj,$trackInfoObj,$sanitizationObj,$action;
	$checkupload=false;
	
	if($filename!='' && count($_FILES[$filename]['name'])>0) {
		while(list($key,$value) = each($_FILES[$filename]['name'])) {
			$destination='';						
			if(!empty($value)) {
				$newtype=array();
				$newtype['name']=$_FILES[$filename]['name'][$key];
				$newtype['type']=$_FILES[$filename]['type'][$key];
				$newtype['tmp_name']=$_FILES[$filename]['tmp_name'][$key];
				$newtype['error']=$_FILES[$filename]['error'][$key];
				$newtype['size']=$_FILES[$filename]['size'][$key];
				
				//$inserted=$uploadMethodsObj->imgResizeHorW($newtype,$folderpath,260,245,'H',1);
				//$inserted=$uploadMethodsObj->imgResize($newtype,$folderpath,1,260,245,1);
				
				$newAID=($db->getAutoincrement($db->TB_media_library));
				$inserted_id="_".$newAID;
				if($newtype['type']!='' && ($newtype['type']=='image/jpeg' || $newtype['type']=='image/png' || $newtype['type']=='image/gif')) {
					$uploaded=$uploadMethodsObj->imgResize_gallery($newtype,BASE_PATH.$pathObj->media_library_files_path,0,80,80,0,array('image/*'),2097152,$inserted_id);
					$uploadMethodsObj->imgResize_gallery($newtype,BASE_PATH.$pathObj->media_library_thumbphotos_path,1,150,150,0,array('image/*'),2097152,$inserted_id);
					$uploadMethodsObj->imgResize_gallery($newtype,BASE_PATH.$pathObj->media_library_mediumphotos_path,1,320,320,0,array('image/*'),2097152,$inserted_id);
				} else if($newtype['type']!='') {
					$file=BASE_PATH.$pathObj->media_library_files_path.$newtype['name'];
					$rewrite=is_file($file)?time():'';
					$uploaded=$uploadMethodsObj->fileUploadMultiple($filename,$key,BASE_PATH.$pathObj->media_library_files_path,'',$rewrite);
				}
				
				if($uploaded!='') {
					if(isset($_REQUEST['action']) && $_REQUEST['action']!='') {
						$arr['file_name']=$uploaded;
						$arr['file_type']=$newtype['type'];
						$arr['title']=(isset($_POST['title']) && trim($_POST['title'])!='')?$sanitizationObj->cleanHTML($sanitizationObj->cleanText(trim($_POST['title']))):'';
						$arr['long_title']=(isset($_POST['long_title']) && trim($_POST['long_title'])!='')?$sanitizationObj->cleanHTML($sanitizationObj->cleanText(trim($_POST['long_title']))):'';
						$arr['description']=(isset($_POST['description']) && trim($_POST['description'])!='')?$sanitizationObj->cleanHTML($sanitizationObj->cleanText(trim($_POST['description']))):'';
						$arr['status']=(isset($_POST['status']) && $_POST['status']!=1)?0:1;
						$trackInfoObj->saveTrack($newAID, $db->TB_media_library);
						if($action=="add") {
							$db->insert($arr,$db->TB_media_library);
						} else if($action=="update") {
							$db->update($arr,"media_library_id=".$_REQUEST['media_library_id'],$db->TB_media_library);
						}
					}
					$checkupload=true;
				}

			}
		}
	}
	return $checkupload;	
}
#################################	 End of file upload ################################################	

function delete_media_library_files($media_library_id='',$tablename,$imgpath)	{
	global $utilityObj,$pathObj;
	$check=false;
	if($media_library_id!='') {
		$sql="SELECT file_name FROM $tablename WHERE media_library_id=$media_library_id AND file_name!=''";
		$query=mysqli_query($db->conn, $sql);
		$num_sql=@mysqli_num_rows($query);
		if($num_sql>0) {
			while($fetch=mysqli_fetch_assoc($query)) {
				$utilityObj->unlinkfile($imgpath,$fetch['file_name']);
				$utilityObj->unlinkfile(BASE_PATH.$pathObj->media_library_thumbphotos_path,$fetch['file_name']);
				$utilityObj->unlinkfile(BASE_PATH.$pathObj->media_library_mediumphotos_path,$fetch['file_name']);
				$check=true;
			}
		}
	}
	return $check;
}
####################### End of delete media library #################################################################

//$db->debug_mode=true;
if(isset($_REQUEST['action']) && $_REQUEST['action']!='') {
	$action=$_REQUEST['action'];
	$error='';
	
	switch($action) {
		case "add":
			if(!isset($_FILES['upload_file']['name'][0]) || (isset($_FILES['upload_file']['name'][0]) && $_FILES['upload_file']['name'][0]=='')) {
				$error='Select files to upload.';
				$upload_file_error='error';
			}
			if($error=='') {
				$msg=(multipleFileUpload('upload_file'))?'success':'fail';
				if($msg!='')	{
					$utilityObj->headerLocation("$addPageName?$msg=added");
				} else
					$utilityObj->headerLocation("$managePageName");
			} else {
				include_once($addPageName);
			}
		break;
		case "update":
			$media_library_file=(isset($_FILES['upload_file']['name'][0]) && $_FILES['upload_file']['name'][0]!='')?$_FILES['upload_file']['name']:'';
			
			//$error= $utilityObj->isEmptyfield(array("Short Title" =>$arr['title'],"Long Title" =>$arr['long_title'],"Photo Description" =>trims($_POST['description'],1)));
			//$error= $utilityObj->isEmptyfield(array("Short Title" =>$arr['title'], "Long Title" =>$arr['long_title']));
			
			$media_library_id=$_REQUEST['media_library_id'];
			if($error=='' && $media_library_id!='') {
				if($media_library_file!='')	{
					//$arr['file_name']=$uploadMethodsObj->imgResizeHorW($_FILES['media_library_file'],$pathObj->media_library_path,150,110,'W');
					//$arr['file_name']=$uploadMethodsObj->imgResize($_FILES['media_library_file'],BASE_PATH.$pathObj->media_library_path,1,555,370,1);
					$msg=(multipleFileUpload('upload_file'))?'success':'fail';
				} else {
					$arr['title']=(isset($_POST['title']) && trim($_POST['title'])!='')?htmlentities($sanitizationObj->cleanText(trim($_POST['title']))):'';
					$arr['long_title']=(isset($_POST['long_title']) && trim($_POST['long_title'])!='')?htmlentities($sanitizationObj->cleanText(trim($_POST['long_title']))):'';
					$arr['description']=(isset($_POST['description']) && trim($_POST['description'])!='')?htmlentities($sanitizationObj->cleanText(trim($_POST['description']))):'';
					
					$arr['status']=(isset($_POST['status']) && $_POST['status']!=1)?0:1;
					
					$trackInfoObj->saveTrack($media_library_id, $db->TB_media_library);
					$WHERE="media_library_id=$media_library_id";
					$db->update($arr,$WHERE,$db->TB_media_library);
					$msg='success';
				}
				$utilityObj->headerLocation("$editPageName?$msg=updated&media_library_id=$media_library_id");
			}
			else
				include_once($editPageName);
		break;
		case "delete_media_library_file":
			$con=(int)$_REQUEST['media_library_id'];
			$WHERE="media_library_id=$con";
			$rec=$db->getSingleRec($db->TB_media_library,$WHERE,'media_library_id,file_name');
			$msg='fail';
			if($rec==true)	{
				$ImgFile=$rec['file_name'];
				if($ImgFile!='')		{
					delete_media_library_files($con,$db->TB_media_library,BASE_PATH.$pathObj->media_library_files_path);
					$arr['file_name']='';
					$db->update($arr,$WHERE,$db->TB_media_library);
					$trackInfoObj->saveTrack($con, $db->TB_media_library);
					$msg='success';
				}
			}
			$utilityObj->headerLocation("edit_media_library.php?$msg=deleted&media_library_id=".$con);
		break;
		case "delete_media_library":
			$con=(int)$_REQUEST['media_library_id'];
			$track_where="primary_key_id=$con AND table_name='$db->TB_media_library'";
			$WHERE="media_library_id=$con";
			$rec=$db->getSingleRec($db->TB_media_library,$WHERE,'media_library_id,file_name');
			$msg='fail';
			if($rec==true)	{
				$ImgFile=$rec['file_name'];
				if($ImgFile!='') {
					$delete=$utilityObj->unlinkfile(BASE_PATH.$pathObj->media_library_path,$ImgFile);
					$msg='success';
				}
				$trackInfoObj->saveTrack($con, $db->TB_media_library);
				delete_media_library_files($con,$db->TB_media_library,BASE_PATH.$pathObj->media_library_files_path);
				$msg=($db->delSingleRec($db->TB_media_library,$WHERE))?'success':'fail';
			}
			header("location:manage_media_library.php?$msg=deleted");
		break;
		case "deleteimg":
			$editPageName='manage_photos.php';
			$con=(int)$_REQUEST['photoid'];
			$WHERE="photo_id=$con";
			$arr=$db->getSingleRec($db->TB_media_library,$WHERE,'photo_id,file_name');
			$ImgFile=$arr['file_name'];
			if($ImgFile!='')	{
				$delete=$utilityObj->unlinkfile(BASE_PATH.$pathObj->media_library_files_path,$ImgFile);
				$delete=$utilityObj->unlinkfile(BASE_PATH.$pathObj->media_library_thumbphotos_path,$ImgFile);
			}	
			$msg=($db->delSingleRec($db->TB_media_library,$WHERE))?'success':'fail';
			header("location:$editPageName?$msg=deleteimg");
		break;
    }
} else
	include_once($addPageName);
?>