<?php
session_start();
include "db.php";

// Agar already login hai to dashboard bhej do
if(isset($_SESSION['admin_id'])){
    header("Location: admin_dashboard.php");
    exit();
}

$error = "";

if($_SERVER['REQUEST_METHOD'] == "POST"){
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Admin ko check karo
    $sql = "SELECT * FROM admins WHERE username = '$username' AND password = '$password' LIMIT 1";
    $result = $conn->query($sql);

    if($result->num_rows == 1){
        $admin = $result->fetch_assoc();
        $_SESSION['admin_id'] = $admin['admin_id'];
        $_SESSION['admin_name'] = $admin['name'];
        $_SESSION['admin_pic']  = $admin['profile_pic'];

        header("Location: admin_dashboard.php");
        exit();
    } else {
        $error = "❌ Invalid username or password!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(to right, #ffe6f0, #fff0e6);
    }
    .login-box {
      max-width: 420px;
      margin: 60px auto;
      background: #fff;
      border-radius: 10px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.1);
      padding: 30px;
    }
    .offer-banner img {
      width: 100%;
      border-radius: 10px;
      margin-bottom: 15px;
    }
  </style>
</head>
<body>
  <?php

include 'preloader.php';

?>

<div class="login-box">
  <div class="offer-banner mb-3">
    <img src="clothnava.jpg" alt="Admin Login" style="height: 200px; object-fit: cover;">
  </div>

  <h4 class="text-center mb-3">Admin Login</h4>

  <?php if ($error): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
  <?php endif; ?>

  <form method="POST">
    <div class="mb-3">
      <input type="text" name="username" class="form-control" placeholder="Enter Username" required>
    </div>
    <div class="mb-3">
      <input type="password" name="password" class="form-control" placeholder="Enter Password" required>
    </div>

    <!-- ✅ Checkbox -->
    <div class="mb-3 form-check">
      <input type="checkbox" class="form-check-input" id="remember" required>
      <label class="form-check-label" for="remember">
        I confirm this is an <b>Admin Login</b>
      </label>
    </div>

    <button type="submit" class="btn btn-dark w-100">Login</button>

    <!-- ✅ Extra info -->
    <p class="mt-3 text-center text-muted" style="font-size: 14px;">
      Forgot password? <a href="#">Reset here</a>
    </p>
  </form>
</div>

</body>
</html>
