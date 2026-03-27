<?php
session_start();
include 'db.php'
;
$search = isset($_GET['search']) ? $_GET['search'] : '';


// ✅ yahan helper function add karo
// ✅ yahan helper function add karo
function buildUrl($params = []) {
    $all = $_GET;

    // ensure main/category/sub are preserved from session
    if(!isset($all['main']) && isset($_SESSION['main'])) $all['main'] = $_SESSION['main'];
    if(!isset($all['category']) && isset($_SESSION['category'])) $all['category'] = $_SESSION['category'];
    if(!isset($all['sub']) && isset($_SESSION['sub'])) $all['sub'] = $_SESSION['sub'];

    foreach ($params as $k => $v) {
        $all[$k] = $v;
    }

    // agar search hai to main=all hata do
    if (!empty($all['search']) && $all['main'] === 'all') {
        unset($all['main']);
    }

    return 'index2.php?' . http_build_query($all);
}


// store params into session if provided
if (isset($_GET['main'])) $_SESSION['main'] = $_GET['main'];
if (isset($_GET['sub']))  $_SESSION['sub']  = $_GET['sub'];

// decide active main/sub (GET > SESSION > default)
$main = isset($_GET['main']) ? mysqli_real_escape_string($conn, $_GET['main']) : (isset($_SESSION['main']) ? mysqli_real_escape_string($conn, $_SESSION['main']) : 'all');
$sub  = isset($_GET['sub'])  ? mysqli_real_escape_string($conn, $_GET['sub'])  : (isset($_SESSION['sub']) ? mysqli_real_escape_string($conn, $_SESSION['sub']) : '');
$size = isset($_GET['size']) ? mysqli_real_escape_string($conn, $_GET['size']) : '';


// --- pagination / sort defaults (add these if missing) ---
$sort  = isset($_GET['sort']) ? mysqli_real_escape_string($conn, $_GET['sort']) : '';
$limit = 8; // items per page (change as you like)
$page  = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $limit;
// Example: logged in user wishlist
$wishlist_items = [];
if(isset($_SESSION['user_id'])){
    $uid = (int)$_SESSION['user_id'];
    $res = $conn->query("SELECT product_id FROM wishlist WHERE user_id = $uid");
    if($res){
        while($w = $res->fetch_assoc()){
            $wishlist_items[] = (int)$w['product_id'];
        }
    }
}


?>



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ecommerce Products</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://kit.fontawesome.com/your-kit.js" crossorigin="anonymous"></script>
<link rel="stylesheet" href="style.css">
  <style>
    .product-card{
        height: 400px;
    }
       .product-card{
        border-radius: 12px;
            backdrop-filter: blur(12px) saturate(150%);
            background: rgba(255, 255, 255, 0.146);
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.605);
            text-align: center;
    }
    .product-card img{
      height: 70%;
    }
    .product-card img{
        height: 69%;
    }
    .page-item a{
        background-color: #000;
        color: #fff;
        padding: 10px 25px;
        margin-top: 30px;
        border-radius: 7px;
    }
.categury{
    font-size: 20px;
    color: black;
    font-weight: 700px;
}
.product-card{
    margin-top: 20px;
}
.list-unstyled li a{
  font-size: 18px;
  text-decoration: none;
}

#priceRange {
    width: 100%;
    height: 6px;        /* slider line thickness */
    -webkit-appearance: none;
    background: #ddd;   /* line color */
    border-radius: 3px;
    outline: none;
}
#priceRange::-webkit-slider-thumb {
    -webkit-appearance: none;
    appearance: none;
    width: 12px;        /* small handle */
    height: 12px;
    background: #333;   /* handle color */
    cursor: pointer;
    border-radius: 50%;
    border: 1px solid #000;
}
#priceRange::-moz-range-thumb {
    width: 12px;
    height: 12px;
    background: #333;
    cursor: pointer;
    border-radius: 50%;
    border: 1px solid #000;
}
ul li a{
  color: black;
}

