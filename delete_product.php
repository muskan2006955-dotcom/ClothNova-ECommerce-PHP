<?php
include "db.php";
session_start();
if(!isset($_SESSION['admin_id'])) die("Access denied.");

$product_id = $_GET['product_id'] ?? 0;

// Pehle variants delete karo
$conn->query("DELETE FROM product_variants WHERE product_id=$product_id");

// Phir product delete karo
$conn->query("DELETE FROM products WHERE product_id=$product_id");

header("Location: view_products.php");
exit();
?>
