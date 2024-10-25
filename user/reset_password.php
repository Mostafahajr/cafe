<?php
require_once '../includes/db_connect.php';

$errors = [];
$success = false;

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Validate the token
    $stmt = $conn->prepare("SELECT email FROM password_resets WHERE token = ? AND expires > ?");
    $stmt->bind_param('ss', $token, date("U"));
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($email);
        $stmt->fetch();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $password = $_POST['password'];
            $passwordConfirm = $_POST['password_confirm'];

            if ($password == $passwordConfirm) {
                $passwordHash = password_hash($password, PASSWORD_BCRYPT);

                // Update the user's password
                $stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
                $stmt->bind_param('ss', $passwordHash, $email);
                $stmt->execute();

                // Delete the reset token
                $stmt = $conn->prepare("DELETE FROM password_resets WHERE token = ?");
                $stmt->bind_param('s', $token);
                $stmt->execute();

                $success = true;
            } else {
                $errors[] = "Passwords do not match.";
            }
        }
    } else {
        $errors[] = "Invalid or expired token.";
    }
} else {
    $errors[] = "No token provided.";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
    <link rel="stylesheet" href="../assets/css/user/authentcation.css">
</head>
<body>
    <div class="login-container">
        <h2>Reset Password</h2>
        <?php if ($success): ?>
            <div class='info'>Your password has been successfully reset. <a href='login.php'>Login</a></div>
        <?php else: ?>
            <?php if (!empty($errors)): ?>
                <?php foreach ($errors as $error): ?>
                    <div class='error'><?= $error ?></div>
                <?php endforeach; ?>
            <?php endif; ?>
            <form method="post">
                <label for="password">New Password:</label>
                <input type="password" name="password" required><br>
                <label for="password_confirm">Confirm Password:</label>
                <input type="password" name="password_confirm" required><br>
                <button type="submit">Reset Password</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
