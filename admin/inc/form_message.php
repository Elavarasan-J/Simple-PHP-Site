<?php
	if(isset($_REQUEST['success']) || isset($_REQUEST['fail']) || (isset($success) && $success!='') || (isset($error) && $error!='') || isset($_GET['ship_pick_status']) || isset($_REQUEST['msg'])){
		if(isset($_REQUEST['success']) || (isset($success) && $success!='')) {
			$msgClass='success';
			$msgIcon='fa-check-circle';
		} else if(isset($_REQUEST['fail']) || (isset($error) && $error!='')) {
			$msgClass='error';
			$msgIcon='fa-warning';
		}
			
		if(isset($_REQUEST['success']) && $_REQUEST['success']=='added')
			$string="Record was successfully added.";
		else if(isset($_REQUEST['fail']) && $_REQUEST['fail']=='added')
			$string="Record was not created.";
			
		else if(isset($_REQUEST['success']) && $_REQUEST['success']=='updated')
			$string="Record was successfully updated.";
		else if(isset($_REQUEST['fail']) && $_REQUEST['fail']=='updated')
			$string="Record was not updated.";
			
		else if(isset($_REQUEST['success']) && $_REQUEST['success']=='trashed')
			$string="Record moved to trash.";
		else if(isset($_REQUEST['fail']) && $_REQUEST['fail']=='trashed')
			$string="Failed to trash record.";
			
		else if(isset($_REQUEST['success']) && $_REQUEST['success']=='restored')
			$string="Record was successfully restored.";
		else if(isset($_REQUEST['fail']) && $_REQUEST['fail']=='restored')
			$string="Failed to restore record.";
			
		else if(isset($_REQUEST['success']) && $_REQUEST['success']=='upload')
			$string="File successfully uploaded.";
		else if(isset($_REQUEST['fail']) && $_REQUEST['fail']=='upload')
			$string="Failed to upload file.";
			
		else if(isset($_REQUEST['success']) && $_REQUEST['success']=='deleted')
			$string="Record was successfully deleted.";
		else if(isset($_REQUEST['fail']) && $_REQUEST['fail']=='deleted')
			$string="Record was not deleted.";
			
		else if(isset($_REQUEST['success']) && $_REQUEST['success']=='block')
			$string="Blocked Successfully.";
		else if(isset($_REQUEST['success']) && $_REQUEST['success']=='unblock')
			$string="Unblocked Successfully.";
			
		else if(isset($_REQUEST['success']) && $_REQUEST['success']=='ordered')
			$string="Records Re-Ordered successfully.";
		else if(isset($_REQUEST['fail']) && $_REQUEST['fail']=='ordered')
			$string="Failed to Re-Ordered.";
			
		else if(isset($_REQUEST['success']) && $_REQUEST['success']=='deleteproduct')
			$string="Product was successfully deleted.";
		else if(isset($_REQUEST['fail']) && $_REQUEST['fail']=='deleteproduct')
			$string="Failed to deleted product.";
			
		else if(isset($_REQUEST['success']) && $_REQUEST['success']=='deleteimg')
			$string="Image was successfully deleted.";
		else if(isset($_REQUEST['fail']) && $_REQUEST['fail']=='deleteimg')
			$string="Failed to delete image.";
			
		else if(isset($_REQUEST['success']) && $_REQUEST['success']=='removed_img')
			$string="Image was successfully removed.";
		else if(isset($_REQUEST['fail']) && $_REQUEST['fail']=='removed_img')
			$string="Failed to remove image.";
			
		else if(isset($_REQUEST['success']) && $_REQUEST['success']=='deletefile')
			$string="File was successfully deleted.";
		else if(isset($_REQUEST['fail']) && $_REQUEST['fail']=='deletefile')
			$string="Failed to delete file.";
			
		else if(isset($_REQUEST['success']) && $_REQUEST['success']=='sent')
			$string="Message was successfully sent.";
		else if(isset($_REQUEST['fail']) && $_REQUEST['fail']=='sent')
			$string="Failed to send message.";
			
		else if(isset($_REQUEST['success']) && $_REQUEST['success']=='ship')
			$string="Shipping Information was successfully updated";
		
		else if(isset($_REQUEST['success']) && $_REQUEST['success']=='orderstatus')
			$string="Order Status was successfully updated";
		
		else if(isset($_REQUEST['success']) && $_REQUEST['success']=='order_details')
			$string="Order Details was successfully updated";
		
		else if(isset($_GET['ship_pick_status']) && $_GET['ship_pick_status']=='updated')
			$string="Ship or Pick-Up option updated";
			
		else if(isset($_REQUEST['msg']) && $_REQUEST['msg']!='')
			$string='<strong>&bull;</strong> '.$_REQUEST['msg'];
			
		else if($error!='') {
			if(isset($error_title) && $error_title!='') {
				$string="<strong>".$error_title."</strong><br />".$error;
			} else {
				$string="<strong>Complete all mandatory fields.</strong><br />".$error;
			}
		}
			
		else if($success!='') {
			$string=$success;
		}
		
		echo '<div class="'.$msgClass.'"><i aria-hidden="true" class="fa '.$msgIcon.'"></i> '.$string.'</div>';
	}
?>