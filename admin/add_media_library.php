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

$head='Add '.$display_title;
$headdesc='This section allows you to add new '.$display_title;
$buttonName='<i class="fa fa-plus"></i> Add '.$display_title;
$action='class_media_library.php?action=add';

$title=(isset($_POST['title']) && trim($_POST['title'])!='')?$sanitize->cleanText(trim($_POST['title'])):'';
$long_title=(isset($_POST['long_title']) && trim($_POST['long_title'])!='')?$sanitize->cleanText(trim($_POST['long_title'])):'';
$description=(isset($_POST['description']) && trim($_POST['description'])!='')?$sanitize->cleanText(trim($_POST['description'])):'';
$status=(isset($_POST["status"]) && trim($_POST["status"])!="")?trim($_POST["status"]):1;

include_once("inc/header.php");
include_once("inc/menu.php");
?>

<!-- page title -->
					<h2 class="title <?php $utilityObj->title_color(); ?>"><i class="fa fa-photo"></i> <?php echo ((isset($head))?$head:''); ?><small class="right"><span class="req">*</span> required fields.</small></h2>
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
                                            <legend>Media File</legend>
                                            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="table1 auto" style="margin:0;">
                                            	<tr>
                                                	<td>
                                                        <label>Select files to upload<span class="req">*</span></label>
                                                        <input type="file" size="40" name="upload_file[]" class="multi<?php echo ((isset($upload_file_error))?' '.$upload_file_error:''); ?>" accept="jpg|gif|png|pdf|doc|docx|xls|xlsx" maxlength="0" multiple="multiple" /><br /><br />
                                                        <small><strong>Note: </strong>Select multiple files for bulk upload.</small>
                									</td>
                                                </tr>
                                            </table>
                                        </fieldset>
                                        <fieldset>
                                            <legend><?php echo ((isset($display_title))?$display_title:''); ?> Info</legend>
                                            <table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin:0;" class="table1 auto">
                                                <tr><td class="<?php echo ((isset($title_error))?$title_error:''); ?>"><label for="title">Short Title:</label><input type="text" class="c" name="title" id="title" style="width:500px;" value="<?php echo ((isset($title))?$title:''); ?>" /></td></tr>
                                                <tr><td class="<?php echo ((isset($long_title_error))?$long_title_error:''); ?>"><label for="long_title">Long Title:</label><input type="text" class="c" name="long_title" id="long_title" style="width:500px;" value="<?php echo ((isset($long_title))?$long_title:''); ?>" /></td></tr>
                                                <tr><td class="<?php echo ((isset($description_error))?$description_error:''); ?>"><label for="editor1">Description:</label><textarea class="ckeditor" style="width:500px;height:80px;" name="description"><?php echo ((isset($description))?$description:'');?></textarea></td></tr>
                                            </table>
                                        </fieldset>
                                    </td>
                                    <td style="width:20px;">&nbsp;</td>
                                    <td style="width:300px;">
                                        <fieldset>
                                            <legend>Settings</legend>
                                            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="table1 auto" style="margin:0;">
				<?php if(isset($media_library) && $media_library==true && $id!='') { ?>
                                                <tr><td><br /><strong>Total Photos: <?php echo ((isset($numphoto))?$numphoto:''); ?></strong></td></tr>
                                                <tr><td><a href="manage_photos.php?media_library_id=<?php echo ((isset($id))?$id:''); ?>" class="button1 green"><i class="fa fa-wrench"></i> Manage Photos</a></td></tr>
				<?php } ?>
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