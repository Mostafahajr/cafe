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


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_POST['user_id'];
    $productIds = $_POST['product_ids'];
    $quantities = $_POST['quantities'];
    $notes = $_POST['notes'];
    $totalPrice = $_POST['total_price'];

    $stmt = $conn->prepare("INSERT INTO orders (user_id, total_price, status) VALUES (?, ?, 'Processing')");
    $stmt->bind_param('id', $userId, $totalPrice);
    $stmt->execute();
    $orderId = $stmt->insert_id;

    foreach ($productIds as $index => $productId) {
        $quantity = $quantities[$index];
        $note = $notes[$index];
        $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, notes) VALUES (?, ?, ?, ?)");
        $stmt->bind_param('iiis', $orderId, $productId, $quantity, $note);
        $stmt->execute();
    }

   
    header("Location: current_orders.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Order</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('../assets/images/banner/cf40c9b7-079b-4d60-a5e7-f8ae776c6e51.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
        }

        .login-container {
            background-color: rgba(255, 255, 255, 0.0);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.9);
            max-width: 400px;
            width: 100%;
            text-align: center;
        }

        .login-container h2 {
            margin-bottom: 20px;
            font-family: 'Arial', sans-serif;
        }

        .login-container label {
            font-family: 'Arial', sans-serif;
        }

        .login-container .form-control, 
        .login-container .btn {
            border-radius: 20px;
            margin-bottom: 15px;
        }

        .btn-add-product {
            background-color: #a0522d;
            color: white;
            border: none;
            cursor: pointer;
        }

        .btn-add-product:hover {
            background-color: #8b4513;
        }

        .btn-primary {
            background-color: #a0522d;
            border: none;
        }

        .btn-primary:hover {
            background-color: #8b4513;
        }
    </style>
</head>
<body>


    <div class="login-container">
        <h2>Create Order</h2>
        <form method="post">
            <div class="mb-3">
                <label for="user_id">User:</label>
                <select name="user_id" class="form-control">
                <?php
                    $result = $conn->query("SELECT id, username FROM users WHERE role='user'");
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row['id'] . "'>" . $row['username'] . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div id="products">
                <div class="product-entry mb-3">
                    <label for="product_ids[]">Product:</label>
                    <select name="product_ids[]" class="form-control">
                    <?php
                        $result = $conn->query("SELECT id, name FROM products");
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="product-entry mb-3">
                    <label for="quantities[]">Quantity:</label>
                    <input type="number" name="quantities[]" min="1" value="1" class="form-control">
                </div>
                <div class="product-entry mb-3">
                    <label for="notes[]">Notes:</label>
                    <input type="text" name="notes[]" class="form-control">
                </div>
            </div>
            <button type="button" class="btn btn-add-product" onclick="addProduct()">Add another product</button>
            <div class="mb-3">
                <label for="total_price">Total Price:</label>
                <input type="text" name="total_price" required class="form-control">
            </div>
            <button type="submit" class="btn btn-primary">Create Order</button>
        </form>
        <div><a href="home.php" class="btn btn-custom mt-3">Back to Home</a></div>
    </div>

    <script>
        function addProduct() {
            var productDiv = document.querySelector('.product-entry');
            var newProduct = productDiv.cloneNode(true);
            document.getElementById('products').appendChild(newProduct);
        }
    </script>
</body>
</html>
