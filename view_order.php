<?php
session_start();
include "db.php";

// Sirf admin allow
if(!isset($_SESSION['admin_id'])){
    die("Access denied. Please login as admin.");
}

// Orders + user info
$sql = "SELECT o.*, u.name AS user_name, u.email
        FROM orders o
        JOIN users u ON o.user_id = u.user_id
        ORDER BY o.order_date DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Orders</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body { margin:0; font-family: Arial, sans-serif; }
    .sidebar { width:200px; height:100vh; background:#fff; border-right:1px solid #eee; position:fixed; top:0; left:0; }
    .sidebar a { display:block; padding:12px 20px; color:#333; text-decoration:none; }
    .sidebar a:hover, .sidebar a.active10 { background:rgb(149,31,51); color:#fff; border-radius:8px; }
    .main-content { margin-left:200px; padding:20px; }
    .card { border-radius:12px; }
    .table thead th { background: rgb(149,31,51); color:#fff; }
    
  </style>
</head>
<body>
<div class="d-flex">

  <!-- Sidebar -->
  <?php include "admin_sidebar.php"; ?>

  <!-- Main Content -->
  <div class="main-content flex-grow-1">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h3>Orders</h3>
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
              <th>Total</th>
              <th>Order Code</th>
              <th>Order Date</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php $i=1; while($row = $result->fetch_assoc()): ?>
              <tr>
                <td><?= $i++ ?></td>
                <td><?= htmlspecialchars($row['user_name']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td>$<?= number_format($row['total'],2) ?></td>
                <td><?= htmlspecialchars($row['order_code']) ?></td>
                <td><?= $row['order_date'] ?></td>
                <td><?= htmlspecialchars($row['status']) ?></td>
                <td>
                  <a href="update_status.php?order_id=<?= $row['order_id'] ?>" class="btn btn-sm btn-warning">
                    Update Status & Message
                  </a>
                </td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
      <?php else: ?>
        <p class="text-center mt-3 mb-0">No orders found.</p>
      <?php endif; ?>
    </div>
  </div>
</div>
</body>
</html>
