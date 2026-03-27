<?php
include "db.php";
session_start();
if(!isset($_SESSION['admin_id'])) die("Access denied.");

$product_id = $_GET['product_id'] ?? 0;

// Fetch product info
$product = $conn->query("SELECT * FROM products WHERE product_id=$product_id")->fetch_assoc();
$images = $conn->query("SELECT * FROM product_images WHERE product_id=$product_id");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>View Product Images</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body { margin:0; font-family: Arial, sans-serif; }
    .sidebar { width:200px; height:100vh; background:#fff; border-right:1px solid #eee; position:fixed; top:0; left:0; }
    .sidebar a { display:block; padding:12px 20px; color:#333; text-decoration:none; }
    .sidebar a:hover, .sidebar a.active { background:#0d6efd; color:#fff; border-radius:8px; }
    .main-content { margin-left:200px; padding:20px; }
    .card { border-radius:12px; padding:20px; box-shadow:0 0 10px rgba(0,0,0,0.05); margin-bottom:20px; }
    .img-thumbnail { border-radius:8px; margin-bottom:10px; }
  </style>
</head>
<body>
<div class="d-flex">

  <!-- Sidebar -->
  <?php include "admin_sidebar.php"; ?>

  <!-- Main Content -->
  <div class="main-content flex-grow-1">
              <div class="d-flex justify-content-between align-items-center mb-4">
      <h3>Images for <?= $product['name']; ?></h3>
      <div>
        <i class="bi bi-bell fs-4 me-3"></i>
<?php if(!empty($_SESSION['admin_pic'])): ?>
  <img src="<?= $_SESSION['admin_pic'] ?>" alt="Admin" class="rounded-circle" width="40" height="40" style="object-fit:cover;">
<?php else: ?>
  <i class="bi bi-person-circle fs-4"></i>
<?php endif; ?>
      </div>
    </div>

    <!-- Single Card for Main + Extra Images -->
<!-- Single Card for Main + Extra Images -->
<div class="card">
    <h5>Images</h5>
    <div class="d-flex align-items-start flex-wrap">
        <!-- Main Image -->
        <div class="me-3 text-center">
            <p class="mb-1"><strong>Main Image</strong></p>
            <img src="images/<?= $product['image']; ?>" width="250" class="img-thumbnail">
        </div>

        <!-- Extra Images -->
        <div class="text-center">
            <p class="mb-1"><strong>Extra Images</strong></p>
            <div class="d-flex flex-wrap">
                <?php if($images->num_rows > 0): ?>
                    <?php while($img = $images->fetch_assoc()): ?>
                        <div class="me-2 mb-2 text-center">
                            <img src="images/<?= $img['image']; ?>" width="150" class="img-thumbnail">
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p class="text-muted">No extra images uploaded.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

    <a href="view_products.php" class="btn btn-secondary mt-3">⬅ Back to Products</a>
  </div>
</div>
</body>
</html>
