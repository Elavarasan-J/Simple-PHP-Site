<?php if($total_records!='' && $total_records>0) { include_once(ADMIN_BASE_PATH."inc/total_records.php"); } ?>
<?php if(isset($res[5]) && $res[5]!='' && $res[5]>1) { ?>
					<div class="pagi-control">
                        <form action="<?php echo $paginationObj->paginate_parameters(); ?>page=1" method="post">
                            <label><input type="number" min="1" name="rpp_input" value="<?php echo $rpp; ?>" style="width:64px;" /> Rows per Page:</label>
                        </form>
                    </div>
	<?php if(isset($res[0]) && $res[0]!='') { ?>
					<div class="pagination">
                        <form action="<?php echo $paginationObj->paginate_parameters(); ?>" method="post" class="go_to_page_form">
                            <label>Goto <input type="number" min="1" name="go_to_page_input" value="<?php echo (isset($_REQUEST['page']))?$_REQUEST['page']:"1"; ?>" style="width:64px;" class="go_to_page_input" /> of <?php echo $res[4]; ?></label>
                        </form>
                        <?php echo "<ul>".$res[0]."</ul>"; ?>
                        <div class="clear"></div>
					</div>
	<?php } ?>
                    <div class="clear"></div>
<?php } ?>