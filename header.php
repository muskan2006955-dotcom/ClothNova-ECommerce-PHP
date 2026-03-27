

<?php
if (session_status() == PHP_SESSION_NONE) session_start();
include 'db.php';

// User info
$userPic = null;
$userName = "Guest";
$isLoggedIn = false;

if (isset($_SESSION['user_id'])) {
    $isLoggedIn = true;
    $uid = (int) $_SESSION['user_id'];
    $sql = "SELECT name, profile_pic, is_subscribed FROM users WHERE user_id = $uid LIMIT 1";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $userPic = !empty($row['profile_pic']) ? $row['profile_pic'] : null;
        $userName = $row['name'];
        $isSubscribed = $row['is_subscribed'];
    } else {
        $isSubscribed = 0;
    }
} else {
    $isSubscribed = 0;
}

// Current main/sub for dynamic links
$main = isset($_GET['main']) ? urlencode($_GET['main']) : 'all';
$sub  = isset($_GET['sub']) ? urlencode($_GET['sub']) : '';
$search_val = isset($_GET['search']) ? trim($_GET['search']) : '';

// Agar search input me kuch hai, sub reset kar do


?>
<?php
$wishlistCount = 0;
if ($isLoggedIn) {
    $res = $conn->query("SELECT COUNT(*) AS total_wish FROM wishlist WHERE user_id=$uid");
    $row = $res->fetch_assoc();
    $wishlistCount = $row['total_wish'] ?? 0;
}
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"/>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<style>
  a{
    text-decoration: none;
    color:#111;
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

.dropdown-menu {
  display: none;
  opacity: 0;
  transition: opacity 0.3s ease-in-out;
}

.dropdown:hover .dropdown-menu {
  display: block;
  opacity: 1;
}
.nav-link {
  position: relative;
  display: inline-block;
  color: #111;
  text-decoration: none;
  padding: 5px 0;
  background-color: transparent !important;
}

.nav-link:hover,
.nav-link.active {
  color: #111;
  background-color: transparent !important;
}

/* Underline animation */
.nav-link::after {
  content: "";
  position: absolute;
  left: 0;
  bottom: 0;
  width: 0%;
  height: 2px;
  background-color: darkred;
  transition: width 0.3s ease-in-out;
}




</style>
<header class=" shadow-sm py-2" >
  <div class="container-fluid">
    <div class="d-flex align-items-center justify-content-between">

      <!-- Left: Logo + Links -->
      <div class="d-flex align-items-center gap-3">
        <!-- <a href="index.php" class="d-flex align-items-center">
          <img src="images/logo.png" alt="Logo" style="height:40px;">
        </a> -->
        <h4 style="color: black;">logo</h4>

        <!-- Main Links -->
        <div class="d-none d-lg-flex align-items-center gap-3 ms-5">
          <a href="index.php" class="text-decoration-none text-dark ms-5 nav-link active">Home</a>

<!-- Men Categories -->
<div class="dropdown me-3">
  <a href="#" class="text-dark dropdown-toggle" data-bs-toggle="dropdown">Men </a>
  <ul class="dropdown-menu">
    <?php
    $men_sql = "SELECT DISTINCT category FROM products WHERE main_category='Men'";
    $men_result = $conn->query($men_sql);
    while($cat = $men_result->fetch_assoc()):
    ?>
      <li>
<a class="dropdown-item" href="index2.php?main=Men&sub=<?php echo urlencode($cat['category']); ?>">
          <?php echo htmlspecialchars($cat['category']); ?>
        </a>
      </li>
    <?php endwhile; ?>
  </ul>
</div>

<!-- Women Categories -->
<div class="dropdown me-3">
  <a href="#" class="text-dark dropdown-toggle" data-bs-toggle="dropdown">Women </a>
  <ul class="dropdown-menu">
    <?php
    $women_sql = "SELECT DISTINCT category FROM products WHERE main_category='Women'";
    $women_result = $conn->query($women_sql);
    while($cat = $women_result->fetch_assoc()):
    ?>
      <li>
<a class="dropdown-item" href="index2.php?main=Women&sub=<?php echo urlencode($cat['category']); ?>">
          <?php echo htmlspecialchars($cat['category']); ?>
        </a>
      </li>
    <?php endwhile; ?>
  </ul>
</div>

<!-- Kids Categories -->
<div class="dropdown">
  <a href="#" class="text-dark dropdown-toggle" data-bs-toggle="dropdown">Kids </a>
  <ul class="dropdown-menu">
    <?php
    $kids_sql = "SELECT DISTINCT category FROM products WHERE main_category='Kids'";
    $kids_result = $conn->query($kids_sql);
    while($cat = $kids_result->fetch_assoc()):
    ?>
      <li>
<a class="dropdown-item" href="index2.php?main=Kids&sub=<?php echo urlencode($cat['category']); ?>">
          <?php echo htmlspecialchars($cat['category']); ?>
        </a>
      </li>
    <?php endwhile; ?>
  </ul>
</div>

<a href="" class="nav-link">About</a>
<a href="" class="nav-link">Blogs</a>
<a href="contact.php" class="nav-link">Contact</a>


      <!-- Center: Search bar -->
<!-- Search Bar -->
<!-- Search Bar -->
<form class="d-flex mb-3 position-relative ms-5" method="GET" action="index2.php" style="width:480px; height: 45px;background-color: whitesmoke; border-radius: 8px; border: 1px solid #ccc;">
    <input type="text" name="search" class="form-control ps-5 " style="background-color: whitesmoke; border-radius: 8px;"
           placeholder="Search products..." 
           value="<?php echo htmlspecialchars($search_val); ?>">
    
    <!-- Magnifying glass icon inside input -->
    <i class="bi bi-search position-absolute" 
       style="left: 12px; top: 50%; transform: translateY(-50%); color: #555;"></i>



    <!-- preserve sort & main -->
    <input type="hidden" name="sort" value="<?php echo isset($sort) ? htmlspecialchars($sort) : ''; ?>">
    <input type="hidden" name="main" value="<?php echo htmlspecialchars($main); ?>">

    <?php if(empty($search_val)): ?>
       <input type="hidden" name="sub" value="<?php echo htmlspecialchars($sub); ?>">
    <?php endif; ?>
</form>





      <!-- Right: Icons -->
      <div class="d-flex align-items-center gap-3">
        <!-- User -->
        <div class="dropdown">
          <a href="#" class="d-flex align-items-center text-dark text-decoration-none dropdown-toggle" id="userDropdown" data-bs-toggle="dropdown">
            <?php if ($isLoggedIn && $userPic): ?>
              <img src="uploads/<?php echo htmlspecialchars($userPic); ?>" alt="profile" class="rounded-circle" width="35" height="35" style="object-fit:cover;">
            <?php else: ?>
              <i class="fa-solid fa-user-circle fa-2x" style="font-size: 26px;" ></i>
            <?php endif; ?>
          </a>
          <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
            <?php if ($isLoggedIn): ?>
              <li><h6 class="dropdown-header">Hi, <?php echo htmlspecialchars($userName); ?></h6></li>
              <li><a class="dropdown-item" href="profile.php">My Profile</a></li>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item" href="logout.php">Logout</a></li>
            <?php else: ?>
              <li><a class="dropdown-item" href="login.php">Login</a></li>
                <li><a class="dropdown-item" href="register.php">sigup</a></li>

            <?php endif; ?>
          </ul>
        </div>

        <!-- Wishlist -->
<a href="wishlist_show.php" class="btn position-relative text-dark fs-9">
  <i class="fa-solid fa-heart" style="font-size: 23px;"></i>
  <?php if($wishlistCount > 0): ?>
    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
      <?php echo $wishlistCount; ?>
    </span>
  <?php endif; ?>
</a>
<!-- <a href="whisper.php" class="text-dark text-decoration-none ms-3">
  <i class="fa-solid fa-comment-dots"></i> Whisper
</a> -->

        <!-- Cart -->
        <button class="btn  position-relative" type="button" data-bs-toggle="offcanvas" data-bs-target="#cartSidebar">
         <i class="fa-solid fa-cart-shopping" style="font-size: 23px;"></i>
          <?php
          $cartCount = 0;
          if ($isLoggedIn) {
              $res = $conn->query("SELECT SUM(quantity) AS total_qty FROM cart WHERE user_id=$uid");
              $row = $res->fetch_assoc();
              $cartCount = $row['total_qty'] ?? 0;
          }
          ?>
          <?php if($cartCount > 0): ?>
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
              <?php echo $cartCount; ?>
            </span>
          <?php endif; ?>
        </button>
      </div>

      <!-- Mobile Hamburger -->
      <a href="#" class="btn btn-light d-lg-none ms-2" data-bs-toggle="offcanvas" data-bs-target="#mobileMenu">
        <i class="bi bi-list fs-4"></i>
      </a>

    </div>
  </div>
</header>

<!-- Mobile Offcanvas Menu -->
<div class="offcanvas offcanvas-start" tabindex="-1" id="mobileMenu">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title">Menu</h5>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
  </div>
  <div class="offcanvas-body">
    <ul class="list-unstyled">
      <li><a href="index.php" class="text-dark text-decoration-none">Home</a></li>
      <li><a href="#" class="text-dark text-decoration-none">Men</a></li>
      <li><a href="#" class="text-dark text-decoration-none">Women</a></li>
      <li><a href="#" class="text-dark text-decoration-none">Kid</a></li>
      <li><a href="#" class="text-dark text-decoration-none">GenZ</a></li>
    </ul>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>