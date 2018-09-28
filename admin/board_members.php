<?php
/**
 * App initiated
 */
require_once('../init.php');
defined('BASE_PATH') OR exit('No direct script access allowed');

$editor = "editor";
$colorbox = 'colorbox';
$slugedit = 'slugedit';
$display_title='Board Member';

$id=(isset($_GET['board_member_id']) && $_GET['board_member_id']!='')?(int)$_GET['board_member_id']:'';
$board_members='';
if(isset($_GET['board_member_id']) && $_GET['board_member_id']!='')	{
	$board_members=$db->getSingleRec($db->TB_board_members,"board_member_id=$id");
}

if($board_members==true && $id!='') {
	extract($board_members);
	$head='Edit '.$display_title;
	$headdesc='This section allows you to update '.$display_title;
	$buttonName='<i class="fa fa-refresh"></i> Update '.$display_title;
	$action="class_board_members.php?action=update&board_member_id=$id";
	
	//print_r($board_members);
	$title=(isset($_POST['title']) && trim($_POST['title'])!='')?$sanitizationObj->cleanText(trim($_POST['title'])):$title;
	$position=(isset($_POST['position']) && trim($_POST['position'])!='')?$sanitizationObj->cleanText(trim($_POST['position'])):$position;
	$description=(isset($_POST['description']) && trim($_POST['description'])!='')?$sanitizationObj->cleanText(trim($_POST['description'])):$description;
	$sort_order=isset($_POST['sort_order'])?trim($_POST['sort_order']):$sort_order;
	$status=(isset($_POST['status']) && $_POST['status']!='')?$_POST['status']:$status;
	
	//$countboard_member=$db->getSingleRecQuery("");
} else {
	$head='Add '.$display_title;
	$headdesc='This section allows you to add new '.$display_title;
	$buttonName='<i class="fa fa-plus"></i> Add '.$display_title;
	
	$action='class_board_members.php?action=add';
	
	$title=(isset($_POST['title']) && trim($_POST['title'])!='')?$sanitizationObj->cleanText(trim($_POST['title'])):'';
	$position=(isset($_POST['position']) && trim($_POST['position'])!='')?$sanitizationObj->cleanText(trim($_POST['position'])):'';
	$description=(isset($_POST['description']) && trim($_POST['description'])!='')?$sanitizationObj->cleanText(trim($_POST['description'])):'';
	$sort_order=isset($_POST['sort_order'])?trim($_POST['sort_order']):'';
	$status=(isset($_POST['status']) && $_POST['status']!='')?$_POST['status']:1;
}

include_once("inc/header.php");
include_once("inc/menu.php");
?>

<!-- page title -->
					<h2 class="title orange"><i class="fa fa-photo"></i> <?php echo ((isset($head))?$head:''); ?><small class="right"><span class="req">*</span> required fields.</small></h2>
					<p><?php echo ((isset($headdesc))?$headdesc:''); ?></p>
					
					<?php include_once('inc/form_message.php'); ?>
					<br />
<!-- Add Customers -->
					<form action="<?php echo ((isset($action))?$action:''); ?>" method="post"  enctype="multipart/form-data">
						<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table1 auto" style="margin:0;">
							<tr>
								<td>
									<fieldset>
										<legend><?php echo ((isset($display_title))?$display_title:''); ?> Info</legend>
										<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin:0;" class="table1 auto">
											<tr><td><label for="board_type">Board Type:</label>
												<select name="board_type" id="board_type" class="chosen" data-placeholder="Select Board Type" style="width:200px;">
													<option value=""></option>
													<?php echo $utilityObj->getArray2List($utilityObj->boardType_arr,$board_type); ?>
												</select>
											</td></tr>
											<tr><td class="<?php echo ((isset($title_error))?$title_error:''); ?>"><label for="title"><span class="req">*</span> Title:</label><input type="text" class="c" name="title" id="title" style="width:500px;" value="<?php echo ((isset($title))?$title:''); ?>" /></td></tr>
											<tr><td class="<?php echo ((isset($position_error))?$position_error:''); ?>"><label for="position">Position:</label><input type="text" class="c" name="position" id="position" style="width:500px;" value="<?php echo ((isset($position))?$position:''); ?>" /></td></tr>
											<tr><td class="<?php echo ((isset($description_error))?$description_error:''); ?>">
												<label for="editor1">Description:</label>
												<a href="media_files.php?field_name=description" class="btn btn-primary" data-toggle="modal" data-target="#modal1"><i class="fa fa-image"></i> Select Media File </a><div class="modal fade" id="modal1" tabindex="-1" role="dialog" aria-labelledby="modalLabel1"></div>
												<textarea class="ckeditor" style="width:500px;height:80px;" name="description"><?php echo ((isset($description))?$description:'');?></textarea>
											</td></tr>
										</table>
									</fieldset>
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
										<legend>Settings</legend>
										<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table1 auto" style="margin:0;">
											<tr><td><label for="status">Status:</label>
												<select name="status" id="status" class="chosen" data-placeholder="Select Status">
													<option value=""></option>
													<?php echo $utilityObj->getArray2List($utilityObj->status_array,$status); ?>
												</select>
											</td></tr>
											<tr>
												<td>
													<label for="sort_order">Sort Order</label>
													<input type="number" step="any" min="1" name="sort_order" id="sort_order" style="width:100px;" value="<?php echo ((isset($sort_order))?$sort_order:''); ?>" />
												</td>
											</tr>
											<tr><td><button class="button1" type="submit" name="submit"><?php echo ((isset($buttonName))?$buttonName:''); ?></button> &nbsp; <a class="button1 red" href="manage_board_members.php"><i class="fa fa-ban" aria-hidden="true"></i>  Cancel</a></td></tr>
										</table>
									</fieldset>
								</td>
							</tr>
						</table>
					</form>
<?php /* if($board_members==true && $id!='')	{ ?>
                    <div class="notes">
						<?php echo userEditActivity($db->TB_track_detail,"table_name='$db->TB_board_members' AND primary_key_id=$id"); ?>
					</div>
<?php } */ ?>
<?php include_once("jscript.php"); ?>
<?php include_once("inc/footer.php"); ?>