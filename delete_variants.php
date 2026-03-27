<?php
include "db.php";
session_start();

if(!isset($_SESSION['admin_id'])){
    die("Access denied.");
}

if(isset($_GET['id'])){
    $variant_id = (int) $_GET['id'];

    $sql = "DELETE FROM product_variants WHERE variant_id = $variant_id";
    if($conn->query($sql)){
        header("Location: view_all_variants.php?msg=deleted");
        exit;
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
