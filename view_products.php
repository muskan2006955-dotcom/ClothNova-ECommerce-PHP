<?php
include "db.php";
session_start();

// Sirf admin allow
if(!isset($_SESSION['admin_id'])){
    die("Access denied.");
}

// Products with total stock
$sql = "SELECT p.product_id, p.name, p.price, p.main_category, p.sub_category,
               (SELECT SUM(v.stock) FROM product_variants v WHERE v.product_id = p.product_id) as total_stock
        FROM products p
        ORDER BY p.created_at DESC";
$products = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>All Products</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body { margin:0; font-family: Arial, sans-serif; }
    .sidebar { width:200px; height:100vh; background:#fff; border-right:1px solid #eee; position:fixed; top:0; left:0; }
    .sidebar a { display:block; padding:12px 20px; color:#333; text-decoration:none; }
    .sidebar a:hover, .sidebar a.active { background:#0d6efd; color:#fff; border-radius:8px; }
    .main-content { margin-left:200px; padding:20px; }
    .card { border-radius:12px; }
    .sidebar a:hover, .sidebar a.active3 {
  background:rgb(149, 31, 51);
  color: #fff;
  border-radius: 8px;
}
    .custom-header th{
  background: rgb(149, 31, 51); /* orange gradient */
  color: #fff;
  text-transform: uppercase;
  letter-spacing: 1px;
}
  </style>
</head>
<body>
<div class="d-flex">

  <!-- Sidebar -->
  <?php include "admin_sidebar.php"; ?>

  <!-- Main Content -->
  <div class="main-content flex-grow-1">
    
            <div class="d-flex justify-content-between align-items-center mb-4">
      <h3>All Products</h3>
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
      <table class="table table-bordered table-hover mb-0">
        <thead class="custom-header">
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Price</th>
            <th>Main Category</th>
            <th>Sub Category</th>
            <th>Total Stock</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php while($p = $products->fetch_assoc()): ?>
          <tr>
            <td><?= $p['product_id']; ?></td>
            <td><?= $p['name']; ?></td>
            <td>$<?= number_format($p['price'],2); ?></td>
            <td><?= $p['main_category']; ?></td>
            <td><?= $p['sub_category']; ?></td>
            <td><?= $p['total_stock'] ?? 0; ?></td>
            <td>
              <a href="update_product.php?product_id=<?= $p['product_id']; ?>" class="btn btn-sm btn-warning">Update</a>
              <a href="delete_product.php?product_id=<?= $p['product_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
              <a href="view_variants.php?product_id=<?= $p['product_id']; ?>" class="btn btn-sm btn-info"> Variants</a>
              <a href="view_product_images.php?product_id=<?= $p['product_id']; ?>" class="btn btn-sm btn-info">Images</a>
            </td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>

  </div>
</div>
</body>
</html>
