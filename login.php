<?php
session_start();
include "db.php";

$message = "";

// Agar form submit hua hai
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $sql = "SELECT * FROM users WHERE email = '$email' LIMIT 1";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['name'] = $user['name'];
            header("Location: index.php");
            exit;
        } else {
            $message = "❌ Wrong password!";
        }
    } else {
        $message = "❌ User not found!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login</title>
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
    <img src="clothnava.jpg" alt="Offer" style="height: 200px; object-fit: cover;">
  </div>

  <h4 class="text-center mb-3">Login to Your Account</h4>

  <?php if ($message): ?>
    <div class="alert alert-danger"><?php echo $message; ?></div>
  <?php endif; ?>

  <form method="POST">
    <div class="mb-3">
      <input type="email" name="email" class="form-control" placeholder="Enter Email" required>
    </div>
    <div class="mb-3">
      <input type="password" name="password" class="form-control" placeholder="Enter Password" required>
    </div>

    <!-- ✅ Checkbox -->
    <div class="mb-3 form-check">
      <input type="checkbox" class="form-check-input" id="terms" required>
      <label class="form-check-label" for="terms">
        I agree to the <a href="#">Terms</a> & <a href="#">Privacy Policy</a>
      </label>
    </div>

    <button type="submit" class="btn btn-dark w-100">Login</button>

    <!-- ✅ Professional extra links -->
<p class="mt-3 text-center text-muted" style="font-size: 14px;">
  Forgot your password? <a href="forget_password.php">Reset it</a>
</p>

    <p class="text-center text-muted" style="font-size: 14px;">
      Don’t have an account? <a href="register.php">Create one</a>
    </p>
  </form>
</div>

</body>
</html>
