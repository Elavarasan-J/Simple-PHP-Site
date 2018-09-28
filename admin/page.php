<?php
/**
 * App initiated
 */
require_once('../init.php');
defined('BASE_PATH') OR exit('No direct script access allowed');

$editor = 'editor';
$colorbox = 'colorbox';
$slugedit = 'slugedit';
	
$id=(isset($_GET["page"]) && $_REQUEST["page"]!="")?(int)$_REQUEST["page"]:"";
$formsrequest='';
if(isset($_REQUEST["page"]) && $_REQUEST["page"]!="")	{
	$formsrequest=$db->getSingleRec($db->TB_page ,"page_id=$id");
}
if($formsrequest==true && $id!=NULL) {
	extract($formsrequest);
	$buttonName='<i aria-hidden="true" class="fa fa-refresh"></i> Update Page';
	$action='class_page.php?action=update&page='.$id;
	$heading='<i aria-hidden="true" class="fa fa-pencil"></i> Edit Page';
	$parent_where=" WHERE page_id!=$id AND status=1";
	
	$parent_id=(isset($_POST["parent_id"]) && trim($_POST["parent_id"])!="")?trim($_POST["parent_id"]):$parent_id;
	$page_template=(isset($_POST["page_template"]) && trim($_POST["page_template"])!="")?trim($_POST["page_template"]):$page_template;
	$title=(isset($_POST["title"]) && trim($_POST["title"])!="")?trim($_POST["title"]):$title;
	$subtitle=(isset($_POST["subtitle"]) && trim($_POST["subtitle"])!="")?trim($_POST["subtitle"]):$subtitle;
	$slug=(isset($_POST["slug"]) && trim($_POST["slug"])!="")?trim($_POST["slug"]):$slug;
	
	$remove_link=(isset($_POST['remove_link']) && $_POST['remove_link']!='')?$_POST['remove_link']:$remove_link;
	
	$short_description=(isset($_POST["short_description"]) && trim($_POST["short_description"])!="")?html_entity_decode(trim($_POST["short_description"])):$short_description;
	$description=(isset($_POST["description"]) && trim($_POST["description"])!="")?html_entity_decode(trim($_POST["description"])):$description;
	
	$button_text=isset($_POST['button_text'])?trim($_POST['button_text']):$button_text;
	$button_link_page=(isset($_POST["button_link_page"]) && trim($_POST["button_link_page"])!="")?trim($_POST["button_link_page"]):$button_link_page;
	
	$sort_order=isset($_POST['sort_order'])?trim($_POST['sort_order']):$sort_order;
	$status=(isset($_POST["status"]) && trim($_POST["status"])!="")?trim($_POST["status"]):$status;
} else {
	$buttonName='<i aria-hidden="true" class="fa fa-plus-square"></i> Add Page';
	$action='class_page.php?action=add';
	$heading='<i aria-hidden="true" class="fa fa-plus-square"></i> Add Page';
	$parent_where=" WHERE status=1";
	
	$parent_id=(isset($_POST["parent_id"]) && trim($_POST["parent_id"])!="")?trim($_POST["parent_id"]):"";
	$page_template=(isset($_POST["page_template"]) && trim($_POST["page_template"])!="")?trim($_POST["page_template"]):"page-_default_left_sidebar.php";
	$title=(isset($_POST["title"]) && trim($_POST["title"])!="")?trim($_POST["title"]):"";
	$subtitle=(isset($_POST["subtitle"]) && trim($_POST["subtitle"])!="")?trim($_POST["subtitle"]):"";
	$slug=(isset($_POST["slug"]) && trim($_POST["slug"])!="")?trim($_POST["slug"]):"";
	
	$remove_link=(isset($_POST['remove_link']) && $_POST['remove_link']!='')?$_POST['remove_link']:0;
	
	$short_description=(isset($_POST["short_description"]) && trim($_POST["short_description"])!="")?html_entity_decode(trim($_POST["short_description"])):"";
	$description=(isset($_POST["description"]) && trim($_POST["description"])!="")?html_entity_decode(trim($_POST["description"])):"";
	
	$button_text=isset($_POST['button_text'])?trim($_POST['button_text']):'';
	$button_link_page=(isset($_POST["button_link_page"]) && trim($_POST["button_link_page"])!="")?trim($_POST["button_link_page"]):'';
	
	$sort_order=isset($_POST['sort_order'])?trim($_POST['sort_order']):0;
	$status=(isset($_POST["status"]) && trim($_POST["status"])!="")?trim($_POST["status"]):1;
}

include_once("inc/header.php");
include_once("inc/menu.php");
//printR($_POST,0,0);
$templatesArr=$utilityObj->getTemplatesForOpts();

