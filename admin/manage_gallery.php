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
			$WHERE_ARRAY[].="title LIKE '%".$_POST['stitle']."%'";
		}
		if(isset($_POST['sstatus']) && $_POST['sstatus']!='' && $_POST['sstatus']!=-1) {
			$WHERE_ARRAY[].="status=$_POST[sstatus]";
		}
		$_SESSION['WHERE_ARRAYs']=$WHERE_ARRAY;
	} else {
		$_SESSION['WHERE_ARRAYs']=array();
	}
		
	$WHERE=$utilityObj->array_implode_notnull($_SESSION['WHERE_ARRAYs'],'AND');
	
	if($WHERE!='')
		$WHERE="WHERE $WHERE";
	
	$sql="SELECT * FROM $db->TB_gallery $WHERE ORDER BY title";
		
	$query=mysqli_query($db->conn, $sql);
	$rows=@mysqli_num_rows($query);
	
	$pagination_settings_rec=$db->getSingleRec($db->TB_pagination_settings,"pagination_settings_id=1");
	
	$rpp=(isset($_SESSION['THESPA_RPP']) && $_SESSION['THESPA_RPP']!='')?$_SESSION['THESPA_RPP']:$pagination_settings_rec['rows_per_page'];
	$res=$paginationObj->paginate($rows,$rpp,$pagination_settings_rec['first_link_text'],$pagination_settings_rec['last_link_text'],$pagination_settings_rec['previous_link_text'],$pagination_settings_rec['next_link_text']);
	
	$sql=$sql.$res[1]; 
	$query=mysqli_query($db->conn, $sql);
	$numquery=@mysqli_num_rows($query);
	
	$total_records=($rows>0)?$rows:0;
	
	include_once("inc/header.php");
	include_once("inc/menu.php");
?>
						<h2 class="title <?php $utilityObj->title_color(); ?>"><i aria-hidden="true" class="fa fa-database"></i> Manage Accordions</h2>
					<?php  ?>
                        <div class="top-bg">
                            <table cellspacing="0" cellpadding="0" class="filter manage" style="margin-top:0; width:100%">
                                <tr>
                                    <td>
                        				<form method="post" action="manage_page.php">
                                        	<input type="text" name="stitle" placeholder="Gallery Title:" />
                                            <select name="sstatus" id="status" class="chosen" data-placeholder="Filter by Status:" style="width:130px;">
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
					<?php  ?>
                    
                    	<?php include_once("inc/form_message.php"); ?>

						<?php //include("inc/pagenavi.php"); ?>
						<table cellspacing="0" cellpadding="0" width="100%" class="table user manage-page-table">
							<tr>
								<th>S.No</th>
								<th>Title</th>
								<th>Actions</th>
							</tr>
<?php
	$i=1;
	if($numquery>0) {
		while($fetch=mysqli_fetch_array($query)) {
			extract($fetch);
?>
                            <tr class="<?php if($i%2==0)echo "even";else echo "no"; ?>">
                                <td><?php echo $res[2]+$i; ?></td>
                                <td><?php echo $title; ?></td>
								<td>
                                	<span class="desktop"><a href="gallery.php?gallery=<?php echo $gallery_id; ?>" class="edit"><i aria-hidden="true" class="fa fa-pencil"></i> Edit</a></span>
									<span class="mobile"><a href="gallery.php?gallery=<?php echo $gallery_id; ?>" class="edit"><i aria-hidden="true" class="fa fa-pencil"></i></a></span>
                                </td>
                            </tr>
<?php
			$i++;
		}
	} else { ?>
                            <tr>
                                <td colspan="6">No records found.</td>
                            </tr>
<?php } ?>
							<?php //echo $resTR; ?>
						</table>
<?php //include("inc/pagenavi.php"); ?>
<!-- start footer -->
<?php include_once("inc/footer.php");?>
<!-- end footer -->

		