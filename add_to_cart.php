<?php
session_start();
include "db.php";

if(!isset($_SESSION['user_id'])){
    die("Please login first.");
}

$user_id = $_SESSION['user_id'];
$product_id = intval($_POST['product_id']);
$quantity = intval($_POST['quantity']);

// Default
$variant_id = "NULL";

// Agar size & color select kiye hain to variant dhundo
if (!empty($_POST['size']) && !empty($_POST['color'])) {
    $size = $conn->real_escape_string($_POST['size']);
    $color = $conn->real_escape_string($_POST['color']);

    $variantSql = "SELECT variant_id FROM product_variants 
                   WHERE product_id = $product_id 
                   AND size = '$size' 
                   AND color = '$color' LIMIT 1";
    $variantRes = $conn->query($variantSql);
    if($variantRes->num_rows > 0){
        $variantRow = $variantRes->fetch_assoc();
        $variant_id = $variantRow['variant_id'];
    }
}

// Check if same product+variant already exists
$checkSql = "SELECT * FROM cart 
             WHERE user_id = $user_id 
             AND product_id = $product_id 
             AND " . ($variant_id !== "NULL" ? "variant_id = $variant_id" : "variant_id IS NULL");

$checkRes = $conn->query($checkSql);

if($checkRes->num_rows > 0){
    // Update quantity
    $row = $checkRes->fetch_assoc();
    $newQty = $row['quantity'] + $quantity;
    $conn->query("UPDATE cart SET quantity = $newQty WHERE cart_id = ".$row['cart_id']);
} else {
    // Insert new row
    $conn->query("INSERT INTO cart (user_id, product_id, variant_id, quantity) 
                  VALUES ($user_id, $product_id, ".($variant_id !== "NULL" ? $variant_id : "NULL").", $quantity)");
}

header("Location: cart.php");
exit;
?>
