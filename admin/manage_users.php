<?php
session_start();
require_once '../includes/db_connect.php';
// Fetch admin user details
$user_id = $_SESSION['user_id'];
$query = $conn->prepare("SELECT username, profile_image FROM users WHERE id = ?");
$query->bind_param('i', $user_id);
$query->execute();
$result = $query->get_result();
$user = $result->fetch_assoc();

$profile_image = $user['profile_image'] ? '../uploads/' . $user['profile_image'] : 'https://via.placeholder.com/32';
$username = $user['username'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['delete'])) {
        $id = $_POST['user_id'];
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
    }
}

$users = $conn->query("SELECT * FROM users where role='user'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Manage Users</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            margin: 20px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
            color: #333;
        }
        td img {
            width: 50px; /* Adjust as needed */
            height: 50px; /* Adjust as needed */
            object-fit: cover;
            border-radius: 50%;
        }
        .action-btn {
            background-color: #e74c3c;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 4px;
        }
        .action-btn:hover {
            background-color: #c0392b;
        }
        .container a {
            display: block;
            text-align: center;
            margin: 20px 0;
            text-decoration: none;
            color: #3498db;
            font-size: 16px;
        }
        .container a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg  navbar-dark bg-secondary mb-4">
  <div class="container-fluid">
    <a class="navbar-brand" href="home.php">KO艾弗EE</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link " aria-current="page" href="home.php">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="manage_products.php">Products</a>
        </li>
       
        <li class="nav-item">
          <a class="nav-link active" href="manage_users.php" >Users</a>
        </li>
        <li class="nav-item">
          <a class="nav-link " href="create_order.php" >Make Orders</a>
        </li>
        <li class="nav-item">
          <a class="nav-link " href="current_orders.php" >Orders</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="view_checks.php" >Checks</a>
        </li>
        
      </ul>
  
    </div>
  </div>
  <div class="d-flex align-items-center me-5 pe-5">
               
                    <div class="dropdown text-end">
                        <a href="#" class="d-block link-body-emphasis text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="<?php echo $profile_image; ?>" alt="<?php echo htmlspecialchars($username); ?>" width="32" height="32" class="rounded-circle">
                            <?php echo htmlspecialchars($username); ?>
                        </a>
                        <ul class="dropdown-menu text-small">
                            <li class="dropdown-submenu">
                                <a class="dropdown-item dropdown-toggle" href="#" id="settingsDropdown" data-bs-toggle="dropdown" aria-expanded="false">Settings</a>
                                <ul class="dropdown-menu" aria-labelledby="settingsDropdown">
                                    <li><a class="dropdown-item" href="add_account.php">Add Account</a></li>
                                    <li><a class="dropdown-item" href="../user/login.php">Log In</a></li>
                                    <li><a class="dropdown-item" href="change_profile_picture.php">Change Profile Picture</a></li>
                                </ul>
                            </li>
                            <li><a class="dropdown-item" href="myprofile.php">Profile</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="../user/logout.php">Sign out</a></li>
                        </ul>
                    </div>
                </div>
</nav>
    <div class="container">
        <h1>Manage Users</h1>
        <table>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Role</th>
                <th>Image</th>
                <th>Action</th>
            </tr>
            <?php while ($user = $users->fetch_assoc()): ?>
                <tr>
                    <td><?= $user['id'] ?></td>
                    <td><?= $user['username'] ?></td>
                    <td><?= $user['email'] ?></td>
                    <td><?= $user['role'] ?></td>
                    <td>
                        <?php if (!empty($user['profile_image'])): ?>
                            <img src="../uploads/<?= htmlspecialchars($user['profile_image']) ?>" alt="User Image">
                        <?php else: ?>
                            <img src="../images/default-profile.png" alt="Default Image">
                        <?php endif; ?>
                    </td>
                    <td>
                        <form method="post">
                            <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                            <button type="submit" name="delete" class="action-btn">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
        <a href="add_user.php">Add New User</a>
    </div>
</body>
</html>
