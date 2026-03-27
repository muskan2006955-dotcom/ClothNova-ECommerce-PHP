<?php
include "db.php";
session_start();
if(!isset($_SESSION['admin_id'])) die("Access denied.");

$product_id = $_GET['product_id'] ?? 0;

// Fetch product
$product = $conn->query("SELECT * FROM products WHERE product_id=$product_id")->fetch_assoc();

// Fetch extra images
$images = $conn->query("SELECT * FROM product_images WHERE product_id=$product_id");

if($_SERVER['REQUEST_METHOD'] == "POST"){
    $name  = $_POST['name'];
    $price = $_POST['price'];
    $desc  = $_POST['description'];
    $main  = $_POST['main_category'];
    $sub   = $_POST['sub_category'];
    $category = $_POST['category'];
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;

    // Main image
    $image = $product['image'];
    if(!empty($_FILES['image']['name'])){
        $image = time() . "_" . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], "images/" . $image);
    }

    // Update products table
    $sql = "UPDATE products SET 
            name='$name', price='$price', description='$desc', 
            main_category='$main', sub_category='$sub', category='$category',
            image='$image', is_featured='$is_featured'
            WHERE product_id=$product_id";

    if($conn->query($sql)){

        // Extra images update
        if(!empty($_FILES['extra_images']['name'][0])){
            // Delete old images from folder & table
            $old_images = $conn->query("SELECT image FROM product_images WHERE product_id=$product_id");
            while($img = $old_images->fetch_assoc()){
                if(file_exists("images/".$img['image'])) unlink("images/".$img['image']);
            }
            $conn->query("DELETE FROM product_images WHERE product_id=$product_id");

            // Insert new extra images
            foreach($_FILES['extra_images']['tmp_name'] as $key => $tmp_name){
                if(!empty($_FILES['extra_images']['name'][$key])){
                    $new_img = time() . "_" . basename($_FILES['extra_images']['name'][$key]);
                    move_uploaded_file($tmp_name, "images/" . $new_img);
                    $conn->query("INSERT INTO product_images (product_id, image) VALUES ('$product_id', '$new_img')");
                }
            }
        }

        header("Location: view_product_images.php");
        exit();
    } else {
        echo "Error: ".$conn->error;
    }
}
?>
