<?php

// Start the session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Redirect to login if not logged in
function checkLogin() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: /user/login.php");
        exit();
    }
}

// Get user by ID
function getUserById($conn, $userId) {
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

// Get all products
function getAllProducts($conn) {
    $result = $conn->query("SELECT * FROM products");
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Get all categories
function getAllCategories($conn) {
    $result = $conn->query("SELECT * FROM categories");
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Get orders for a specific user
function getOrdersByUserId($conn, $userId) {
    $stmt = $conn->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Update order status
function updateOrderStatus($conn, $orderId, $status) {
    $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->bind_param('si', $status, $orderId);
    $stmt->execute();
}

?>
