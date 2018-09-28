<?php
/**
 * App initiated
 */
$uploadMethods='yes';
require_once('../init.php');
defined('BASE_PATH') OR exit('No direct script access allowed');

	$section_loader_js=1;
	$total_columns=1;
	
	$sql="SELECT * FROM $db->TB_board_members ORDER BY board_type ASC, sort_order ASC";
	
	$query=mysqli_query($db->conn, $sql);
	$num_sql=@mysqli_num_rows($query);
	//echo $num_sql;
	
	$total_records=($num_sql>0)?$num_sql:0;
	
	include_once("inc/header.php");
	include_once("inc/menu.php");
?>
<!-- page title -->
				<h2 class="title light-blue"><i aria-hidden="true" class="fa fa-sort-amount-asc"></i> Board Member Ordering</h2><br /><br />
                
                <?php include_once("inc/form_message.php"); ?>
                
                <form method="post" action="class_sort.php?action=board_members">
                    <?php if($total_records>0) { ?><button type="submit" class="button1" name="submit1"><i aria-hidden="true" class="fa fa-refresh"></i> Update Orders</button><?php } ?>
                    <table cellspacing="0" cellpadding="0" width="100%" class="table" id="table" style="margin-bottom:15px;">
                    	<thead>
                        <tr class="nodrop">
                            <th>Order #</th>
                            <th>Board Type</th>
                            <th>Title</th>
                            <th>Thumb</th>
                        </tr>
                        </thead>
                         <tbody class="ui-sortable">
<?php
	$i=1;
	if($total_records>0) {
		while($fetch=mysqli_fetch_array($query)) {
			extract($fetch);
			$img_file=BASE_PATH.$pathObj->media_library_files_path.$featured_img;
			$img_path=SITE_PATH.$pathObj->media_library_files_path.$featured_img;
			
			$thumb='';
			if(is_file($img_file)) {
				$thumb='<img src="'.$img_path.'" alt="" />';
			}
?>
                        <tr id="<?php echo $i; ?>" style="cursor: move;" class="moveproduct<?php echo $utilityObj->evenClass(); ?>">
                            <td><?php echo $sort_order; ?></td>
							<td><?php echo $utilityObj->boardType_arr[$board_type]; ?></td>
                            <td><?php echo $title; ?></td>
                            <td><?php echo $thumb; ?><input type="hidden" name="display_order[<?php echo $board_member_id; ?>]" value="<?php echo $i; ?>"></td>
                        </tr>
<?php
			$i++;
		}
	}
?>					</tbody>
                    </table>
                    <?php if($total_records>0) { ?><button type="submit" class="button1 bottom" name="submit2"><i aria-hidden="true" class="fa fa-refresh"></i> Update Orders</button><?php } ?>
                </form>
<?php
	include_once("inc/footer.php");
?>