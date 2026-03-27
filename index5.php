<?php
session_start();
include "db.php";

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Default category
$category = isset($_GET['main_category']) ? $_GET['main_category'] : 'Men';

// Handle photo upload
if (isset($_POST['upload'])) {
    if (!empty($_FILES['photo']['name'])) {
        $targetDir = "uploads/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $fileName = time() . "_" . basename($_FILES['photo']['name']);
        $targetFile = $targetDir . $fileName;

        if (move_uploaded_file($_FILES['photo']['tmp_name'], $targetFile)) {
            $_SESSION['user_photo'] = $targetFile;
        }
    }
}

$userPhoto = isset($_SESSION['user_photo']) ? $_SESSION['user_photo'] : "uploads/default.png";

// Fetch products by category
$stmt = $conn->prepare("SELECT * FROM products WHERE main_category = ?");
$stmt->bind_param("s", $category);
$stmt->execute();
$result = $stmt->get_result();
$products = $result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Cloth Nova TryOn</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css"/>
  <style>
    body { background:#f8f9fa; }
    .userimg { width:100%; height:510px; object-fit:cover; border:2px solid #ccc; border-radius:10px; }
    .swiper { height:500px; }
    .swiper-slide { display:flex; flex-direction:column; align-items:center; justify-content:center; }
    .swiper-slide img { width:100%; height:500px; object-fit:cover; border-radius:10px; border:2px solid #ddd; }
    .category-buttons { text-align:center; margin-bottom:15px; }
  </style>
</head>
<body>
<div class="container py-3">

  <!-- Upload form -->
  <form action="index5.php" method="POST" enctype="multipart/form-data" class="mb-3 text-center">
    <input type="file" name="photo" required class="form-control mb-2" style="max-width:300px;display:inline-block;">
    <button type="submit" name="upload" class="btn btn-primary">Upload</button>
  </form>

  <!-- Layout row -->
  <div class="row">
    <!-- Left side (User Photo) -->
    <div class="col-md-6 text-center">
      <h5>Your Photo</h5>
      <img src="<?= htmlspecialchars($userPhoto) ?>" alt="Your Photo"  class="img-fluid userimg">
    </div>

    <!-- Right side (Slider + Filter) -->
    <div class="col-md-6">
      <div class="category-buttons">
        <a href="index5.php?main_category=Men" class="btn btn-outline-dark <?= $category=='Men'?'active':'' ?>">Men</a>
        <a href="index5.php?main_category=Women" class="btn btn-outline-dark <?= $category=='Women'?'active':'' ?>">Women</a>
        <a href="index5.php?main_category=Kids" class="btn btn-outline-dark <?= $category=='Kids'?'active':'' ?>">Kids</a>
      </div>

      <!-- Vertical Swiper Slider -->
      <div class="swiper">
        <div class="swiper-wrapper">
          <?php foreach($products as $p): ?>
            <div class="swiper-slide">
              <img src="images/<?= htmlspecialchars($p['image']) ?>" alt="<?= htmlspecialchars($p['name']) ?>">
              <p class="mt-2"><?= htmlspecialchars($p['name']) ?></p>
            </div>
          <?php endforeach; ?>
        </div>
        <!-- Slider controls -->
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
      </div>
    </div>
  </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
<script>
  var swiper = new Swiper(".swiper", {
    direction: "vertical",
    slidesPerView: 1,   // 🔥 ek waqt me sirf 1 dress
    spaceBetween: 10,
    navigation: {
      nextEl: ".swiper-button-next",
      prevEl: ".swiper-button-prev",
    },
    mousewheel: true,
  });
</script>
</body>
</html>
