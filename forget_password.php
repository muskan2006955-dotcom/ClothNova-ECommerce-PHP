<?php
session_start();
include "db.php";

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "❌ Please enter a valid email address.";
    } else {
        $emailEsc = $conn->real_escape_string($email);
        $res = $conn->query("SELECT user_id FROM users WHERE email = '$emailEsc' LIMIT 1");
        if ($res && $res->num_rows > 0) {
            // Email exists → store in session and redirect to reset page (no link)
            $_SESSION['reset_email'] = $emailEsc;

            // Optional: you can also set a short-lived flag to prevent direct open of reset page
            $_SESSION['allow_reset'] = time(); // timestamp

            header("Location: reset_password.php");
            exit;
        } else {
            $message = "❌ No account found with that email.";
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Forgot Password</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background: linear-gradient(135deg,#fff8f0,#fff0f8); }
    .card { border-radius: 12px; box-shadow: 0 6px 20px rgba(0,0,0,0.06); }
  </style>
</head>
<body>
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
      <div class="card p-4">
        <h4 class="mb-3 text-center">Forgot Password</h4>
        <p class="text-muted text-center">Enter your registered email. If it exists we'll take you to the challenge to reset your password.</p>

        <?php if ($message): ?>
          <div class="alert alert-info"><?php echo $message; ?></div>
        <?php endif; ?>

        <form method="POST">
          <div class="mb-3">
            <label class="form-label">Email address</label>
            <input type="email" name="email" class="form-control" required placeholder="Enter your registered email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
          </div>
          <button type="submit" class="btn btn-primary w-100">Proceed</button>
        </form>

        <p class="text-center mt-3"><a href="login.php">Back to Login</a></p>
      </div>
    </div>
  </div>
</div>
</body>
</html>
