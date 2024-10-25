<?php
session_start();
require_once '../includes/db_connect.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Get the logged-in user's ID
$user_id = $_SESSION['user_id'];

// Fetch user details from the database
$query = $conn->prepare("SELECT username, email, profile_image FROM users WHERE id = ?");
if ($query == false) {
    die('Error preparing the SQL statement: ' . $conn->error);
}

$query->bind_param('i', $user_id);
$query->execute();
$result = $query->get_result();
if ($result == false) {
    die('Error executing the SQL statement: ' . $query->error);
}

$user = $result->fetch_assoc();
if ($user == null) {
    die('User not found.');
}

$name = $user['username'];
$email = $user['email'];
$profile_image = $user['profile_image'] ? '../uploads/' . $user['profile_image'] : 'https://via.placeholder.com/150';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>User Profile</title>
    <style>
        .profile-image {
            border-radius: 50%;
            width: 150px;
            height: 150px;
            object-fit: cover;
        }
        .card-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
        }
        .card-body {
            font-size: 1.1rem;
        }
        .user-info {
            padding: 1rem;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                <h3 class="text-center">User Profile</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 d-flex justify-content-center align-items-center">
                        <img src="<?php echo $profile_image; ?>" alt="Profile Image" class="profile-image">
                    </div>
                    <div class="col-md-8">
                        <div class="user-info">
                            <h5>Name: <?php echo htmlspecialchars($name); ?></h5>
                            <p>Email: <?php echo htmlspecialchars($email); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <a href="home.php">Back to Home</a>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
