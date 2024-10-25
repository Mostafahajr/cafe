<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require __DIR__ . "/vendor/autoload.php";
$mail=new PHPMailer(true);
$mai->isSMTP();
 $mail->SMTPDebug = SMTP::DEBUG_SERVER;
$mail->SMTPAuth=true;
$mail->Host="smtp.example.com";
$mail->SMTPSecure= PHPMailer::ENCRYPTION_STARTTLS;
$mail->Port="587";
$mail->Username = "tallata363@gmail.com";
$mail->Password = "Abdo259001#3";

$mail->isHtml(true);

return $mail;