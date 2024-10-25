<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;

require_once 'includes/db_connect.php';

$email=$_POST['email'];

$token=bin2hex(random_bytes(16));

$token_hash=hash("sha256",$token);

$expiry=date("Y-M-D H:I:S",time()+ 60 * 30);

$sql_update_password="update users
Set `reset_password_hash`=?,
`reset_expired_at`";

$stmt = $conn->prepare($sql_update_password);
$stmt->bind_param("sss",$token_hash,$expiry,$email);
$stmt->execute();
if ($mysqli->affected_rows) {

    $mail = require __DIR__ . "/mailer.php";

    $mail->setFrom("noreply@example.com");
    $mail->addAddress($email);
    $mail->Subject = "Password Reset";
    $mail->Body = <<<END

    Click <a href="http://example.com/reset_password.php?token=$token">here</a> 
    to reset your password.

    END;

    try {

        $mail->send();

    } catch (Exception $e) {

        echo "Message could not be sent. Mailer error: {$mail->ErrorInfo}";

    }

}

echo "Message sent, please check your inbox.";



?>