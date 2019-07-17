<?php
/**
* Simple example script using PHPMailer with exceptions enabled
* @package phpmailer
* @version $Id$
*/

require '../class.phpmailer.php';
		$email='future.syg1118@gmail.com';
		//$fmail = explode('||', $email);
		//if(count($fmail) > 1)
			//$email = $fmail;
		$content='hello. this is medken mail test.';
		try {
			$mail = new PHPMailer(true); //New instance, with exceptions enabled

			$body = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"><html>  <head>    
			<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">  </head>  <body>				
				<p>Dear.</p> 
				<p>'.$content.'</p> <br><br> 
				<p>Regards.</p> 
				</body>
				</html>';
			$body             = preg_replace('/\\\\/','', $body); //Strip backslashes

			$mail->IsSMTP();                           // tell the class to use SMTP
			$mail->SMTPAuth   = true;                  // enable SMTP authentication
			$mail->Port       = 587;                    // set the SMTP server port
			$mail->Host       = "tk2-205-12004.vs.sakura.ne.jp"; // SMTP server
			$mail->Username   = "support@tk2-205-12004.vs.sakura.ne.jp";     // SMTP server username
			$mail->Password   = "support123";            // SMTP server password

			$mail->IsSendmail();  // tell the class to use Sendmail

			//$mail->AddReplyTo("contact@safety-motocle.com","Support");

			$mail->From       = "support@tk2-205-12004.vs.sakura.ne.jp";
			$mail->FromName   = "Medken Mail";

			$to = $email;
			$mail -> charSet = "UTF-8";
			$mail->AddAddress($to);

			$mail->Subject  = "message from medken.net";

			$mail->WordWrap   = 1000; // set word wrap
			$mail->MsgHTML($body);

			$mail->IsHTML(true); // send as HTML

			$mail->Send();
			//echo 'Message has been sent.';
			return 1;
		} catch (phpmailerException $e) {
			//echo $e->errorMessage();
			return 0;
		}
?>