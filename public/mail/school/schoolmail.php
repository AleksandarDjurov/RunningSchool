<?php
/**
 * Simple example script using PHPMailer with exceptions enabled
 * @package phpmailer
 * @version $Id$
 */
header('Content-Type: text/html; charset=utf-8');
require '../class.phpmailer.php';
//ini_set("log_errors", 1);
//ini_set("error_log", "/tmp/php-error.log");
if(isset($_REQUEST['data'])){
	$data = json_decode($_REQUEST['data'], JSON_UNESCAPED_UNICODE);
	for($i = 0; $i < count($data); $i++) {
		$email = $data[$i]['email'];
		if(strpos($email, '@') !== false){			
		}else{
			continue;
		}
		//$fmail = explode('||', $email);
		//if(count($fmail) > 1)
		//$email = $fmail;
		$content = $data[$i]['content'];
		$subject = $data[$i]['subject'];
		$fromname= $data[$i]['fromname'];

		try {
			$mail = new PHPMailer(true); //New instance, with exceptions enabled

			$body = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"><html>  <head>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8">  </head>  <body><p>' . $content . '</p>
				</body>
				</html>';
			$body = preg_replace('/\\\\/', '', $body); //Strip backslashes
			//$mail->IsSMTP();                           // tell the class to use SMTP
			$mail->SMTPAuth = true;                  // enable SMTP authentication
			$mail->SMTPOptions = array(
				'ssl' => array(
					'verify_peer' => false,
					'verify_peer_name' => false,
					'allow_self_signed' => true
				)
			);
			$mail->SMTPSecure = 'tls';
			if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') { //localhost
				$mail->Port = 587;//25;                    // set the SMTP server port
				$mail->Host = "smtp.gmail.com";
				$mail->Username = "######";     // SMTP server username
				$mail->Password = "######";            // SMTP server password
				$mail->From = "future.syg1118@gmail.com";
			}else { //server
				$mail->Port = 2525;//25;                    // set the SMTP server port
				$mail->Host = "mail.runnerschoolitalia.com";
				$mail->Username = "gold@runnerschoolitalia.com";     // SMTP server username
				$mail->Password = "dnflskfk!@#";            // SMTP server password
				$mail->From = "info@runnerschoolitalia.com";
			}

			//$mail->IsSendmail();  // tell the class to use Sendmail
            //$mail->From = "admin@admin.com";
			$mail->FromName = $fromname;

			//$to = $email;
			$to = $email;
			//error_log( $email."::" );
			//$mail -> charSet = "UTF-8";
			$mail->CharSet = "UTF-8";
			$mail->AddAddress($to);

			$mail->Subject = $subject;
			///$mail->setLanguage("jp");
			//$mail->WordWrap   = 1000; // set word wrap
			$mail->MsgHTML($body);
			//$mail->Body  = $body;

			$mail->IsHTML(true); // send as HTML

			$result = $mail->Send();
 			//print_r($result);
			//error_log( ":sent:" );
			//echo 'Message has been sent.';
		} catch (phpmailerException $e) {
			//echo $e->errorMessage();
			return 0;
		}
	}
	return 1;
}
?>