.filter-box {
  max-height: calc(700vh - 100px); /* screen height se adjust */
  overflow-y: auto;   /* scroll enable */
  position: sticky;
  top: 100px;         /* navbar ke neeche chipka rahe */
  padding-right: 5px;
}

/* Navbar Sticky */
header {
  position: sticky;
  top: 0;
  z-index: 1; /* upar rahe sab ke */
   background: linear-gradient(to right, #ffe6f0, #fff0e6);
}
 ::-webkit-scrollbar{
    width: 0.300rem;

 }
 ::-webkit-scrollbar-track{
    background-color: #F2F2F2;
 }
 ::-webkit-scrollbar-thumb{
    background-color: rgb(129, 22, 40);
 }

footer{
  position: relative;
  top: 50px;
}

  </style>
</head>
<body>
  <!-- Preloader -->
<?php
include 'preloader.php';
?>
<div class="container-fluid header" id="header" style="background-color:rgb(149, 31, 51);color:white;">
    <div class="row">
        <div class="col-7 ">
          <p class="ms-5 ">E comerce  <i class="fa-solid fa-bag-shopping"></i></p>
         
        </div>
        <div class="col-4 ms-5">
            <p class="ms-5">30% off sale on ramazan</p>
        </div>
    </div>
    </div>
<!-- ===== New Header Start ===== -->
<?php  include "header.php";   ?>
<!-- ===== New Header End ===== -->
<!-- OFFCANVAS -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="cartSidebar" aria-labelledby="cartSidebarLabel">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title" id="cartSidebarLabel">Shopping Cart</h5>
    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"></button>
  </div>
  <div class="offcanvas-body">
    <?php
    if(isset($_SESSION['user_id'])){
        $user_id = $_SESSION['user_id'];
        $cartQuery = $conn->query("SELECT c.*, p.name, p.price, p.image FROM cart c JOIN products p ON c.product_id=p.product_id WHERE c.user_id=$user_id");
        $total = 0;
        if($cartQuery->num_rows > 0){
            while($item = $cartQuery->fetch_assoc()){
                $subtotal = $item['price'] * $item['quantity'];
                $total += $subtotal;
                echo "<div class='d-flex justify-content-between mb-2'>
                        <div>
                            <img src='images/{$item['image']}' width='50'>
                            {$item['name']} x {$item['quantity']}
                        </div>
                        <div>Rs. {$subtotal}</div>
                      </div>";
            }
        } else {
            echo "<p>Your cart is empty</p>";
        }
    } else {
        echo "<p>Please login to see your cart.</p>";
    }
    ?>
        <hr>
    <p>Total: <b>Rs. <?php echo $total ?? 0; ?></b></p>
    <a href="cart.php" class="btn btn-dark w-100">Go to Cart</a>
  </div>
</div>
<div class="offcanvas offcanvas-end" tabindex="-1" id="heartSidebar">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title">Wishlist</h5>
    <button class="btn-close" data-bs-dismiss="offcanvas"></button>
  </div>
  <div class="offcanvas-body">No items in wishlist.</div>
</div>

<div class="offcanvas offcanvas-end" tabindex="-1" id="searchSidebar">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title">Search</h5>
    <button class="btn-close" data-bs-dismiss="offcanvas"></button>
  </div>
  <div class="offcanvas-body">
    <input type="text" class="form-control" placeholder="Search...">
  </div>
</div>
<div class="container-fluid">
  <div class="row">
    <!-- Left Filter (desktop only) -->
  <div class="col-md-2 filter-box mt-3">
  <h5 >Filter</h5>
  <hr>

  <!-- Size -->
  <p>
    <a class="d-flex justify-content-between align-items-center text-decoration-none" 
       data-bs-toggle="collapse" href="#sizeCollapse" role="button" aria-expanded="true"style="color: rgb(149, 31, 51); font-size: 17px; font-weight: 700;">
      Size
      <span class="bi bi-chevron-down"></span>
    </a>
  </p>
<div class="collapse show" id="sizeCollapse">
  <div class="d-flex gap-2 flex-wrap">
   
       <a href="<?php echo buildUrl(['size'=>'S']); ?>" class="badge" style="background-color: rgb(149, 31, 51);">S</a>
<a href="<?php echo buildUrl(['size'=>'M']); ?>" class="badge " style="background-color: rgb(149, 31, 51);">M</a>
       <a href="<?php echo buildUrl(['size'=>'X']); ?>" class="badge " style="background-color: rgb(149, 31, 51);">X</a>
<a href="<?php echo buildUrl(['size'=>'XL']); ?>" class="badge " style="background-color: rgb(149, 31, 51);">XL</a>

  </div>
</div>
  <hr>

  <!-- Colors -->
  <p class="mt-3">
    <a class="d-flex justify-content-between align-items-center  text-decoration-none" 
       data-bs-toggle="collapse" href="#colorCollapse" role="button" aria-expanded="true" style="color: rgb(149, 31, 51); font-size: 17px; font-weight: 700;">
      Colors
      <span class="bi bi-chevron-down"></span>
    </a>
  </p>
  <div class="collapse show" id="colorCollapse">
<div class="filter-section mb-4">
  <h5>Color</h5>
<form method="GET" action="index2.php">
    <!-- Preserve main/sub when applying colors -->
    <input type="hidden" name="main" value="<?php echo htmlspecialchars($main); ?>">
    <input type="hidden" name="sub" value="<?php echo htmlspecialchars($sub); ?>">
    <input type="hidden" name="size" value="<?php echo htmlspecialchars($size); ?>">
    <input type="hidden" name="sort" value="<?php echo htmlspecialchars($sort); ?>">
    <input type="hidden" name="search" value="<?php echo htmlspecialchars($search); ?>">

    <label><input type="checkbox" name="color[]" value="Red" <?php if(!empty($_GET['color']) && in_array('Red', (array)$_GET['color'])) echo 'checked'; ?>> Red</label><br>
    <label><input type="checkbox" name="color[]" value="Blue" <?php if(!empty($_GET['color']) && in_array('Blue', (array)$_GET['color'])) echo 'checked'; ?>> Blue</label><br>
    <label><input type="checkbox" name="color[]" value="Black" <?php if(!empty($_GET['color']) && in_array('Black', (array)$_GET['color'])) echo 'checked'; ?>> Black</label><br>
    <label><input type="checkbox" name="color[]" value="White" <?php if(!empty($_GET['color']) && in_array('White', (array)$_GET['color'])) echo 'checked'; ?>> White</label><br>

    <button type="submit" class="btn btn-sm btn-warning mt-2" style="color: white;">Apply</button>
</form>

</div>

  </div>

  <!-- Categories -->

<hr>

 <!-- Price Range Slider (No button, numbers below line) -->
<p class="mt-3">
  <a class="d-flex justify-content-between align-items-center text-decoration-none" 
     data-bs-toggle="collapse" href="#priceCollapse" role="button" aria-expanded="true" style="color: rgb(149, 31, 51); font-size: 17px; font-weight: 700;">
    Price Range
    <span class="bi bi-chevron-down"></span>
  </a>
</p>
<div class="collapse show" id="priceCollapse">
  <div class="d-flex flex-column align-items-start">
    <input type="range" id="priceRange" min="1000" max="10000" step="500"
           value="<?php echo isset($_GET['max_price']) ? (int)$_GET['max_price'] : 10000; ?>"
           oninput="document.getElementById('priceDisplay').innerText=this.value;">
    <small class="mt-1">Selected Price: Rs. <span id="priceDisplay"><?php echo isset($_GET['max_price']) ? (int)$_GET['max_price'] : 10000; ?></span></small>
  </div>
</div>
  

  <!-- ===== Suit Types Filter ===== -->
<p class="mt-3">
  <a class="d-flex justify-content-between align-items-center  text-decoration-none" 
     data-bs-toggle="collapse" href="#suitTypesCollapse" role="button" aria-expanded="true" style="color: rgb(149, 31, 51); font-size: 17px; font-weight: 700;">
    Suit Types
    <span class="bi bi-chevron-down"></span>
  </a>
</p>
<div class="collapse show" id="suitTypesCollapse">
  <ul class="list-unstyled">
    <li><a href="<?php echo buildUrl(['sub'=>'casual','page'=>1]); ?>">Casual</a></li>
    <li><a href="<?php echo buildUrl(['sub'=>'fancy','page'=>1]); ?>">Fancy</a></li>
    <li><a href="index2.php?main=<?php echo $main; ?>&sub=formal&page=1">Formal Suit</a></li>
  </ul>
</div>
  <hr>
<!-- ===== Discount / Bundle Filter ===== -->
<!-- ===== Discount / Bundle Filter ===== -->
<p class="mt-3">
  <a class="d-flex justify-content-between align-items-center  text-decoration-none" 
     data-bs-toggle="collapse" href="#discountCollapse" role="button" aria-expanded="true" style="color: rgb(149, 31, 51); font-size: 17px; font-weight: 700;">
    Discount / Bundle
    <span class="bi bi-chevron-down"></span>
  </a>
</p>
<div class="collapse show" id="discountCollapse">
  <ul class="list-unstyled">
    <li><a href="<?php echo buildUrl(['discount'=>10,'page'=>1]); ?>">10% Off</a></li>
    <li><a href="<?php echo buildUrl(['discount'=>20,'page'=>1]); ?>">20% Off</a></li>
       <li><a href="<?php echo buildUrl(['discount'=>10,'page'=>1]); ?>">30% Off</a></li>
    <li><a href="<?php echo buildUrl(['discount'=>20,'page'=>1]); ?>">40% Off</a></li>
       <li><a href="<?php echo buildUrl(['discount'=>10,'page'=>1]); ?>">50% Off</a></li>
    <li><a href="<?php echo buildUrl(['discount'=>'bundle','page'=>1]); ?>">Buy 1 Get 1</a></li>
  </ul>
</div>
  <hr>

  
</div>

    <!-- Products Grid -->
    <div class="col-md-10 py-4">
      <!-- Heading + Sort dropdown -->

      <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0">Sweaters</h3>
<?php $search_val = isset($_GET['search']) ? urlencode($_GET['search']) : ''; ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <?php $search_val = isset($_GET['search']) ? urlencode($_GET['search']) : ''; ?>
<select onchange="window.location='<?php echo buildUrl(); ?>&sort='+this.value" style="width: 250px; height: 33px; border:2px solid rgb(149, 31, 51); ">
        <option value="">Sort By : <span style="color: black; font-weight: 700;">Recommend</span></option>
        <option value="low_high" <?php echo ($sort=='low_high') ? 'selected' : ''; ?>>Price: Low to High</option>
        <option value="high_low" <?php echo ($sort=='high_low') ? 'selected' : ''; ?>>Price: High to Low</option>
        <option value="newest" <?php echo ($sort=='newest') ? 'selected' : ''; ?>>Newest</option>
        <option value="best_selling" <?php echo ($sort=='best_selling') ? 'selected' : ''; ?>>Best Selling</option>
    </select>
</div>



      </div>

   <?php include 'db.php'; ?>

<div class="container-fluid mt-5">

  <!-- Category Links -->


  <div class="row justify-content-around">
<?php

include 'db.php';
$search = $_GET['search'] ?? '';
$search = mysqli_real_escape_string($conn, $search);

$where_parts = [];

// agar search diya gaya hai
if (!empty($search)) {
    $search = $conn->real_escape_string($search);

    // agar men, women, kids likha gaya hai to usko directly main_category me map karo
    if (in_array(strtolower($search), ['men','women','kids'])) {
        $where_parts[] = "main_category = '" . ucfirst(strtolower($search)) . "'";
    } else {
        // sirf main_category aur category check karo (sub_category ko ignore karo)
        $where_parts[] = "(main_category REGEXP '[[:<:]]{$search}[[:>:]]'
                           OR category REGEXP '[[:<:]]{$search}[[:>:]]')";
    }
}


