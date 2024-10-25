<?php
session_start();
require_once '../includes/db_connect.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $profileImage = $_FILES['profile_image'];

    // Validate inputs
    if (empty($username) || empty($password) || empty($email) || empty($role)) {
        $errors[] = 'All fields are required.';
    }

    // Validate and upload image
    if ($profileImage['error'] == UPLOAD_ERR_OK) {
        $ext = pathinfo($profileImage['name'], PATHINFO_EXTENSION);
        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($ext, $allowed_ext)) {
            $errors[] = 'Invalid image format. Allowed formats: jpg, jpeg, png, gif.';
        } else {
            $upload_dir = '../uploads/';
            $new_filename = uniqid() . '.' . $ext;
            $upload_file = $upload_dir . $new_filename;

            if (move_uploaded_file($profileImage['tmp_name'], $upload_file)) {
                // File uploaded successfully
                // Save the new filename to the database along with other user details
            } else {
                $errors[] = 'Image upload failed.';
            }
        }
    } else {
        $errors[] = 'Image upload error.';
    }

    if (empty($errors)) {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert user into the database
        $stmt = $conn->prepare("INSERT INTO users (username, password, email, role, profile_image) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param('sssss', $username, $hashed_password, $email, $role, $new_filename);

        if ($stmt->execute()) {
            header("Location: login.php");
            exit();
        } else {
            $errors[] = 'Database insertion error.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Sign Up</title>
    <link rel="stylesheet" href="../assets/css/user/authentcation.css">
</head>
<body>
    <div class="signup-container">
        <h2>Sign Up</h2>
        <?php
        if (!empty($errors)) {
            foreach ($errors as $error) {
                echo "<div class='error'>$error</div>";
            }
        }
        ?>
        <form method="post" action="signup.php" enctype="multipart/form-data">
            <label for="username">Username:</label>
            <input type="text" name="username" required><br>
            <label for="password">Password:</label>
            <input type="password" name="password" required><br>
            <label for="email">Email:</label>
            <input type="email" name="email" required><br>
            <label for="role">Role:</label>
            <select name="role" required>
                <option value="user">User</option>
                <option value="admin">Administrator</option>
            </select><br>
            <label for="profile_image">Profile Image:</label>
            <input type="file" name="profile_image" accept="image/*" required><br>
            <button type="submit">Sign Up</button>
        </form>
        <span>Already have an account?</span>
        <a href="login.php"> Login here</a>
    </div>
</body>
</html>
