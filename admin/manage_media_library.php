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
	
	$sql="SELECT * FROM $db->TB_media_library $WHERE ORDER BY title ASC";
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
						<h2 class="title <?php $utilityObj->title_color(); ?>"><i aria-hidden="true" class="fa fa-database"></i> Manage Slider</h2>
                        
                        <div class="top-bg">
                            <table cellspacing="0" cellpadding="0" class="filter manage" style="margin-top:0; width:100%">
                                <tr>
                                    <td>
                        				<form method="post" action="manage_media_library.php">
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
								<th width="8%">S.No</th>
								<th width="25%">Title</th>
                            	<th width="12%">Media Preview</th>
								<th width="25%">Copy Link</th>
                                <th width="15%">Status</th>
								<th width="15%">Actions</th>
							</tr>
<?php
	if($numquery>0) {
		$s_no=$res[2]+1;
		while($fetch=mysqli_fetch_array($query)) {
			extract($fetch);
			$media_file=BASE_PATH.$pathObj->media_library_thumbphotos_path.$file_name;
			$media_path=SITE_PATH.$pathObj->media_library_thumbphotos_path.$file_name;
			$medium_path=SITE_PATH.$pathObj->media_library_mediumphotos_path.$file_name;
			$original_path=SITE_PATH.$pathObj->media_library_files_path.$file_name;
			if(isset($file_type) && $file_type!='' && ($file_type=='image/jpeg' || $file_type=='image/png' || $file_type=='image/gif')) {
				
				$thumb='';
				if(is_file($media_file)) {
					$thumb='<img src="'.$media_path.'" alt="" />';
				}
				$link='
					<input type="text" value="'.$media_path.'" style="margin-right:0;margin-bottom:3px;"><a class="button1 green copyPrevText" style="width:90px;text-align:center;margin-bottom:3px;"><i class="fa fa-copy"></i> Thumb</a>
					<input type="text" value="'.$medium_path.'" style="margin-right:0;margin-bottom:3px;"><a class="button1 green copyPrevText" style="width:90px;text-align:center;margin-bottom:3px;"><i class="fa fa-copy"></i> Medium</a>
					<input type="text" value="'.$original_path.'" style="margin-right:0;margin-bottom:3px;"><a class="button1 green copyPrevText" style="width:90px;text-align:center;margin-bottom:3px;"><i class="fa fa-copy"></i> Original</a>
				';
			} else if(isset($file_type) && $file_type!='') {
				$file_ext=str_replace('application/','',$file_type);
				$fa_file_type='-'.$file_ext;
				$thumb='<i class="fa fa-file'.$fa_file_type.'-o fa-3x"></i>';
				$link='<input type="text" value="'.$original_path.'" style="margin-right:0;margin-bottom:3px;"><a class="button1 green copyPrevText" style="width:90px;text-align:center;margin-bottom:3px;"><i class="fa fa-copy"></i> '.(strtoupper($file_ext)).'</a>';
			}
?>
							<tr class="<?php echo $utilityObj->evenClass(); ?>">
								<td><?php echo $s_no; ?></td>
								<td><?php echo $title; ?></td>
                            	<td><?php echo '<div class="thumb_preview">'.$thumb.'</div>'; ?></td>
								<td><?php echo $link; ?></td>
								<td><a href="class_action.php?action=switchStatus&key=media_library_id&id=<?php echo $media_library_id; ?>&table=media_library&page=manage_media_library"><span class="<?php echo $utilityObj->status_array[$status]; ?>"><?php echo $utilityObj->status_array[$status]; ?></span></a></td>
								<td><span class="desktop"><a href="edit_media_library.php?media_library_id=<?php echo $media_library_id; ?>" class="edit"><i aria-hidden="true" class="fa fa-pencil"></i> Edit</a> <a href="class_media_library.php?action=delete_media_library&media_library_id=<?php echo $media_library_id; ?>" class="delete"   onclick="return confirm('Are you sure to delete this Slider?');"><i aria-hidden="true" class="fa fa-trash"></i> Delete</a></span>
                                	<span class="mobile"><a href="edit_media_library.php?media_library_id=<?php echo $media_library_id; ?>" class="edit"><i aria-hidden="true" class="fa fa-pencil"></i></a> <a href="class_media_library.php?action=delete_media_library&media_library_id=<?php echo $media_library_id; ?>" class="delete" onclick="return confirm('Are you sure to delete this Slider?');"><i aria-hidden="true" class="fa fa-trash"></i></a></span>
                                </td>
							</tr>
<?php
			$s_no++;
		}
	}
?>	
						</table>
<?php include("inc/pagenavi.php"); ?>
<?php include_once("inc/footer.php");?>
