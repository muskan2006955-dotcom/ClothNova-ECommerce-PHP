<?php
session_start();
include "db.php";

$message = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // check if email already exists
    $check = $conn->query("SELECT * FROM users WHERE email='$email'");
    if($check->num_rows > 0){
        $message = "⚠️ Email already registered!";
    } else {
        $sql = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$password')";
        if($conn->query($sql)){
            $message = "✅ Registration successful! <a href='login.php'>Login here</a>";
        } else {
            $message = "❌ Error: " . $conn->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Signup - Cloth Nova</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(to right, #ffe6f0, #fff0e6);
    }
    .signup-box {
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
<div class="signup-box">
  <div class="offer-banner mb-3">
    <img src="clothnava.jpg" alt="Offer" style="height: 200px; object-fit: cover;">
  </div>

  <h4 class="text-center mb-3">Create Your Account</h4>

  <?php if($message): ?>
    <div class="alert alert-info"><?php echo $message; ?></div>
  <?php endif; ?>

  <form method="POST">
    <div class="mb-3">
      <input type="text" name="name" class="form-control" placeholder="Full Name" required>
    </div>
    <div class="mb-3">
      <input type="email" name="email" class="form-control" placeholder="Enter Email" required>
    </div>
    <div class="mb-3">
      <input type="password" name="password" class="form-control" placeholder="Enter Password" required>
    </div>

    <!-- ✅ Terms & Privacy -->
    <div class="mb-3 form-check">
      <input type="checkbox" class="form-check-input" id="terms" required>
      <label class="form-check-label" for="terms">
        I agree to the <a href="#">Terms</a> & <a href="#">Privacy Policy</a>
      </label>
    </div>

    <button type="submit" class="btn btn-success w-100">Signup</button>

    <!-- ✅ Professional note -->
    <p class="mt-3 text-center text-muted" style="font-size: 14px;">
      Already have an account? <a href="login.php">Login here</a>
    </p>
  </form>
</div>

</body>
</html>
