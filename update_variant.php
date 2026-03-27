<?php
include "db.php";
session_start();
if(!isset($_SESSION['admin_id'])) die("Access denied.");

$variant_id = $_GET['variant_id'] ?? 0;

// Variant ka data
$variant = $conn->query("SELECT * FROM product_variants WHERE variant_id=$variant_id")->fetch_assoc();
$product_id = $variant['product_id']; // redirect ke liye

if($_SERVER['REQUEST_METHOD'] == "POST"){
    $size = $_POST['size'];
    $color = $_POST['color'];
    $stock = $_POST['stock'];
    $extra_price = $_POST['extra_price'];

    $sql = "UPDATE product_variants SET 
            size='$size', color='$color', stock='$stock', extra_price='$extra_price'
            WHERE variant_id=$variant_id";

    if($conn->query($sql)){
        header("Location: view_variants.php?product_id=$product_id");
        exit();
    } else {
        $error = "❌ Error: ".$conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Update Variant</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body { margin:0; font-family: Arial, sans-serif; }
    .sidebar { width:200px; height:100vh; background:#fff; border-right:1px solid #eee; position:fixed; top:0; left:0; }
    .sidebar a { display:block; padding:12px 20px; color:#333; text-decoration:none; }
    .sidebar a:hover, .sidebar a.active { background:#0d6efd; color:#fff; border-radius:8px; }
    .main-content { margin-left:200px; padding:20px; }
    .card { border-radius:12px; padding:20px; box-shadow:0 0 10px rgba(0,0,0,0.05); }
  </style>
</head>
<body>
<div class="d-flex">

  <!-- Sidebar -->
  <?php include "admin_sidebar.php"; ?>

  <!-- Main Content -->
  <div class="main-content flex-grow-1">
                <div class="d-flex justify-content-between align-items-center mb-4">
      <h3>update Product</h3>
      <div>
        <i class="bi bi-bell fs-4 me-3"></i>
<?php if(!empty($_SESSION['admin_pic'])): ?>
  <img src="<?= $_SESSION['admin_pic'] ?>" alt="Admin" class="rounded-circle" width="40" height="40" style="object-fit:cover;">
<?php else: ?>
  <i class="bi bi-person-circle fs-4"></i>
<?php endif; ?>
      </div>
    </div>
   

    <div class="card">
      <?php if(isset($error)) echo "<p class='text-danger'>$error</p>"; ?>
      <form method="POST" class="row g-3">
          <div class="col-md-6">
              <label class="form-label">Size</label>
              <select name="size" class="form-select" required>
                  <?php 
                  $sizes = ['S','M','L','XL','XXL'];
                  foreach($sizes as $s){
                      $sel = ($s == $variant['size']) ? "selected" : "";
                      echo "<option value='$s' $sel>$s</option>";
                  }
                  ?>
              </select>
          </div>
          <div class="col-md-6">
              <label class="form-label">Color</label>
              <select name="color" class="form-select" required>
                  <?php 
                  $colors = ['Red','Blue','Black','White','Green','Yellow','Pink','Purple','Orange','Grey','Brown','Beige','Navy','Maroon','Cyan'];
                  foreach($colors as $c){
                      $sel = ($c == $variant['color']) ? "selected" : "";
                      echo "<option value='$c' $sel>$c</option>";
                  }
                  ?>
              </select>
          </div>
          <div class="col-md-6">
              <label class="form-label">Stock</label>
              <input type="number" name="stock" class="form-control" value="<?= $variant['stock']; ?>" required>
          </div>
          <div class="col-md-6">
              <label class="form-label">Extra Price</label>
              <input type="number" step="0.01" name="extra_price" class="form-control" value="<?= $variant['extra_price']; ?>">
          </div>
          <div class="col-12">
              <button type="submit" class="btn btn-primary px-4">Update Variant</button>
          </div>
      </form>
    </div>
  </div>
</div>
</body>
</html>
