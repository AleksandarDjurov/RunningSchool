<?php
/**
* Simple example script using PHPMailer with exceptions enabled
* @package phpmailer
* @version $Id$
*/
header('Content-Type: text/html; charset=utf-8');
//header('Content-type: text/plain; charset=utf-8');
require '../class.phpmailer.php';
	if(isset($_REQUEST['email']) && isset($_REQUEST['content'])){
		$email=$_REQUEST['email'];
		//$fmail = explode('||', $email);
		//if(count($fmail) > 1)
			//$email = $fmail;
		$content=$_REQUEST['content'];
		$subject=$_REQUEST['subject'];
		try {
			$mail = new PHPMailer(true); //New instance, with exceptions enabled

			$body = '<p>'.$content.'</p>';
			$body             = preg_replace('/\\\\/','', $body); //Strip backslashes

			$mail->IsSMTP();                           // tell the class to use SMTP
			$mail->SMTPAuth   = true;                  // enable SMTP authentication
			$mail->Port       = 465;//25;                    // set the SMTP server port
			$mail->Host       = "sg2plcpnl0077.prod.sin2.secureserver.net";//"mail.safety-motocle.com"; // SMTP server
			$mail->Username   = "contact@safety-motocle.com";     // SMTP server username
			$mail->Password   = "D!CA@4MIh%#x";            // SMTP server password

			$mail->IsSendmail();  // tell the class to use Sendmail

			//$mail->AddReplyTo("contact@safety-motocle.com","Support");

			$mail->From       = "contact@safety-motocle.com";
			$mail->FromName   = "動物病院EES事務局";

			//$to = $email;
			$to = $email;
			//$mail -> charSet = "UTF-8";
			$mail->CharSet = "UTF-8";
			$mail->AddAddress($to);

			$mail->Subject  = $subject;
			///$mail->setLanguage("jp");
			//$mail->WordWrap   = 1000; // set word wrap
			$mail->MsgHTML($body);
			//$mail->Body  = $body;

			$mail->IsHTML(true); // send as HTML

			$mail->Send();
			//echo 'Message has been sent.';
			return 1;
		} catch (phpmailerException $e) {
			//echo $e->errorMessage();
			return 0;
		}
	}
?>