// filters
if ($main !== 'all') {
    $where_parts[] = "main_category = '" . $conn->real_escape_string($main) . "'";
}

if (!empty($sub)) {
    $where_parts[] = "category = '" . $conn->real_escape_string($sub) . "'";
}


// final query
$where_sql = "";
if (!empty($where_parts)) {
    $where_sql = "WHERE " . implode(" AND ", $where_parts);
}

$sql = "SELECT * FROM products $where_sql";


// -----------------------
// Pagination
// -----------------------
$limit = 8;
$page  = max(1, (int)($_GET['page'] ?? 1));
$offset = ($page - 1) * $limit;

// -----------------------
// Build WHERE clause
// -----------------------

// -----------------------
// Build WHERE clause
// -----------------------


if (!empty($size)) {
    $where_parts[] = "v.size = '$size'";
}

// color filter
if (!empty($_GET['color'])) {
    $colors = (array) $_GET['color']; // force array
    $safeColors = array_map(function($c) use ($conn) {
        return "'" . mysqli_real_escape_string($conn, $c) . "'";
    }, $colors);
    $where_parts[] = "v.color IN (" . implode(',', $safeColors) . ")";
}
// size filter
if (!empty($size)) {
    $where_parts[] = "v.size = '$size'";
}

$where = $where_parts ? "WHERE ".implode(' AND ', $where_parts) : '';
// discount filter
// discount filter
if (!empty($_GET['discount'])) {
    $discount = $_GET['discount'];
    if (is_numeric($discount)) {
        $where_parts[] = "p.discount >= ".(int)$discount;
    } elseif ($discount === 'bundle') {
        $where_parts[] = "p.is_bundle = 1";
    }
}

