<?php
session_start();
include 'db.php';

if(!isset($_SESSION['user_id'])){
    echo "Please login to add wishlist.";
    exit;
}

$user_id = (int)$_SESSION['user_id'];
$product_id = isset($_GET['product_id']) ? (int)$_GET['product_id'] : 0;

if($product_id <= 0){
    echo "Invalid product ID.";
    exit;
}

$result = $conn->query("SELECT * FROM products WHERE product_id = $product_id");
if($result->num_rows == 0){
    echo "Product does not exist.";
    exit;
}

$wish_check = $conn->query("SELECT * FROM wishlist WHERE user_id = $user_id AND product_id = $product_id");
if($wish_check->num_rows > 0){
    $conn->query("DELETE FROM wishlist WHERE user_id = $user_id AND product_id = $product_id");
    echo "Removed from Wishlist";
    header("Location: index2.php");
} else {
    $conn->query("INSERT INTO wishlist(user_id, product_id) VALUES($user_id, $product_id)");
    echo "Added to Wishlist";
        header("Location: index2.php");

}
?>
