<?php
/**
 * App initiated
 */
require_once('../init.php');
defined('BASE_PATH') OR exit('No direct script access allowed');

$colorbox = 'colorbox';
	
$id=(isset($_GET["gallery"]) && $_REQUEST["gallery"]!="")?(int)$_REQUEST["gallery"]:"";
$gallery_arr='';
if(isset($_REQUEST["gallery"]) && $_REQUEST["gallery"]!="")	{
	$gallery_arr=$db->getSingleRec($db->TB_gallery, "gallery_id=$id");
}

if($gallery_arr==true && $id!=NULL) {
	extract($gallery_arr);

	$buttonName='<i aria-hidden="true" class="fa fa-refresh"></i> Update Gallery';
	$action='class_gallery.php?action=update&gallery='.$id;
	$heading='<i aria-hidden="true" class="fa fa-pencil"></i> Edit Gallery';
	
	$title=(isset($_POST["title"]) && trim($_POST["title"])!="")?trim($_POST["title"]):$title;
	$gallery_items=(isset($_POST["gallery_items"]) && is_array($_POST["gallery_items"]) && count($_POST["gallery_items"])>0)?$_POST["gallery_items"]:(($gallery_items!='')?explode(',',$gallery_items):'');
	$status=(isset($_POST["status"]) && trim($_POST["status"])!="")?trim($_POST["status"]):$status;
} else {
	$buttonName='<i aria-hidden="true" class="fa fa-plus-square"></i> Add Gallery';
	$action='class_gallery.php?action=add';
	$heading='<i aria-hidden="true" class="fa fa-plus-square"></i> Add Gallery';
	
	$title=(isset($_POST["title"]) && trim($_POST["title"])!="")?trim($_POST["title"]):"";
	$gallery_items=(isset($_POST["gallery_items"]) && is_array($_POST["gallery_items"]) && count($_POST["gallery_items"])>0)?$_POST["gallery_items"]:'';
	$status=(isset($_POST["status"]) && trim($_POST["status"])!="")?trim($_POST["status"]):1;
}

include_once("inc/header.php");
include_once("inc/menu.php");
//printR($_POST,0,0);
$templatesArr=$utilityObj->getTemplatesForOpts();

$gallery_rec = $pageInfoObj->getPageArr();
?>
						<h2 class="title <?php $utilityObj->title_color(); ?>"><?php echo $heading; ?><small class="right"><span class="req">*</span> required fields.</small></h2>
						
						<?php include_once("inc/form_message.php"); ?><br />
						<form action="<?php echo $action; ?>" method="post" name="form" enctype="multipart/form-data">
							<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table1 auto page-table" style="margin:0;">
								<tr>
									<td>
										<fieldset>
											<legend>Info</legend>
											<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table1 auto" style="margin:0;">
												<tr>
													<td class="<?php echo (isset($title_error))?$title_error:''; ?>">
														<label for="title"><span class="req">*</span> Title: </label>
														<input type="text" name="title" id="title" style="width:100%;" value="<?php echo $title; ?>" onblur="updateSlug(this.value,'title');" />
													</td>
												</tr>
											</table>
										</fieldset>
										<fieldset>
											<legend>Gallery Items</legend>
											<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table1 auto" style="margin:0;">
												<tr>
													<td>
															<a href="media_files.php?type=image&field_name=gallery_items&field_class=add" class="btn btn-primary" data-toggle="modal" data-target="#modal1"><i class="fa fa-image"></i> Select Image </a>
															<div class="clearfix"></div>
														<?php
															if(isset($gallery_items) && is_array($gallery_items) && count($gallery_items>0)) {
																foreach($gallery_items as $img) {
																	echo '<div class="thumb_preview" style="margin:20px 20px 0 0;"><div style="width:150px;height:150px;display:table-cell;vertical-align:middle;text-align:center;"><img src="'.SITE_PATH.$pathObj->media_library_thumbphotos_path.$img.'" alt=""><input type="hidden" name="gallery_items[]" value="'.$img.'"><a onclick="removeThis(this);" class="button1 red delete removeThis" title="remove"><i class="fa fa-remove"></i></a></div></div>';
																}
															}
														?>
															<div class="modal fade" id="modal1" tabindex="-1" role="dialog" aria-labelledby="modalLabel1"></div>
													</td>
												</tr>
											</table>
										</fieldset>
										<button type="submit" name="submit" class="button1" /><?php echo $buttonName; ?></button> &nbsp; <a href="manage_gallery.php" class="button1 red"><i class="fa fa-ban"></i> Cancel</a>
									</td>
									<td style="width:20px;">&nbsp;</td>
									<td style="width:300px;">
										<fieldset>
											<legend>Featured Image</legend>
											<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table1 auto" style="margin:0;">
												<tr>
													<td class="media-wrap">
													<?php if(isset($featured_img) && $featured_img!='') { ?>
														<div class="form-group media-file"><div class="thumb_preview"><img src="<?php echo SITE_PATH.$pathObj->media_library_files_path.$featured_img; ?>" alt=""></div><input type="hidden" name="featured_img" value="<?php echo $featured_img; ?>"></div>
													<?php } ?>
														<a href="media_files.php?type=image&field_name=featured_img&field_class=use" class="btn btn-primary" data-toggle="modal" data-target="#modal2"><i class="fa fa-image"></i> Select Image </a>
														<div class="modal fade" id="modal2" tabindex="-1" role="dialog" aria-labelledby="modalLabel2"></div>
													</td>
												</tr>
											</table>
										</fieldset>
										<fieldset>
											<legend>Settings</legend>
											<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table1 auto" style="margin:0;">
												<tr>
													<td>
										<?php
											if($_SESSION[ADMIN_SESSION]['full_privilege']==1) {
										?>
														<label for="status">Status</label>
														<select name="status" id="status" class="chosen" >
															<?php echo $utilityObj->getArray2List($utilityObj->status_array,$status); ?>	
														</select>
										<?php
											} else {
												echo 'Status: <strong>'.$status_array[$status].'</strong><input type="hidden" value="'.$status.'" name="status" id="status">';
											}
										?>
													</td>
												</tr>
												<tr>
													<td><button type="submit" name="submit" class="button1" /><?php echo $buttonName; ?></button> &nbsp; <a href="manage_gallery.php" class="button1 red"><i class="fa fa-ban"></i> Cancel</a></td>
												</tr>
											</table>
										</fieldset>
									</td>
								</tr>
							</table>
						</form>
<?php if(isset($skincon) && $skincon!='' && $id!='') { ?>
	<div class="notes"><?php echo userEditActivity($db->TB_track_detail,"table_name='$db->TB_skin_condition' AND primary_key_id=$id"); ?></div>
<?php } ?>
<?php include_once("jscript.php"); ?>
<?php include_once("inc/footer.php");?>