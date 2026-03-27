<?php
include "db.php";
session_start();

if(!isset($_SESSION['admin_id'])){
    die("Access denied.");
}

$product_id = $_GET['product_id'] ?? 0;

// Product name
$product = $conn->query("SELECT name FROM products WHERE product_id=$product_id")->fetch_assoc();

// Variants list
$variants = $conn->query("SELECT * FROM product_variants WHERE product_id=$product_id");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Product Variants</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body { margin:0; font-family: Arial, sans-serif; }
    .sidebar { width:200px; height:100vh; background:#fff; border-right:1px solid #eee; position:fixed; top:0; left:0; }
    .sidebar a { display:block; padding:12px 20px; color:#333; text-decoration:none; }
    .sidebar a:hover, .sidebar a.active { background:#0d6efd; color:#fff; border-radius:8px; }
    .main-content { margin-left:200px; padding:20px; }
    .card { border-radius:12px; }
    .btn-space > * { margin-right:5px; }
  </style>
</head>
<body>
<div class="d-flex">

  <!-- Sidebar -->
<?php
include 'admin_sidebar.php'
?>
  <!-- Main Content -->
  <div class="main-content flex-grow-1">
            <div class="d-flex justify-content-between align-items-center mb-4">
      <h3>Variants for: <?= $product['name']; ?></h3>
      <div>
        <i class="bi bi-bell fs-4 me-3"></i>
<?php if(!empty($_SESSION['admin_pic'])): ?>
  <img src="<?= $_SESSION['admin_pic'] ?>" alt="Admin" class="rounded-circle" width="40" height="40" style="object-fit:cover;">
<?php else: ?>
  <i class="bi bi-person-circle fs-4"></i>
<?php endif; ?>
      </div>
    </div>
  

    <div class="card p-4 shadow-sm">
      <?php if($variants->num_rows > 0): ?>
        <div class="table-responsive">
          <table class="table table-bordered table-hover mb-0 align-middle">
            <thead class="table-dark">
              <tr>
                <th>ID</th>
                <th>Size</th>
                <th>Color</th>
                <th>Stock</th>
                <th>Extra Price</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php while($v = $variants->fetch_assoc()): ?>
                <tr>
                  <td><?= $v['variant_id']; ?></td>
                  <td><?= $v['size']; ?></td>
                  <td>
                    <span style="display:inline-block;width:20px;height:20px;
                                 border-radius:50%;background:<?= $v['color']; ?>;
                                 border:1px solid #000; margin-right:5px;"></span>
                    <?= $v['color']; ?>
                  </td>
                  <td><?= $v['stock']; ?></td>
                  <td>Rs. <?= $v['extra_price']; ?></td>
                  <td class="btn-space">
                    <a href="update_variant.php?variant_id=<?= $v['variant_id']; ?>" class="btn btn-sm btn-warning">Update</a>
                    <a href="delete_variant.php?variant_id=<?= $v['variant_id']; ?>&product_id=<?= $product_id; ?>" 
                       class="btn btn-sm btn-danger" onclick="return confirm('Delete this variant?')">Delete</a>
                  </td>
                </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>
      <?php else: ?>
        <p class="text-muted">No variants added for this product yet.</p>
      <?php endif; ?>

      <a href="view_products.php" class="btn btn-warning mt-3">⬅ Back to Products</a>
    </div>
  </div>

</div>
</body>
</html>
