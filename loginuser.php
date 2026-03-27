<?php include("db.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login / Signup</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(to right, #ffe6f0, #fff0e6);
    }
    .login-box {
      max-width: 400px;
      margin: 60px auto;
      background: #fff;
      border-radius: 10px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.1);
      padding: 30px;
    }
    .offer-banner img {
      width: 100%;
      border-top-left-radius: 10px;
      border-top-right-radius: 10px;
    }
  </style>
</head>
<body>

<div class="login-box">
  <div class="offer-banner mb-3">
    <img src="clothnava.jpg" alt="Offer">
  </div>

  <h4 class="mb-3 text-center">Login or Signup</h4>

  <form action="process.php" method="POST">
    <div class="mb-3">
      <input type="email" name="email" class="form-control" placeholder="Enter Email" required>
    </div>

    <div class="mb-3">
      <input type="password" name="password" class="form-control" placeholder="Enter Password" required>
    </div>

    <div class="mb-3 form-check">
      <input type="checkbox" class="form-check-input" id="terms" required>
      <label class="form-check-label" for="terms">
        I agree to the <a href="#">Terms</a> & <a href="#">Privacy Policy</a>
      </label>
    </div>

    <button type="submit" name="submit" class="btn btn-dark w-100">Continue</button>
  </form>

  <p class="mt-3 text-center">
    Trouble logging in? <a href="#">Get Help</a>
  </p>
</div>

</body>
</html>
