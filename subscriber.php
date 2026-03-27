<?php
session_start();
include "db.php";

if(!isset($_SESSION['admin_id'])){
    header("Location: admin_login.php");
    exit();
}

// Fetch all subscribed users
$subscribers = $conn->query("SELECT user_id, name, email, created_at FROM users WHERE is_subscribed=1 ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Subscribers</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<style>
body { background: #f9fafb; }
.sidebar { height: 100vh; background: #fff; border-right: 1px solid #eee; }
.sidebar a { display: block; padding: 12px 20px; color: #333; text-decoration: none; }
.sidebar a:hover, .sidebar a.active6 { background: rgb(149, 31, 51); color: #fff; border-radius: 8px; }
.card { border-radius: 12px; }
.table th{
  background: rgb(149, 31, 51);
  color: white;
}
</style>
</head>
<body>
<div class="d-flex">
  <!-- Sidebar -->
  <?php include 'admin_sidebar.php' ?>

  <!-- Main -->
  <div class="flex-grow-1 p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
      <h3>User subscribers</h3>
      <div>
        <i class="bi bi-bell fs-4 me-3"></i>
<?php if(!empty($_SESSION['admin_pic'])): ?>
  <img src="<?= $_SESSION['admin_pic'] ?>" alt="Admin" class="rounded-circle" width="40" height="40" style="object-fit:cover;">
<?php else: ?>
  <i class="bi bi-person-circle fs-4"></i>
<?php endif; ?>
      </div>
    </div>


    <!-- Subscribers Table -->
    <div class="card p-3 shadow-sm">
      <h6>All Subscribed Users</h6>
      <table class="table">
        <thead>
          <tr>
            <th>User ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Subscribed At</th>
          </tr>
        </thead>
        <tbody>
          <?php while($row = $subscribers->fetch_assoc()): ?>
          <tr>
            <td><?= $row['user_id'] ?></td>
            <td><?= $row['name'] ?></td>
            <td><?= $row['email'] ?></td>
            <td><?= date("d M Y, h:i A", strtotime($row['created_at'])) ?></td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
</body>
</html>
