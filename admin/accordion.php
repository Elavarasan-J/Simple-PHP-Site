<?php
/**
 * App initiated
 */
require_once('../init.php');
defined('BASE_PATH') OR exit('No direct script access allowed');

$editor = 'editor';
$colorbox = 'colorbox';
$accordions=1;
	
$id=(isset($_GET["page"]) && $_REQUEST["page"]!="")?(int)$_REQUEST["page"]:"";
$accordion_arr='';
if(isset($_REQUEST["page"]) && $_REQUEST["page"]!="")	{
	$accordion_arr=$db->getMultipleRec("SELECT * FROM $db->TB_page_accordion WHERE page_id=$id");
}
if($accordion_arr==true && $id!=NULL) {
	$buttonName='<i aria-hidden="true" class="fa fa-refresh"></i> Update Accordion';
	$action='class_accordion.php?action=update&page='.$id;
	$heading='<i aria-hidden="true" class="fa fa-pencil"></i> Edit Accordion';
	
	$page_id=(isset($_POST["page_id"]) && trim($_POST["page_id"])!="")?trim($_POST["page_id"]):$id;
	
	if(isset($_POST['accordion_title']) && is_array($_POST['accordion_title']) && count($_POST['accordion_title'])>0) {
		foreach($_POST['accordion_title'] as $key=>$val) {
			if(is_array($accordion_arr) && count($accordion_arr)>0) {
				$accordion[$key]['accordion_id']=$accordion_arr[$key]['accordion_id'];
			}
			$accordion[$key]['accordion_title']=trim($POST['accordion_title'][$key]);
			$accordion[$key]['accordion_desc']=html_entity_decode(trim($_POST['accordion_desc'][$key]));
			$accordion[$key]['accordion_sort_order']=$_POST['accordion_sort_order'][$key];
			$accordion[$key]['accordion_status']=$_POST['accordion_status'][$key];
		}
	} else if(is_array($accordion_arr) && count($accordion_arr)>0) {
		foreach($accordion_arr as $key=>$val) {
			$accordion[$key]['accordion_id']=trim($accordion_arr[$key]['accordion_id']);
			$accordion[$key]['accordion_title']=$accordion_arr[$key]['accordion_title'];
			$accordion[$key]['accordion_desc']=html_entity_decode(trim($accordion_arr[$key]['accordion_desc']));
			$accordion[$key]['accordion_sort_order']=$accordion_arr[$key]['accordion_sort_order'];
			$accordion[$key]['accordion_status']=$accordion_arr[$key]['accordion_status'];
		}
	} else {
		$accordion[0]['accordion_title']='';
		$accordion[0]['accordion_desc']='';
		$accordion[0]['accordion_sort_order']='';
		$accordion[0]['accordion_status']=1;
	}
} else {
	$buttonName='<i aria-hidden="true" class="fa fa-plus-square"></i> Add Accordion';
	$action='class_accordion.php?action=add';
	$heading='<i aria-hidden="true" class="fa fa-plus-square"></i> Add Accordion';
	
	$page_id=(isset($_POST["page_id"]) && trim($_POST["page_id"])!="")?trim($_POST["page_id"]):"";
	
	if(isset($_POST['accordion_title']) && is_array($_POST['accordion_title']) && count($_POST['accordion_title'])>0) {
		foreach($_POST['accordion_title'] as $key=>$val) {
			$accordion[$key]['accordion_title']=trim($_POST['accordion_title'][$key]);
			$accordion[$key]['accordion_desc']=html_entity_decode(trim($_POST['accordion_desc'][$key]));
			$accordion[$key]['accordion_sort_order']=$_POST['accordion_sort_order'][$key];
			$accordion[$key]['accordion_status']=$_POST['accordion_status'][$key];
		}
	} else {
		$accordion[0]['accordion_title']='';
		$accordion[0]['accordion_desc']='';
		$accordion[0]['accordion_sort_order']='';
		$accordion[0]['accordion_status']=1;
	}
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
											<legend>Accordion</legend>
											<div id="accordion_main">
							<?php
								foreach($accordion as $key=>$val) {
							?>
												<fieldset>
													<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table1 auto" style="margin:0;">
														<tr>
															<td>
																<label for="accordion_title">Accordion Title:</label>
																<input type="text" name="accordion_title[<?php echo $key; ?>]" style="width:100%;" value="<?php echo $val['accordion_title']; ?>" onblur="updateSlug(this.value);" /><?php if(isset($val['accordion_id']) && $val['accordion_id']!='') { ?><input type="hidden" name="accordion_id[<?php echo $key; ?>]" value="<?php echo $val['accordion_id']; ?>" /><?php } ?>
															</td>
														</tr>
														<tr>
															<td>
																<label for="accordion_desc">Accordion Description:</label>
																<a href="media_files.php?field_name=accordion_desc[<?php echo $key; ?>]" class="btn btn-primary" data-toggle="modal" data-target="#modal_<?php echo $key; ?>"><i class="fa fa-image"></i> Select Media File </a><div class="modal fade" id="modal_<?php echo $key; ?>" tabindex="-1" role="dialog" aria-labelledby="modalLabel1"></div>
																<textarea name="accordion_desc[<?php echo $key; ?>]" class="ckeditor" style="width:650px;"><?php echo $val['accordion_desc']; ?></textarea>
															</td>
														</tr>
														<tr>
															<td><label for="accordion_sort_order">Sort Order#:</label><input type="number" step="any" min="0" class="c" value="<?php echo $val['accordion_sort_order']; ?>" name="accordion_sort_order[<?php echo $key; ?>]" style="width:85px"></td>
														</tr>
														<tr>
															<td>
																<label for="accordion_status">Status</label>
																<select name="accordion_status[<?php echo $key; ?>]" class="chosen" >
																	<?php echo $utilityObj->getArray2List($utilityObj->status_array,$val['accordion_status']); ?>	
																</select>
															</td>
														</tr>
							<?php if(isset($val['accordion_id']) && $val['accordion_id']!='') { ?>
														<tr>
															<td><a href="class_page.php?action=delete_accordion&page_id=<?php echo $id; ?>&id=<?php echo $val['accordion_id']; ?>" class="button1 red delete" onclick="return confirm('Are you sure that you want to remove?');"><i class="fa fa-remove"></i> Delete</a></td>
														</tr>
							<?php } ?>
													</table>
												</fieldset>
							<?php
								}
							?>
											</div>
											<a href="#" class="button1 green" id="add_more_accordion"><i class="fa fa-plus"></i> Add More Accordion</a>
										</fieldset>
										<button type="submit" name="submit" class="button1" /><?php echo $buttonName; ?></button> &nbsp; <a href="manage_pages.php" class="button1 red"><i class="fa fa-ban"></i> Cancel</a>
									</td>
									<td style="width:20px;">&nbsp;</td>
									<td style="width:300px;">
										<fieldset>
											<legend>Settings</legend>
											<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table1 auto" style="margin:0;">
												<tr>
													<td>
														<label for="page_id">Page:</label>
														<select name="page_id" id="page_id" class="chosen" data-placeholder="Select Page" style="width:250px;">
															<option value="">-- No Parent --</option>
															<?php echo $pageInfoObj->listPage($pages_rec,array($page_id),0); ?>
														</select>
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