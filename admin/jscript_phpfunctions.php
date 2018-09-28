<?php 
/**
 * App initiated
 */
require_once('../init.php');
defined('BASE_PATH') OR exit('No direct script access allowed');

if(isset($_REQUEST['keyName']) && isset($_REQUEST['keyVal']) && isset($_REQUEST['keyText']) && isset($_REQUEST['tableName']) && $_REQUEST['keyName']!='' && $_REQUEST['keyVal']!='' && $_REQUEST['keyText']!='' && $_REQUEST['tableName']!='') {
	$options=$utilityObj->returnOptions("SELECT * FROM $_REQUEST[tableName] WHERE $_REQUEST[keyName]=$_REQUEST[keyVal] AND status=1",$_REQUEST['keyText'],0);
	echo '<option value=""></option>'.$options; exit;
} else if(isset($_REQUEST['slugvalue']) && $_REQUEST['slugvalue']!='') {
	$slug = $utilityObj->to_slug($_REQUEST['slugvalue']);
	echo $slug; exit;
} else if(isset($_REQUEST['search_termsvalue']) && $_REQUEST['search_termsvalue']!='') {
	$slug = ($_REQUEST['search_termsvalue']);
	echo $slug; exit;
} else if(isset($_REQUEST['genPass']) && $_REQUEST['genPass']=='1') {
	$pass = $passwordObj->generatePassword();
	echo $pass; exit;
} else if(isset($_REQUEST['addMoreAccordion']) && $_REQUEST['addMoreAccordion']=='1') {
	$editor_id=$_REQUEST['editor_id'];
	$editor_id = (isset($editor_id) && $editor_id!='')?$editor_id:1;
	$accordion = $utilityObj->add_more_accordion($editor_id);
	echo $accordion; exit;
} else if(isset($_REQUEST['addMorePDF']) && $_REQUEST['addMorePDF']=='1') {
	$new_pdf_no=(isset($_REQUEST['new_pdf_no']) && $_REQUEST['new_pdf_no']!='')?$_REQUEST['new_pdf_no']:'';
	$pdf = $utilityObj->add_more_pdf($new_pdf_no);
	echo $pdf; exit;
}
?>