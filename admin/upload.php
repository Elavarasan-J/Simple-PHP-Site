<?php
include_once("inc/checkinfo.php");
include_once("inc/utility.php");
include_once("inc/class.upload.php");

if(isset($_FILES['files']['name'])) {
	$files=$_FILES['files']['name'];
	$file_count=(is_array($files) && count($files)>0)?count($files):1;
	for($i=0;$i<$file_count;$i++) {
		$path=get_site_info("dir").$uploads_path;
		$file=$path.$files[$i];
		$rewrite=is_file($file)?time():'';
		if($_FILES['files']['type'][$i]=='image/jpeg') {
			$files_arr[]=$filename1=imgResizeMultiple($_FILES['files'],$i,$path,0,800,800,0,"_original_".$rewrite);
			$filename2=imgResizeMultiple($_FILES['files'],$i,$path,1,350,350,0,"_medium_".$rewrite);
			$filename3=imgResizeMultiple($_FILES['files'],$i,$path,1,150,150,0,"_thumb_".$rewrite);
		} else {
			$files_arr[]=$filename=fileUploadMultiple('files',$i,$path,'',$rewrite);
		}
	//	$file[$j]['files']=$filename;
	}
	exit;
	$finfo = finfo_open(FILEINFO_MIME_TYPE);
	foreach($files_arr as $file) {
		$media="../".$uploads_path.$file;
		$file_type=finfo_file($finfo, $media);
		$file_url=get_site_info("url").$uploads_path;
		if($file_type=="image/jpeg") {
			$newFiles.='<div class="media_wrap insert_media"><img src="'.$file_url.(str_replace('original','thumb',$file)).'" alt=""></div>';
		} else if($file_type=="application/pdf") {
			$newFiles.='<div class="media_wrap insert_media"><a href="'.$file_url.$file.'">'.$file.'</a></div>';
		}
	}
	finfo_close($finfo);
	
	$new_files=implode(',',$files_arr);
	echo $newFiles;
//	$arr['files']=($saved_files!='')?($saved_files.','.$new_files):$new_files;
}
?>