$pages_rec = $pageInfoObj->getPageArr();
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
														<label for="title" class="iblock"><span class="req">*</span> Title: <i class="fa fa-spinner fa-spin" id="slug_imgloader" style="display:none;font-size:15px;line-height:15px;vertical-align:top;animation-duration:1s;"></i></label>
													<?php if($slug!='') { ?>
														&nbsp; &nbsp; &nbsp; &nbsp; <label for="slug" class="iblock">Slug:</label>
														<span class="tags" id="slug_tag"><?php echo $slug; ?></span>
														<input type="text" name="slug" id="slug" value="<?php echo $slug; ?>" style="display:none;margin-right:5px;line-height:18px;padding:1px;vertical-align:middle;" />
														<label class="iblock" id="edit_cancel"><input type="checkbox" name="edit_slug" id="edit_slug" style="display:none;" /> <a style="text-decoration:underline;cursor:pointer;">Edit</a></label>
													<?php } ?>
														&nbsp; &nbsp; &nbsp; &nbsp; <label for="remove_link" class="iblock"><input type="checkbox" name="remove_link" id="remove_link" value="1"<?php echo ($remove_link==1)?' checked="checked"':''; ?> /> Remove Link</label>
														<input type="text" name="title" id="title" style="width:100%;" value="<?php echo $title; ?>" onblur="updateSlug(this.value,'title');" />
													</td>
												</tr>
												<tr>
													<td>
														<label for="subtitle" class="iblock">Sub Title: <i class="fa fa-spinner fa-spin" id="slug_imgloader" style="display:none;font-size:15px;line-height:15px;vertical-align:top;animation-duration:1s;"></i></label>
														<input type="text" name="subtitle" id="subtitle" style="width:100%;" value="<?php echo $subtitle; ?>" onblur="updateSlug(this.value,'subtitle');" />
													</td>
												</tr>
												<tr>
													<td>
														<label for="short_description">Short Description: (Optional)</label>
														<textarea name="short_description" id="short_description" style="width:100%;"><?php echo $short_description; ?></textarea>
													</td>
												</tr>
												<tr>
													<td>
														<label for="description">Description:</label>
														<a href="media_files.php?field_name=description" class="btn btn-primary" data-toggle="modal" data-target="#modal1"><i class="fa fa-image"></i> Select Media File </a><div class="modal fade" id="modal1" tabindex="-1" role="dialog" aria-labelledby="modalLabel1"></div>
														<textarea name="description" class="ckeditor" style="width:650px;"><?php echo $description; ?></textarea>
													</td>
												</tr>
											</table>
										</fieldset>
										<button type="submit" name="submit" class="button1" /><?php echo $buttonName; ?></button> &nbsp; <a href="manage_pages.php" class="button1 red"><i class="fa fa-ban"></i> Cancel</a>
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
														<a href="media_files.php?type=image&field_name=featured_img&field_class=use" class="btn btn-primary" data-toggle="modal" data-target="#modal3"><i class="fa fa-image"></i> Select Image </a>
														<div class="modal fade" id="modal3" tabindex="-1" role="dialog" aria-labelledby="modalLabel1"></div>
													</td>
												</tr>
											</table>
										</fieldset>
										<fieldset>
											<legend>More Button</legend>
											<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table1 auto" style="margin:0;">
												<tr>
													<td><label for="button_text">Button Text:</label><input type="text" value="<?php echo $button_text; ?>" name="button_text" id="button_text" style="width:100%;"></td>
												</tr>
												<tr>
													<td>
														<label for="button_link_page">Button Link Page:</label>
														<select name="button_link_page" id="button_link_page" class="chosen" data-placeholder="Select Page" style="width:100%;">
															<option value="">-- No Page --</option>
															<?php echo $pageInfoObj->listPage($pages_rec,array($button_link_page),0); ?>
														</select>
													</td>
												</tr>
											</table>
										</fieldset>
										<fieldset>
											<legend>Settings</legend>
											<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table1 auto" style="margin:0;">
												<tr>
													<td>
														<label for="page_template">Page Template:</label>
														<select name="page_template" id="page_template" class="chosen" style="width:250px;">
															<?php echo $utilityObj->getArray2List($templatesArr,$page_template); ?>
														</select>
													</td>
												</tr>
												<tr>
													<td>
														<label for="parent_id">Page Parent:</label>
														<select name="parent_id" id="parent_id" class="chosen" data-placeholder="Select Page" style="width:250px;">
															<option value="">-- No Parent --</option>
															<?php echo $pageInfoObj->listPage($pages_rec,array($parent_id),0); ?>
														</select>
													</td>
												</tr>
												<tr>
													<td><label for="sort_order">Sort Order#:</label><input type="number" step="any" min="0" class="c" value="<?php echo $sort_order; ?>" name="sort_order" id="sort_order" style="width:85px"></td>
												</tr>
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
													<td><button type="submit" name="submit" class="button1" /><?php echo $buttonName; ?></button> &nbsp; <a href="manage_pages.php" class="button1 red"><i class="fa fa-ban"></i> Cancel</a></td>
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