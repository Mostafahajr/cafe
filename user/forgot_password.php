<?php
require_once '../includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];

    // Check if the email exists in the users table
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $token = bin2hex(random_bytes(16));
        $expires = date("U") + 3600; // 1 hour from now

        // Store the reset token and expiration
        $stmt = $conn->prepare("INSERT INTO password_resets (email, token, expires) VALUES (?, ?, ?)");
        $stmt->bind_param('ssi', $email, $token, $expires);
        $stmt->execute();

        // Send the reset link to the user's email
        $resetLink = "http://yourwebsite.com/reset_password.php?token=" . $token;
        $subject = "Password Reset Request";
        $message = "
        <html>
        <head>
            <title>Password Reset</title>
        </head>
        <body>
            <p>Click the button below to reset your password:</p>
            <a href='$resetLink' style='display: inline-block; padding: 10px 20px; font-size: 16px; color: #fff; background-color: #007bff; text-decoration: none; border-radius: 5px;'>Reset Password</a>
            <p>If you did not request this password reset, please ignore this email.</p>
        </body>
        </html>
        ";

        // Set content-type header for HTML email
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

        // Additional headers
        $headers .= 'From: noreply@example.com' . "\r\n";

        mail($email, $subject, $message, $headers);

        // Redirect to a confirmation page
        header("Location: confirmation.php");
        exit();
    } else {
        echo "No account found with that email address.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Forget Password</title>
    <link rel="stylesheet" href="../assets/css/user/authentcation.css">
</head>
<body>
    <div class="login-container">
        <h2>Forget Password</h2>
        <form method="post">
            <label for="email">Email:</label>
            <input type="email" name="email" required><br>
            <button type="submit">Send Reset Link</button>
        </form>
    </div>
</body>
</html>
