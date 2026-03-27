<?php
session_start();
include 'db.php';

// Get category from URL
$category = isset($_GET['category']) ? $_GET['category'] : 'all';

// Wishlist items for logged-in user
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

// Check if user is subscribed
$showSubscribe = false;
if(isset($_SESSION['user_id'])){
    $uid = intval($_SESSION['user_id']);
    $res = $conn->query("SELECT is_subscribed, email FROM users WHERE user_id=$uid")->fetch_assoc();
    if($res && $res['is_subscribed']==0){
        $showSubscribe = true;
        $userEmail = $res['email'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Header + Sliders Layout</title>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
          <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

  <!-- Swiper -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css"/>
        <link rel="stylesheet" href="style.css">


</head>
<style>
     .carousel-item {
      height: 80vh;
      background-size: cover;
      background-position: center;
      position: relative;
      z-index: -1;
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
    #preloader {
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
}
header {
  position: sticky;
  top: 0;
  z-index: 1030; /* upar rahe sab ke */
 background: linear-gradient(to right, #ffe6f0, #fff0e6);
}
#cartSidebar{
  z-index: 1050;
}
body, html{margin:0; padding:0; font-family:Arial, sans-serif;}
.hero{
    height:80vh;
    display:flex;
    justify-content:center;
    align-items:center;
background: linear-gradient(to right, #ecc5d5ee, #fff0e6); 
    overflow:hidden;
    padding-top:80px; /* header offset */
}
.container-hero{
    display:flex;
    justify-content:space-between;
    align-items:center;
    width:90%;
    max-width:1200px;
    position: relative;
}

/* Product Image */
.product{
    width:350px;
    opacity:0;
    animation: slideUp 1.5s ease-out forwards, float 4s ease-in-out infinite 2s;
    transition: transform 0.4s ease;
}
.product:hover{
    transform: scale(1.08) rotate(3deg);
}
@keyframes slideUp{ from{ transform: translateY(150px); opacity:0;} to{ transform: translateY(0); opacity:1;} }
@keyframes float{ 0%,100%{ transform: translateY(0) scale(1);} 50%{ transform: translateY(-12px) scale(1.02);} }

/* Text */
.left-text{ flex:1; font-size:2rem; color:#fff; opacity:0; animation: fadeIn 1.8s ease forwards 1.2s; }
.right-text{ flex:1; font-size:1.1rem; line-height:1.6; color:#f9f9f9; opacity:0; animation: fadeIn 1.8s ease forwards 1.4s; }
@keyframes fadeIn{ from{ opacity:0; transform: translateY(40px);} to{ opacity:1; transform: translateY(0);} }

/* Bounce Text */
.bounce-container{
    position: absolute;
    top: 8%;
    left:50%;
    transform:translateX(-50%);
    display:flex;
    gap:12px;
    font-size:4.5rem;
    font-weight:bold;
    color:gold;
    text-shadow:2px 2px 6px rgba(0,0,0,0.4);
    perspective:800px;
    opacity:0;
    animation: slideBounce 1.5s ease-out forwards;
}
.bounce-container span{
    display:inline-block;
    transform-style: preserve-3d;
    animation: bounce 1s infinite;
}
.bounce-container span:nth-child(1){ animation-delay:0s; }
.bounce-container span:nth-child(2){ animation-delay:0.1s; }
.bounce-container span:nth-child(3){ animation-delay:0.2s; }
.bounce-container span:nth-child(4){ animation-delay:0.3s; }
.bounce-container span:nth-child(5){ animation-delay:0.4s; }
.bounce-container span:nth-child(6){ animation-delay:0.5s; }

@keyframes slideBounce{ from{ transform: translate(-50%,200px); opacity:0; } to{ transform: translate(-50%,0); opacity:1; } }
@keyframes bounce{ 0%,100%{ transform: translateY(0) rotateX(0); } 50%{ transform: translateY(-18px) rotateX(20deg); } }

/* Cracked Line */
.cracked-line{
    position:absolute;
    top:20%;
    left:50%;
    transform:translateX(-50%);
    width:280px;
    height:5px;
    background: repeating-linear-gradient(to right, gold, gold 14px, transparent 14px, transparent 24px);
    box-shadow:0 2px 8px rgba(0,0,0,0.5);
    animation: crackAnim 1.5s ease-out forwards 1.2s;
    opacity:0;
}
@keyframes crackAnim{ from{ transform:translateX(-50%) scaleX(0); opacity:0; } to{ transform:translateX(-50%) scaleX(1); opacity:1; } }
/* Sidebar */
  #saleTab {
    position: fixed;
    top: 200px;
    right: -400px; /* hidden by default */
    width: 400px;
    height: 250px;
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
    padding: 20px;
    margin-top: 20px;
  }
  .shop-btn {
    position: relative;
    display: inline-block;
    padding: 12px 30px;
    font-size: 16px;
    font-weight: bold;
    color: rgb(149, 31, 51); /* default text color */
    background: transparent;
    border: 2px solid rgb(149, 31, 51);
    border-radius: 8px;
    cursor: pointer;
    overflow: hidden;
    transition: color 0.4s ease;
}
.brand-logo {
  width: 80px;
  height: 80px;
    filter: brightness(0) saturate(100%) 
          invert(86%) sepia(12%) 
          saturate(2100%) hue-rotate(300deg) 
          brightness(105%) contrast(98%);
}

.shop-btn::before {
    content: "";
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: rgb(149, 31, 51); /* pani color */
    transition: all 0.5s ease;
    z-index: 0;
}

.shop-btn:hover::before {
    left: 0; /* slide left to right */
}

.shop-btn:hover {
    color: #fff; /* text color change while background slides */
}

.shop-btn span {
    position: relative;
    z-index: 1; /* text upar rahe */
}

.btn-store {
  display:flex;
  align-items:center;
  padding:0 20px;
  height:60px; /* button height */
  border: 2px solid gold;
  border-radius:10px;
  background: transparent;
  color: black;
  text-decoration:none;
  font-family: Arial, sans-serif;
  transition: all 0.3s ease;
}

.btn-store:hover{
  background: linear-gradient(90deg, #00b4ff, #00fff7);
  color:white;
  border-color: #00fff7;
}

.btn-icon{
  font-size:28px;
  margin-right:10px;
  display:flex;
  align-items:center;
}

.btn-text small{
  display:block;
  font-size:10px;
}

.btn-text strong{
  font-size:14px;
}
.gradient-text {
  font-size: 2.2rem; /* apni zarurat ke hisaab se */
  font-weight: bold;
  background: linear-gradient(90deg, #FFD700, #FFB800, #FFD700); /* gold gradient */
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
  text-align: center;
}
.gradient-bg {
  background: linear-gradient(135deg, #fff8e1, #ffe57f); /* light golden gradient */
  border-radius: 5px;
  height: 240px;
  
}
.gradient-bg img{
  height: 260px;
}
  .glass-box {
    background: rgba(255, 255, 255, 0.2);
    border-radius: 15px;
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.3);
  }

.nav-link:hover::after,
.nav-link.active::after {
  width: 100%;
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
<body>
<!-- Preloader -->
<div id="preloader">
  <div class="loader"></div>
</div>
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

<!-- HEADER -->
<div class="container-fluid header" id="header" style="background-color:rgb(149, 31, 51); color: white;">
    <div class="row">
        <div class="col-7 ">
          <p class="ms-5 ">E comerce  <i class="fa-solid fa-bag-shopping"></i></p>
         
        </div>
        <div class="col-4 ms-5">
            <p class="ms-5">30% off sale on ramazan</p>
        </div>
    </div>
    </div>
<?php
include 'header.php'
?>

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

<!-- HERO SLIDER -->
<!-- HERO SLIDER -->
<!-- HERO SLIDER -->
<!-- HERO SLIDER -->
<section class="hero">
   <!-- <div class="container-hero ">
     <div class="alert alert-danger mb-3" 
            style="background: transparent; border:none; color: black; font-size: 26px;">
          <span style="color: gold; font-weight:800; ">Flash Sale is Live!</span> <br>
          <small style="font-size: 25px; font-weight: 800;">Sale ends in <span id="timer" ></span></small>
       </div> -->
   <div class="left-text text-center" style="width: 400px;">
  
       <h2 class="" style="margin-bottom: 20px; color: rgb(149, 31, 51); font-weight: 600; ">✨ 50% PERCENT OFF</h2>
       <h4 style="font-size: 18.5px; margin-bottom: 100px; color: #000;">🔥 Hurry! Grab your favorites before the sale ends.
🛒 Limited stock available – Don’t miss out!</h5>
   </div>

        <img src="f2.avif" class="product me-3" 
             style="height: 500px; width: 580px; border-radius: 10px;" 
             alt="Product">

        <div class="right-text ms-4"  style="margin-bottom:100px;">
            <h2 style="color: rgb(149, 31, 51); font-weight: 900; font-size: 26px;">FASHION OF YEAR</h2>
            <h6 style="color: #000;">
             🔥 Hurry! Grab your favorites before the sale ends.
 Limited stock available – Don’t miss out!
 Elevate your wardrobe with this year’s hottest fashion.

            </h6>
<button class="shop-btn" onclick="location.href='index2.php'"><span>Shop Now</span></button>
        </div>

        <div class="bounce-container">
            <span>G</span><span>L</span><span>A</span>
            <span>M</span><span>O</span><span>U</span><span>R</span>
        </div>
        <div class="cracked-line"></div>
    </div>
</section>



<!-- STATIC BRAND LOGOS -->


<!-- BRANDS SLIDER -->
 <?php include 'db.php'; ?>
<div class="container-fluid my-5" >
  <h2 class="text-center mb-4 fw-bold" style="color:rgb(149, 31, 51);">Don't Miss This Week's Sales</h2>
  <p class="text-center" >Get your favorite brands at the best prices this week.</p>
<?php
// Swiper ke liye featured products fetch karo
if($category == 'all'){
    $sql = "SELECT * FROM products WHERE is_featured = 1 LIMIT 8";
} else {
    $sql = "SELECT * FROM products WHERE category='$category' AND is_featured = 1 LIMIT 8";
}

$result = $conn->query($sql);
?>

<div class="container my-5">
  <h2 class="text-center mb-4  text-warning">
    <?php echo ucfirst($category); ?> Featured Products
  </h2>

  <div class="swiper mySwiper mt-5">
    <div class="swiper-wrapper">

      <?php
      if($category == 'all'){
          $sql = "SELECT * FROM products  LIMIT 12";
      } else {
          $sql = "SELECT * FROM products  LIMIT 12";
      }

      $result = $conn->query($sql);

      if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
          $pid = $row['product_id'];  // ✅ yahan pe define karo
        $heartClass = in_array($pid, $wishlist_items) ? 'text-danger' : 'text-secondary';
        
          echo '
          <div class="swiper-slide">
            <div class="card text-center position-relative product-card">
              <img src="images/'.$row["image"].'" class="card-img-top" alt="'.$row["name"].'" style="height:220px; object-fit:cover;">
              
              <!-- Heart Icon -->
               <a href="wishlist_toggle.php?product_id='.$pid.'" class="wishlist">
                <i class="fa-solid fa-heart '.$heartClass.'"></i>
              </a>
              
              <div class="card-body">
                <h6 class="card-title">'.$row["name"].'</h6>
                <p class="card-text">Rs. '.$row["price"].'</p>
              </div>
            </div>
          </div>';
        }
      } else {
        echo "<p class='text-center'>No products found.</p>";
      }
      ?>

    </div>

    <!-- Swiper Navigation -->
    <div class="swiper-button-next" style="height: 30px;"></div>
    <div class="swiper-button-prev"></div>
    <div class="swiper-pagination mt-5"></div>
  </div>
</div>



<div class="container section mt-5" style="background: linear-gradient(to right, #a84d73ee, #fff0e6);">
  <div class="row align-items-center">
    
    <!-- Left Side Image -->
    <div class="col-md-3 text-center">
      <img src="istockphoto-2149680141-612x612-fotor-bg-remover-20250921114211.png" alt="Ad Image" class="img-fluid mb-3" style="height: 270px;">
    </div>

    <!-- Right Side Glass Content -->
    <div class="col-md-8 d-flex justify-content-center mt-3">
      <div class="glass-box p-4 text-center">
        <h5 class="fw-bold" style="color: rgb(149, 31, 51);">F5 Sale Offer for 20 days</h5>
        <h2 class="text-dark">This Add For Sale</h2>
        <p>
          Lorem ipsum dolor sit amet, consectetur adipisicing elit. 
          Molestiae inventore veritatis est maxime, quia aliquam dolores 
          totam cumque minus corporis praesentium voluptatem corrupti 
          repellat rem eaque repudiandae error magni beatae!
        </p>
      </div>
    </div>

  </div>
</div>


 
            
<?php include 'db.php'; ?>

<div class="container-fluid mt-5">
 

  <!-- Category Links -->
 
<div class="container-fluid mt-5">
  <h2 class="text-center mb-4 fw-bold text-warning">
    <?php echo ucfirst($category); ?> Products
  </h2>
  <p class="text-center mb-5">Get your favorite brands at the best prices this week.</p>

  <!-- Category Links -->
 

 <div class="container my-5">
  <!-- Women Products -->
  <h2 class="text-center mb-4" style="color:rgb(149, 31, 51);">Women's Collection</h2>
  <div class="row justify-content-around">


<?php
$sql = "SELECT * FROM products WHERE main_category='women' LIMIT 5";
$result = $conn->query($sql);


if($result->num_rows > 0){
    while($row = $result->fetch_assoc()){
        $pid = $row['product_id'];  // ✅ yahan pe define karo
        $heartClass = in_array($pid, $wishlist_items) ? 'text-danger' : 'text-secondary';
        
        echo '
        <div class="card product-card mb-4" style="width: 15.7rem; height: 370px;">
          <img src="images/'.$row["image"].'" class="card-img-top img-fluid" alt="'.$row["name"].'" style="height: 50%; object-fit:cover;">

          <!-- Add to Cart -->
          <div class="product-actions" >
            <a href="product-detail.php?id='.$pid.'" class="btn btn-sm btn-outline-dark add-to-cart" style="background-color:pink; border:none;">
              <i class="bi bi-cart"></i> Add to Cart
            </a>
          </div>

          <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
              <div class="badge text-warning" style="background-color:rgb(149, 31, 51);">Hot</div>
              <div class="text-end">
                <small class="text-muted">
                  <i class="fa-solid fa-star"   style="color:orange; font-size:18px"></i>
                  <i class="fa-solid fa-star"   style="color:orange; font-size:18px"></i>
                  <i class="fa-solid fa-star"   style="color:orange; font-size:18px"></i>
                  <i class="fa-solid fa-star"   style="color:orange; font-size:18px"></i>
                  <i class="fa-regular fa-star" style="color:orange; font-size:18px"></i>
                </small>
              </div>
            </div>
            <h6 class="mb-2">'.$row["name"].'</h6>
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
}

?>



  </div>

  <!-- Men Products -->
  <h2 class="text-center my-4" style="color:rgb(149, 31, 51);">Men's Collection</h2>
  <div class="row justify-content-around">
    <?php
$sql = "SELECT * FROM products WHERE main_category='women' LIMIT 5";
$result = $conn->query($sql);
if($result->num_rows > 0){
    while($row = $result->fetch_assoc()){
        $pid = $row['product_id'];
        $heartClass = ""; // wishlist logic
        echo '
        <div class="card product-card mb-4" style="width: 15.7rem; height: 370px;">
          <img src="images/'.$row["image"].'" class="card-img-top img-fluid" alt="'.$row["name"].'" style="height: 50%; object-fit:cover;">

          <!-- Wishlist Heart -->
      

          <!-- Add to Cart -->
             <div class="product-actions">
          <a href="product-detail.php?id='.$pid.'" class="btn btn-sm btn-outline-dark add-to-cart" style="background-color:pink;border:none;">
            <i class="bi bi-cart"></i> Add to Cart
          </a>
           </div>

  
          <div class="card-body">

            <!-- Badge + Ratings -->
                         <div class="d-flex justify-content-between mb-2">
              <div class="badge text-warning" style="background-color:rgb(149, 31, 51);">Hot</div>
              <div class="text-end">
                <small class="text-muted">
                  <i class="fa-solid fa-star"   style="color:orange; font-size:18px"></i>
                  <i class="fa-solid fa-star"   style="color:orange; font-size:18px"></i>
                  <i class="fa-solid fa-star"   style="color:orange; font-size:18px"></i>
                  <i class="fa-solid fa-star"   style="color:orange; font-size:18px"></i>
                  <i class="fa-regular fa-star" style="color:orange; font-size:18px"></i>
                </small>
              </div>
            </div>

            <!-- Product Name -->
            <h6 class="mb-2">'.$row["name"].'</h6>

            <!-- Price + Discount -->
            <div class="d-flex justify-content-between align-items-center">
            '.(
                ($row['discount'] > 0) 
                ? "<div>
                     <div class='fw-bold text-danger'>Rs. ".number_format($row['price'] - ($row['price'] * $row['discount'] / 100), 2)."</div>
                     <small class='text-muted text-decoration-line-through'>Rs. ".number_format($row['price'], 2)."</small>
                     <span class='badge bg-success ms-2'>-{$row['discount']}%</span>
                   </div>"
                : "<div class='fw-bold'>Rs. ".number_format($row['price'], 2)."</div>"
            ).
            
            '    <a href="wishlist_toggle.php?product_id='.$pid.'" class="wishlist">
                <i class="fa-solid fa-heart '.$heartClass.'"></i>
              </a>
            </div>
          </div>
        </div>';
    }
}
?>

  </div>
</div>
<!-- 
        <div class="container-fluid  " style="background-color: rgb(149, 31, 51);">
                
                <hr class="mt-5">
                <div class="container-fluid mt-5" style="color: white;" >
                  <div class="row justify-content-center">
                    <div class="col-5">
                      <h2>Join our new sletter with 10$ off</h2>
                      <p>Lorem ipsum dol. Harum quia quisquam quis quaerat incidunt fugiat.</p>
                    </div>
                    <div class="col-6">
                      <input type="text" style="width: 500px; height: 60px;">
                      <button class="btn btn-warning" style="height: 60px;">subscribe</button>
                      <p>Lorem ipsum dolor <span class="text-warning">sit amet consectetur adipisicing elit. Atque, id.</span></p>
                    </div>
                  </div>
                </div>



         
               
            <hr>
  </div> -->
  
<div class="container-fluid mt-5">
  <center> <h2 style="color:gold;">BRANDS COLLECTION</h2></center>
  <div class="row justify-content-center gap-4 ms-5 mt-5">
    <div class="col-2"><img src="OIP__1_-removebg-preview.png" class="brand-logo"></div>
    <div class="col-2"><img src="OIP__2_-removebg-preview.png" class="brand-logo"></div>
    <div class="col-2"><img src="OIP__3_-removebg-preview.png" class="brand-logo"></div>
    <div class="col-2"><img src="OIP__4_-removebg-preview.png" class="brand-logo"></div>
    <div class="col-2"><img src="OIP__5_-removebg-preview.png" class="brand-logo"></div>
  </div>
</div>
<hr>
  <div class="container-fluid  " >
             
              

<div class="container my-5 gradient-bg" style="border: 5px solid ; width:1200px ; border-image: linear-gradient(45deg, #d4af37,#f5f5f5,  #c0c0c0, #ffd700) 1;
">
  <div class="row align-items-center">
    <div class="col-3 text-center">
      <img src="aid1.webp" alt="" style="max-width:100%; height: 230px;">
    </div>
    <div class="col-9 text-center">
<h2 class="gradient-text" style="font-weight: 700; color: rgb(149, 31, 51);">MORE KNOCKOUT OFFERS WAITING</h2>
      <h5>ONLY THE <span style="color: rgb(149, 31, 51);">CLOTH NOVA</span> APP</h5>
      <h5 class="mt-3 d-flex justify-content-center align-items-center gap-3">
        DOWNLOAD NOW
        <div class="download-buttons" style="display:flex; gap:20px;">

          <!-- Button 1: Play Store -->
          <a href="#" class="btn-store">
            <div class="btn-icon">
              <i class="fab fa-google-play"></i>
            </div>
            <div class="btn-text">
              <small class="mt-3">Get it on</small><br>
              <strong style="position: relative; bottom: 20px;">Google Play</strong>
            </div>
          </a>

          <!-- Button 2: Download -->
          <a href="#" class="btn-store">
            <div class="btn-icon">
              <i class="fas fa-download"></i>
            </div>
            <div class="btn-text">
              <small class="mt-3">Download on</small><br>
              <strong style="position: relative; bottom: 20px;">PlayStore</strong>
            </div>
          </a>

        </div>
      </h5>
    </div>
  </div>
</div>
<!-- Buttons Container -->


<style>

</style>

<!-- FontAwesome -->

<!-- Footer Start -->
<!-- Footer -->
<!-- Footer -->
<footer class="py-5 mt-5" style=" color:#000">
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



<!-- Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<!-- Footer End -->

<!-- FontAwesome -->
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

<!-- JS -->


<script>
  var swiper = new Swiper(".mySwiper", {
    slidesPerView: 6,
    spaceBetween: 20,
    loop: true,
    autoplay: {
      delay: 2000,
      disableOnInteraction: false,
    },
    navigation: {
      nextEl: ".swiper-button-next",
      prevEl: ".swiper-button-prev",
    },
    pagination: {
      el: ".swiper-pagination",
      clickable: true,
    },
    breakpoints: {
      320: { slidesPerView: 2 },
      576: { slidesPerView: 3 },
      768: { slidesPerView: 4 },
      992: { slidesPerView: 5 },
      1200: { slidesPerView: 6 }
    }
  });
</script>
<script>
document.querySelectorAll('.wishlist i').forEach(icon => {
  icon.addEventListener('click', function(e){
    e.preventDefault(); // prevent page reload
    this.classList.toggle('text-danger');
    this.classList.toggle('text-secondary');

    // Optional: send AJAX request to update wishlist in backend
    let pid = this.closest('a').href.split('product_id=')[1];
    fetch('wishlist_toggle.php?product_id=' + pid)
      .then(res => res.text())
      .then(console.log);
  });
});
</script>
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

<!-- Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet"> 
