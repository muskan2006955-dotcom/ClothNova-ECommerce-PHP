<?php
session_start();
include 'db.php';

$message = ""; // yahan rakho

if($_SERVER['REQUEST_METHOD'] == "POST"){
    $name         = $_POST['name'] ?? '';
    $price        = $_POST['price'] ?? 0;
    $description  = $_POST['description'] ?? '';
    $main_category= $_POST['main_category'] ?? '';
    $sub_category = $_POST['sub_category'] ?? '';
    $category     = $_POST['category'] ?? '';
    $is_featured  = isset($_POST['is_featured']) ? 1 : 0;

    // ===== MAIN IMAGE =====
    $image = "";
    if(!empty($_FILES['image']['name'])){
        $image = uniqid() . "_" . $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], "images/" . $image);
    }

    // ===== INSERT PRODUCT =====
    $sql = "INSERT INTO products 
            (name, description, price, main_category, sub_category, category, image, is_featured, sales_count) 
            VALUES 
            ('$name', '$description', '$price', '$main_category', '$sub_category', '$category', '$image', '$is_featured', 0)";
    
    if($conn->query($sql)){
        $product_id = $conn->insert_id;

        // ===== EXTRA IMAGES (max 4) =====
        if(!empty($_FILES['extra_images']['name'][0])){
            $count = count($_FILES['extra_images']['name']);
            $limit = min($count, 4);

            for($i=0; $i<$limit; $i++){
                $file_name = $_FILES['extra_images']['name'][$i];
                $tmp_name  = $_FILES['extra_images']['tmp_name'][$i];

                if($file_name != ""){
                    $extra_img = uniqid() . "_" . $file_name;
                    move_uploaded_file($tmp_name, "images/" . $extra_img);

                    $conn->query("INSERT INTO product_images (product_id, image) VALUES ('$product_id', '$extra_img')");
                }
            }
        }

        // ✅ message variable set karo
        $message = "<div class='alert alert-success mt-3'>
                      ✅ Product added successfully! 
                      <a href='add_variant.php?product_id=$product_id' class='btn btn-sm btn-success ms-2'>
                        Add Variants
                      </a>
                    </div>";
    } else {
        $message = "<div class='alert alert-danger mt-3'>❌ Error: ".$conn->error."</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Product</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <style>
.sidebar {
  width: 200px;
  height: 100vh;
  background: #fff;
  border-right: 1px solid #eee;
  flex-shrink: 0; /* taake sidebar compress na ho */
}

.sidebar a {
  display: block;
  padding: 12px 20px;
  color: #333;
  text-decoration: none;
}

.sidebar a:hover, .sidebar a.active {
  background: rgb(149, 31, 51);
  color: black;
  border-radius: 8px;
}
.sidebar a:hover, .sidebar a.active2 {
  background:rgb(149, 31, 51);
  color: white;
  border-radius: 8px;
}

  </style>
</head>
<body>
<div class="d-flex">
  <!-- Sidebar -->
  <?php include 'admin_sidebar.php'; ?>
<style>  #preloader {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: #fff; /* background white */
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 99999; /* sab upar */
}

.loader {
  border: 8px solid #f3f3f3;
  border-top: 8px solid rgb(149, 31, 51);
  border-radius: 50%;
  width: 70px;
  height: 70px;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

/* Extra CSS */
#preloader.fade-out {
  opacity: 0;
  transition: opacity 0.6s ease;
  pointer-events: none;
}</style>
<body>
    <!-- Preloader -->

    
</body>
<script>
  window.addEventListener("load", function () {
    let preloader = document.getElementById("preloader");
    // Fade-out class add karo
    preloader.classList.add("fade-out");
    // Aur 600ms baad completely hide karo
    setTimeout(() => {
      preloader.style.display = "none";
    }, 900);
  });
</script>

  <!-- Main Content -->
  <div class="flex-grow-1 p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
      <h3>Add New Product</h3>
      <div>
        <i class="bi bi-bell fs-4 me-3"></i>
<?php if(!empty($_SESSION['admin_pic'])): ?>
  <img src="<?= $_SESSION['admin_pic'] ?>" alt="Admin" class="rounded-circle" width="40" height="40" style="object-fit:cover;">
<?php else: ?>
  <i class="bi bi-person-circle fs-4"></i>
<?php endif; ?>
      </div>
    </div>

    <div class="card p-4">
  <form method="POST" enctype="multipart/form-data" class="row g-3">

    <!-- Top 3 inputs -->
    <div class="col-md-4">
      <label class="form-label">Product Name</label>
      <input type="text" name="name" class="form-control" required>
    </div>

    <div class="col-md-4">
      <label class="form-label">Base Price</label>
      <input type="number" step="0.01" name="price" class="form-control" required>
    </div>
<div class="col-md-4">
  <label class="form-label">Main Category</label>
  <select name="main_category" class="form-select" required>
    <option value="">-- Select Main Category --</option>
    <option value="Men">Men</option>
    <option value="Women">Women</option>
    <option value="Kids">Kids</option>
  </select>
</div>


    <!-- Next row with 2 inputs -->
<div class="col-md-4">
  <label class="form-label">Sub Category</label>
  <select name="sub_category" class="form-select" required>
    <option value="">-- Select Sub Category --</option>
    <option value="Shirts">Shirts</option>
    <option value="T-Shirts">T-Shirts</option>
    <option value="Jeans">Jeans</option>
    <option value="Jackets">Jackets</option>
    <option value="Dresses">Dresses</option>
    <option value="Skirts">Skirts</option>
    <option value="Shorts">Shorts</option>
    <option value="Sweaters">Sweaters</option>
    <option value="Hoodies">Hoodies</option>
    <option value="Coats">Coats</option>
    <option value="Blazers">Blazers</option>
    <option value="Trousers">Trousers</option>
    <option value="Leggings">Leggings</option>
    <option value="Shoes">Shoes</option>
    <option value="Accessories">Accessories</option>
  </select>
</div>

    <div class="col-md-6">
      <label class="form-label">Category</label>
      <input type="text" name="category" class="form-control">
    </div>

    <!-- Description full width -->
    <div class="col-12">
      <label class="form-label">Description</label>
      <textarea name="description" class="form-control" rows="3"></textarea>
    </div>

    <!-- Image uploads -->
    <div class="col-md-6">
      <label class="form-label">Main Image</label>
      <input type="file" name="image" class="form-control" required>
    </div>

    <div class="col-md-6">
      <label class="form-label">Extra Images (Max 4)</label>
      <input type="file" name="extra_images[]" class="form-control" multiple>
      <div class="form-text">Select up to 4 images</div>
    </div>

    <!-- Featured checkbox -->
    <div class="col-12 form-check">
      <input type="checkbox" name="is_featured" class="form-check-input" id="featuredCheck">
      <label class="form-check-label" for="featuredCheck">Mark as Featured</label>
    </div>

    <!-- Submit button -->
    <div class="col-12">
      <button type="submit" class="btn btn-warning px-4">Add Product</button>
    </div>
  </form>
  <!-- Submit button -->

<!-- ✅ Success/Error message -->
<div class="col-12">
  <?= $message ?>
</div>

</div>

  </div>
</div>
</body>
</html>