// ab saari conditions collect ho gayi → abhi where banao
$where = count($where_parts) ? "WHERE ".implode(" AND ", $where_parts) : "";


$sql = "SELECT * FROM products p $where ORDER BY ... LIMIT ...";

// -----------------------
// Sorting
// -----------------------
$orderBy = "ORDER BY p.product_id DESC";
switch($sort){
    case 'low_high': $orderBy = "ORDER BY p.price ASC"; break;
    case 'high_low': $orderBy = "ORDER BY p.price DESC"; break;
    case 'newest': $orderBy = "ORDER BY p.created_at DESC"; break;
    case 'best_selling': $orderBy = "ORDER BY p.sales_count DESC"; break;
}

// -----------------------
// Total products for pagination
// -----------------------
$countSql = "SELECT COUNT(DISTINCT p.product_id) AS total 
             FROM products p 
             LEFT JOIN product_variants v ON p.product_id = v.product_id
             $where";
$countResult = $conn->query($countSql);
$totalRows = ($countResult) ? (int)$countResult->fetch_assoc()['total'] : 0;
$totalPages = max(1, ceil($totalRows / $limit));

// -----------------------
// Get products for current page
// -----------------------
$sql = "SELECT DISTINCT p.* 
        FROM products p 
        LEFT JOIN product_variants v ON p.product_id = v.product_id
        $where $orderBy LIMIT $offset, $limit";
