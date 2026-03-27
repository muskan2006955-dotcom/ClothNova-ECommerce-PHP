<?php
include "db.php";
session_start();

if(!isset($_SESSION['admin_id'])){
    die("Access denied.");
}

$variant_id = (int) ($_GET['id'] ?? 0);

// Get existing data
$sql = "SELECT * FROM product_variants WHERE variant_id = $variant_id";
$result = $conn->query($sql);
if($result->num_rows == 0){
    die("Variant not found.");
}
$variant = $result->fetch_assoc();

// Update if submitted
if($_SERVER['REQUEST_METHOD'] == "POST"){
    $size = $_POST['size'];
    $color = $_POST['color'];
    $extra_price = $_POST['extra_price'];

    $update = "UPDATE product_variants 
               SET size='$size', color='$color', extra_price='$extra_price' 
               WHERE variant_id = $variant_id";
    if($conn->query($update)){
        header("Location: view_all_variants.php?msg=updated");
        exit;
    } else {
        echo "<p class='text-danger'>Error: " . $conn->error . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Variant</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body { display:flex; min-height:100vh; font-family:Arial, sans-serif; margin:0; }
  
    .content { flex-grow:1; padding:20px; }
    .card { border-radius:12px; }
  </style>
</head>
<body>

<!-- Sidebar -->

<?php
include 'admin_sidebar.php';
?>

<!-- Main Content -->
<div class="content">
  <div class="card shadow-sm p-4">
    <div class="text-primary mb-3">
      <h3>Edit Variant</h3>
    </div>

    <form method="POST">
      <div class="mb-3">
        <label class="form-label">Size</label>
        <input type="text" name="size" value="<?= $variant['size']; ?>" class="form-control" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Color</label>
        <input type="text" name="color" value="<?= $variant['color']; ?>" class="form-control" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Extra Price</label>
        <input type="number" step="0.01" name="extra_price" value="<?= $variant['extra_price']; ?>" class="form-control" required>
      </div>

      <button type="submit" class="btn btn-primary">Update Variant</button>
      <a href="view_all_variants.php" class="btn btn-secondary">Cancel</a>
    </form>
  </div>
</div>

</body>
</html>
