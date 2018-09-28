<?php
/**
 * App initiated
 */
require_once('../init.php');
defined('BASE_PATH') OR exit('No direct script access allowed');

	if(isset($_REQUEST['init']) && $_REQUEST['init']==1) {
		if(isset($_SESSION['WHERE_ARRAYs'])) {
			unset($_SESSION['WHERE_ARRAYs']);
		}
		if(isset($_SESSION['THESPA_RPP']) && $_SESSION['THESPA_RPP']!='') {
			unset($_SESSION['THESPA_RPP']);
		}
	}
	if(isset($_REQUEST['rpp_input']) && $_REQUEST['rpp_input']!='') {
		$_SESSION['THESPA_RPP']=$_REQUEST['rpp_input'];
	}
	
	$WHERE='';
	$WHERE_ARRAY=array();
	
	if(isset($_POST['search'])) {
		if(isset($_POST['stitle']) && $_POST['stitle']!='') {
			$WHERE_ARRAY[].="a.title LIKE '%$_POST[stitle]%'";
		}
		if(isset($_POST['scategory']) && $_POST['scategory']!='') {
			$WHERE_ARRAY[].="a.post_category_id=$_POST[scategory]";
		}
		if(isset($_POST['status']) && $_POST['status']!='' && $_POST['status']!=-1) {
			$WHERE_ARRAY[].="a.status=$_POST[status]";
		}
		$_SESSION['WHERE_ARRAYs']=$WHERE_ARRAY;
	} else {
		$_SESSION['WHERE_ARRAYs']=array();
	}
		
	$WHERE=$utilityObj->array_implode_notnull($_SESSION['WHERE_ARRAYs'],'AND');
	
	if($WHERE!='')
		$WHERE="WHERE $WHERE AND a.post_category_id=b.post_category_id";
	else
		$WHERE="WHERE a.post_category_id=b.post_category_id";
	
	$sql="SELECT a.*, b.title as cat_title FROM $db->TB_post a, $db->TB_post_category b $WHERE ORDER BY cat_title ASC, published_time DESC";
	
	$query=mysqli_query($db->conn, $sql);
	$rows=@mysqli_num_rows($query);
	
	$pagination_settings_rec=$db->getSingleRec($db->TB_pagination_settings,"pagination_settings_id=1");
	
	$rpp=(isset($_SESSION['THESPA_RPP']) && $_SESSION['THESPA_RPP']!='')?$_SESSION['THESPA_RPP']:$pagination_settings_rec['rows_per_page'];
	$res=$paginationObj->paginate($rows,$rpp,$pagination_settings_rec['first_link_text'],$pagination_settings_rec['last_link_text'],$pagination_settings_rec['previous_link_text'],$pagination_settings_rec['next_link_text']);
	
	$sql=$sql.$res[1]; 
	$query=mysqli_query($db->conn, $sql);
	$numquery=@mysqli_num_rows($query);
	
	$total_records=($rows>0)?$rows:0;
	$moduleName='post';
	
	include_once("inc/header.php");
	include_once("inc/menu.php");
?>
						<h2 class="title <?php $utilityObj->title_color(); ?>"><i aria-hidden="true" class="fa fa-database"></i> Manage Posts</h2>
                        
                        <div class="top-bg">
                            <table cellspacing="0" cellpadding="0" class="filter manage" style="margin-top:0; width:100%">
                                <tr>
                                    <td>
                        				<form method="post" action="manage_posts.php">
                                        	<input type="text" name="stitle" placeholder="Post Title:" />
                                            <select name="scategory" id="scategory" class="chosen" data-placeholder="Filter by Category" style="width:150px">
                                            	<option value=""></option>
                                                <option value="">All</option>
                                                <?php echo $utilityObj->getOptions("SELECT post_category_id,title FROM $db->TB_post_category WHERE post_category_id IN (SELECT DISTINCT post_category_id FROM $db->TB_post)",1,0); ?>
                                            </select>
                                            <select name="status" id="status" class="chosen" data-placeholder="Filter by Status:" style="width:130px;">
                                            	<option value=""></option>
                                                <option value="">All</option>
                                                <?php echo $utilityObj->getArray2List($utilityObj->status_array,-1); ?>
                                            </select>
                                            <input type="submit" class="search-btn" name="search" value="Search" />
                                        </form>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    
                    	<?php include_once("inc/form_message.php"); ?>

						<?php include("inc/pagenavi.php"); ?>
						<table cellspacing="0" cellpadding="0" width="100%" class="table user">
							<tr>
								<th>S.No</th>
								<th>Title</th>
								<th>Category</th>
                                <th>Published</th>
                                <th>Status</th>
								<th>Actions</th>
							</tr>
<?php
	$i=1;
	if($numquery>0) {
		while($fetch=mysqli_fetch_array($query)) {
			extract($fetch);
?>
			
                            <tr  class="<?php if($i%2==0)echo "even";else echo "no"; ?>">
                                <td><?php echo $res[2]+$i; ?></td>
                                <td><?php echo $title; ?></td>
                                <td><?php echo $cat_title; ?></td>
                                <td><?php echo $published_time ?></td>
<?php include(ADMIN_BASE_PATH.'inc/statusActionCol.php'); ?>
                            </tr>
<?php
			$i++;
		}
	} else { ?>
                            <tr>
                                <td colspan="6">No records found.</td>
                            </tr>
<?php } ?>
					</table>
<?php include("inc/pagenavi.php"); ?>
<?php include_once("inc/footer.php");?>