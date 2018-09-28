<?php
/**
 * App initiated
 */
$checkAdminLogin='no';
$phpMailer='yes';
require_once('init.php');

defined('BASE_PATH') OR exit('No direct script access allowed');

if(isset($_POST['formName']) && $_POST['formName']=='contactForm') {
	$result = array('status'=>0,'message'=>'Please complete all required fields.');
	
	if(isset($_POST['name'],$_POST['email'],$_POST['phone'],$_POST['message'],$_POST['g-recaptcha-response'])) {
		// $error=$validateObj->isEmpty(array('Name'=>$_POST['name'],'Email Address'=>$_POST['email']));
		// $error=$validateObj->verifyEmail($_POST['email']);
		$error = '';
		$errorFields=array();
		
		if($_POST['name']==''){
			$error++;
			array_push($errorFields, 'name');
		}
		if($_POST['email']=='' || $validateObj->verifyEmail($_POST['email'])!=''){
			$error++;
			array_push($errorFields, 'email');
		}
		
		if(!empty($_POST['g-recaptcha-response'])) {
			$secret = '6LcQUxsUAAAAAKhU3Cm49LeePnzau1_zbW7TcFFM';
			$verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$_POST['g-recaptcha-response']);
			$responseData = json_decode($verifyResponse);
			if(!$responseData->success) {
				$error.='reCaptcha validation failed. Please try again.';
				array_push($errorFields, 'recaptcha');
			}
		} else {
			$error.='Please click on the reCaptcha box.';
			array_push($errorFields, 'recaptcha');
		}
		if($error!='') {
			$result = array('status'=>0,'message'=>'Please complete all required fields.','errorFields'=>$errorFields);
		} else {
			#######################################################################################################################################
			//	0 = Email-Content will not be printed, but Email will be sent to the recipient and DB will be updated.
			//	1 = Print Email-Content and Stop execution. Email will not be sent, and DB will not be updated.
					$print_mail=0;

			//	0 = Email will not be sent.
			//	1 = Email will be sent to the recipient
					$send_mail=1;

			//	From Email Address
					$fromEmail="info@anncaresfoundation.org";
					$replyToEmail=$fromEmail;

			//	Live To Email ID
				//	$arrayToEmail=array("info@anncaresfoundation.org");
			//	Test To Email ID
					$arrayToEmail=array("gs8@gtectsystems.com");
			#######################################################################################################################################
			
			###########################
			foreach($arrayToEmail as $key=>$toEmail) {	
				$emailContent='
					<!DOCTYPE html>
						<html>
							<head>
								<meta charset="UTF-8">
								<meta http-equiv="X-UA-Compatible" content="IE=edge">
								<title>Ann Cares Foundation</title>
								<meta name="description" content="">
								<meta name="viewport" content="width=device-width, initial-scale=1.0">
								<style>
									@media screen and (min-width:320px) and (max-width: 599px) {
										.main-table{width: 100% !important;}
										.col{display:block !important;}
										.left-col,.right-col{width: 100% !important;}
										.left-col{padding-right:0 !important;border-right:0 !important;}
										.right-col{padding-left:0 !important;}
										.top{text-align:center !important;padding:5px 0 !important;}
										.center{text-align:center !important;}
										.title-lg{font-size:30px !important;line-height:40px !important;padding-bottom:5px !important;}
										.title-sm{font-size:14px !important;line-height:22px !important;}
										.title-md{font-size:20px !important;line-height:30px !important;padding-top:0 !important;}
										.subs-btn{padding-top:15px !important;padding-bottom:0px !important;display:block !important;}
										.subs-btn >table{margin:0 auto !important;}
										.col.center{padding-right:0 !important;}
										.col-middle{width:180px !important;margin:0 auto !important;padding-right:0 !important;}
										.bb{border-bottom:1px solid #dcdcdc; !important}
										.logo-wrap{padding:0 20px !important;}
										.spcl{margin:0 auto !important;}
										.spcl td{padding:10px 0 !important;}
										.col.center.padd25{padding:25px !important;}
										.padd20{padding:25px 20px !important;}
										.subs-save-btn td{font-size:15px !important;}
										.padd10{padding-bottom:15px !important;}
										.paddt10{padding-top:10px !important;}
									}
								</style>
							</head>
							<body style="outline:none;margin:0;padding:0;font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22px;color:#333;background-color:#fff;">
								<table style="width:100%;border-spacing:0;border-collapse:collapse;padding:0;" cellpadding="0" cellspacing="0" bgcolor="#fff">
									<tr>
										<td align="center">
											<table class="main-table" style="width:600px;margin:0 auto;border-spacing:0;border-collapse:collapse;" cellpadding="0" cellspacing="0">
												<tr>
													<td style="padding:10px 0 15px 20px;">
														<table style="width:100%;border-spacing:0;border-collapse:collapse;" cellpadding="0" cellspacing="0">
															<tr>
																<td align="center">
																	<img style="vertical-align:middle;" src="'.ASSET_PATH.'images/logo.png"  alt="Ann Cares">
																</td>
															</tr>
														</table>
													</td>
												</tr>

												<tr>
													<td style="padding:20px;border:1px solid #322c8c;border-bottom-width:3px;">
														<table style="width:100%;border-spacing:0;border-collapse:collapse;" cellpadding="0" cellspacing="0">
															<tr>
																<td style="font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:16px;line-height:24px;letter-spacing:0.2px;padding-bottom:18px;">CONTACT US</td>
																<td style="font-family:Arial, Helvetica, sans-serif;text-align:right;font-weight:bold;font-size:14px;line-height:22px;padding-bottom:18px;">'.date("d-m-Y").'</td>
															</tr>

															<tr>
																<td style="font-family:Arial, Helvetica, sans-serif;font-size:18px;font-weight:bold;line-height:27px;letter-spacing:0.2px;text-decoration:underline;color:#555;padding-bottom:10px;">User Information</td>
															</tr>

															<tr>
																<td style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22px;color:#555;padding-bottom:5px;"><strong>Name</strong> : '.$_POST['name'].'</td>
															</tr>

															<tr>
																<td style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22px;color:#555;padding-bottom:5px;"><strong>Email Address</strong> : '.$_POST['email'].'</td>
															</tr>

															<tr>
																<td style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22px;color:#555;padding-bottom:5px;"><strong>Phone Number</strong> : '.$_POST['phone'].'</td>
															</tr>

															 <tr>
																<td style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22px;color:#555;padding-bottom:5px;"><strong>Message</strong> :<br>'.$_POST['message'].'</td>
															</tr>
														</table>
													</td>
												</tr>

											</table>
										</td>
									</tr>
								</table>
							</body>
						</html>
				';
				$subject= "Ann Cares Contact Us Form Submission - [".$_POST['name']."]";

	##### Email Direct #####
				if($print_mail==1)	{
					$utilityObj->printAny($_POST);

					echo '<a href="'.$pageInfoObj->the_page_link(16).'" style="color:#0066FF;">Back</a> &nbsp; &mdash; &nbsp; To Email:'.$toEmail.'<br /><hr /><br />';
					echo $emailContent;
				} else {
					$m = new PHPMailer(); // create the mail
					$m->setFrom($fromEmail,'Ann Cares');
					$m->addAddress($toEmail);

					$m->Subject=$subject;
					$m->msgHTML($emailContent);
/*
					if($toEmail==$arr['salon_email'] || $toEmail=='gs4@gtectsystems') {
						$ch = curl_init();

						curl_setopt($ch, CURLOPT_URL,SITE_PATH.'na/new-account-print-content.php');
						curl_setopt($ch, CURLOPT_POST, 1);
						curl_setopt($ch, CURLOPT_POSTFIELDS,
						//			"id=".$_REQUEST['id']);

						// in real life you should use something like:
						// curl_setopt($ch, CURLOPT_POSTFIELDS, 
									http_build_query(array('id' => $lastRecID)));

						// receive server response ...
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

						$html = curl_exec ($ch);
					//	echo $html; exit;
						include_once("mpdf60/mpdf.php");
						$mpdf=new mPDF('utf-8', array(210,297), 0, '', 8, 8, 8, 8, 0, 0);

						$mpdf->WriteHTML($html);
					//	$mpdf->Output(); exit;
						$pdfString = $mpdf->Output('', 'S');

						$m->addStringAttachment($pdfString, 'new_account_'.$lastRecID.'.pdf',  'base64', 'application/pdf');
					}
*/
					if($send_mail==1)
						$m->send();
				}
##### Email libmail #####
			}
			if($print_mail==1)	{ exit; }
			###########################
			
			$result = array('status'=>1,'message'=>'Thank You! Your message has been sent.');
		}
	}
	echo json_encode($result); exit;
} else if(isset($_POST['formName']) && $_POST['formName']=='volunteerForm') {
	$result = array('status'=>0,'message'=>'Please complete all required fields.');
	
	if(isset($_POST['fname'],$_POST['lname'],$_POST['address'],$_POST['city'],$_POST['postal'],$_POST['email'],$_POST['phone'],$_POST['hours'],$_POST['comments'],$_POST['g-recaptcha-response'])) {
		// $error=$validateObj->isEmpty(array('Name'=>$_POST['fname'],'Email'=>$_POST['email'],'Phone Number'=>$_POST['phone']));
		// $error=$validateObj->verifyEmail($_POST['email']);
		
		$error = '';
		 
		$errorFields=array();
		if($_POST['fname']==''){
			$error++;
			array_push($errorFields, 'fname');	
		}
		if($_POST['email']=='' || $validateObj->verifyEmail($_POST['email'])!=''){
			$error++;
			array_push($errorFields, 'email');	
		}
		if($_POST['phone']=='') array_push($errorFields, 'phone');
		if(!empty($_POST['g-recaptcha-response'])) {
			$secret = '6LcQUxsUAAAAAKhU3Cm49LeePnzau1_zbW7TcFFM';
			$verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$_POST['g-recaptcha-response']);
			$responseData = json_decode($verifyResponse);
			if(!$responseData->success) {
				$error.='reCaptcha validation failed. Please try again.';
				array_push($errorFields, 'recaptcha');
			}
		} else {
			$error.='Please click on the reCaptcha box.';
			array_push($errorFields, 'recaptcha');
		}
		if($error!='') {
			$result = array('status'=>0,'message'=>'Please complete all required fields.','errorFields'=>$errorFields);
		} else {
			#######################################################################################################################################
			//	0 = Email-Content will not be printed, but Email will be sent to the recipient and DB will be updated.
			//	1 = Print Email-Content and Stop execution. Email will not be sent, and DB will not be updated.
					$print_mail=0;

			//	0 = Email will not be sent.
			//	1 = Email will be sent to the recipient
					$send_mail=1;

			//	From Email Address
					$fromEmail="info@anncaresfoundation.org";
					$replyToEmail=$fromEmail;

			//	Live To Email ID
				//	$arrayToEmail=array("info@anncaresfoundation.org");
			//	Test To Email ID
					$arrayToEmail=array("gs8@gtectsystems.com");
			#######################################################################################################################################
			
			###########################
			foreach($arrayToEmail as $key=>$toEmail) {	
				$emailContent='
					<!DOCTYPE html>
						<html>
							<head>
								<meta charset="UTF-8">
								<meta http-equiv="X-UA-Compatible" content="IE=edge">
								<title>Ann Cares</title>
								<meta name="description" content="">
								<meta name="viewport" content="width=device-width, initial-scale=1.0">
								<style>
									@media screen and (min-width:320px) and (max-width: 599px) {
										.main-table{width: 100% !important;}
										.col{display:block !important;}
										.left-col,.right-col{width: 100% !important;}
										.left-col{padding-right:0 !important;border-right:0 !important;}
										.right-col{padding-left:0 !important;}
										.top{text-align:center !important;padding:5px 0 !important;}
										.center{text-align:center !important;}
										.title-lg{font-size:30px !important;line-height:40px !important;padding-bottom:5px !important;}
										.title-sm{font-size:14px !important;line-height:22px !important;}
										.title-md{font-size:20px !important;line-height:30px !important;padding-top:0 !important;}
										.subs-btn{padding-top:15px !important;padding-bottom:0px !important;display:block !important;}
										.subs-btn >table{margin:0 auto !important;}
										.col.center{padding-right:0 !important;}
										.col-middle{width:180px !important;margin:0 auto !important;padding-right:0 !important;}
										.bb{border-bottom:1px solid #dcdcdc; !important}
										.logo-wrap{padding:0 20px !important;}
										.spcl{margin:0 auto !important;}
										.spcl td{padding:10px 0 !important;}
										.col.center.padd25{padding:25px !important;}
										.padd20{padding:25px 20px !important;}
										.subs-save-btn td{font-size:15px !important;}
										.padd10{padding-bottom:15px !important;}
										.paddt10{padding-top:10px !important;}
									}
								</style>
							</head>
							<body style="outline:none;margin:0;padding:0;font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22px;color:#333;background-color:#fff;">
								<table style="width:100%;border-spacing:0;border-collapse:collapse;padding:0;" cellpadding="0" cellspacing="0" bgcolor="#fff">
									<tr>
										<td align="center">
											<table class="main-table" style="width:600px;margin:0 auto;border-spacing:0;border-collapse:collapse;" cellpadding="0" cellspacing="0">
												<tr>
													<td style="padding:10px 0 15px 20px;">
														<table style="width:100%;border-spacing:0;border-collapse:collapse;" cellpadding="0" cellspacing="0">
															<tr>
																<td align="center">
																	<img style="vertical-align:middle;" src="'.ASSET_PATH.'images/logo.png"  alt="Ann Cares">
																</td>
															</tr>
														</table>
													</td>
												</tr>

												<tr>
													<td style="padding:20px;border:1px solid #322c8c;border-bottom-width:3px;">
														<table style="width:100%;border-spacing:0;border-collapse:collapse;" cellpadding="0" cellspacing="0">
															<tr>
																<td style="font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:16px;line-height:24px;letter-spacing:0.2px;padding-bottom:18px;color:#322c8c;">VOLUNTEER APPLICATION</td>
																<td style="font-family:Arial, Helvetica, sans-serif;text-align:right;font-weight:bold;font-size:14px;line-height:22px;padding-bottom:18px;">'.date("d-m-Y").'</td>
															</tr>

															<tr>
																<td style="font-family:Arial, Helvetica, sans-serif;font-size:18px;font-weight:bold;line-height:27px;letter-spacing:0.2px;text-decoration:underline;color:#555;padding-bottom:10px;">User Information</td>
															</tr>

															<tr>
																<td style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22px;color:#555;padding-bottom:5px;"><strong>Name</strong> : '.$_POST['fname'].' '.$_POST['lname'].'</td>
															</tr>

															<tr>
																<td style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22px;color:#555;padding-bottom:5px;"><strong>Address</strong> : '.$_POST['address'].'</td>
															</tr>

															<tr>
																<td style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22px;color:#555;padding-bottom:5px;"><strong>City</strong> : '.$_POST['city'].'</td>
															</tr>

															<tr>
																<td style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22px;color:#555;padding-bottom:5px;"><strong>Postal Code</strong> : '.$_POST['postal'].'</td>
															</tr>

															<tr>
																<td style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22px;color:#555;padding-bottom:5px;"><strong>Email</strong> : '.$_POST['email'].'</td>
															</tr>

															<tr>
																<td style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22px;color:#555;padding-bottom:5px;"><strong>Phone Number</strong> : '.$_POST['phone'].'</td>
															</tr>

															<tr>
																<td style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22px;color:#555;padding-bottom:5px;"><strong>Volunteer Days</strong> :<br> '.implode(', ',$_POST['days']).'</td>
															</tr>

															<tr>
																<td style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22px;color:#555;padding-bottom:5px;"><strong>Volunteer Hours</strong> : '.$_POST['hours'].'</td>
															</tr>

															<tr>
																<td style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22px;color:#555;padding-bottom:5px;"><strong>Comments/Questions</strong> :<br>'.$_POST['comments'].'</td>
															</tr>
														</table>
													</td>
												</tr>

											</table>
										</td>
									</tr>
								</table>
							</body>
						</html>
				';
				$subject= "Ann Cares Volunteer Application Form Submission - [".$_POST['fname']."]";

	##### Email Direct #####
				if($print_mail==1)	{
					$utilityObj->printAny($_POST);

					echo '<a href="'.$pageInfoObj->the_page_link(16).'" style="color:#0066FF;">Back</a> &nbsp; &mdash; &nbsp; To Email:'.$toEmail.'<br /><hr /><br />';
					echo $emailContent;
				} else {
					$m = new PHPMailer(); // create the mail
					$m->setFrom($fromEmail,'Ann Cares');
					$m->addAddress($toEmail);

					$m->Subject=$subject;
					$m->msgHTML($emailContent);
					/*
						if($toEmail==$arr['salon_email'] || $toEmail=='gs4@gtectsystems') {
							$ch = curl_init();

							curl_setopt($ch, CURLOPT_URL,SITE_PATH.'na/new-account-print-content.php');
							curl_setopt($ch, CURLOPT_POST, 1);
							curl_setopt($ch, CURLOPT_POSTFIELDS,
							//			"id=".$_REQUEST['id']);

							// in real life you should use something like:
							// curl_setopt($ch, CURLOPT_POSTFIELDS, 
										http_build_query(array('id' => $lastRecID)));

							// receive server response ...
							curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

							$html = curl_exec ($ch);
						//	echo $html; exit;
							include_once("mpdf60/mpdf.php");
							$mpdf=new mPDF('utf-8', array(210,297), 0, '', 8, 8, 8, 8, 0, 0);

							$mpdf->WriteHTML($html);
						//	$mpdf->Output(); exit;
							$pdfString = $mpdf->Output('', 'S');

							$m->addStringAttachment($pdfString, 'new_account_'.$lastRecID.'.pdf',  'base64', 'application/pdf');
						}
					*/
					if($send_mail==1)
						$m->send();
				}
##### Email libmail #####
			}
			if($print_mail==1)	{ exit; }
			###########################
			
			$result = array('status'=>1,'message'=>'Thank You! Your application has been sent.');
		}
	}
	echo json_encode($result); exit;
	
}else if(isset($_POST['formName']) && $_POST['formName'] == 'newsletterForm'){
	$result = array('status'=>0,'message'=>'Please complete all required fields.');
	
	if(isset($_POST['name'], $_POST['email'])){
		
		$error = '';
		$errorFields = array();
		if($_POST['name'] == ''){
			$error++;
			array_push($errorFields, 'name');
		}
		if($_POST['email'] == '' && $_POST['email']=='' || $validateObj->verifyEmail($_POST['email'])!=''){
			$error++;
			array_push($errorFields, 'email');
		}
		
		if($error!='') {
			$result = array('status'=>0,'message'=>'Please complete all required fields.','errorFields'=>$errorFields);
		} else {
			$mainTable = $db->TB_newsletter_signup;
			
			$arr['name'] = (trim($_POST['name'])!='')?trim($_POST['name']):'';
			$arr['email'] = (trim($_POST['email'])!='')?trim($_POST['email']):'';
			
			$email_arr = $db->getSingleRec($mainTable, "email='".$arr['email']."'");
			if($email_arr['email'] == $arr['email']){
				array_push($errorFields, 'email');
				$result = array('status'=>0,'message'=>'Email already subscribed.','errorFields'=>$errorFields);
			}else{
				$id = $db->getAutoincrement($mainTable);
				$db->insert($arr,$mainTable);
				$result = array('status'=>1,'message'=>'Thank You for your subscription.');	
			}
		}
	}
	
	echo json_encode($result); exit;
	
} else {
	exit('No direct script access allowed');
}

?>



