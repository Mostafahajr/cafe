<?php
session_start();
require_once '../includes/db_connect.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Fetch user data from the database
$user_id = $_SESSION['user_id'];
$query = $conn->prepare("SELECT username, profile_image FROM users WHERE id = ?");
 $query->bind_param('i', $user_id);
$query->execute();
$result = $query->get_result();
$user = $result->fetch_assoc();

if ($user == null) {
    die('User not found.');
}

$profile_image = $user['profile_image'] ? '../uploads/' . $user['profile_image'] : 'https://via.placeholder.com/32';
$username = $user['username'];

// Handle product deletion
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete'])) {
    $id = $_POST['product_id'];
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
}

$products = $conn->query("SELECT * FROM products");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
    color: #8B4513;
}
        .product-list {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            
        }
        .product-container {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            margin: 10px;
            width: calc(33.333% - 20px);
            box-sizing: border-box;
            text-align: center;

        }
        .product-image {
            width: 100%;
            height: auto;
            max-height: 200px;
            object-fit: cover;
            border-radius: 8px;
        }
        header {
            background-color: #f8f9fa;
            padding: 10px 0;
            border-bottom: 1px solid #e9ecef;
        }
        .card {
    background: #fff;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    border-radius: 10px;
    overflow: hidden;
    transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
    cursor: pointer;
    position: relative;
}

.card:hover {
    transform: scale(1.05);
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
}



    </style>
    <title>Manage Products</title>
</head>
<body>
    <!-- Header -->
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
          <a class="nav-link active" href="manage_products.php">Products</a>
        </li>
       
        <li class="nav-item">
          <a class="nav-link " href="manage_users.php" >Users</a>
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
    <div class="container mt-4">
        <h1>Manage Products</h1>

        <!-- Link to Add Product Page -->
        <a href="add_product.php" class="btn btn-secondary mb-4">Add Product</a>
        <a href="add_category.php" class="btn btn-secondary mb-4">Add category</a>

        <!-- Display Products -->
        <h2 class="mt-4">Product List</h2>
        <div class="product-list">
            <?php while ($product = $products->fetch_assoc()): ?>
                <div class="product-container shadow-lg p-3 mb-5 bg-body-tertiary rounded card">
                    <img src="../uploads/<?= $product['image'] ?>" alt="<?= $product['name'] ?>" class="product-image">
                    <h3><?= $product['name'] ?></h3>
                    <p><?= $product['description'] ?></p>
                    <p><strong>Price:</strong> <?= $product['price'] ?></p>
                    
                    <form method="post">
                        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                        <button type="submit" name="delete" class="btn btn-secondary">Delete</button>
                    </form>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
