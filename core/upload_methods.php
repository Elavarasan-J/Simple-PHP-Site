<?php
defined('BASE_PATH') OR exit('No direct script access allowed');

if(file_exists(BASE_PATH.'core/class.upload.php')) { require_once(BASE_PATH.'core/class.upload.php'); }
class UploadMethods {
	function __construct() {
		
	}

	##########################################################################################################################
	//name="pdf",folder name[,optionals]
	function fileUpload($filename,$foldername,$hiddenfile='',$rewritename='') {
		$rewritename=($rewritename!='')?($rewritename.'_'):$rewritename;
		$uploadfile="";
		$file=$filename;
		$ori=$_FILES[$file]['name'];
		$tmporary=$_FILES[$file]['tmp_name'];
		$type=$_FILES[$file]['type'];
		
		if($hiddenfile!=NULL)
			$uploadfile=$hiddenfile;
		
		if($ori!="") {
			// IF another file upload,then delete the old file
			if(is_file($foldername.$hiddenfile) && $filename!="" && $hiddenfile!="" ) {
				$filename=$foldername.$hiddenfile;
				unlink($filename);
			}
			
			//$random=rand(1,99999);
			$destination=$rewritename.$ori;
			$des=$foldername.$destination;
			move_uploaded_file($tmporary,$des);
			chmod($des,0777);
			$uploadfile=$destination;
		}
		return $uploadfile;
	}
	function fileUploadMultiple($filename,$i,$foldername,$hiddenfile='',$rewritename='') {
		$rewritename=($rewritename!='')?($rewritename.'_'):$rewritename;
		$uploadfile="";
		$file=$filename;
		$ori=$_FILES[$file]['name'][$i];
		$tmporary=$_FILES[$file]['tmp_name'][$i];
		$type=$_FILES[$file]['type'][$i];
		
		if($hiddenfile!=NULL)
			$uploadfile=$hiddenfile;
		
		if($ori!="") {
			// IF another file upload,then delete the old file
			if(is_file($foldername.$hiddenfile) && $filename!="" && $hiddenfile!="" ) {
				$filename=$foldername.$hiddenfile;
				unlink($filename);
			}
			
			//$random=rand(1,99999);
			$destination=$rewritename.$ori;
			$des=$foldername.$destination;
			move_uploaded_file($tmporary,$des);
			chmod($des,0777);
			$uploadfile=$destination;
		}
		return $uploadfile;
	}

	// Include class.upload.php when use this function
	function imgResize($file_ori,$UploadPath,$Resize=1,$imgx=130,$imgy=150,$clean=0,$postFix="") {
		$dest_filename='';
		$handle = new upload($file_ori);
		$img_proper=getimagesize($file_ori['tmp_name']);
		if ($handle->uploaded) {
			if($img_proper[0]>$imgx && $img_proper[1]>$imgy && $Resize==1)	{
				$handle->image_x              = $imgx;
				$handle->image_y              = $imgy;
				$handle->image_resize         = true;
				$handle->image_ratio_y        = true;
				$handle->image_ratio_x        = true;
				$handle->dir_auto_create      = true;
				$handle->dir_auto_chmod       = true;
				$handle->dir_chmod            = 0777;
			} else if($img_proper[0]>$imgx && $img_proper[1]<$imgy  && $Resize==1)	{
				$handle->image_x              = $imgx;
				$handle->image_resize         = true;
				$handle->image_ratio_y        = true;
				$handle->dir_auto_create      = true;
				$handle->dir_auto_chmod       = true;
				$handle->dir_chmod            = 0777;
			} else if($img_proper[0]<$imgx && $img_proper[1]>$imgy  && $Resize==1)	{
				$handle->image_y              = $imgy;
				$handle->image_resize         = true;
				$handle->image_ratio_x        = true;
				$handle->dir_auto_create      = true;
				$handle->dir_auto_chmod       = true;
				$handle->dir_chmod            = 0777;
			}
			$handle->file_name_body_add = $postFix;
			$handle->process($UploadPath);
			if ($handle->processed) {
				$dest_filename=$handle->file_dst_name;
				if($clean==1)
					$handle->clean();
			} else
				$dest_filename='';
		}
		return $dest_filename;
	}

	// Include class.upload.php when use this function
	function imgResize_gallery($file_ori,$UploadPath,$Resize=1,$imgx=130,$imgy=150,$clean=0,$allowedFormat=array('image/*'),$Maxfilesize=2097152,$filename_body='')	{
		$dest_filename='';
		
		$handle = new upload($file_ori);
		$handle->allowed = $allowedFormat;
		$handle->file_max_size = $Maxfilesize;
		$img_proper=getimagesize($file_ori['tmp_name']);
		
		if($filename_body!='')
			$handle->file_name_body_add = $filename_body;
		
		if ($handle->uploaded) {
			if((($img_proper[0]>$imgx && $img_proper[1]>$imgy) || ($img_proper[0]>$imgx && $img_proper[1]<$imgy))  && $Resize==1)	{
				$handle->image_x              = $imgx;
				$handle->image_resize         = true;
				$handle->image_ratio_y        = true;
				$handle->dir_auto_create      = true;
				$handle->dir_auto_chmod       = true;
				$handle->dir_chmod            = 0777;
			} else if($img_proper[0]<$imgx && $img_proper[1]>$imgy  && $Resize==1)	{
				$handle->image_y              = $imgy;
				$handle->image_resize         = true;
				$handle->image_ratio_x        = true;
				$handle->dir_auto_create      = true;
				$handle->dir_auto_chmod       = true;
				$handle->dir_chmod            = 0777;
			}
			$handle->process($UploadPath);
			if ($handle->processed) {
				$dest_filename=$handle->file_dst_name;
				if($clean==1)
					$handle->clean();
			} else
				$dest_filename='';
		}
		return $dest_filename;
	}

	// Include class.upload.php when use this function
	function imgResizeHorW($file_ori,$UploadPath,$imgx=130,$imgy=150,$type='H',$clean=0) {
		$dest_filename='';
		
		$handle = new upload($file_ori);
		
		$img_proper=getimagesize($file_ori['tmp_name']);
		
		if ($handle->uploaded) {
			if($type=='W')	{
				//$handle->image_x              = $imgx;
				$handle->image_x              = $imgx;
				$handle->image_resize         = true;
				$handle->image_ratio_y        = true;
			} else if($type=='H')	{
				$handle->image_y              = $imgy;
				$handle->image_resize         = true;
				$handle->image_ratio_x        = true;
			}
		}
		$handle->process($UploadPath);
		if ($handle->processed) {
			$dest_filename=$handle->file_dst_name;
			if($clean==1)
				$handle->clean();
		} else
			$dest_filename='';
		return $dest_filename;
	}

	// Include class.upload.php when use this function
	function imgResizeHeight($file_ori,$UploadPath,$Resize=1,$imgx=130,$imgy=150,$clean=0) {
		$dest_filename='';
		$handle = new upload($file_ori);
		$img_proper=getimagesize($file_ori['tmp_name']);
		
		if ($handle->uploaded) {
			//$handle->image_x              = $imgx;
			$handle->image_y              = $imgy;
			$handle->image_resize         = true;
			$handle->image_ratio_x        = true;
		}
		$handle->process($UploadPath);
		if ($handle->processed) {
			$dest_filename=$handle->file_dst_name;
			if($clean==1)
				$handle->clean();
		} else
			$dest_filename='';
		return $dest_filename;
	}
	##########################################################################################################################
}
$uploadMethodsObj = new UploadMethods;