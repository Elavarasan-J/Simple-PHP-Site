<?php
/**
 * App initiated
 */
require_once('../init.php');
defined('BASE_PATH') OR exit('No direct script access allowed');

//$db->debug_mode=true;
if(isset($_REQUEST['action']) && $_REQUEST['action']!='') {
	$action=$_REQUEST['action'];
	switch($action) {
		case "board_members":
			$pageName=$action."_ordering.php";
			if(isset($_POST['display_order']) && is_array($_POST['display_order']) && count($_POST['display_order'])>0)	{
				$i=1;
				foreach($_POST['display_order'] as $key=>$value) {
					//echo '<br/>'.$key.'::Order::'.$i;
					mysqli_query($db->conn, "UPDATE ".$db->{'TB_'.$action}." SET sort_order=$i WHERE board_member_id=$key");
					$i++;
				}
				header("Location:".$action."_ordering.php?success=ordered");
			} else
				header("Location:".$action."_ordering.php?fail=ordered");
		break;
	}
}
?>