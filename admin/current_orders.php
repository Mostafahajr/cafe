<?php
session_start();
require_once '../includes/db_connect.php';

$orders = $conn->query("SELECT * FROM orders WHERE status='Processing'");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $orderId = $_POST['order_id'];
    $status = $_POST['status'];
    
    $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->bind_param('si', $status, $orderId);
    $stmt->execute();

    header("Location: current_orders.php");
}
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Fetch admin user details
$user_id = $_SESSION['user_id'];
$query = $conn->prepare("SELECT username, profile_image FROM users WHERE id = ?");
$query->bind_param('i', $user_id);
$query->execute();
$result = $query->get_result();
$user = $result->fetch_assoc();

$profile_image = $user['profile_image'] ? '../uploads/' . $user['profile_image'] : 'https://via.placeholder.com/32';
$username = $user['username'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Current Orders</title>
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/admin/m-order.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 50px;
        }
        .table-container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #333;
        }
        .btn-custom {
            background-color: #007bff;
            color: #fff;
        }
        .btn-custom:hover {
            background-color: #0056b3;
        }
        .form-select-sm {
            width: auto;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="table-container">
            <h1 class="text-center mb-4">Current Orders</h1>
           
<body class="bg-light">

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
          <a class="nav-link" href="manage_users.php" >Users</a>
        </li>
        <li class="nav-item">
          <a class="nav-link " href="create_order.php" >Make Orders</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="current_orders.php" >Orders</a>
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
    <div class="container p-5 bg-white mt-5">
            <div class="header ">
                <h3 class="text-center  mb-4">Current Orders</h3>
                
            </div>
            
            <table class="table text-center">

                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>User ID</th>
                        <th>Total Price</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($order = $orders->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($order['id']) ?></td>
                            <td><?= htmlspecialchars($order['user_id']) ?></td>
                            <td><?= htmlspecialchars($order['total_price']) ?></td>
                            <form method="post">
                            <td>
                               
                                    <input type="hidden" name="order_id" value="<?= htmlspecialchars($order['id']) ?>">
                                    <select name="status" class="form-select form-select-sm">
                                        <option value="Processing" <?= $order['status'] == 'Processing' ? 'selected' : '' ?>>Processing</option>
                                        <option value="Out for Delivery" <?= $order['status'] == 'Out for Delivery' ? 'selected' : '' ?>>Out for Delivery</option>
                                        <option value="Done" <?= $order['status'] == 'Done' ? 'selected' : '' ?>>Done</option>
                                    </select>
                                
                               </td>
                            <td>
                          
                                    <button type="submit" class="btn btn-primary btn-sm ">Update Status</button>
                                
                            </td>
                            </form>
                        </tr>
                        
                    <?php endwhile; ?>
                </tbody>
            </table>
        
    </div>
    <!-- Bootstrap JS and dependencies -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


</body>
</html>
