ر<?php
session_start();
require_once '../includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $stmt = $conn->prepare("INSERT INTO categories (name) VALUES (?)");
    $stmt->bind_param('s', $name);
    $stmt->execute();

    // Redirect to a specific page after successful addition
    header("Location: manage_products.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Category</title>
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

        .container {
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(246, 246, 246, 0.973);
            width: 400px;
            background-color: rgba(0, 0, 0, 0.6);
        }

        .container h1 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 40px;
            color: azure;
        }

        .container label {
            display: block;
            margin-bottom: 5px;
            color: rgb(255, 255, 255);
            font-size: 18px;
            font-family: Impact, 'Arial Narrow Bold', sans-serif;
        }

        .container input {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .container button {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            background-color: #007BFF;
            color: white;
            font-size: 16px;
        }

        .container button:hover {
            background-color: #0056b3;
        }

        .container .success {
            color: #28a745;
            margin-bottom: 10px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Add Category</h1>
        <form method="post">
            <label for="name">Category Name:</label>
            <input type="text" name="name" required>
            <button type="submit">Add Category</button>
        </form>
    </div>
</body>
</html>
