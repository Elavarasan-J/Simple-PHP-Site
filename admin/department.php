<?php
/**
 * App initiated
 */
$uploadMethods='yes';
require_once('../init.php');
defined('BASE_PATH') OR exit('No direct script access allowed');

	$id=(isset($_GET['department_id']) && $_GET['department_id']!='')?(int)$_GET['department_id']:'';
	$department='';
	if(isset($_GET['department_id']) && $_GET['department_id']!='')	{
		$department=$db->getSingleRec($db->TB_department,"department_id=$id");
	}
	if($department==true && $id!='')	{
		extract($department);
		$heading='Edit Department';
		$headingdesc='This section allows you to edit Department.';
		$buttonName='<i aria-hidden="true" class="fa fa-refresh"></i> Update Department';
		$action="update&department=$id";
		
		$department_name=isset($_POST['department_name'])?trim($_POST['department_name']):$department_name;
		$full_privilege=(isset($_POST['full_privilege']) && $_POST['full_privilege']!='')?$_POST['full_privilege']:$full_privilege;
        $privileges=(isset($_POST['privileges']) && $_POST['privileges']!='')?$_POST['privileges']:$privileges;
		$status=isset($_POST['status'])?trim($_POST['status']):$status;
	} else {
		$heading='Add Department';
		$headingdesc='This section allows you to add a new Department.';
		$buttonName='<i aria-hidden="true" class="fa fa-plus"></i> Add Department';
		$action='add';
		
		$department_name=isset($_POST['department_name'])?trim($_POST['department_name']):'';
		$full_privilege=(isset($_POST['full_privilege']) && $_POST['full_privilege']!='')?$_POST['full_privilege']:0;
        $privileges=(isset($_POST['privileges']) && $_POST['privileges']!='')?$_POST['privileges']:1;
		$status=isset($_POST['status'])?trim($_POST['status']):'1';
	}
	
	$managePage="department.php";
	
	include_once("inc/header.php");
	include_once("inc/menu.php");
?>

<!-- page title -->
				<h2 class="title <?php $utilityObj->title_color(); ?>"><i aria-hidden="true" class="fa fa-navicon"></i> Manage Department</h2>
                <p>This section allows you to add a new department. The fields marked with <span class="req">*</span> are mandatory.</p>
                    
				<?php include_once('inc/form_message.php'); ?><br />

<!-- data table -->
					<form action="classDepartment.php?action=<?php echo ((isset($action))?$action:''); ?>" method="post" name="prod_departmentform" enctype="multipart/form-data">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="table1 dept-table auto" style="margin:0;">
                            <tr>
                                <td>
                                    <fieldset>
                                        <legend>Department Info</legend>
                                        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="table1 auto" style="margin:0;">
                                            <tr>
                                                <td class="<?php echo ((isset($department_name_error))?$department_name_error:''); ?>"><label for="department_name">Department Name: <span class="req">*</span></label><input type="text" class="c" value="<?php echo ((isset($department_name))?$department_name:''); ?>" name="department_name" id="department_name" style="width:100%"></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <label for="privileges" class="iblock">Privileges:</label> &nbsp; &nbsp; <label class="iblock"><input type="checkbox" name="full_privilege" value="1" id="full_privilege"<?php echo ($full_privilege==1)?' checked="checked"':''; ?> /> Full Privilege</label>
                                                    <select name="privileges[]" id="privileges" class="chosen" data-placeholder="Select Privileges" style="width:100%;" multiple="multiple">
                                                        <option value=""></option>
                                                        <?php $utilityObj->getOptionsList("SELECT * FROM $db->TB_admin_menu WHERE parent_menu_id='0' AND menu_status=1 ORDER BY menu_order_num ASC",2,0,$privileges); ?>
                                                    </select>
                                                </td>
                                            </tr>
                                        </table>
                                    </fieldset>
                                </td>
                                <td style="width:20px;">&nbsp;</td>
                                <td style="width:300px;">
                                    <fieldset>
                                        <legend>Settings</legend>
                                        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="table1 auto" style="margin:0;">
                                            <tr>
                                                <td><label for="status">Status:</label>
                                                    <select name="status" id="status" class="chosen" data-placeholder="Choose a Status" style="width:100px;">
                                                        <?php echo $utilityObj->getArray2List($utilityObj->status_array,$status); ?>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><button type="submit" class="button1" name="submit" style="margin:0;"><?php echo ((isset($buttonName))?$buttonName:''); ?></button><?php if($department==true && $id!='') { ?> &nbsp; <a href="<?php echo ((isset($managePage))?$managePage:''); ?>" class="button1 red"><i aria-hidden="true" class="fa fa-ban"></i>  Cancel</a><?php } ?></td>
                                            </tr>
                                        </table>
                                    </fieldset>
                                </td>
                            </tr>
                        </table>
                    </form>
<?php 
$sql="SELECT * FROM $db->TB_department ORDER BY department_name ASC";
$query=mysqli_query($db->conn, $sql);
$numquery=@mysqli_num_rows($query);
?>
					<p class="re">Total Records : <?php echo ((isset($numquery))?$numquery:''); ?></p>
                    <table cellspacing="0" cellpadding="0" width="100%" class="table department">
                    	<tr>
                            <th width="15%">S.No</th>
                            <th width="35%">Department Name</th>
                            <th width="15%">Full Privilege</th>
                            <th width="15%">Status</th>
                            <th width="20%">Actions</th>
                        </tr>
<?php
	if($numquery>0) {
		$s_no=1;
		while($fetch=mysqli_fetch_array($query)) {
			extract($fetch);
?>
							<tr class="<?php echo $utilityObj->evenClass(); ?>">
								<td><?php echo ((isset($s_no))?$s_no:''); ?></td>
                                <td><?php echo ((isset($department_name))?$department_name:''); ?></td>
                                <td><?php echo "<a href='classDepartment.php?action=switchStatus&department=$department_id&key=full_privilege'><span class='".$utilityObj->yesno_array[$full_privilege]."'>".$utilityObj->yesno_array[$full_privilege]."</span></a>"; ?></td>
								<td><?php echo "<a href='classDepartment.php?action=switchStatus&department=$department_id'><span class='".$utilityObj->status_array[$status]."'>".$utilityObj->status_array[$status]."</span></a>"; ?></td>
								<td><span class="desktop"><a href="department.php?department_id=<?php echo ((isset($department_id))?$department_id:''); ?>" class="edit"><i aria-hidden="true" class="fa fa-pencil"></i> Edit</a> <a href="classDepartment.php?action=delete&department_id=<?php echo ((isset($department_id))?$department_id:''); ?>" class="delete"   onclick="return confirm('Are you sure to delete this record?');"><i aria-hidden="true" class="fa fa-trash"></i> Delete</a></span>
                                	<span class="mobile"><a href="department.php?department_id=<?php echo ((isset($department_id))?$department_id:''); ?>" class="edit"><i aria-hidden="true" class="fa fa-pencil"></i></a> <a href="classDepartment.php?action=delete&department_id=<?php echo ((isset($department_id))?$department_id:''); ?>" class="delete" onclick="return confirm('Are you sure to delete this Department?');"><i aria-hidden="true" class="fa fa-trash"></i></a></span>
                                </td>
							</tr>
<?php
			$s_no++;
		}
	}
?>
					</table>
<!-- pagination -->
<?php //include_once("includes/pagenavi.php"); ?>
<?php include_once("inc/footer.php"); ?>