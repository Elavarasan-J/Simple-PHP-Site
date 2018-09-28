<?php 
/**
 * App initiated
 */
require_once('../init.php');
defined('BASE_PATH') OR exit('No direct script access allowed');
	
	if(isset($_POST['submit'])) {
		$error='';
		
		$arr['rows_per_page']=(isset($_POST['rows_per_page']) && $_POST['rows_per_page']!='')?$_POST['rows_per_page']:'';
		$arr['web_rpp']=(isset($_POST['web_rpp']) && $_POST['web_rpp']!='')?$_POST['web_rpp']:'';
		$arr['first_link_text']=(isset($_POST['first_link_text']) && trim($_POST['first_link_text'])!='')?htmlentities(trim($_POST['first_link_text'])):'';
		$arr['last_link_text']=(isset($_POST['last_link_text']) && trim($_POST['last_link_text'])!='')?htmlentities(trim($_POST['last_link_text'])):'';
		$arr['previous_link_text']=(isset($_POST['previous_link_text']) && trim($_POST['previous_link_text'])!='')?htmlentities(trim($_POST['previous_link_text'])):'';
		$arr['next_link_text']=(isset($_POST['next_link_text']) && trim($_POST['next_link_text'])!='')?htmlentities(trim($_POST['next_link_text'])):'';
		
		if($_POST['rows_per_page']=='') {
			$error.=($_POST['rows_per_page']=='')?'<strong>&bull; Rows per Page:</strong> You must enter the number of rows per page.<br />':'';
			$rows_per_page_error="error";
		}
/*
		if($_POST['web_rpp']=='') {
			$error.=($_POST['web_rpp']=='')?'<strong>&bull; Website Rows per Page:</strong> You must enter the number of rows per page for website.<br />':'';
			$web_rpp_error="error";
		}
*/
		if($error=='') {
			$WHERE="pagination_settings_id=1";
			$db->update($arr,$WHERE,$db->TB_pagination_settings);
			/*echo '<script type="text/javascript">window.location="pagination-settings.php";</script>';*/
			header("location:pagination-settings.php?success=updated");
		}
	}
	
	$pagination_settings_rec=$db->getSingleRec($db->TB_pagination_settings,"pagination_settings_id=1");
	
	if($pagination_settings_rec==TRUE) {
		extract($pagination_settings_rec);
		$rows_per_page=(isset($_POST['rows_per_page']) && $_POST['rows_per_page']!='')?$_POST['rows_per_page']:$rows_per_page;
	//	$web_rpp=(isset($_POST['web_rpp']) && $_POST['web_rpp']!='')?$_POST['web_rpp']:$web_rpp;
		$first_link_text=(isset($_POST['first_link_text']) && trim($_POST['first_link_text'])!='')?trim($_POST['first_link_text']):$first_link_text;
		$last_link_text=(isset($_POST['last_link_text']) && trim($_POST['last_link_text'])!='')?trim($_POST['last_link_text']):$last_link_text;
		$previous_link_text=(isset($_POST['previous_link_text']) && trim($_POST['previous_link_text'])!='')?trim($_POST['previous_link_text']):$previous_link_text;
		$next_link_text=(isset($_POST['next_link_text']) && trim($_POST['next_link_text'])!='')?trim($_POST['next_link_text']):$next_link_text;
	} else {
		$rows_per_page=(isset($_POST['rows_per_page']) && $_POST['rows_per_page']!='')?$_POST['rows_per_page']:'';
	//	$web_rpp=(isset($_POST['web_rpp']) && $_POST['web_rpp']!='')?$_POST['web_rpp']:'';
		$first_link_text=(isset($_POST['first_link_text']) && trim($_POST['first_link_text'])!='')?trim($_POST['first_link_text']):'';
		$last_link_text=(isset($_POST['last_link_text']) && trim($_POST['last_link_text'])!='')?trim($_POST['last_link_text']):'';
		$previous_link_text=(isset($_POST['previous_link_text']) && trim($_POST['previous_link_text'])!='')?trim($_POST['previous_link_text']):'';
		$next_link_text=(isset($_POST['next_link_text']) && trim($_POST['next_link_text'])!='')?trim($_POST['next_link_text']):'';
	}
	
	include_once("inc/header.php");
	include_once("inc/menu.php");
?>

<!-- page title -->
					<h2 class="title <?php $utilityObj->title_color(); ?>"><i aria-hidden="true" class="fa fa-ellipsis-h"></i> Pagination Settings</h2>
                    <p>This section allows you to set default pagination settings.</p>
                    
					<?php include_once('inc/form_message.php'); ?><br />
					
<!-- Add Customers -->
					<form name="pagination_settings_form" action="pagination-settings.php" method="post" >
						<div class="imp">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="table1 auto" style="margin:0;">
                                <tr>
                                    <td class="<?php echo (isset($rows_per_page_error))?$rows_per_page_error:''; ?>"><label for="rows_per_page">Rows Per Page: <span class="req">*</span></label><input type="text" value="<?php echo $rows_per_page; ?>" name="rows_per_page" id="rows_per_page" style="width:100px;"></td>
                                </tr>
<?php /*?>
                                <tr>
                                    <td class="<?php echo $web_rpp_error; ?>"><label for="web_rpp">Website Rows Per Page: <span class="req">*</span></label><input type="text" value="<?php echo $web_rpp; ?>" name="web_rpp" id="web_rpp" style="width:100px;"></td>
                                </tr>
<?php */?>
                                <tr>
                                    <td><label for="first_link_text">First Link Text:</label><input type="text" class="c" value="<?php echo $first_link_text; ?>" name="first_link_text" id="first_link_text" style="width:200px;"></td>
                                </tr>
                                <tr>
                                    <td><label for="last_link_text">Last Link Text:</label><input type="text" class="c" value="<?php echo $last_link_text; ?>" name="last_link_text" id="last_link_text" style="width:200px;"></td>
                                </tr>
                                <tr>
                                    <td><label for="previous_link_text">Previous Link Text:</label><input type="text" class="c" value="<?php echo $previous_link_text; ?>" name="previous_link_text" id="previous_link_text" style="width:200px;"></td>
                                </tr>
                                <tr>
                                    <td><label for="next_link_text">Next Link Text:</label><input type="text" class="c" value="<?php echo $next_link_text; ?>" name="next_link_text" id="next_link_text" style="width:200px;"></td>
                                </tr>
                                <tr>
                                    <td><button type="submit" class="button1" name="submit"><i aria-hidden="true" class="fa fa-refresh"></i>  Update</button></td>
                                </tr>
                            </table>
                    		<div class="clear"></div>
                    	</div>                    
                    </form>
<?php 
	include_once("inc/footer.php");
?>