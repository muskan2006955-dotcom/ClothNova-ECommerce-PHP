<?php
include "db.php";
session_start();
if(!isset($_SESSION['admin_id'])) die("Access denied.");

$variant_id = $_GET['variant_id'] ?? 0;
$conn->query("DELETE FROM product_variants WHERE variant_id=$variant_id");

header("Location: view_variants.php?product_id=".$_GET['product_id']);
exit();
?>
