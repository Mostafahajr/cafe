<?php
require_once '../includes/db_connect.php';

$category_id = $_GET['category_id'];

// Fetch products from the database
$query = $conn->prepare("SELECT id, name, price, image FROM products WHERE category_id = ?");
$query->bind_param('i', $category_id);
$query->execute();
$result = $query->get_result();

while ($product = $result->fetch_assoc()) {
    
    echo '<div class="col-md-4 mb-4">';
    
    echo '    <div class="card product-card">';
    echo '        <img src="../uploads/' . htmlspecialchars($product['image']) . '" class="card-img-top" alt="' . htmlspecialchars($product['name']) . '">';
    echo '        <div class="card-body">';
    echo '            <h5 class="card-title">' . htmlspecialchars($product['name']) . '</h5>';
    echo '            <p class="card-text">$' . number_format($product['price'], 2) . '</p>';
    echo '            <button class="btn btn-primary add-to-order" data-product-id="' . $product['id'] . '" data-product-name="' . htmlspecialchars($product['name']) . '" data-product-price="' . $product['price'] . '" data-product-image="../uploads/' . htmlspecialchars($product['image']) . '">Add to My Order</button>';
    echo '        </div>';
    echo '    </div>';
    echo '</div>';
}

$query->close();
$conn->close();
?>
