<?php
session_start();
require_once '../includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $username = $_POST['username'];






    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $email = $_POST['email'];
    $role = $_POST['role'];

    // Handle file upload
    $profilePicture = '';
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == UPLOAD_ERR_OK) {
        $uploadDir = '../uploads/';
        $uploadFile = $uploadDir . basename($_FILES['profile_image']['name']);

        // Check if upload directory exists, create if not
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Validate the file
        $fileType = mime_content_type($_FILES['profile_image']['tmp_name']);
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (in_array($fileType, $allowedTypes)) {
            if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $uploadFile)) {
                $profilePicture = basename($_FILES['profile_image']['name']);
            } else {
                echo "File upload failed.";
                exit;
            }
        } else {
            echo "Invalid file type.";
            exit;
        }
    }
    
    // Prepare SQL statement
    $stmt = $conn->prepare("INSERT INTO users (username, password, email, role, profile_image) VALUES (?, ?, ?, ?, ?)");
    
    if ($stmt === false) {
        // Display error message if preparation fails
        echo "Error preparing statement: " . $conn->error;
        exit;
    }

    // Bind parameters
    if (!$stmt->bind_param('sssss', $username, $password, $email, $role, $profilePicture)) {
        echo "Error binding parameters: " . $stmt->error;
        exit;
    }
    
  
    if ($stmt->execute()) {
 
        header('Location: manage_users.php');
        exit;
    } else {
        echo "Failed to add user: " . $stmt->error;
    }


    $stmt->close();
    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <title>Add User</title>
    <link rel="stylesheet" href="../assets/css/admin/user.css"> <!-- Make sure the path is correct -->
</head>
<body>
    <div class="container">
        <div class="login-container">
            <h2>Add User</h2>
            <form method="post" enctype="multipart/form-data" action="add_user.php">
                <div class="error">
                    <!-- Display error messages here -->
                </div>
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
                
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
                
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
                
                <label for="role">Role:</label>
                <select name="role" id="role">
                    <option value="admin">Admin</option>
                    <option value="user">User</option>
                </select>
                
                <label for="profile_image">Profile Picture:</label>
                <input type="file" id="profile_image" name="profile_image" accept="image/*">
                
                <button type="submit">Add User</button>
                <a href="login.php">Back to Login</a>
            </form>
        </div>
    </div>
</body>
</html>