$result = $conn->query($sql);


// --- Render products (example) ---
if ($result && $result->num_rows > 0) {
  while($row = $result->fetch_assoc()){
    $name = htmlspecialchars($row['name']);
    $price = number_format((float)$row['price'], 2);
    $img = 'images/' . $row['image'];
    $pid = (int)$row['product_id'];
$heartClass = in_array($pid, $wishlist_items) ? 'text-danger' : 'text-secondary';

echo '
<div class="card product-card mb-4" style="width: 16rem; height: 420px; ">
  
  <!-- Product Image -->
  <a href="product-detail.php?id='.$pid.'&main='.urlencode($main).'&sub='.urlencode($sub).'">
    <img src="images/'.$row["image"].'" class="card-img-top img-fluid" alt="'.$row["name"].'"      style="width:100%; height:250px; object-fit:cover;">

  </a>

  <!-- Add to Cart -->
  <div class="product-actions">
    <a href="product-detail.php?id='.$pid.'&return=index2.php?main='.urlencode($main).'&sub='.urlencode($sub).'&page='.$page.'">
      <i class="bi bi-cart"></i> Add to Cart
    </a>
  </div>

<!-- Wishlist -->
<a href="wishlist_toggle.php?product_id='.$pid.'" class="wishlist">
<i class="fa-solid fa-heart"></i>      
</a>
  <div class="card-body">

    <!-- Badge + Ratings -->
    <div class="d-flex justify-content-between mb-2">
      <div class="badge-accent">Hot</div>
      <div class="text-end">
        <small class="text-muted">
          <i class="fa-solid fa-star" style="color:orange;"></i>
          <i class="fa-solid fa-star"   style="color:orange;" ></i>
          <i class="fa-solid fa-star"   style="color:orange;"></i>
          <i class="fa-solid fa-star"   style="color:orange;"></i>
          <i class="fa-regular fa-star"   style="color:orange;"></i>
        </small>
      </div>
    </div>

    <!-- Product Name -->
    <div class="d-flex justify-content-between mb-2">
      <h6 class="mb-1">'.$row["name"].'</h6>
    </div>

    <!-- Price + View Button -->
    <div class="d-flex justify-content-between align-items-center">
'.(
    ($row['discount'] > 0) 
    ? "<div>
         <div class='fw-bold text-danger'>Rs. ".number_format($row['price'] - ($row['price'] * $row['discount'] / 100), 2)."</div>
         <small class='text-muted text-decoration-line-through'>Rs. ".number_format($row['price'], 2)."</small>
         <span class='badge bg-success ms-2'>-{$row['discount']}%</span>
       </div>"
    : "<div class='fw-bold'>Rs. ".number_format($row['price'], 2)."</div>"
).'
      <a href="wishlist_toggle.php?product_id='.$pid.'" class="wishlist">
    <i class="fa-solid fa-heart '.$heartClass.'"></i>
</a>
    </div>

  </div>
</div>';

  }
} else {
  echo "<p class='text-center'>No products found</p>";
}

