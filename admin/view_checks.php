<?php
session_start();
require_once '../includes/db_connect.php';

// Fetch all users with role 'user'
$users = $conn->query("SELECT id, username FROM users WHERE role='user'");

// Initialize $orders
$orders = null;

// If the form is submitted, filter orders based on the input
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userId = $_POST['user_id'];
    $startDate = $_POST['start_date'];
    $endDate = $_POST['end_date'];

    $query = "SELECT orders.id, orders.total_price, orders.status, orders.created_at 
              FROM orders 
              WHERE orders.created_at BETWEEN '$startDate' AND '$endDate'";
    
    if ($userId != 'all') {
        $query .= " AND orders.user_id = $userId";
    }

    $orders = $conn->query($query);
} else {
    // If no form submission, show all orders
    $orders = $conn->query("SELECT orders.id, orders.total_price, orders.status, orders.created_at 
                            FROM orders");
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
    <title>View Checks</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>
        /* body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    background-image: url('../assets/images/banner/115c224b-fb13-4b20-b9bd-526d54ae9666.jpg'); /* Replace with your image URL */
    /* background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    background-attachment: fixed; // Makes the background image fixed 
}  */
 .container {
  
           max-width: 100%;
            padding-top :60px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            background-color: rgba(255, 255, 255, 0.9); 
            text-align: center;
            position: absolute;
            margin: 30px;
            height: 100%;
          
        }
        h2 {
            text-align: center;
            color: #004085;
            margin-bottom: 30px;
            font-family: Arial, Helvetica, sans-serif;

        }
        .form-label {
            font-weight: bold;
            color: #555;
        }
        .form-select, .form-control {
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            width: 150px;
            margin: 20px;

            font-size: 18px;
            font-weight: bold;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #004085;
        }
        .btn-primary:focus, .btn-primary:active {
            box-shadow: none;
        }

        .card-title {
            text-align: center;
            color: #333;
        }
        .table-responsive {
            margin-top: 20px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table thead {
            background-color: #343a40;
            color: #fff;
        }
        .table th, .table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #f9f9f9;
        }
        .table td img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 50%;
        }
        .card {
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background-color: #fff;
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
          <a class="nav-link" href="manage_users.php" >Users</a>
        </li>
        <li class="nav-item">
          <a class="nav-link " href="create_order.php" >Make Orders</a>
        </li>
        <li class="nav-item">
          <a class="nav-link " href="current_orders.php" >Orders</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="view_checks.php" >Checks</a>
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

    <div class="container-fluid">
    
        <div class="card shadow-sm p-4 mb-4">
            <h2 class="mb-4">Filter Orders</h2>
            <form method="post">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="user_id" class="form-label">User:</label>
                        <select name="user_id" class="form-select" required>
                            <option value="all">All Users</option>
                            <?php while ($user = $users->fetch_assoc()): ?>
                                <option value="<?= $user['id'] ?>"><?= $user['username'] ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <label for="start_date" class="form-label">Start Date:</label>
                        <input type="date" name="start_date" class="form-control" required>
                    </div>
               
                    <div class="col-md-6">
                        <label for="end_date" class="form-label">End Date:</label>
                        <input type="date" name="end_date" class="form-control" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">View Checks</button>
           <a href="home.php" class="btn btn-secondary ">Back to Home</a> </form>
        </div>

   
   
        <?php if ($orders): ?>
            <div class="card shadow-sm">
                <div class="card-body">
                    <h2 class="card-title mb-4">Orders</h2>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead class="table-secondary">
                                <tr>
                                    <th>Order ID</th>
                                    <th>Total Price</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($order = $orders->fetch_assoc()): ?>
                                    <tr>
                                        <td><?= $order['id'] ?></td>
                                        <td><?= $order['total_price'] ?></td>
                                        <td><?= $order['status'] ?></td>
                                        <td><?= $order['created_at'] ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
