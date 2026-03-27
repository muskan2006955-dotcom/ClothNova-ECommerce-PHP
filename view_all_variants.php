<?php
session_start();
include "db.php";

// Sirf admin allow
if(!isset($_SESSION['admin_id'])){
    die("Access denied. Please login as admin.");
}

// Variants + product join
$sql = "SELECT v.variant_id, v.product_id, v.size, v.color, v.extra_price, 
               p.name AS product_name, p.price AS base_price
        FROM product_variants v
        JOIN products p ON v.product_id = p.product_id
        ORDER BY p.product_id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Variants</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body { margin:0; font-family: Arial, sans-serif; }
    .sidebar { width:200px; height:100vh; background:#fff; border-right:1px solid #eee; position:fixed; top:0; left:0; }
    .sidebar a { display:block; padding:12px 20px; color:#333; text-decoration:none; }
    .sidebar a:hover, .sidebar a.active { background:#0d6efd; color:#fff; border-radius:8px; }
    .main-content { margin-left:200px; padding:20px; }
    .card { border-radius:12px; }
    .color-circle { display:inline-block; width:20px; height:20px; border-radius:50%; border:1px solid #000; margin-right:5px; vertical-align:middle; }
  .sidebar a:hover, .sidebar a.active5 {
  background: rgb(149, 31, 51);
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
      <h3>Add New Product</h3>
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
      <table class="custom-header">
          <tr>
            <th>Product</th>
            <th>Base Price</th>
            <th>Size</th>
            <th>Color</th>
            <th>Extra Price</th>
            <th>Total Price</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php while($row = $result->fetch_assoc()): 
              $total_price = $row['base_price'] + $row['extra_price'];
          ?>
          <tr>
            <td><?= $row['product_name']; ?></td>
            <td>Rs. <?= number_format($row['base_price'],2); ?></td>
            <td><?= $row['size']; ?></td>
            <td>
              <span class="color-circle" style="background:<?= $row['color']; ?>;"></span>
              <?= $row['color']; ?>
            </td>
            <td>+ Rs. <?= number_format($row['extra_price'],2); ?></td>
            <td>Rs. <?= number_format($total_price,2); ?></td>
            <td>
              <a href="edit_variant.php?id=<?= $row['variant_id']; ?>" class="btn btn-sm btn-warning">Edit</a>
              <a href="delete_variants.php?id=<?= $row['variant_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this variant?')">Delete</a>
            </td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
      <?php else: ?>
        <p class="text-center mt-3">No variants found.</p>
      <?php endif; ?>
    </div>
  </div>
</div>
</body>
</html>
