<?php 
/**
 * App initiated
 */
require_once('../init.php');
defined('BASE_PATH') OR exit('No direct script access allowed');
		
	$WHERE='';
	$WHERE_ARRAY=array();
	
	if(isset($_REQUEST['init']) && $_REQUEST['init']==1) {
		if(isset($_SESSION['WHERE_ARRAYs'])) {
			unset($_SESSION['WHERE_ARRAYs']);
		}
		if(isset($_SESSION['THESPA_where_start_date'])) {
			unset($_SESSION['THESPA_where_start_date']);
		}
		if(isset($_SESSION['THESPA_where_end_date'])) {
			unset($_SESSION['THESPA_where_end_date']);
		}
		if(isset($_SESSION['THESPA_RPP']) && $_SESSION['THESPA_RPP']!='') {
			unset($_SESSION['THESPA_RPP']);
		}
	}
	
	if(isset($_REQUEST['rpp_input']) && $_REQUEST['rpp_input']!='') {
		$_SESSION['THESPA_RPP']=$_REQUEST['rpp_input'];
	} else if( isset($_REQUEST['status_search']) && isset($_REQUEST['user_search']) && isset($_REQUEST['submit_search']) ) {
		$status_search=(int)$_REQUEST['status_search'];
		$user_search=trim($_REQUEST['user_search']);
		
		if(isset($_REQUEST['status_search']) && $_REQUEST['status_search']!='') {
			$WHERE_ARRAY[].="status=$status_search";
		}
		if(isset($_REQUEST['user_search']) && $_REQUEST['user_search']!='') {
			$WHERE_ARRAY[].="user_name LIKE '%$user_search%'";
		}
		$_SESSION['WHERE_ARRAYs']=$WHERE_ARRAY;
	}
	
	if(isset($_SESSION['WHERE_ARRAYs']))
		$WHERE=$utilityObj->array_implode_notnull($_SESSION['WHERE_ARRAYs'],'AND');
	
	if($WHERE!='')
		$WHERE="WHERE $WHERE";
	
	$select_sql="SELECT *,if(status=1,'Active','In-Active') as status FROM $db->tablename_user $WHERE";
	//echo $select_sql;
	$selectuser=mysqli_query($db->conn, $select_sql);
	$rows=@mysqli_num_rows($selectuser);
	
	$pagination_settings_rec=$db->getSingleRec($db->TB_pagination_settings,"pagination_settings_id=1");
	
	$rpp=(isset($_SESSION['THESPA_RPP']) && $_SESSION['THESPA_RPP']!='')?$_SESSION['THESPA_RPP']:$pagination_settings_rec['rows_per_page'];
	$res=$paginationObj->paginate($rows,$rpp,$pagination_settings_rec['first_link_text'],$pagination_settings_rec['last_link_text'],$pagination_settings_rec['previous_link_text'],$pagination_settings_rec['next_link_text']);
	$sql=$select_sql.$res[1];
	$selectuser=mysqli_query($db->conn, $sql);
	$num_user=@mysqli_num_rows($selectuser);
	
	$total_records=($rows>0)?$rows:0;
	
	include_once("inc/header.php");
	include_once("inc/menu.php");
?>
                    <h2 class="title <?php $utilityObj->title_color(); ?>"><i aria-hidden="true" class="fa fa-lock"></i> Manage User Accounts</h2><br /><br />
                    <div class="top-bg">
                        <form method="post"  name="user_search_form" action="manage_users.php">
                            <table cellspacing="0" cellpadding="0" class="filter users" style="margin-top:0; width:100%">
                                <tr>
                                    <td width="30%">
                                        <select class="chosen" data-placeholder="Filter By Status:" name="status_search" style="width:205px">
                                            <option value=""></option>
                                            <option value="1">Active</option>
                                            <option value="0">InActive</option>
                                        </select>
                                    </td>
                                    <td width="70%">
                                         <input type="text" style="width:200px;" placeholder="Search Username:" name="user_search" /> 
                                         <input type="submit"  class="search-btn" value="Search" name="submit_search"  />
                                    </td>
                                </tr>
                            </table>
                        </form>
                    </div>
<?php include("inc/pagenavi.php"); ?>
                    <table cellspacing="0" cellpadding="0" width="100%" class="table user">
                        <tr>
                            <th width="5%">S.No</th>
                            <th width="20%">Name</th>
                            <th width="20%">Username</th>
                            <th width="26%">E-mail</th>
                            <th width="13%">Status</th>
                            <th width="16%">Actions</th>
                        </tr>
<?php
	if($num_user>0) {
		$s_no=$res[2]+1;
		while($fetch=mysqli_fetch_array($selectuser)) {
			extract($fetch);
			
			$randkey=rand();
			$user=$passwordObj->encrypt_old($user_id,$randkey);
			$userid=$passwordObj->encrypt($user,$randkey);
			
			$randkey1=rand();
			$user1=$passwordObj->encrypt_old($user_id,$randkey1);
			$userid1=$passwordObj->encrypt($user1,$randkey1);
?>
                        <tr class="<?php echo $utilityObj->evenClass(); ?>">
                            <td><?php echo $s_no; ?></td>
                            <td><?php echo ucfirst($full_name); ?></td>
                            <td><?php echo $user_name; ?></td>
                            <td><?php echo $email; ?></td>
							<td><a href="class_action.php?action=switchStatus&key=user_id&id=<?php echo $user_id; ?>&table=security&page=manage_users"><span class="<?php echo $status; ?>"><?php echo $status; ?></span></a></td>
                            <td><span class="desktop"><a href="edit_user.php?key=<?php echo $randkey; ?>&user=<?php echo $user; ?>&userid=<?php echo $userid;?>" class="edit"><i aria-hidden="true" class="fa fa-pencil"></i> Edit</a> <a href="ClassUser.php?action=deleteuser&key=<?php echo $randkey1; ?>&user=<?php echo $user1; ?>&userid=<?php echo $userid1;?>" class="delete" onclick="return confirm('Are you sure that you want to delete?');"><i aria-hidden="true" class="fa fa-trash"></i> Delete</a></span>
                                <span class="mobile"><a href="edit_user.php?key=<?php echo $randkey; ?>&user=<?php echo $user; ?>&userid=<?php echo $userid;?>" class="edit"><i aria-hidden="true" class="fa fa-pencil"></i></a> <a href="ClassUser.php?action=deleteuser&key=<?php echo $randkey1; ?>&user=<?php echo $user1; ?>&userid=<?php echo $userid1;?>" class="delete" onclick="return confirm('Are you sure that you want to delete?');"><i aria-hidden="true" class="fa fa-trash"></i></a></span>
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