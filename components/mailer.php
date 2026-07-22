<?php
/* -----------------------------------------------------------
   EstateFlow - ef_send_mail()
   Sends an email through SMTP (PHPMailer). If SMTP is not set
   up yet, it falls back to PHP's basic mail() function.
   Returns true when the email was accepted for delivery.
   ----------------------------------------------------------- */

require_once __DIR__ . '/mail_config.php';
require_once __DIR__ . '/phpmailer/Exception.php';
require_once __DIR__ . '/phpmailer/PHPMailer.php';
require_once __DIR__ . '/phpmailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function ef_send_mail(string $to_email, string $to_name, string $subject, string $body): bool
{
   /* If the mailbox password has not been filled in yet, SMTP cannot
      work. On a local test machine we quietly return false (the page
      then shows the reset link on screen instead). On the live server
      we log it so the problem is visible in the error log. */
   if (EF_SMTP_PASS === 'PUT-THE-MAILBOX-PASSWORD-HERE') {
      $is_local = (bool) getenv('ESTATEFLOW_LOCAL')
               || in_array($_SERVER['SERVER_ADDR'] ?? '', ['127.0.0.1', '::1'], true);
      if (!$is_local) {
         error_log('EstateFlow mail error: SMTP password not configured in components/mail_config.php');
      }
      return false;
   }

   $mail = new PHPMailer(true);
   try {
      $mail->isSMTP();
      $mail->Host       = EF_SMTP_HOST;
      $mail->SMTPAuth   = true;
      $mail->Username   = EF_SMTP_USER;
      $mail->Password   = EF_SMTP_PASS;
      $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // SSL on port 465
      $mail->Port       = EF_SMTP_PORT;
      $mail->CharSet    = 'UTF-8';

      $mail->setFrom(EF_SMTP_USER, EF_MAIL_FROM_NAME);
      $mail->addAddress($to_email, $to_name);
      $mail->Subject = $subject;
      $mail->Body    = $body;

      return $mail->send();
   } catch (Exception $e) {
      error_log('EstateFlow mail error: ' . $mail->ErrorInfo);
      return false;
   }
}
