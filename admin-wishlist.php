<?php
session_start();
include "db.php";

// Sirf admin allow
if(!isset($_SESSION['admin_id'])){
    die("Access denied. Please login as admin.");
}

// Wishlist with product + user info
$sql = "SELECT w.wishlist_id, w.created_at, 
               u.name AS user_name, u.email,
               p.name AS product_name, p.image
        FROM wishlist w
        JOIN users u ON w.user_id = u.user_id
        JOIN products p ON w.product_id = p.product_id
        ORDER BY w.created_at DESC";

$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Wishlist (Admin)</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body { margin:0; font-family: Arial, sans-serif; }
    .sidebar { width:200px; height:100vh; background:#fff; border-right:1px solid #eee; position:fixed; top:0; left:0; }
    .sidebar a { display:block; padding:12px 20px; color:#333; text-decoration:none; }
    .sidebar a:hover, .sidebar a.active { background:#0d6efd; color:#fff; border-radius:8px; }
    .main-content { margin-left:200px; padding:20px; }
    .card { border-radius:12px; }
    .product-img {
      width: 60px;
      height: 60px;
      object-fit: cover;
      border-radius: 5px;
    }
    .table thead th {
      background:rgb(149, 31, 51);
      color: #fff;
    }
    .sidebar a:hover, .sidebar a.active9 {
  background: rgb(149, 31, 51);
  color: #fff;
  border-radius: 8px;
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
      <h3>User Wishlists</h3>
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
      <?php if($result->num_rows > 0): ?>
      <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle mb-0">
          <thead>
            <tr>
              <th>#</th>
              <th>User</th>
              <th>Email</th>
              <th>Product</th>
              <th>Image</th>
              <th>Date Added</th>
            </tr>
          </thead>
          <tbody>
            <?php $i=1; while($row = $result->fetch_assoc()): ?>
              <tr>
                <td><?= $i++ ?></td>
                <td><?= htmlspecialchars($row['user_name']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td><?= htmlspecialchars($row['product_name']) ?></td>
                <td><img src="images/<?= htmlspecialchars($row['image']) ?>" class="product-img"></td>
                <td><?= $row['created_at'] ?></td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
      <?php else: ?>
        <p class="text-center mt-3 mb-0">No wishlist items found</p>
      <?php endif; ?>
    </div>
  </div>
</div>
</body>
</html>
