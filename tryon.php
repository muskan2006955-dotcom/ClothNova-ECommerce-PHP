<?php
include "db.php";
session_start();

// Default men
$category = isset($_GET['main_category']) ? $_GET['main_category'] : 'Men';

// Handle photo upload
if (isset($_POST['upload'])) {
    if (!empty($_FILES['photo']['name'])) {
        $targetDir = "uploads/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true); // folder banade agar na ho
        }

        $fileName = time() . "_" . basename($_FILES['photo']['name']);
        $targetFile = $targetDir . $fileName;

        if (move_uploaded_file($_FILES['photo']['tmp_name'], $targetFile)) {
            $_SESSION['user_photo'] = $targetFile; // session me save
        }
    }
}

// Get user photo (default agar upload na ho)
$userPhoto = isset($_SESSION['user_photo']) ? $_SESSION['user_photo'] : "uploads/default.png";

// Fetch products
$stmt = $conn->prepare("SELECT * FROM products WHERE main_category = ?");
$stmt->bind_param("s", $category);
$stmt->execute();
$result = $stmt->get_result();
$products = $result->fetch_all(MYSQLI_ASSOC);
?>
