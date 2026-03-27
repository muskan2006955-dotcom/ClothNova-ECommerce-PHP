<?php 
include "db.php"; 
session_start();  

// Sirf admin ko allow karo
if(!isset($_SESSION['admin_id'])){
    die("Access denied. Please login as admin."); 
}

// Form submit hua?
if($_SERVER['REQUEST_METHOD'] == "POST"){     
    $product_id  = $_POST['product_id'] ?? 0;     
    $size        = $_POST['size'] ?? '';     
    $color       = $_POST['color'] ?? '';     
    $extra_price = $_POST['extra_price'] ?? 0;     
    $stock       = $_POST['stock'] ?? 0;      
    
    $sql = "INSERT INTO product_variants
            (product_id, size, color, extra_price, stock) 
            VALUES 
            ('$product_id', '$size', '$color', '$extra_price', '$stock')";
    
    if($conn->query($sql)){
        $message = "<div class='alert alert-success'>✅ Variant added successfully!</div>";
    } else {
        $message = "<div class='alert alert-danger'>❌ Error: ".$conn->error."</div>";
    }
}  

// Product list nikaal lo (dropdown me dikhane ke liye) 
$products = $conn->query("SELECT product_id, name FROM products ORDER BY name ASC"); 
$product_id = $_GET['product_id'] ?? null;  
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Product Variant</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body { margin:0; font-family: Arial, sans-serif; }
    .sidebar { width:200px; height:100vh; background:#fff; border-right:1px solid #eee; position:fixed; top:0; left:0; }
    .sidebar a { display:block; padding:12px 20px; color:#333; text-decoration:none; }
    .sidebar a:hover, .sidebar a.active { background:#0d6efd; color:#fff; border-radius:8px; }
    .main-content { margin-left:200px; padding:20px; }
    .sidebar a:hover, .sidebar a.active8{
  background: rgb(149, 31, 51);
  color: #fff;
  border-radius: 8px;
}
  </style>
</head>
<body>
<div class="d-flex">
  
  <!-- Sidebar -->
  <?php include "admin_sidebar.php"; ?>

  <!-- Main Content -->
  <div class="main-content flex-grow-1">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h3>Add Variants</h3>
      <div>
        <i class="bi bi-bell fs-4 me-3"></i>
<?php if(!empty($_SESSION['admin_pic'])): ?>
  <img src="<?= $_SESSION['admin_pic'] ?>" alt="Admin" class="rounded-circle" width="40" height="40" style="object-fit:cover;">
<?php else: ?>
  <i class="bi bi-person-circle fs-4"></i>
<?php endif; ?>
      </div>
    </div>

    <?php if(isset($message)) echo $message; ?>

    <div class="card p-4">
      <form method="POST" class="row g-3">
        
        <?php if($product_id): ?>
          <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
          <div class="col-12">
            <p><strong>Product:</strong>
              <?php
                $p = $conn->query("SELECT name FROM products WHERE product_id=$product_id")->fetch_assoc();
                echo $p['name'];
              ?>
            </p>
          </div>
        <?php else: ?>
          <div class="col-md-12">
            <label class="form-label">Select Product</label>
            <select name="product_id" class="form-select" required>
              <option value="">-- Select Product --</option>
              <?php while($p = $products->fetch_assoc()): ?>
                <option value="<?php echo $p['product_id']; ?>">
                  <?php echo $p['name']; ?>
                </option>
              <?php endwhile; ?>
            </select>
          </div>
        <?php endif; ?>

    <div class="col-md-4">
  <label class="form-label">Size</label>
  <select name="size" class="form-select" required>
    <option value="">-- Select Size --</option>
    <option value="S">S</option>
    <option value="M">M</option>
    <option value="L">L</option>
    <option value="XL">XL</option>
    <option value="XXL">XXL</option>
  </select>
</div>

<!-- Color dropdown -->
<div class="col-md-4">
  <label class="form-label">Color</label>
  <select name="color" class="form-select" required>
    <option value="">-- Select Color --</option>
    <option value="Red">Red</option>
    <option value="Blue">Blue</option>
    <option value="Black">Black</option>
    <option value="White">White</option>
    <option value="Green">Green</option>
    <option value="Yellow">Yellow</option>
    <option value="Pink">Pink</option>
    <option value="Orange">Orange</option>
    <option value="Purple">Purple</option>
    <option value="Brown">Brown</option>
    <option value="Grey">Grey</option>
    <option value="Maroon">Maroon</option>
    <option value="Navy">Navy</option>
    <option value="Beige">Beige</option>
    <option value="Turquoise">Turquoise</option>
  </select>
</div>


        <div class="col-md-4">
          <label class="form-label">Extra Price (if any)</label>
          <input type="number" step="0.01" name="extra_price" class="form-control" value="0">
        </div>

        <div class="col-md-6">
          <label class="form-label">Stock</label>
          <input type="number" name="stock" class="form-control" required>
        </div>

        <div class="col-12">
          <button type="submit" class="btn btn-warning px-4">Add Variant</button>
        </div>
      </form>
    </div>
  </div>
</div>
</body>
</html>