// --- Pagination HTML (use the same $totalPages variable) ---
echo '<nav><ul class="pagination justify-content-center mt-4" style="gap: 10px;">';
for ($i = 1; $i <= $totalPages; $i++) {
    $active = ($i == $page) ? 'active' : '';
    echo '<li class="page-item '.$active.'">
            <a class="page-link bg-warning" href="'.buildUrl(['page'=>$i]).'">'.$i.'</a>
          </li>';
}

echo '</ul></nav>';










?>

  </div>
</div><!-- yahan product grid close ho rahi hai -->

      <!-- Pagination Section -->
 

      </div>
    </div>
  </div>
</div>






<footer class="py-5 mt-5" style="background: linear-gradient(to right, #ffe6f0, #fff0e6); color:#000">
  <div class="container">
    <div class="row">
      <!-- Logo / About -->
      <div class="col-md-4 mb-4">
        <h5 class="fw-bold text-dark">Cloth Nova</h5>
        <p class="small text-dark">
          Stylish & trendy outfits for everyone.  
          Discover fashion that suits your personality ✨
        </p>
        <div>
          <a href="#" class=" me-3"><i class="bi bi-facebook"></i></a>
          <a href="#" class=" me-3"><i class="bi bi-instagram"></i></a>
          <a href="#" class=" me-3"><i class="bi bi-twitter-x"></i></a>
        </div>
      </div>

      <!-- Quick Links -->
      <div class="col-md-2 mb-4">
        <h6 class="fw-bold text-dark">Quick Links</h6>
        <ul class="list-unstyled small">
          <li><a href="#" class=" text-decoration-none">Home</a></li>
          <li><a href="#" class=" text-decoration-none">Shop</a></li>
          <li><a href="#" class=" text-decoration-none">About</a></li>
          <li><a href="#" class=" text-decoration-none">Contact</a></li>
        </ul>
      </div>

      <!-- Customer Care -->
      <div class="col-md-3 mb-4">
        <h6 class="fw-bold text-dark">Customer Care</h6>
        <ul class="list-unstyled small">
          <li><a href="#" class=" text-decoration-none">FAQs</a></li>
          <li><a href="#" class=" text-decoration-none">Shipping & Returns</a></li>
          <li><a href="#" class=" text-decoration-none">Privacy Policy</a></li>
          <li><a href="#" class=" text-decoration-none">Terms & Conditions</a></li>
        </ul>
      </div>

      <!-- Newsletter -->
      <div class="col-md-3 mb-4">
        <h6 class="fw-bold">Newsletter</h6>
        <p class="small">Subscribe to get the latest offers & trends.</p>
        <form class="d-flex">
          <input type="email" class="form-control me-2" placeholder="Enter email">
          <button class="btn btn-warning">Go</button>
        </form>
      </div>

    </div>

    <hr class="border-secondary">

    <div class="text-center small">
      © 2025 Cloth Nova. All Rights Reserved.
    </div>
  </div>
