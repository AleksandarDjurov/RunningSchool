<?php
/**
 * Simple example script using PHPMailer with exceptions enabled
 * @package phpmailer
 * @version $Id$
 */
require_once("PHPMailer/jphpmailer.php");
mb_language("japanese");
mb_internal_encoding("UTF-8");
if(isset($_REQUEST['data'])){
	$data = json_decode($_REQUEST['data'], JSON_UNESCAPED_UNICODE);
	for($i = 0; $i < count($data); $i++) {
		$email = $data[$i]['email'];
		//$fmail = explode('||', $email);
		//if(count($fmail) > 1)
		//$email = $fmail;
		$content = $data[$i]['content'];
		$subject = $data[$i]['subject'];
		$fromname = $data[$i]['fromname'];
		try {
			$mail = new JPHPMailer();

			$body = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"><html>  <head>
			<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">  </head>  <body><p>' . $content . '</p>
				</body>
				</html>';
			$body = preg_replace('/\\\\/', '', $body); //Strip backslashes

			$mail->IsSMTP();                           // tell the class to use SMTP
			$mail->SMTPAuth = true;                  // enable SMTP authentication
			$mail->Port = 587;//25;                    // set the SMTP server port
			$mail->Host = "motocle2.sakura.ne.jp";//"mail.safety-motocle.com"; // SMTP server
			$mail->Username = "info@motocle2.com";     // SMTP server username
			$mail->Password = "abc123456";            // SMTP server password

			$mail->IsSendmail();  // tell the class to use Sendmail
			

$mail->setFrom("info@motocle2.com", $fromname);

			$to = $email;
			$mail->addTo($to);

			$mail->setSubject($subject);
			$mail->setHtmlBody($body);

			$mail->IsHTML(true); // send as HTML

			$mail->Send();
			//echo 'Message has been sent.';
		} catch (phpmailerException $e) {
			//echo $e->errorMessage();
			return 0;
		}
	}
	return 1;
}
?>



