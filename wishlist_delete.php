<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    die("Please login first.");
}

$user_id = (int)$_SESSION['user_id'];

if (isset($_GET['product_id'])) {
    $product_id = (int)$_GET['product_id'];

    // delete query
    $sql = "DELETE FROM wishlist WHERE user_id=$user_id AND product_id=$product_id";
    $conn->query($sql);
}

// wapas wishlist page pe bhejo
header("Location: wishlist_show.php");
exit;