</footer>
</body>
</html>


<script>
// Optionally, if you want to auto-submit filter when slider changes
document.getElementById('priceRange').addEventListener('change', function(){
    const params = new URLSearchParams(window.location.search);
    params.set('max_price', this.value);
    window.location.search = params.toString();
});

</script>
<!-- SALE TAB -->
<div id="saleTab">
  <div id="saleHandle">
    <span>upto 50% discount</span>
  </div>
       <h2>🔥 Big Sale is Live!</h2>

  <div id="saleContent" class="d-flex">
   <div class="timer">
    <p>Flat 50% OFF on all products.</p>
    <div id="saleTimer"></div>
       <button>subscribe <i class="fa-solid fa-bell-concierge" style="color: gold;"></i></button>

   </div>
   <img src="istockphoto-2149680141-612x612-fotor-bg-remover-20250921114211.png" alt="">
  </div>
</div>

<style>
  /* Sidebar */
 #saleTab {
    position: fixed;
    top: 200px;
    right: -400px; /* hidden by default */
    width: 400px;
    height: 270px;
    background: linear-gradient(135deg, #ff0077, #ff3333);
    color: white;
    border-radius: 12px 0 0 12px;
    box-shadow: -4px 4px 12px rgba(0,0,0,0.3);
    transition: right 0.5s ease;
    z-index: 5000;
  }

  #saleTab.open {
    right: 0;
  }

  /* Handle = prchi */
  #saleHandle {
    position: absolute;
    top: 0;
    left: -40px; /* sirf 50px bahar dikh rahi */
    width: 40px;
    height: 100%;
    background: gold;
    color: black;
    font-weight: bold;
    font-size: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    writing-mode: vertical-rl;  /* vertical likhne ke liye */
    text-orientation: mixed;
    cursor: pointer;
    border-radius: 8px 0 0 8px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.3);
  }

  #saleHandle span {
    transform: rotate(180deg); /* 50% seedha dikhega */
  }

  #saleContent {
    padding: 25px;
    margin-top: 20px;
  }
  #saleContent{
  height: 170px;
}
#saleContent button{
  width: 150px;
  height: 35px;
  border: 1px solid rgb(149, 31, 51);
  border-radius: 15px;
  background-color: rgb(149, 31, 51);
  color: white;
  margin-top: 20px;
}
</style>

<script>
  const saleTab = document.getElementById("saleTab");
  const saleHandle = document.getElementById("saleHandle");

  saleHandle.addEventListener("click", () => {
    saleTab.classList.toggle("open");
  });

  // Countdown Timer (20 din)
  const endDate = new Date();
  endDate.setDate(endDate.getDate() + 20);

  setInterval(function () {
    const now = new Date().getTime();
    const distance = endDate - now;

    const days = Math.floor(distance / (1000 * 60 * 60 * 24));
    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    const seconds = Math.floor((distance % (1000 * 60)) / 1000);

    document.getElementById("saleTimer").innerHTML =
      `${days}d ${hours}h ${minutes}m ${seconds}s`;
  }, 1000);
</script>
