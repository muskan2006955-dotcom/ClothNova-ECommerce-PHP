<?php
include "header.php"; // ✅ Header
include "db.php";

$message = "";

// Handle form submit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $msg = trim($_POST['message']);

    if (!empty($name) && !empty($email) && !empty($msg)) {
        $sql = "INSERT INTO contacts (name, email, message) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $name, $email, $msg);

        if ($stmt->execute()) {
            $message = "✅ Message sent successfully!";
        } else {
            $message = "❌ Error: " . $conn->error;
        }
    } else {
        $message = "⚠️ Please fill all fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Contact Us - AutoWorld</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
    background: linear-gradient(to right, #ffe6f0, #fff0e6);
  }

    .contact-container {
      padding: 50px 20px;
      background-color: #f8f9fa;
    }

    .contact-form-box {
      background: #fff;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }

    .contact-form-box input,
    .contact-form-box textarea {
      border-radius: 4px;
    }

    .contact-form-box button {
      background-color: #007bff;
      color: #fff;
      font-weight: bold;
      border-radius: 4px;
    }

    .map-box iframe {
      width: 100%;
      height: 400px;
      border: none;
      border-radius: 10px;
    }

    .site-map-title {
      font-weight: bold;
      font-size: 20px;
      margin-bottom: 15px;
    }

  </style>
</head>
<body>

<div class="container contact-container">
  <div class="row">
    <!-- Left: Contact Form -->
    <div class="col-md-6">
      <div class="contact-form-box">
        <h3>Contact Us</h3>

        <?php if($message): ?>
          <div class="alert alert-info mt-3"><?php echo $message; ?></div>
        <?php endif; ?>

        <form method="POST" class="mt-3">
          <div class="mb-3">
            <label>Username</label>
            <input type="text" name="name" class="form-control" required>
          </div>
          <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required>
          </div>
          <div class="mb-3">
            <label>Message</label>
            <textarea name="message" class="form-control" rows="5" required></textarea>
          </div>
          <button type="submit" class="btn w-100">SUBMIT</button>
        </form>
      </div>
    </div>

    <!-- Right: Google Map -->
    <div class="col-md-6 mt-4 mt-md-0">
      <div class="site-map-title">Our Site Map</div>
      <div class="map-box">
        <iframe 
          src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2624.9999238577583!2d2.2933083156741884!3d48.858844079287616!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47e66fdf4b0e2db3%3A0xdda1f771a53e8bb!2sEiffel%20Tower!5e0!3m2!1sen!2sfr!4v1600000000000!5m2!1sen!2sfr" 
          allowfullscreen="" loading="lazy">
        </iframe>
      </div>
    </div>
  </div>
</div>

<footer class="text-dark pt-5 mt-5" style="background-color:#f9f9f9; border-top:1px solid #ddd;">
  <div class="container-fluid">
    <div class="row">

      <!-- About / Brand -->
      <div class="col-md-4 mb-4">
        <h5 class="fw-bold">Cloth Nova</h5>
        <p>Trendy fashion for everyone. Quality clothing, accessories, and more to elevate your style.</p>
      </div>

      <!-- Quick Links -->
      <div class="col-md-2 mb-4">
        <h6 class="fw-bold">Quick Links</h6>
        <ul class="list-unstyled">
          <li><a href="index.php" class="text-dark text-decoration-none">Home</a></li>
          <li><a href="products.php" class="text-dark text-decoration-none">Shop</a></li>
          <li><a href="about.php" class="text-dark text-decoration-none">About Us</a></li>
          <li><a href="contactus.php" class="text-dark text-decoration-none">Contact Us</a></li>
        </ul>
      </div>

      <!-- Customer Service -->
      <div class="col-md-3 mb-4">
        <h6 class="fw-bold">Customer Service</h6>
        <ul class="list-unstyled">
          <li><a href="#" class="text-dark text-decoration-none">Shipping & Returns</a></li>
          <li><a href="#" class="text-dark text-decoration-none">Privacy Policy</a></li>
          <li><a href="#" class="text-dark text-decoration-none">Terms & Conditions</a></li>
        </ul>
      </div>

      <!-- Contact / Social -->
      <div class="col-md-3 mb-4">
        <h6 class="fw-bold">Contact Us</h6>
        <p>Email: info@clothnova.com</p>
        <p>Phone: +92 300 1234567</p>
        <div class="d-flex gap-2">
          <a href="#" class="text-dark fs-5"><i class="bi bi-facebook"></i></a>
          <a href="#" class="text-dark fs-5"><i class="bi bi-instagram"></i></a>
          <a href="#" class="text-dark fs-5"><i class="bi bi-twitter"></i></a>
        </div>
      </div>

    </div>

    <hr>

    <div class="text-center pb-3">
      &copy; <?php echo date('Y'); ?> Cloth Nova. All rights reserved.
    </div>
  </div>
</footer>
</body>
</html>
