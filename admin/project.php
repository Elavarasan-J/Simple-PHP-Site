<?php
/**
 * App initiated
 */
require_once('../init.php');
defined('BASE_PATH') OR exit('No direct script access allowed');

$id=(isset($_REQUEST["project"]) && $_REQUEST["project"]!="")?(int)$_REQUEST["project"]:"";
$project='';
if(isset($_REQUEST["project"]) && $_REQUEST["project"]!="") {
    $project=$db->getSingleRec($db->TB_project, "project_id=$id");
}

if($project==true && $id!=NULL) {
    extract($project);
    $heading="Update Project";
    $buttonName='Update Project';
    $action='class_project.php?action=update&project_id='.$id;

    $title=(isset($_POST["title"]) && trim($_POST["title"])!="")?trim($_POST["title"]):$title;
    $place=(isset($_POST["place"]) && trim($_POST["place"])!="")?trim($_POST["place"]):$place;
    $from_date=(isset($_POST["from_date"]) && trim($_POST["from_date"])!="")?trim($_POST["from_date"]):$timeObj->switch_date_format($from_date,'-');
    $to_date=(isset($_POST["to_date"]) && trim($_POST["to_date"])!="")?trim($_POST["to_date"]):$timeObj->switch_date_format($to_date,'-');
    $supported_by=(isset($_POST["supported_by"]) && trim($_POST["supported_by"])!="")?trim($_POST["supported_by"]):$supported_by;
    $funded_by=(isset($_POST["funded_by"]) && trim($_POST["funded_by"])!="")?trim($_POST["funded_by"]):$funded_by;
    $description=(isset($_POST["description"]) && trim($_POST["description"])!="")?html_entity_decode(trim($_POST["description"])):$description;

    $link_name=(isset($_POST["link_name"]) && trim($_POST["link_name"])!="")?trim($_POST["link_name"]):$link_name;
    $external_link=(isset($_POST["external_link"]) && ($_POST["external_link"])!="")?($_POST["external_link"]):$external_link;
    $link_url=(isset($_POST["link_url"]) && trim($_POST["link_url"])!="")?trim($_POST["link_url"]):$link_url;
    $status=(isset($_POST["status"]) && trim($_POST["status"])!="")?trim($_POST["status"]):$status;
} else {
    $heading="Add Project";
    $buttonName='Create Project';
    $action='class_project.php?action=add';

    $title=(isset($_POST["title"]) && trim($_POST["title"])!="")?trim($_POST["title"]):'';
    $place=(isset($_POST["place"]) && trim($_POST["place"])!="")?trim($_POST["place"]):'';
    $from_date=(isset($_POST["from_date"]) && trim($_POST["from_date"])!="")?trim($_POST["from_date"]):'';
    $to_date=(isset($_POST["to_date"]) && trim($_POST["to_date"])!="")?trim($_POST["to_date"]):'';
    $supported_by=(isset($_POST["supported_by"]) && trim($_POST["supported_by"])!="")?trim($_POST["supported_by"]):'';
    $funded_by=(isset($_POST["funded_by"]) && trim($_POST["funded_by"])!="")?trim($_POST["funded_by"]):'';
    $description=(isset($_POST["description"]) && trim($_POST["description"])!="")?html_entity_decode(trim($_POST["description"])):"";

    $link_name=(isset($_POST["link_name"]) && trim($_POST["link_name"])!="")?trim($_POST["link_name"]):"Read More";
    $external_link=(isset($_POST["external_link"]) && ($_POST["external_link"])!="")?($_POST["external_link"]):0;
    $link_url=(isset($_POST["link_url"]) && trim($_POST["link_url"])!="")?trim($_POST["link_url"]):"";
    $status=(isset($_POST["status"]) && trim($_POST["status"])!="")?trim($_POST["status"]):1;
}

