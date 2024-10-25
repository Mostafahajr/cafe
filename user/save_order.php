<?php

require_once '../includes/db_connect.php';


$orderData = json_decode($_POST['orderData'], true);

// Extract order details
$userId = $orderData['user_id'];
$totalPrice = $orderData['total_price'];
$orderItems = $orderData['items'];

// Insert into orders table
$sql = "INSERT INTO orders (user_id, total_price) VALUES (?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("id", $userId, $totalPrice);

if ($stmt->execute()) {
    $orderId = $stmt->insert_id; // Get the inserted order ID

    // Insert items into order_items table
    $sql = "INSERT INTO order_items (order_id, product_name, product_price, quantity, note) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    
    foreach ($orderItems as $item) {
        $stmt->bind_param("isdss", $orderId, $item['name'], $item['price'], $item['quantity'], $item['note']);
        $stmt->execute();
    }

    echo "Order saved successfully!";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
