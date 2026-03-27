<?php
session_start();
include "db.php";

// User login check
if(!isset($_SESSION['user_id'])){
    die("Please login first.");
}

$user_id = $_SESSION['user_id'];

// Cart item id URL se lo
if(isset($_GET['id'])){
    $cart_id = (int)$_GET['id'];

    // Delete sirf us user ka cart item
    $sql = "DELETE FROM cart WHERE cart_id = $cart_id AND user_id = $user_id";
    if($conn->query($sql)){
        header("Location: cart.php"); // wapis cart page par bhej do
        exit;
    } else {
        echo "Error deleting item: " . $conn->error;
    }
} else {
    echo "Invalid request.";
}
