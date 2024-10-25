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

// Fetch current profile picture (optional for display purposes)
$query = $conn->prepare("SELECT profile_image FROM users WHERE id = ?");
$query->bind_param('i', $user_id);
$query->execute();
$result = $query->get_result();
$user = $result->fetch_assoc();
$current_profile_image = $user['profile_image'] ? '../uploads/' . $user['profile_image'] : 'https://via.placeholder.com/150';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if a file is uploaded
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['profile_image']['tmp_name'];
        $file_name = $_FILES['profile_image']['name'];
        $file_size = $_FILES['profile_image']['size'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        // Set the allowed file extensions and max file size (optional)
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
        $max_file_size = 2 * 1024 * 1024; // 2MB

        if (in_array($file_ext, $allowed_extensions) && $file_size <= $max_file_size) {
            $new_file_name = $user_id . '_' . time() . '.' . $file_ext;
            $upload_path = '../uploads/' . $new_file_name;

            // Move the uploaded file to the destination directory
            if (move_uploaded_file($file_tmp, $upload_path)) {
                // Update the user's profile picture in the database
                $update_query = $conn->prepare("UPDATE users SET profile_image = ? WHERE id = ?");
                $update_query->bind_param('si', $new_file_name, $user_id);
                $update_query->execute();

                // Redirect to the profile page or display a success message
                header('Location: myprofile.php');
                exit();
            } else {
                echo '<div class="alert alert-danger">Error uploading the file.</div>';
            }
        } else {
            echo '<div class="alert alert-danger">Invalid file type or size.</div>';
        }
    } else {
        echo '<div class="alert alert-danger">No file uploaded or there was an upload error.</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Change Profile Picture</title>
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
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                <h3>Change Profile Picture</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 d-flex justify-content-center align-items-center">
                        <img src="<?php echo $current_profile_image; ?>" alt="Current Profile Picture" class="profile-image img-thumbnail">
                    </div>
                    <div class="col-md-8">
                        <form action="myprofile.php" method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="profile_image" class="form-label">New Profile Picture</label>
                                <input type="file" name="profile_image" class="form-control" id="profile_image" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Upload</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
