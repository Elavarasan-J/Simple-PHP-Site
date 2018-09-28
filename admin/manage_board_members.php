<?php
/**
 * App initiated
 */
$uploadMethods='yes';
require_once('../init.php');
defined('BASE_PATH') OR exit('No direct script access allowed');

if(isset($_REQUEST['init']) && $_REQUEST['init']==1) {
	if(isset($_SESSION['WHERE_ARRAYs'])) {
		unset($_SESSION['WHERE_ARRAYs']);
	}
	if(isset($_SESSION['DSS_RPP']) && $_SESSION['DSS_RPP']!='') {
		unset($_SESSION['DSS_RPP']);
	}
}
	
$WHERE='';
$WHERE_ARRAY=array();

if(isset($_REQUEST['rpp_input']) && $_REQUEST['rpp_input']!='') {
	$_SESSION['DSS_RPP']=$_REQUEST['rpp_input'];
} else if(isset($_REQUEST['filter']) || isset($_REQUEST['search'])) {
	if(isset($_REQUEST['status']) && $_REQUEST['filter'] && $_REQUEST['status']!='') {
		$status=$_REQUEST['status'];
		$WHERE_ARRAY[]="status=$status";
	}
	if(isset($_REQUEST['title']) && $_REQUEST['filter'] && $_REQUEST['title']!='') {
		$title=$_REQUEST['title'];
		$WHERE_ARRAY[]="title LIKE '%$title%'";
	}
	if(isset($_REQUEST['board_member_id']) && $_REQUEST['search'] && $_REQUEST['board_member_id']!='') {
		$board_member_id=$_REQUEST['board_member_id'];
		$WHERE_ARRAY[]="board_member_id LIKE '%$board_member_id%'";
	}
	$_SESSION['WHERE_ARRAYs']=$WHERE_ARRAY;
}
$where_arr_session=((isset($_SESSION['WHERE_ARRAYs']) && is_array($_SESSION['WHERE_ARRAYs']) && count($_SESSION['WHERE_ARRAYs'])>0)?$_SESSION['WHERE_ARRAYs']:array());
$WHERE=$utilityObj->array_implode_notnull($where_arr_session,'AND');
	
if($WHERE!='')
	$WHERE="WHERE $WHERE";

$sql="SELECT board_member_id,board_type,title,sort_order,featured_img,if(status=1,'Active','In-Active') as status FROM $db->TB_board_members $WHERE ORDER BY board_type ASC, sort_order ASC";
//echo $sql;	
$query=mysqli_query($db->conn, $sql);
$num_sql=@mysqli_num_rows($query);
	
$pagination_settings_rec=$db->getSingleRec($db->TB_pagination_settings,"pagination_settings_id=1");
$rpp=(isset($_SESSION['DSS_RPP']) && $_SESSION['DSS_RPP']!='')?$_SESSION['DSS_RPP']:$pagination_settings_rec['rows_per_page'];
$res=$paginationObj->paginate($num_sql,$rpp,$pagination_settings_rec['first_link_text'],$pagination_settings_rec['last_link_text'],$pagination_settings_rec['previous_link_text'],$pagination_settings_rec['next_link_text']);

$sql=$sql.$res[1]; 
$query=mysqli_query($db->conn, $sql);
$numquery=@mysqli_num_rows($query);
	
$total_records=($num_sql>0)?$num_sql:0;

include_once("inc/header.php");
include_once("inc/menu.php");
?>

<!-- page title -->
					<h2 class="title orange"><i class="fa fa-photo"></i> Manage Committee</h2>
                    
					<?php include_once('inc/form_message.php'); ?>

<!-- manage options -->
					<div class="top-bg">
                        <table cellspacing="0" cellpadding="0" class="filter customers" style="margin-top:0; width:100%">
                            <tr>
                                <td width="30%">
                        			<form method="post" action="manage_board_members.php" style="display:inline;">
                                        <input type="text" name="title" placeholder="Title:" style="width:150px;"/>
                                        <select name="status" class="chosen" data-placeholder="Filter By Status">
                                            <option value=""></option>
                                            <option value="">All</option>
                                            <?php echo $utilityObj->getArray2List($utilityObj->status_array,1); ?>
                                        </select>
                                        <input type="submit" value="Filter" name="filter" class="filter-btn" /> &nbsp; &nbsp; &nbsp;
                        			</form>
                                  </td>
                                  <td width="70%">
                        			<form method="post" action="manage_board_members.php" style="display:inline;">
                                        <input type="text" name="board_member_id" placeholder="Management Committee ID:" style="width:200px;"/>
                                        <input type="submit" value="Search" name="search" class="search-btn" />
                        			</form>
                                </td>
                            </tr>
                        </table>
                  </div> 
					                          
<!-- Add Customers -->
<?php include("inc/pagenavi.php"); ?>
                    <table cellspacing="0" cellpadding="0" width="100%" class="table">
                        <tr>
                        	<th width="7%">S.No</th>
                            <th width="15%">Board Type</th>
                            <th width="22%">Title</th>
                            <th width="12%">Thumb</th>
                            <th width="9%">Sort Order</th>
                            <th width="10%">Status</th>
                            <th width="15%">Actions</th>
                        </tr>
							
<?php
	if($numquery>0) {
		$s_no=$res[2]+1;
		while($fetch=mysqli_fetch_array($query)) {
			extract($fetch);
			$img_file=BASE_PATH.$pathObj->media_library_files_path.$featured_img;
			$img_path=SITE_PATH.$pathObj->media_library_files_path.$featured_img;
			
			$thumb='';
			if(is_file($img_file)) {
				$thumb='<img src="'.$img_path.'" alt="" />';
			}
?>
                        <tr class="<?php echo $utilityObj->evenClass(); ?>">
                        	<td><?php echo $s_no; ?></td>
							<td><?php echo $utilityObj->boardType_arr[$board_type]; ?></td>
                            <td><?php echo $title; ?></td>
                            <td><?php echo $thumb; ?></td>
                            <td><?php echo $sort_order; ?></td>
                            <td><?php echo "<span class='$status'>".$status."</span>"; ?></td>
                            <td><span class="desktop"><a href="board_members.php?board_member_id=<?php echo $board_member_id; ?>" class="edit"><i class="fa fa-pencil"></i> Edit</a><a href="class_board_members.php?action=delete_board_member&board_member_id=<?php echo $board_member_id; ?>" class="delete" onclick="return confirm('Are you sure that you want to delete?');"><i class="fa fa-trash"></i> Delete</a></span>
                            	<span class="mobile"><a href="board_members.php?board_member_id=<?php echo $board_member_id; ?>" class="edit"><i class="fa fa-pencil"></i></a><a href="class_board_members.php?action=delete_board_member&board_member_id=<?php echo $board_member_id; ?>" class="delete" onclick="return confirm('Are you sure that you want to delete?');"><i class="fa fa-trash"></i></a></span>
                            </td>
                        </tr>
<?php
			$s_no++;
		}
	}
?>	
							
						</table>
<!-- pagination -->
<?php
	include("inc/pagenavi.php");
	include_once("inc/footer.php");
?>