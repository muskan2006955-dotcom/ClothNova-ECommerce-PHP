<?php
include "db.php";
session_start();
if(!isset($_SESSION['admin_id'])) die("Access denied.");

$product_id = $_GET['product_id'] ?? 0;

// Fetch product data
$product = $conn->query("SELECT * FROM products WHERE product_id=$product_id")->fetch_assoc();
$images = $conn->query("SELECT * FROM product_images WHERE product_id=$product_id");

if($_SERVER['REQUEST_METHOD'] == "POST"){
    $name  = $_POST['name'];
    $price = $_POST['price'];
    $desc  = $_POST['description'];
    $main  = $_POST['main_category'];
    $sub   = $_POST['sub_category'];
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;

    // Main image update
    $image = $product['image'];
    if(!empty($_FILES['image']['name'])){
        $image = time() . "_" . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], "images/" . $image);
    }

    $sql = "UPDATE products SET 
            name='$name', price='$price', description='$desc',
            main_category='$main', sub_category='$sub',
            image='$image', is_featured='$is_featured'
            WHERE product_id=$product_id";

    if($conn->query($sql)){
        // Extra images update
        if(!empty($_FILES['extra_images']['name'][0])){
            $conn->query("DELETE FROM product_images WHERE product_id=$product_id");
            foreach($_FILES['extra_images']['tmp_name'] as $key => $tmp_name){
                if(!empty($_FILES['extra_images']['name'][$key])){
                    $new_img = time() . "_" . basename($_FILES['extra_images']['name'][$key]);
                    move_uploaded_file($tmp_name, "images/" . $new_img);
                    $conn->query("INSERT INTO product_images (product_id, image) VALUES ('$product_id', '$new_img')");
                }
            }
        }
        header("Location: view_products.php");
        exit();
    } else {
        $error = "❌ Error: ".$conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Update Product</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body { margin:0; font-family: Arial, sans-serif; }
    .sidebar { width:200px; height:100vh; background:#fff; border-right:1px solid #eee; position:fixed; top:0; left:0; }
    .sidebar a { display:block; padding:12px 20px; color:#333; text-decoration:none; }
    .sidebar a:hover, .sidebar a.active { background:#0d6efd; color:#fff; border-radius:8px; }
    .main-content { margin-left:200px; padding:20px; }
    .card { border-radius:12px; padding:20px; box-shadow:0 0 10px rgba(0,0,0,0.05); }
    .img-thumbnail { border-radius:8px; }
  </style>
</head>
<body>
<div class="d-flex">

  <!-- Sidebar -->
  <?php include "admin_sidebar.php"; ?>

  <!-- Main Content -->
  <div class="main-content flex-grow-1">
                <div class="d-flex justify-content-between align-items-center mb-4">
      <h3>Update product</h3>
      <div>
        <i class="bi bi-bell fs-4 me-3"></i>
<?php if(!empty($_SESSION['admin_pic'])): ?>
  <img src="<?= $_SESSION['admin_pic'] ?>" alt="Admin" class="rounded-circle" width="40" height="40" style="object-fit:cover;">
<?php else: ?>
  <i class="bi bi-person-circle fs-4"></i>
<?php endif; ?>
      </div>
    </div>

    <div class="card">
      <?php if(isset($error)) echo "<p class='text-danger'>$error</p>"; ?>
      <form method="POST" enctype="multipart/form-data" class="row g-3">
          <div class="col-md-6">
              <label class="form-label">Product Name</label>
              <input type="text" name="name" class="form-control" value="<?= $product['name']; ?>" required>
          </div>
          <div class="col-md-6">
              <label class="form-label">Price</label>
              <input type="number" step="0.01" name="price" class="form-control" value="<?= $product['price']; ?>" required>
          </div>
          <div class="col-12">
              <label class="form-label">Description</label>
              <textarea name="description" class="form-control" rows="3"><?= $product['description']; ?></textarea>
          </div>
          <div class="col-md-6">
              <label class="form-label">Main Category</label>
              <input type="text" name="main_category" class="form-control" value="<?= $product['main_category']; ?>">
          </div>
          <div class="col-md-6">
              <label class="form-label">Sub Category</label>
              <input type="text" name="sub_category" class="form-control" value="<?= $product['sub_category']; ?>">
          </div>
          <div class="col-md-6">
              <label class="form-label">Main Image</label>
              <input type="file" name="image" class="form-control">
              <?php if($product['image']): ?>
                  <img src="images/<?= $product['image']; ?>" width="100" class="mt-2">
              <?php endif; ?>
          </div>
          <div class="col-md-6">
              <label class="form-label">Extra Images (Max 4)</label>
              <input type="file" name="extra_images[]" class="form-control" multiple>
              <div class="mt-3 d-flex flex-wrap">
                  <?php 
                  if($images->num_rows > 0){
                      while($img = $images->fetch_assoc()): ?>
                          <div class="me-2 text-center">
                              <img src="images/<?= $img['image']; ?>" width="100" class="img-thumbnail mb-1">
                              <p class="small text-muted">Current</p>
                          </div>
                  <?php endwhile; } ?>
              </div>
          </div>
          <div class="col-12 form-check">
              <input type="checkbox" name="is_featured" class="form-check-input" id="featuredCheck" <?= $product['is_featured'] ? 'checked' : ''; ?>>
              <label class="form-check-label" for="featuredCheck">Featured</label>
          </div>
          <div class="col-12">
              <button type="submit" class="btn btn-primary px-4">Update Product</button>
          </div>
      </form>
    </div>
  </div>
</div>
</body>
</html>
