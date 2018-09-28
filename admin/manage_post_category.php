<?php
/**
 * App initiated
 */
require_once('../init.php');
defined('BASE_PATH') OR exit('No direct script access allowed');

	$editor = 'editor';
	$colorbox = 'colorbox';
	$slugedit = 'slugedit';
	
	$managePage="post_category.php";
	$classPage="class_post_category.php";
	
	$id=(isset($_GET['post_category']) && $_GET['post_category']!='')?(int)$_GET['post_category']:'';
	$post_category_items='';
	if(isset($_GET['post_category']) && $_GET['post_category']!='')	{
		$post_category_items=$db->getSingleRec($db->TB_post_category,"post_category_id=$id");
	}
	if($post_category_items==true && $id!='')	{
		extract($post_category_items);
		$buttonName='<i aria-hidden="true" class="fa fa-refresh"></i> Update';
		$action=$classPage.'?action=update&post_category='.$id;
		$heading='<i aria-hidden="true" class="fa fa-pencil"></i> Edit Post Category';
		$headingdesc='This section allows you to edit Post Category.';
		
		$title=(isset($_POST["title"]) && trim($_POST["title"])!="")?trim($_POST["title"]):$title;
		$slug=(isset($_POST["slug"]) && trim($_POST["slug"])!="")?trim($_POST["slug"]):$slug;
		$description=(isset($_POST["description"]) && trim($_POST["description"])!="")?html_entity_decode(trim($_POST["description"])):$description;
		$status=(isset($_POST["status"]) && trim($_POST["status"])!="")?trim($_POST["status"]):$status;
	} else {
		$buttonName='<i aria-hidden="true" class="fa fa-plus-square"></i> Add';
		$action=$classPage.'?action=add';
		$heading='<i aria-hidden="true" class="fa fa-plus-square"></i> Add Post Category';
		$headingdesc='This section allows you to add a new Post Category.';
		
		$title=(isset($_POST["title"]) && trim($_POST["title"])!="")?trim($_POST["title"]):"";
		$slug=(isset($_POST["slug"]) && trim($_POST["slug"])!="")?trim($_POST["slug"]):"";
		$description=(isset($_POST["description"]) && trim($_POST["description"])!="")?html_entity_decode(trim($_POST["description"])):"";
		$sort_order=isset($_POST['sort_order'])?trim($_POST['sort_order']):0;
		$status=(isset($_POST["status"]) && trim($_POST["status"])!="")?trim($_POST["status"]):1;
	}
	
	$moduleName='post_category';
	include_once("inc/header.php");
	include_once("inc/menu.php");
?>

<!-- post_category title -->
				<h2 class="title <?php $utilityObj->title_color(); ?>"><?php echo $heading; ?></h2>
				<p><?php echo $headingdesc; ?> The fields marked with <span class="req">*</span> are mandatory.</p>
				
				<?php include_once('inc/form_message.php'); ?><br />
<!-- data table -->
						<form action="<?php echo $action; ?>" method="post" name="form" enctype="multipart/form-data">
							<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table1 auto" style="margin:0;">
								<tr>
									<td>
										<fieldset>
											<legend>Info</legend>
											<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table1 auto" style="margin:0;">
												<tr>
													<td class="<?php echo $title_error; ?>">
														<label for="title" class="iblock"><span class="req">*</span> Title: <i class="fa fa-spinner fa-spin" id="slug_imgloader" style="display:none;font-size:15px;line-height:15px;vertical-align:top;animation-duration:1s;"></i></label>
													<?php if($slug!='') { ?>
														<label for="slug" class="iblock">Slug:</label>
														<span class="tags" id="slug_tag"><?php echo $slug; ?></span>
														<input type="text" name="slug" id="slug" value="<?php echo $slug; ?>" style="display:none;margin-right:5px;line-height:18px;padding:1px;vertical-align:middle;" />
														<label class="iblock" id="edit_cancel"><input type="checkbox" name="edit_slug" id="edit_slug" style="display:none;" /> <a style="text-decoration:underline;cursor:pointer;">Edit</a></label>
													<?php } ?>
														<input type="text" name="title" id="title" style="width:100%;" value="<?php echo $title; ?>" onblur="updateSlug(this.value,'title');" />
													</td>
												</tr>
												<tr>
													<td>
														<label for="description">Description:</label>
														<textarea name="description" class="ckeditor" style="width:650px;"><?php echo $description; ?></textarea>
													</td>
												</tr>
											</table>
										</fieldset>
									</td>
									<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
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
												<tr>
													<td>
														<label for="status">Status</label>
														<select name="status" id="status" class="chosen" >
															<?php echo $utilityObj->getArray2List($utilityObj->status_array,$status); ?>
														</select>
													</td>
												</tr>
												<tr>
													<td><button type="submit" name="submit" class="button1" /><?php echo $buttonName; ?></button> &nbsp; <a href="post_category.php" class="button1 red"><i class="fa fa-ban"></i> Cancel</a></td>
												</tr>
											</table>
										</fieldset>
									</td>
								</tr>
							</table>
						</form>
<?php 
$querys="SELECT * FROM $db->TB_post_category";
$sql=mysqli_query($db->conn, $querys);

//$querys1="SELECT * FROM $db->TB_admin_menu WHERE parent_menu_id<1 AND menu_id IN ($user_modules_list) ORDER BY menu_order_num ASC";
$querys1="SELECT * FROM $db->TB_post_category ORDER BY title ASC";
$sql1=mysqli_query($db->conn, $querys1);
?>
					<p class="re">Total Records : <?php echo @mysqli_num_rows($sql); ?></p>
					<table cellspacing="0" cellpadding="0" width="100%" class="table table-striped">
						<tr>
							<th width="10%">S.No</th>
							<th width="20%">Title</th>
							<th width="20%">Slug</th>
							<th width="25%">Description</th>
							<th width="10%">Status</th>
							<th width="15%">Actions</th>
						</tr>
<?php
	$no=1;
	if(@mysqli_num_rows($sql1)>0) {
		while($fetch=mysqli_fetch_array($sql1))	{
			$parent_menu_id=$fetch[0];
			extract($fetch);
			//$menu_rec=$db->getSingleRec($db->TB_admin_menu,"menu_id=$parent_menu_id",'menu_name');
?>
						<tr>
							<td><?php echo $no; ?></td>
							<td><?php echo ucfirst($title); ?>&nbsp;</td>
							<td><span class="tags"><?php echo ($slug); ?></span>&nbsp;</td>
							<td><?php echo html_entity_decode($description); ?>&nbsp;</td>
<?php include(ADMIN_BASE_PATH.'inc/statusActionCol.php'); ?>
						</tr>
<?php
			$no++;
		}
	}
?>
					</table>
<!-- pagination -->
<?php
	//include_once("includes/pagenavi.php");
?>
<?php 
	include_once("inc/footer.php");
?>