$editor = 'editor';
$colorbox = 'colorbox';
$script = 'slugedit';
$datecombo='datecombo';
include_once("inc/header.php");
include_once("inc/menu.php");
?>
                        <h2 class="title <?php $utilityObj->title_color(); ?>"><i aria-hidden="true" class="fa fa-plus-square"></i> <?php echo $heading; ?><small class="right"><span class="req">*</span> required fields.</small></h2>
                        <p>The fields marked with <span class="req">*</span> are mandatory.</p>
                        
                        <?php include_once("inc/form_message.php"); ?><br />
						
						<form action="<?php echo $action; ?>" method="post" name="project" enctype="multipart/form-data">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="table1 auto" style="margin:0;">
                                <tr>
                                    <td>
                                        <fieldset>
                                            <legend>Project Info</legend>
                                			<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table1 auto" style="margin:0;">
                                                <tr>
                                                    <td>
                                                        <label for="title">Title <span class="req">*</span></label>
                                                        <input type="text" name="title" id="title" style="width:100%;" value="<?php echo stripslashes($title); ?>" />
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <label for="place">Place:</label>
                                                        <input type="text" name="place" id="place" style="width:100%;" value="<?php echo stripslashes($place); ?>" />
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <label for="date">Date Range:</label>
                                                        <span class="input-daterange">
															<input type="text" name="filter_from" placeholder="From:" value="<?php echo $from_date; ?>" class="datepicker "  style="width:200px" >
															<input type="text" name="filter_to" placeholder="To:" value="<?php echo $to_date; ?>" class="datepicker "  style="width:200px" >
				                                        </span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <label for="supported_by">Supported By:</label>
                                                        <input type="text" name="supported_by" id="supported_by" style="width:100%;" value="<?php echo stripslashes($supported_by); ?>" />
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <label for="funded_by">Funded By:</label>
                                                        <input type="text" name="funded_by" id="funded_by" style="width:100%;" value="<?php echo stripslashes($funded_by); ?>" />
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <label for="description">Description:</label> 
                                                        <textarea name="description" class="ckeditor"><?php echo ((isset($description) && $description!='')?$description:''); ?></textarea>
                                                    </td>
                                                </tr>
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
														<div class="form-group media-file"><div class="thumb_preview"><img src="<?php echo SITE_PATH.$pathObj->media_library_files_path.$featured_img; ?>" alt=""><a href="class_action.php?action=remove_featured_img&key=project_id&id=<?php echo $project_id; ?>&table=project&page=project" class="button1 red delete" onclick="return confirm('Are you sure to remove this Project Image?');"><i class="fa fa-remove"></i> Remove</a></div><input type="hidden" name="featured_img" value="<?php echo $featured_img; ?>"></div>
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
                                                        <label for="link_name">Link Name:</label>
                                                        <input type="text" name="link_name" id="link_name" style="width:100%;" value="<?php echo stripslashes($link_name); ?>" />
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <label for="link_url" style="float:left; padding-right:50px;">Link URL:</label>
                                                        <label for="external_link" style="float:left;">External <input type="checkbox" name="external_link" id="external_link"<?php echo ($external_link==1)?' checked="checked"':''; ?> /></label>
                                                        <div class="clear"></div>
                                                        <input type="text" name="link_url" id="link_url" style="width:100%;" value="<?php echo $link_url; ?>" />
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <label for="status">Status</label>
                                                        <select name="status" id="status" class="chosen" >
                                                            <?php echo $utilityObj->getArray2List($utilityObj->status_array,$status); ?>
                                                        </select>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2"><button type="submit" name="submit" class="button1"  /><i aria-hidden="true" class="fa fa-pencil"></i> <?php echo $buttonName; ?></button> &nbsp; <a href="manage_project.php" class="button1 red"><i class="fa fa-ban"></i> Cancel</a></td>
                                                </tr>
                                            </table>
                                        </fieldset>
                                    </td>
                                </tr>
                            </table>
						</form>
<?php include_once("jscript.php"); ?>
<?php include_once("inc/footer.php");?>