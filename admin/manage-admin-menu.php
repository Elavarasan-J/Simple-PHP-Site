<?php
/**
 * App initiated
 */
require_once('../init.php');
defined('BASE_PATH') OR exit('No direct script access allowed');

$id=(isset($_GET['menu']) && $_GET['menu']!='')?(int)$_GET['menu']:'';
$menu_items='';
if(isset($_GET['menu']) && $_GET['menu']!='')	{
	$menu_items=$db->getSingleRec($db->TB_admin_menu,"menu_id=$id");
}
if($menu_items==true && $id!='')	{
	extract($menu_items);
	$heading='Edit Menu';
	$headingdesc='This section allows you to edit Menu.';
	$buttonName='<i aria-hidden="true" class="fa fa-refresh"></i> Update Menu';
	$action="update_menu&menu=$id";
	$_SESSION['EDIT_MENU']=$id;

	$active_menu_id=$menu_id;

	$menu_name=isset($_POST['menu_name'])?trim($_POST['menu_name']):$menu_name;
	$menu_link=isset($_POST['menu_link'])?trim($_POST['menu_link']):$menu_link;
	$class_name=isset($_POST['class_name'])?trim($_POST['class_name']):$class_name;
	$active_page=isset($_POST['active_page'])?trim($_POST['active_page']):$active_page;
	$menu_order_num=isset($_POST['menu_order_num'])?trim($_POST['menu_order_num']):$menu_order_num;
	$menu_target=isset($_POST['menu_target'])?trim($_POST['menu_target']):$menu_target;
	$menu_status=isset($_POST['menu_status'])?trim($_POST['menu_status']):$menu_status;
} else {
	$heading='Add Menu';
	$headingdesc='This section allows you to add a new Menu.';
	$buttonName='<i aria-hidden="true" class="fa fa-plus"></i> Add Menu';
	$action='add_menu';
	$_SESSION['EDIT_MENU']='';

	$menu_name=isset($_POST['menu_name'])?trim($_POST['menu_name']):'';
	$menu_link=isset($_POST['menu_link'])?trim($_POST['menu_link']):'';
	$class_name=isset($_POST['class_name'])?trim($_POST['class_name']):'';
	$active_page=isset($_POST['active_page'])?trim($_POST['active_page']):'';
	$menu_order_num=isset($_POST['menu_order_num'])?trim($_POST['menu_order_num']):'';
	$menu_target=isset($_POST['menu_target'])?trim($_POST['menu_target']):'1';
	$menu_status=isset($_POST['menu_status'])?trim($_POST['menu_status']):'1';
}

$managePage="manage-admin-menu.php";

include_once("inc/header.php");
include_once("inc/menu.php");
?>

<!-- page title -->
				<h2 class="title <?php $utilityObj->title_color(); ?>"><i aria-hidden="true" class="fa fa-navicon"></i> Manage Menu</h2>
                <p>This section allows you to add a new page menu. The fields marked with <span class="req">*</span> are mandatory.</p>
                    
				<?php include_once('inc/form_message.php'); ?><br />

<!-- data table -->
					<form action="ClassAdminMenu.php?action=<?php echo $action; ?>" method="post" name="prod_menuform" enctype="multipart/form-data">
						<div class="imp">
                        	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table1 auto" style="margin:0;">
                            	<tr>
                                	<td>
                                    	<label for="parent_menu_id">Parent Menu Name:</label>
                                        <select name="parent_menu_id" id="parent_menu_id" class="chosen" data-placeholder="Select Parent Menu" style="width:363px;">
                                    		<option value=""></option>
                                            <option value="0">No Parent.</option>
											<?php $adminUtilityObj->getOptions3LevelMenu(((isset($active_menu_id))?$active_menu_id:''),2,0,((isset($parent_menu_id))?$parent_menu_id:'')); ?>
										</select>
                                    </td>
                                </tr>
                                <tr>
                                	<td class="<?php echo (isset($menu_name_error))?$menu_name_error:''; ?>"><label for="menu_name">Menu Name: <span class="req">*</span></label><input type="text" class="c" value="<?php echo $menu_name; ?>" name="menu_name" id="menu_name" style="width:350px"></td>
                                </tr>
                                <tr>
                                	<td class="<?php echo (isset($menu_link_error))?$menu_link_error:''; ?>"><label for="menu_link">Menu Link: <span class="req">*</span></label><input type="text" class="c" value="<?php echo $menu_link; ?>" name="menu_link" id="menu_link" style="width:350px"></td>
                                </tr>
                                <tr>
                                	<td><label for="class_name">Class Name:</label><input type="text" class="c" value="<?php echo $class_name; ?>" name="class_name" id="class_name" style="width:350px"></td>
                                </tr>
                                <tr>
                                	<td><label for="active_page">Active Page:</label><input type="text" class="c" value="<?php echo $active_page; ?>" name="active_page" id="active_page" style="width:350px" multiple="multiple"></td>
                                </tr>
                                <tr>
                                	<td><label for="menu_order_num">Menu Order#:</label><input type="number" step="any" min="1" class="c" value="<?php echo $menu_order_num; ?>" name="menu_order_num" id="menu_order_num" style="width:85px"></td>
                                </tr>
								<tr>
									<td><label for="menu_target">Target Window:</label>
                                		<select name="menu_target" id="menu_target" class="chosen" data-placeholder="Choose a Target" style="width:100px;">
                                			<?php echo $utilityObj->getArray2List($utilityObj->target_array,$menu_target); ?>
                                		</select>
                                	</td>
                            	</tr>
								<tr>
									<td><label for="menu_status">Status:</label>
                                		<select name="menu_status" id="menu_status" class="chosen" data-placeholder="Choose a Status" style="width:100px;">
                                			<?php echo $utilityObj->getArray2List($utilityObj->status_array,$menu_status); ?>
                                		</select>
                                	</td>
                            	</tr>
                                <tr>
                                	<td><button type="submit" class="button1" name="submit_navigation" style="margin:0;"><?php echo $buttonName; ?></button><?php if($menu_items==true && $id!='') { ?> &nbsp; <a href="<?php echo $managePage; ?>" class="button1 red"><i aria-hidden="true" class="fa fa-ban"></i>  Cancel</a><?php } ?></td>
                                </tr>
                        	</table>
						</div>
                    </form>
