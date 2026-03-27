<?php
session_start();
include 'db.php';

// agar user login nahi hai to redirect
if(!isset($_SESSION['user_id'])){
  header("Location: login.php");
  exit;
}

$user_id = (int)$_SESSION['user_id'];

// user data nikaalo
$sql = "SELECT * FROM users WHERE user_id = $user_id LIMIT 1";
$result = $conn->query($sql);
$user = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Profile</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background:#f8f9fa; }



    .sidebar a {
      color: #333;
      text-decoration: none;
      display: block;
      margin: 10px 0;
      font-weight: 500;
    }
    .sidebar a:hover {
      color: #0d6efd;
    }
    .profile-pic {
      width: 80px; height: 80px;
      border-radius: 50%; object-fit: cover;
      margin-bottom: 15px;
      border: 2px solid #ddd;
    }

    .sidebar {
  width: 300px;
  min-height: 100vh;
  background: #fff;
  border-right: 1px solid #dee2e6;
  padding: 20px;
}

.main-content {
  flex-grow: 1;
  padding: 40px;
  /* pehle yahan margin-left:300px tha → isy hata do */
  max-width: 900px;  /* optional, form zyada lamba na lage */
}

  </style>
</head>
<body>
<div class="d-flex">
  <!-- Sidebar -->
  <div class="sidebar">
    <?php
include 'user_sidebar.php';
?>

  </div>

  <!-- Main Content -->
  <div class="main-content">
    <h3 class="mb-4 text-warning">My Profile</h3>
    <!-- form -->
     <form action="profile_update.php" method="post" enctype="multipart/form-data" class="card p-4 shadow-sm bg-white">
      <div class="row mb-3">
        <div class="col-md-6">
          <label class="form-label">Name</label>
          <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($user['name']) ?>">
        </div>
        <div class="col-md-6">
          <label class="form-label">Email</label>
          <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" readonly>
        </div>
      </div>

      <div class="row mb-3">
        <div class="col-md-6">
          <label class="form-label">Password (leave blank if unchanged)</label>
          <input type="password" name="password" class="form-control">
        </div>
        <div class="col-md-6">
          <label class="form-label">Profile Picture</label>
          <input type="file" name="profile_pic" class="form-control">
        </div>
      </div>

      <div class="mb-3 form-check">
        <input type="checkbox" name="is_subscribed" class="form-check-input" 
          <?= $user['is_subscribed'] ? 'checked' : '' ?>>
        <label class="form-check-label">Subscribe to newsletter</label>
      </div>

      <button type="submit" class="btn btn-warning">Save Changes</button>
    </form>
  </div>
</div>


</body>
</html>
