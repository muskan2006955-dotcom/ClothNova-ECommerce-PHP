<?php
session_start();
include "db.php";

if(!isset($_SESSION['user_id'])){
    echo "error|Please login first.";
    exit;
}

$user_id = $_SESSION['user_id'];
$phone   = $_POST['phone'] ?? '';
$address = $_POST['address'] ?? '';

// Check if user already has a pending order
$check = $conn->query("SELECT order_id FROM orders WHERE user_id=$user_id AND status='Pending' LIMIT 1");
if($check->num_rows > 0 && !isset($_GET['force'])) {
    echo "already_pending";
    exit;
}

// Calculate total
$sql = "SELECT (p.price + IFNULL(v.extra_price,0)) * c.quantity AS subtotal
        FROM cart c
        JOIN products p ON c.product_id = p.product_id
        LEFT JOIN product_variants v ON c.variant_id = v.variant_id
        WHERE c.user_id = $user_id";
$result = $conn->query($sql);

$total = 0;
while($row = $result->fetch_assoc()){
    $total += $row['subtotal'];
}

if($total <= 0){
    echo "error|Cart is empty.";
    exit;
}

// Generate order code
$order_code = "ORD".time().rand(100,999);

// Insert order
$conn->query("INSERT INTO orders (user_id, total, status, order_code, phone, address)
              VALUES ($user_id, $total, 'Pending', '$order_code', '$phone', '$address')");
$order_id = $conn->insert_id;

// Process cart items
$orderItems = $conn->query("SELECT c.variant_id, c.product_id, c.quantity, (p.price + IFNULL(v.extra_price,0)) AS price
              FROM cart c
              JOIN products p ON c.product_id = p.product_id
              LEFT JOIN product_variants v ON c.variant_id = v.variant_id
              WHERE c.user_id = $user_id");

while($item = $orderItems->fetch_assoc()){
    $variant_id = $item['variant_id'];
    $product_id = $item['product_id'];
    $qty = (int)$item['quantity'];
    $price = (float)$item['price'];

    if($variant_id && $variant_id != 0){
        // Check variant stock
        $checkStock = $conn->query("SELECT stock FROM product_variants WHERE variant_id=$variant_id LIMIT 1");
        if($checkStock && $checkStock->num_rows > 0){
            $row = $checkStock->fetch_assoc();
            if($row['stock'] < $qty){
                echo "error|Not enough stock for variant ID: $variant_id";
                exit;
            }
            $conn->query("UPDATE product_variants SET stock = stock - $qty WHERE variant_id = $variant_id");
        }
    } else {
        // Check product stock
        $checkStock = $conn->query("SELECT stock FROM products WHERE product_id=$product_id LIMIT 1");
        if($checkStock && $checkStock->num_rows > 0){
            $row = $checkStock->fetch_assoc();
            if($row['stock'] < $qty){
                echo "error|Not enough stock for product ID: $product_id";
                exit;
            }
            $conn->query("UPDATE products SET stock = stock - $qty WHERE product_id = $product_id");
        }
    }

    // Insert order items
    $conn->query("INSERT INTO order_items (order_id, product_id, variant_id, quantity, price)
                  VALUES ($order_id, $product_id, ".($variant_id ?: "NULL").", $qty, $price)");
}

// Clear cart
$conn->query("DELETE FROM cart WHERE user_id=$user_id");

// Clean response
ob_clean();
echo "success|$order_code|$total";
exit;
