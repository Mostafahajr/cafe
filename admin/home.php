<?php

session_start();
require_once '../includes/db_connect.php';

// Check if the admin user is logged in
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

// Fetch total number of users
$total_users_query = "SELECT COUNT(*) AS total FROM users where role='user'";
$total_users_result = $conn->query($total_users_query);
$total_users = $total_users_result->fetch_assoc()['total'];

// Fetch total number of products
$total_products_query = "SELECT COUNT(*) AS total FROM products";
$total_products_result = $conn->query($total_products_query);
$total_products = $total_products_result->fetch_assoc()['total'];

// Fetch total number of orders
$total_orders_query = "SELECT COUNT(*) AS total FROM orders";
$total_orders_result = $conn->query($total_orders_query);
$total_orders = $total_orders_result->fetch_assoc()['total'];

// Fetch total sales amount
$total_sales_query = "SELECT SUM(total_price) AS total FROM orders";
$total_sales_result = $conn->query($total_sales_query);
$total_sales = $total_sales_result->fetch_assoc()['total'];

// Close database connection
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> -->

    <title>Admin Dashboard</title>
    <style>
        a{
            text-decoration: none;
        }
        .card{
            transition: .6s ease-out;
            box-shadow: #33333322 1px 2px 7px;
            z-index: 2;
            
        }
        .card:hover{
            transform: scale(1.05,1.1);
            text-decoration: none;
            box-shadow: #333333 1px 2px 7px;
        }
    </style>
</head>
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
          <a class="nav-link active" aria-current="page" href="home.php">Home</a>
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
          <a class="nav-link" href="current_orders.php" >Orders</a>
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



<!-- Dashboard Content -->
<div class="container mt-4 ">
    
    <div class="row d-flex justify-content-around align-items-center">
        <!-- Card Example -->
        <div class="col-md-4 mb-4">
            <a href="manage_users.php">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="mr-3">
                                <h5 class="card-title">Total Users</h5>
                                <h3 class="card-text"><?php echo $total_users; ?></h3>
                            </div>
                            <div class="icon">
                                <i class="fas fa-users fa-3x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
           
        </div>
        <!-- Card Example -->
        <div class="col-md-4 mb-4">
            <a href="manage_products.php">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="mr-3">
                                <h5 class="card-title">Total Products</h5>
                                <h3 class="card-text"><?php echo $total_products; ?></h3>
                            </div>
                            <div class="icon">
                                <i class="fas fa-box fa-3x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
            
        </div>
    </div>
    <div class="row d-flex justify-content-around align-items-center">
        <!-- Card Example -->
        <div class="col-md-4 mb-4">
            <a href="current_orders.php">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="mr-3">
                                <h5 class="card-title">Total Orders</h5>
                                <h3 class="card-text"><?php echo $total_orders; ?></h3>
                            </div>
                            <div class="icon">
                                <i class="fas fa-shopping-cart fa-3x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
            
        </div>
        <!-- Card Example -->
        <div class="col-md-4 mb-4">
            <a href="view_checks.php">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="mr-3">
                                <h5 class="card-title">Total Sales</h5>
                                <h3 class="card-text">$<?php echo number_format($total_sales, 2); ?></h3>
                            </div>
                            <div class="icon">
                                <i class="fas fa-dollar-sign fa-3x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
           
        </div>
    </div>
   
    

</div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