<?php
	$fields_arr=array("menu_id","menu_name","menu_link","class_name","active_page","menu_order_num","menu_target","menu_status");
	$query='SELECT
			p.menu_id as parent_id,
			p.menu_name as parent_title,
			p.menu_link as parent_link,
			p.class_name as parent_class,
			p.active_page as parent_active,
			p.menu_order_num as parent_orderNum,
			p.menu_target as parent_target,
			p.menu_status as parent_status,

			c1.menu_id as child_1_id,
			c1.menu_name as child_1_title,
			c1.menu_link as child_1_link,
			c1.class_name as child_1_class,
			c1.active_page as child_1_active,
			c1.menu_order_num as child_1_orderNum,
			c1.menu_target as child_1_target,
			c1.menu_status as child_1_status,

			c2.menu_id as child_2_id,
			c2.menu_name as child_2_title,
			c2.menu_link as child_2_link,
			c2.class_name as child_2_class,
			c2.active_page as child_2_active,
			c2.menu_order_num as child_2_orderNum,
			c2.menu_target as child_2_target,
			c2.menu_status as child_2_status,

			c3.menu_id as child_3_id,
			c3.menu_name as child_3_title,
			c3.menu_link as child_3_link,
			c3.class_name as child_3_class,
			c3.active_page as child_3_active,
			c3.menu_order_num as child_3_orderNum,
			c3.menu_target as child_3_target,
			c3.menu_status as child_3_status
		FROM 
			myrtle_admin_menu p
		LEFT JOIN myrtle_admin_menu c1
			ON c1.parent_menu_id = p.menu_id
		LEFT JOIN myrtle_admin_menu c2
			ON c2.parent_menu_id = c1.menu_id
		LEFT JOIN myrtle_admin_menu c3
			ON c3.parent_menu_id = c2.menu_id
		WHERE
			p.parent_menu_id=0
		ORDER BY p.menu_order_num ASC, c1.menu_order_num ASC, c2.menu_order_num ASC, c3.menu_order_num ASC
	';

	$menuArr=$db->getMultipleRec($query);

	//$utilityObj->printAny($menuArr); exit;
	$menuSortedArr=array();
	if(is_array($menuArr) && count($menuArr)>0) {
		foreach($menuArr as $key=>$arr) {
		//	$menuSortedArr[$arr['parent_id']]['level']=0;
			$menuSortedArr[$arr['parent_id']]['id']=$arr['parent_id'];
			$menuSortedArr[$arr['parent_id']]['title']=$arr['parent_title'];
			$menuSortedArr[$arr['parent_id']]['link']=$arr['parent_link'];
			$menuSortedArr[$arr['parent_id']]['class']=$arr['parent_class'];
			$menuSortedArr[$arr['parent_id']]['active']=$arr['parent_active'];
			$menuSortedArr[$arr['parent_id']]['orderNum']=$arr['parent_orderNum'];
			$menuSortedArr[$arr['parent_id']]['target']=$arr['parent_target'];
			$menuSortedArr[$arr['parent_id']]['status']=$arr['parent_status'];

			if(isset($arr['child_1_id']) && $arr['child_1_id']!='') {
				$menuSortedArr[$arr['parent_id']]['children'][$arr['child_1_id']]['id']=$arr['child_1_id'];
				$menuSortedArr[$arr['parent_id']]['children'][$arr['child_1_id']]['title']=$arr['child_1_title'];
				$menuSortedArr[$arr['parent_id']]['children'][$arr['child_1_id']]['link']=$arr['child_1_link'];
				$menuSortedArr[$arr['parent_id']]['children'][$arr['child_1_id']]['class']=$arr['child_1_class'];
				$menuSortedArr[$arr['parent_id']]['children'][$arr['child_1_id']]['active']=$arr['child_1_active'];
				$menuSortedArr[$arr['parent_id']]['children'][$arr['child_1_id']]['orderNum']=$arr['child_1_orderNum'];
				$menuSortedArr[$arr['parent_id']]['children'][$arr['child_1_id']]['target']=$arr['child_1_target'];
				$menuSortedArr[$arr['parent_id']]['children'][$arr['child_1_id']]['status']=$arr['child_1_status'];
			}
			if(isset($arr['child_2_id']) && $arr['child_2_id']!='') {
				$menuSortedArr[$arr['parent_id']]['children'][$arr['child_1_id']]['children'][$arr['child_2_id']]['id']=$arr['child_2_id'];
				$menuSortedArr[$arr['parent_id']]['children'][$arr['child_1_id']]['children'][$arr['child_2_id']]['title']=$arr['child_2_title'];
				$menuSortedArr[$arr['parent_id']]['children'][$arr['child_1_id']]['children'][$arr['child_2_id']]['link']=$arr['child_2_link'];
				$menuSortedArr[$arr['parent_id']]['children'][$arr['child_1_id']]['children'][$arr['child_2_id']]['class']=$arr['child_2_class'];
				$menuSortedArr[$arr['parent_id']]['children'][$arr['child_1_id']]['children'][$arr['child_2_id']]['active']=$arr['child_2_active'];
				$menuSortedArr[$arr['parent_id']]['children'][$arr['child_1_id']]['children'][$arr['child_2_id']]['orderNum']=$arr['child_2_orderNum'];
				$menuSortedArr[$arr['parent_id']]['children'][$arr['child_1_id']]['children'][$arr['child_2_id']]['target']=$arr['child_2_target'];
				$menuSortedArr[$arr['parent_id']]['children'][$arr['child_1_id']]['children'][$arr['child_2_id']]['status']=$arr['child_2_status'];
			}
			if(isset($arr['child_3_id']) && $arr['child_3_id']!='') {
				$menuSortedArr[$arr['parent_id']]['children'][$arr['child_1_id']]['children'][$arr['child_2_id']]['children'][$arr['child_3_id']]['id']=$arr['child_3_id'];
				$menuSortedArr[$arr['parent_id']]['children'][$arr['child_1_id']]['children'][$arr['child_2_id']]['children'][$arr['child_3_id']]['title']=$arr['child_3_title'];
				$menuSortedArr[$arr['parent_id']]['children'][$arr['child_1_id']]['children'][$arr['child_2_id']]['children'][$arr['child_3_id']]['link']=$arr['child_3_link'];
				$menuSortedArr[$arr['parent_id']]['children'][$arr['child_1_id']]['children'][$arr['child_2_id']]['children'][$arr['child_3_id']]['class']=$arr['child_3_class'];
				$menuSortedArr[$arr['parent_id']]['children'][$arr['child_1_id']]['children'][$arr['child_2_id']]['children'][$arr['child_3_id']]['active']=$arr['child_3_active'];
				$menuSortedArr[$arr['parent_id']]['children'][$arr['child_1_id']]['children'][$arr['child_2_id']]['children'][$arr['child_3_id']]['orderNum']=$arr['child_3_orderNum'];
				$menuSortedArr[$arr['parent_id']]['children'][$arr['child_1_id']]['children'][$arr['child_2_id']]['children'][$arr['child_3_id']]['target']=$arr['child_3_target'];
				$menuSortedArr[$arr['parent_id']]['children'][$arr['child_1_id']]['children'][$arr['child_2_id']]['children'][$arr['child_3_id']]['status']=$arr['child_3_status'];
			}
		}
	//	$utilityObj->printAny($menuSortedArr);
	}
	
	$rows=$utilityObj->multiLevelTr($menuSortedArr);
	echo '<p class="re">Total Records : '.substr_count($rows, "<tr>").'</p>
			<table cellspacing="0" cellpadding="0" width="100%" class="table table-striped table-hover">
				<tr>
					<th>S.No</th>
					<th>Menu Name</th>
					<th>Menu Link</th>
					<th>Active Page</th>
					<th>Order Number</th>
					<th>Status</th>
					<th>Actions</th>
				</tr>
				'.$rows.'
			</table>';
include_once("inc/footer.php");
?>