<?php
include "db.php";

$message = "";

// Handle form submit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize inputs
    $name = trim($_POST['name']);
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $msg = htmlspecialchars(trim($_POST['message']));
    $phone = isset($_POST['phone']) ? trim($_POST['phone']) : "";
    $subject = isset($_POST['subject']) ? htmlspecialchars(trim($_POST['subject'])) : "";

    // Name validation: only alphabets & spaces, min length 3
    $name_ok = preg_match("/^[A-Za-z ]{3,}$/", $name);

    if (empty($name) || empty($email) || empty($msg)) {
        $message = "⚠️ Please fill all required fields.";
    } elseif (!$name_ok) {
        $message = "⚠️ Name must be only letters (min 3 characters).";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "⚠️ Invalid email format.";
    } elseif (!empty($phone) && !preg_match("/^[0-9]{10,15}$/", $phone)) {
        $message = "⚠️ Phone must be 10–15 digits.";
    } else {
        // Insert into DB with prepared statement
        $sql = "INSERT INTO contacts (name, email, phone, subject, message) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $name, $email, $phone, $subject, $msg);

        if ($stmt->execute()) {
            $message = "✅ Message sent successfully!";
        } else {
            $message = "❌ Error: " . $conn->error;
        }
    }
}
?>

<?php if ($message): ?>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      Swal.fire({
        icon: "<?php echo (strpos($message, '✅') !== false) ? 'success' : ((strpos($message, '⚠️') !== false) ? 'warning' : 'error'); ?>",
        title: "<?php echo addslashes($message); ?>",
        showConfirmButton: false,
        timer: 2000
      });
    });
  </script>
<?php endif; ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contact Us</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(to right, #ffe6f0, #fff0e6);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      justify-content: center;
    }

    .glass-form {
      background: rgba(255, 255, 255, 0.35);
      border-radius: 15px;
      padding: 25px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
      backdrop-filter: blur(10px);
      height: 100%;
    }

    .form-control {
      border: none;
      border-bottom: 2px solid #ccc;
      border-radius: 0;
      box-shadow: none;
      background: transparent;
      height: 38px;
      font-size: 14px;
    }

    .form-control:focus {
      border-bottom: 2px solid #ff6699;
      outline: none;
      box-shadow: none;
    }

    .form-floating>.form-control:focus~label,
    .form-floating>.form-control:not(:placeholder-shown)~label {
      transform: scale(.85) translateY(-1rem);
      opacity: 0.8;
    }

    .form-floating>label {
      color: #555;
      font-size: 14px;
      transition: all 0.3s ease;
    }

    .contact-img {
      object-fit: cover;
      width: 100%;
      height: 70%;
      border-radius: 15px;
    }

    .con { height: 400px; }
  </style>
</head>
<body>
<?php
include 'navbar.php';
include 'header.php';
include 'preloader.php';
?>

<div class="container py-5">
  <div class="row align-items-stretch g-4">

    <!-- Right: Image -->
    <div class="col-md-6 ">
      <img src="istockphoto-1212230880-612x612-removebg-preview.png" class="contact-img me-5" alt="Contact Image">
    </div>

    <!-- Left: Contact Form -->
    <div class="col-md-6 con ">
      <div class="glass-form me-5">
        <h2 class="mb-4">Contact Us</h2>

        <form method="post">
          <div class="row g-3">
            <div class="col-md-6 form-floating">
              <input type="text"
                     class="form-control"
                     id="name"
                     name="name"
                     placeholder="Name"
                     required
                     pattern="[A-Za-z\s]{3,}"
                     minlength="3"
                     title="Only letters and spaces allowed, at least 3 characters">
              <label for="name">Name</label>
            </div>
            <div class="col-md-6 form-floating">
              <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
              <label for="email">Email</label>
            </div>
            <div class="col-md-6 form-floating">
              <input type="text" class="form-control" id="phone" name="phone" placeholder="Phone" pattern="[0-9]{10,15}">
              <label for="phone">Phone</label>
            </div>
            <div class="col-md-6 form-floating">
              <input type="text" class="form-control" id="subject" name="subject" placeholder="Subject">
              <label for="subject">Subject</label>
            </div>
            <div class="col-12 form-floating">
              <textarea class="form-control" placeholder="Leave your feedback here" name="message" id="feedback" style="height: 100px" required></textarea>
              <label for="feedback">Feedback</label>
            </div>
          </div>
          <button class="btn btn-danger mt-3 px-4" type="submit">Send Message</button>
        </form>

      </div>
    </div>
  </div>

  <!-- Map -->
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12 mt-4 mt-md-0 ">
        <h2 class="text-center mb-4">OUR SITES MAP</h2>
        <div class="map-box">
          <iframe 
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2624.9999238577583!2d2.2933083156741884!3d48.858844079287616!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47e66fdf4b0e2db3%3A0xdda1f771a53e8bb!2sEiffel%20Tower!5e0!3m2!1sen!2sfr!4v1600000000000!5m2!1sen!2sfr" 
            allowfullscreen="" loading="lazy" style="width: 100%; height: 320px;">
          </iframe>
        </div>
      </div>
    </div>
  </div>
</div>

</body>
</html>
