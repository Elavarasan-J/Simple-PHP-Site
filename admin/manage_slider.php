<?php
/**
 * App initiated
 */
require_once('../init.php');
defined('BASE_PATH') OR exit('No direct script access allowed');

	$colorbox = 'colorbox';
	
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
			$WHERE_ARRAY[].="title LIKE '%$_POST[stitle]%'";
		}
		if(isset($_POST['status']) && $_POST['status']!='' && $_POST['status']!=-1) {
			$WHERE_ARRAY[].="status=$_POST[status]";
		}
		$_SESSION['WHERE_ARRAYs']=$WHERE_ARRAY;
	} else {
		$_SESSION['WHERE_ARRAYs']=array();
	}
		
	$WHERE=$utilityObj->array_implode_notnull($_SESSION['WHERE_ARRAYs'],'AND');
	
	if($WHERE!='')
		$WHERE="WHERE $WHERE";
	
	$sql="SELECT * FROM $db->TB_slider $WHERE ORDER BY sort_order ASC";
	
	$query=mysqli_query($db->conn, $sql);
	$rows=@mysqli_num_rows($query);
	
	$pagination_settings_rec=$db->getSingleRec($db->TB_pagination_settings,"pagination_settings_id=1");
	
	$rpp=(isset($_SESSION['THESPA_RPP']) && $_SESSION['THESPA_RPP']!='')?$_SESSION['THESPA_RPP']:$pagination_settings_rec['rows_per_page'];
	$res=$paginationObj->paginate($rows,$rpp,$pagination_settings_rec['first_link_text'],$pagination_settings_rec['last_link_text'],$pagination_settings_rec['previous_link_text'],$pagination_settings_rec['next_link_text']);
	
	$sql=$sql.$res[1]; 
	$query=mysqli_query($db->conn, $sql);
	$numquery=@mysqli_num_rows($query);
	
	$total_records=($rows>0)?$rows:0;
	$moduleName='slider';
	
	include_once("inc/header.php");
	include_once("inc/menu.php");
?>
						<h2 class="title <?php $utilityObj->title_color(); ?>"><i aria-hidden="true" class="fa fa-database"></i> Manage Slider</h2>
                        
                        <div class="top-bg">
                            <table cellspacing="0" cellpadding="0" class="filter manage" style="margin-top:0; width:100%">
                                <tr>
                                    <td>
                        				<form method="post" action="manage_slider.php">
                                        	<input type="text" name="stitle" placeholder="Title:" />
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
								<th width="5%">S.No</th>
								<th width="25%">Title</th>
								<th width="25%">Slider Image</th>
								<th width="10%" style="text-align:center;">Sort Order #</th>
                                <th width="15%">Status</th>
								<th width="20%">Actions</th>
							</tr>
<?php
	if($numquery>0) {
		$s_no=$res[2]+1;
		while($fetch=mysqli_fetch_array($query)) {
			extract($fetch);

			$thumb_file=BASE_PATH.$pathObj->slider_img_path.$slider_thumb;
			$thumb_url=SITE_PATH.$pathObj->slider_img_path.$slider_thumb;
			$img_file=BASE_PATH.$pathObj->slider_img_path.$slider_img;
			$img_url=SITE_PATH.$pathObj->slider_img_path.$slider_img;
			$thumb='';
			if(is_file($thumb_file) && is_file($img_file)) {
				$thumb='<div class="thumb_preview"><a href="'.$img_url.'" class="group1"><img src="'.$thumb_url.'" alt="" /></a></div>';
			}
?>
							<tr class="<?php echo $utilityObj->evenClass(); ?>">
								<td><?php echo $s_no; ?></td>
								<td><?php echo $title; ?></td>
								<td><?php echo $thumb; ?></td>
                                <td style="text-align:center;"><?php echo $sort_order; ?></td>
<?php include(ADMIN_BASE_PATH.'inc/statusActionCol.php'); ?>
							</tr>
<?php
			$s_no++;
		}
	}
?>	
						</table>
<?php include("inc/pagenavi.php"); ?>
<?php include_once("inc/footer.php");?>
