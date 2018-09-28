<?php
/**
 * App initiated
 */
$uploadMethods='yes';
require_once('../init.php');
defined('BASE_PATH') OR exit('No direct script access allowed');

$editor="editor";
$colorbox = 'colorbox';
$datetimepicker=1;
$display_title='Media Library';

$id=(isset($_GET['media_library_id']) && $_GET['media_library_id']!='')?(int)$_GET['media_library_id']:'';

if(isset($_GET['media_library_id']) && $_GET['media_library_id']!='')	{
	$media_library=$db->getSingleRec($db->TB_media_library,"media_library_id=$id");
}

if(isset($media_library) && $media_library==true && $id!='') {
	extract($media_library);
	$head='Edit '.$display_title;
	$headdesc='This section allows you to update '.$display_title;
	$buttonName='<i class="fa fa-refresh"></i> Update '.$display_title;
	$action="class_media_library.php?action=update&media_library_id=$id";
	
	$title=(isset($_POST['title']) && trim($_POST['title'])!='')?$sanitize->cleanText(trim($_POST['title'])):$title;
	$long_title=(isset($_POST['long_title']) && trim($_POST['long_title'])!='')?$sanitize->cleanText(trim($_POST['long_title'])):$long_title;
	$description=(isset($_POST['description']) && trim($_POST['description'])!='')?$sanitize->cleanText(trim($_POST['description'])):$description;
	$status=(isset($_POST["status"]) && trim($_POST["status"])!="")?trim($_POST["status"]):$status;
} else {
	$utilityObj-headerLocation(ADMIN_PATH."manage_medai_library.php?init=1");
}

include_once("inc/header.php");
include_once("inc/menu.php");
?>

<!-- page title -->
					<h2 class="title <?php $utilityObj->title_color(); ?>"><i class="fa fa-photo"></i> <?php echo ((isset($head))?$head:''); ?></h2>
					<p><?php echo ((isset($headdesc))?$headdesc:''); ?></p>
					
					<?php include_once('inc/form_message.php'); ?>
					<br />
<!-- Add Customers -->
					<div class="imp">
						<form action="<?php echo ((isset($action))?$action:''); ?>" method="post"  enctype="multipart/form-data">
							<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table1 auto" style="margin:0;">
								<tr>
									<td>
										<fieldset>
											<legend><?php echo ((isset($display_title))?$display_title:''); ?> File</legend>
											<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin:0;" class="table1 auto">
												<tr>
													<td>
														<label>Media File:</label>
				<?php
					if(isset($media_library) && $media_library==true && $id!='') {
									if(isset($file_name) && $file_name!='') {
										$media_file=BASE_PATH.$pathObj->media_library_mediumphotos_path.$file_name;
										$media_path=SITE_PATH.$pathObj->media_library_mediumphotos_path.$file_name;
										if($file_type!='' && ($file_type=='image/jpeg' || $file_type=='image/png' || $file_type=='image/gif')) {
											if(is_file($media_file)) {
												echo '<p class="thumb_preview">
														<a href="'.$media_path.'" class="group1" title="Media Library File"><img src="'.$media_path.'" alt="'.$file_name.'" class="imb" /></a>
														<a href="class_media_library.php?action=delete_media_library_file&media_library_id='.$id.'" onclick="return confirm(\'Are you sure that you want to remove?\');" class="button1 red delete" title="remove"><i class="fa fa-remove"></i></a>
													</p>';
											}
										} else if($file_type!='') {
				?>
														<div class="thumb_preview queue">
															<a href="<?php echo $media_path; ?>" class="imb uploaded-file-name" target="_blank"><i class="fa fa-file-pdf-o"></i> <?php echo $file_name; ?></a>
															<a href="class_page.php?action=delete_media_library_file&media_library_id=<?php echo $id; ?>" class="button1 red delete" onclick="return confirm('Are you sure that you want to remove?');"><i class="fa fa-remove"></i></a>
														</div>
				<?php
										}
									} else {
				?>
														<label>Select files to upload:</label>
														<input type="file" size="40" name="upload_file[]" class="multi" accept="jpg|gif|png|pdf|doc|docx|xls|xlsx" maxlength="0" multiple="multiple" /><br /><br />
														<small><strong>Note: </strong>Select multiple files for bulk upload.</small>
				<?php
									}
					}
				?>
													</td>
												</tr>
											</table>
										</fieldset>
										<fieldset>
											<legend><?php echo ((isset($display_title))?$display_title:''); ?> Info</legend>
											<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin:0;" class="table1 auto">
												<tr><td class="<?php echo ((isset($title_error))?$title_error:''); ?>"><label for="title">Short Photo Title:</label><input type="text" class="c" name="title" id="title" style="width:500px;" value="<?php echo ((isset($title))?$title:''); ?>" /></td></tr>
												<tr><td class="<?php echo ((isset($long_title_error))?$long_title_error:''); ?>"><label for="long_title">Long Photo Title:</label><input type="text" class="c" name="long_title" id="long_title" style="width:500px;" value="<?php echo ((isset($long_title))?$long_title:''); ?>" /></td></tr>
												<tr><td class="<?php echo ((isset($description_error))?$description_error:''); ?>"><label for="editor1">Photo Description:</label><textarea class="ckeditor" style="width:500px;height:80px;" name="description"><?php echo ((isset($description))?$description:'');?></textarea></td></tr>
											</table>
										</fieldset>
									</td>
									<td style="width:20px;">&nbsp;</td>
									<td style="width:300px;">
										<fieldset>
											<legend>Settings</legend>
											<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table1 auto" style="margin:0;">
												<tr><td><label for="status">Status:</label>
													<select name="status" id="status" class="chosen" data-placeholder="Select Status">
														<option value=""></option>
														<?php echo $utilityObj->getArray2List($utilityObj->status_array,$status); ?>
													</select>
												</td></tr>
												<tr><td><button class="button1" type="submit" name="submit"><?php echo ((isset($buttonName))?$buttonName:''); ?></button> &nbsp; <a class="button1 red" href="manage_media_library.php"><i class="fa fa-ban" aria-hidden="true"></i>  Cancel</a></td></tr>
											</table>
										</fieldset>
									</td>
								</tr>
							</table>
						</form>
					</div>
<?php /* if($media_library==true && $id!='')	{ ?>
					<div class="notes">
						<?php echo userEditActivity($db->TB_track_detail,"table_name='$db->TB_media_library' AND primary_key_id=$id"); ?>
					</div>
<?php } */ ?>
<?php 
	include_once("inc/footer.php");
?>