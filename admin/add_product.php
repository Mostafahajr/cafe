<?php
session_start();
require_once '../includes/db_connect.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $category_id = $_POST['category_id'];

    // Handle image upload
    $image = $_FILES['image'];
    $upload_dir = '../uploads/';
    $image_name = uniqid() . '.' . pathinfo($image['name'], PATHINFO_EXTENSION);
    $upload_file = $upload_dir . $image_name;

    if (move_uploaded_file($image['tmp_name'], $upload_file)) {
        // Corrected bind_param call with 'ssdis' and corresponding variables
        $stmt = $conn->prepare("INSERT INTO products (name, description, price, category_id, image) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param('ssdis', $name, $description, $price, $category_id, $image_name);
        $stmt->execute();
        echo "<div class='alert alert-success'>Product added successfully!</div>";
    } else {
        echo "<div class='alert alert-danger'>Image upload failed.</div>";
    }
}

$categories = $conn->query("SELECT id, name FROM categories");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add New Product</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body,
        html {
            width: 100%;
            height: 100%;
            margin: 0;
            font-family: Arial, sans-serif;
            background-image: url("../assets/images/banner/carousel-1.jpg");
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-container {
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(246, 246, 246, 0.973);
            width: 400px;
            background-color: rgba(0, 0, 0, 0.6); /* Add a background color to improve contrast */
        }

        .login-container h1 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 40px;
            color: azure;
        }

        .login-container label {
            display: block;
            margin-bottom: 5px;
            color: rgb(255, 255, 255);
            font-size: 18px;
            font-family: Impact, 'Arial Narrow Bold', sans-serif;
        }

        .login-container input,
        .login-container textarea,
        .login-container select {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .login-container button {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            background-color: #007BFF;
            color: white;
            font-size: 16px;
        }

        .login-container button:hover {
            background-color: #0056b3;
        }

        .login-container .error {
            color: red;
            margin-bottom: 10px;
            text-align: center;
        }

        .login-container a {
            display: block;
            text-align: center;
            margin-top: 10px;
            color: #007BFF;
            text-decoration: none;
        }

        .login-container a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>Add New Product</h1>
        <a href="manage_products.php" class="btn btn-secondary mb-4">Back to Product List</a>
        <form method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="name" class="form-label">Name:</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description:</label>
                <textarea name="description" class="form-control" required></textarea>
            </div>
            <div class="mb-3">
                <label for="price" class="form-label">Price:</label>
                <input type="number" step="0.01" name="price" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="category_id" class="form-label">Category:</label>
                <select name="category_id" class="form-select">
                    <?php while ($category = $categories->fetch_assoc()): ?>
                        <option value="<?= $category['id'] ?>"><?= $category['name'] ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">Product Image:</label>
                <input type="file" name="image" class="form-control" accept="image/*" required>
            </div>
            <button type="submit" name="add" class="btn btn-primary">Add Product</button>
        </form>
    </div>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
