<?php
/**
 * Simple example script using PHPMailer with exceptions enabled
 * @package phpmailer
 * @version $Id$
 */
header('Content-Type: text/html; charset=utf-8');
require '../class.phpmailer.php';

        $email = 'future.syg1118@gmail.com';
        $content = '<h1>This is test html</h1>';
        $subject = 'This is subejct';
        $fromname= 'Sender';
        $mail = new PHPMailer(true); //New instance, with exceptions enabled

        $body = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"><html>  <head>
        <meta http-equiv="Content-Type" content="text/html; charset=JIS">  </head>  <body><p>' . $content . '</p>
            </body>
            </html>';
        $body = preg_replace('/\\\\/', '', $body); //Strip backslashes
        //$mail->IsSMTP();                           // tell the class to use SMTP
        //$mail->Sendmail = 'C:\xampp\xampp735\sendmail\sendmail';
        $mail->SMTPDebug = 1;
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
                $mail->Username = "#######";     // SMTP server username
                $mail->Password = "#######";     // SMTP server password
        }else {
                $mail->Port = 2525;//25;                    // set the SMTP server port
                $mail->Host = "mail.runnerschoolitalia.com";
                //$mail->Host = "mail.infomaniak.com";
                $mail->Username = "gold@runnerschoolitalia.com";     // SMTP server username
                $mail->Password = "dnflskfk!@#";     // SMTP server password
        }
        //$mail->IsSendmail();  // tell the class to use Sendmail
        $mail->From = "future.syg1118@gmail.com";
        $mail->FromName = $fromname;//mb_convert_encoding('世界WiFi 事務局', "JIS", "UTF-8");//"Sekai WiFi";//
        $to = $email;
        $mail->CharSet = "UTF-8";
        $mail->AddAddress($to);
        $mail->Subject = $subject;
        $mail->MsgHTML($body);
        //$mail->Body  = $body;
        $mail->IsHTML(true); // send as HTML
        if(!$mail->Send()) {
            echo "Mailer Error:::::: " . $mail->ErrorInfo;
        } else {
            echo "Message has been sent";
        }
?>



