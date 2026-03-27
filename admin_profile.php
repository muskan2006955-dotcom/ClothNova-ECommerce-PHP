<?php
session_start();
include "db.php";

if(!isset($_SESSION['admin_id'])){
    header("Location: admin_login.php");
    exit();
}

$admin_id = $_SESSION['admin_id'];
$message = "";

// Admin ki detail nikal lo
$sql = "SELECT * FROM admins WHERE admin_id=$admin_id LIMIT 1";
$result = $conn->query($sql);
$admin = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Profile</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<style>
body { background: #f9fafb; }
.sidebar { height: 100vh; background: #fff; border-right: 1px solid #eee; }
.sidebar a { display: block; padding: 12px 20px; color: #333; text-decoration: none; }
.sidebar a:hover, .sidebar a.active7 { background: rgb(149, 31, 51); color: #fff; border-radius: 8px; }
.card { border-radius: 12px; }
.profile-pic { width: 120px; height: 120px; object-fit: cover; border-radius: 50%; }
</style>
</head>
<body>
<div class="d-flex">
  <!-- Sidebar -->
  <?php include 'admin_sidebar.php'; ?>

  <!-- Main Content -->
  <div class="flex-grow-1 p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h3>My Profile</h3>
      <div>
        <i class="bi bi-bell fs-4 me-3"></i>
        <?php if(!empty($admin['profile_pic'])): ?>
          <img src="<?= htmlspecialchars($admin['profile_pic']) ?>" class="rounded-circle" width="40" height="40" style="object-fit:cover;">
        <?php else: ?>
          <i class="bi bi-person-circle fs-4"></i>
        <?php endif; ?>
      </div>
    </div>

    <div class="card p-4 shadow-sm">
      <form action="admin_update_profile.php" method="POST" enctype="multipart/form-data">
        <div class="text-center mb-3">
          <?php if(!empty($admin['profile_pic'])): ?>
            <img src="<?= htmlspecialchars($admin['profile_pic']) ?>" class="profile-pic mb-2">
          <?php else: ?>
            <img src="https://via.placeholder.com/120" class="profile-pic mb-2">
          <?php endif; ?>
          <div>
            <input type="file" name="profile_pic" class="form-control mt-2" accept="image/*">
          </div>
        </div>

        <div class="mb-3">
          <label class="form-label">Full Name</label>
          <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($admin['name']) ?>" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Username</label>
          <input type="text" class="form-control" value="<?= htmlspecialchars($admin['username']) ?>" disabled>
        </div>

        <button type="submit" class="btn btn-warning w-100">Update Profile</button>
      </form>
    </div>
  </div>
</div>
</body>
</html>
