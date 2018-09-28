<?php
exit;?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>PHPMailer - mail() test</title>
</head>
<body>
<?php
require 'class.phpmailer.php';

//Create a new PHPMailer instance
$mail = new PHPMailer();
//Set who the message is to be sent from
$mail->setFrom('jackier@depasqualeco.com', 'Depasquale Salonsystems');
//Set an alternative reply-to address
$mail->addReplyTo('jackier@depasqualeco.com', 'Depasquale Salonsystems');
//Set who the message is to be sent to
$mail->addAddress('sasi@gtectsystems.com', 'sasi Kumar');
$mail->addAddress('sachsase@gmail.com', 'sasi Kumar');

//Set the subject line
$mail->Subject = 'PHPMailer mail() test';
//Read an HTML message body from an external file, convert referenced images to embedded,
//convert HTML into a basic plain-text alternative body
$mail->msgHTML('<table style="font-size: 11px; color: #000000; font-family: arial;" width="600" align="center" cellpadding="0" cellspacing="0"> <tbody> <tr><td style="border-bottom: 10px solid #4C4D4F;"><br><table style="border: 1px solid #4C4D4F; padding: 30px; font-size: 11px; color: #000000; font-family: arial;" width="750" align="center" cellpadding="0" cellspacing="0"><tbody><tr><td colspan="2" align="right" ><img src="http://www.depasqualesalonsystems.com/email.gif" alt="DePasquale Salon Systems"></td></tr><tr><td colspan="2">Hello DePasquale Salon Systems,<br><br> A new order has been successfully placed in DePasquale Salon Systems<br/><br/><strong>Order Details:</strong><br>[Representative_number]<br> Customer Number:[int_cus_number]<br>Salon Name: [salon_name]<br>Name: [Contact]<br> Email: [Email]<br> Phone: [Phone]<br>[Notes]<br>{shipto}<br><span style="font-weight: bold;">Shipping To:</span><br> [Ssalon] [Scontact],<br> [SAddress],<br> [SCity], [SState],<br> [SZip], USA<br>{/shipto}<br>Order ID: [OrderNo]<br>Order Status: [OrderStatus]<br>Opted for : [ShipPickupOption]<br> Date: [Date]<br>Payment Mode: [PMode]<br> <br><br><br>[PRODUCT_PURCHASE_INFO]<br><br></td></tr><tr><td><br></td></tr></tbody></table></td></tr></tbody></table>');
//Replace the plain text body with one created manually
//$mail->AltBody = 'This is a plain-text message body';
//Attach an image file
//$mail->addAttachment('images/phpmailer_mini.gif');
$mail->addAttachment('do.pdf');

//send the message, check for errors
if (!$mail->send()) {
    echo "Mailer Error: " . $mail->ErrorInfo;
} else {
    echo "Message sent!";
}
?>
</body>
</html>