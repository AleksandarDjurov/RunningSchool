<?php
/**
* Simple example script using PHPMailer with exceptions enabled
* @package phpmailer
* @version $Id$
*/

require '../class.phpmailer.php';
	if(isset($_REQUEST['uemail']) && isset($_REQUEST['fmail'])){
		$uemail=$_REQUEST['uemail'];
		$fmail='sinchong1989@gmail.com';//$_REQUEST['fmail'];//."||future.syg1118@gmail.com||2905368844@qq.com";
		$url=$_REQUEST['url'];
		$restaurant=$_REQUEST['restaurant'];
		$uname=$_REQUEST['uname'];
		try {
			$mail = new PHPMailer(true); //New instance, with exceptions enabled

			$body = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"><html>  <head>    
				<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">  </head>  <body>				
					<p>Dear really no array.</p> 
					<p>Your friend '.$uname.' reviwed the .</p> 
					<p>Regards.</p> 
					</body>
					</html>';//<a href="'.$_SERVER['HTTP_HOST'].'/'.$url.'">'.$restaurant.'</a>
			$body             = preg_replace('/\\\\/','', $body); //Strip backslashes
			var_dump($body);
			$mail->IsSMTP();                           // tell the class to use SMTP
			$mail->SMTPAuth   = true;                  // enable SMTP authentication
			$mail->Port       = 25;                    // set the SMTP server port
			$mail->Host       = "mail.safety-motocle.com"; // SMTP server
			$mail->Username   = "contact@safety-motocle.com";     // SMTP server username
			$mail->Password   = "D!CA@4MIh%#x";            // SMTP server password

			$mail->IsSendmail();  // tell the class to use Sendmail

			//$mail->AddReplyTo("contact@safety-motocle.com","Support");

			$mail->From       = "contact@safety-motocle.com";
			$mail->FromName   = "Support";

			$to = $fmail;

			$mail->AddAddress($to);

			$mail->Subject  = $uemail." reviewed new restaurant.";

			$mail->WordWrap   = 1000; // set word wrap
			$mail->MsgHTML($body);

			$mail->IsHTML(true); // send as HTML

			$mail->Send();
			//echo 'Message has been sent.';
			return $fmail;
		} catch (phpmailerException $e) {
			//echo $e->errorMessage();
			return "asdasdasdasd";
		}
	}
?>