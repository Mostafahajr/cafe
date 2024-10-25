<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cafeteria_db";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get category ID from request
if (isset($_GET['category_id'])) {
    $category_id = intval($_GET['category_id']);
} else {
    echo json_encode(['error' => 'No category ID provided']);
    exit;
}

// Prepare and execute query
$sql = "SELECT name, price, image FROM products WHERE category_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $category_id);
$stmt->execute();
$result = $stmt->get_result();

// Fetch all products
$products = [];
while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}

// Return products as JSON
echo json_encode($products);

// Close connection
$stmt->close();
$conn->close();
?>
