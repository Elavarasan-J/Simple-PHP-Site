<?php
/**
 * App initiated
 */
require_once('../init.php');
defined('BASE_PATH') OR exit('No direct script access allowed');

$editor = 'editor';
$colorbox = 'colorbox';
$slugedit = 'slugedit';
$accordions=1;
	
$id=(isset($_GET["post"]) && $_REQUEST["post"]!="")?(int)$_REQUEST["post"]:"";
$formsrequest='';
if(isset($_REQUEST["post"]) && $_REQUEST["post"]!="")	{
	$formsrequest=$db->getSingleRec($db->TB_post ,"post_id=$id");
}
if($formsrequest==true && $id!=NULL) {
	extract($formsrequest);
	$buttonName='<i aria-hidden="true" class="fa fa-refresh"></i> Update Post';
	$action='class_post.php?action=update&post='.$id;
	$heading='<i aria-hidden="true" class="fa fa-pencil"></i> Edit Post';
	$parent_where=" WHERE post_id!=$id AND status=1";
	
	$post_category_id=(isset($_POST["post_category_id"]) && trim($_POST["post_category_id"])!="")?trim($_POST["post_category_id"]):$post_category_id;
	$title=(isset($_POST["title"]) && trim($_POST["title"])!="")?trim($_POST["title"]):$title;
	$slug=(isset($_POST["slug"]) && trim($_POST["slug"])!="")?trim($_POST["slug"]):$slug;
	$short_description=(isset($_POST["short_description"]) && trim($_POST["short_description"])!="")?html_entity_decode(trim($_POST["short_description"])):$short_description;
	$description=(isset($_POST["description"]) && trim($_POST["description"])!="")?html_entity_decode(trim($_POST["description"])):$description;
	$published_time=isset($_POST['published_time'])?trim($_POST['published_time']):$published_time;
	$status=(isset($_POST["status"]) && trim($_POST["status"])!="")?trim($_POST["status"]):$status;
} else {
	$buttonName='<i aria-hidden="true" class="fa fa-plus-square"></i> Add Post';
	$action='class_post.php?action=add';
	$heading='<i aria-hidden="true" class="fa fa-plus-square"></i> Add Post';
	$parent_where=" WHERE status=1";
	
	$post_category_id=(isset($_POST["post_category_id"]) && trim($_POST["post_category_id"])!="")?trim($_POST["post_category_id"]):"";
	$title=(isset($_POST["title"]) && trim($_POST["title"])!="")?trim($_POST["title"]):"";
	$slug=(isset($_POST["slug"]) && trim($_POST["slug"])!="")?trim($_POST["slug"]):"";
	$short_description=(isset($_POST["short_description"]) && trim($_POST["short_description"])!="")?html_entity_decode(trim($_POST["short_description"])):"";
	$description=(isset($_POST["description"]) && trim($_POST["description"])!="")?html_entity_decode(trim($_POST["description"])):"";
	$published_time=isset($_POST['published_time'])?trim($_POST['published_time']):'';
	$status=(isset($_POST["status"]) && trim($_POST["status"])!="")?trim($_POST["status"]):0;
}
	
	$published_arr=$timeObj->split_time($published_time);

include_once("inc/header.php");
include_once("inc/menu.php");
//printR($_POST,0,0);
$templatesArr=$utilityObj->getTemplatesForOpts();
?>
                        <h2 class="title <?php $utilityObj->title_color(); ?>"><?php echo ((isset($heading) && $heading!='')?$heading:''); ?><small class="right"><span class="req">*</span> required fields.</small></h2>
                        
                                
                        <?php include_once("inc/form_message.php"); ?><br />
                        <form action="<?php echo ((isset($action) && $action!='')?$action:''); ?>" method="post" name="form" enctype="multipart/form-data">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="table1 auto" style="margin:0;">
                                <tr>
                                    <td>
                                        <fieldset>
                                            <legend>Info</legend>
                                            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="table1 auto" style="margin:0;">
                                                <tr>
                                                    <td class="<?php echo ((isset($title_error) && $title_error!='')?$title_error:''); ?>">
                                                        <label for="title" class="iblock"><span class="req">*</span> Title: <i class="fa fa-spinner fa-spin" id="slug_imgloader" style="display:none;font-size:15px;line-height:15px;vertical-align:top;animation-duration:1s;"></i></label>
                                                    <?php if($slug!='') { ?>
                                                        <label for="slug" class="iblock">Slug:</label>
                                                        <span class="tags" id="slug_tag"><?php echo ((isset($slug) && $slug!='')?$slug:''); ?></span>
                                                        <input type="text" name="slug" id="slug" value="<?php echo ((isset($slug) && $slug!='')?$slug:''); ?>" style="display:none;margin-right:5px;line-height:18px;padding:1px;vertical-align:middle;" />
                                                        <label class="iblock" id="edit_cancel"><input type="checkbox" name="edit_slug" id="edit_slug" style="display:none;" /> <a style="text-decoration:underline;cursor:pointer;">Edit</a></label>
                                                    <?php } ?>
                                                        <input type="text" name="title" id="title" style="width:100%;" value="<?php echo ((isset($title) && $title!='')?$title:''); ?>" onblur="updateSlug(this.value,'title');" />
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <label for="short_description">Short Description:</label>
                                                        <textarea name="short_description" id="short_description" style="width:100%;"><?php echo ((isset($short_description) && $short_description!='')?$short_description:''); ?></textarea>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <label for="description">Description:</label>
														<a href="media_files.php?field_name=description" class="btn btn-primary" data-toggle="modal" data-target="#modal1"><i class="fa fa-image"></i> Select Media File </a><div class="modal fade" id="modal1" tabindex="-1" role="dialog" aria-labelledby="modalLabel1"></div>
                                                        <textarea name="description" class="ckeditor" style="width:650px;"><?php echo ((isset($description) && $description!='')?$description:''); ?></textarea>
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
                                                    <td class="<?php echo ((isset($post_category_id_error) && $post_category_id_error!='')?$post_category_id_error:''); ?>">
                                                        <label for="post_category_id">Category:</label>
                                                        <select name="post_category_id" id="post_category_id" class="chosen" data-placeholder="Select Post" style="width:250px;">
                                                            <option value=""></option>
                                                            <option value="">-- No Category --</option>
                                                            <?php $utilityObj->getOptionsList("SELECT * FROM $db->TB_post_category WHERE status=1",1,0,$post_category_id); ?>
                                                        </select>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><label for="published_time">Published Time:<br /><small>(YYYY-MM-DD hh:mm:ss)</small></label><input type="text" value="<?php echo ((isset($published_time) && $published_time!='')?$published_time:''); ?>" name="published_time" id="published_time"></td>
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
                                                echo 'Status: <strong>'.$utilityObj->status_array[$status].'</strong><input type="hidden" value="'.$status.'" name="status" id="status">';
                                            }
                                        ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><button type="submit" name="submit" class="button1"  /><?php echo ((isset($buttonName) && $buttonName!='')?$buttonName:''); ?></button> &nbsp; <a href="manage_posts.php" class="button1 red"><i class="fa fa-ban"></i> Cancel</a></td>
                                                </tr>
                                            </table>
                                        </fieldset>
                                    </td>
                                </tr>
                            </table>
                        </form>
<?php if(isset($skincon) && $skincon && $id!='') { ?>
	<div class="notes"><?php echo userEditActivity($db->TB_track_detail,"table_name='$db->TB_skin_condition' AND primary_key_id=$id"); ?></div>
<?php } ?>
<?php include_once("jscript.php"); ?>
<?php include_once("inc/footer.php");?>