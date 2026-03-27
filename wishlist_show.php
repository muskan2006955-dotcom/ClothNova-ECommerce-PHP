<?php 

session_start();
include 'db.php';
if(!isset($_SESSION['user_id'])){
   header("Location: login.php");
}

include 'navbar.php';
include 'header.php';

$main = isset($_GET['main']) ? mysqli_real_escape_string($conn, $_GET['main']) : (isset($_SESSION['main']) ? mysqli_real_escape_string($conn, $_SESSION['main']) : 'all');
$sub  = isset($_GET['sub'])  ? mysqli_real_escape_string($conn, $_GET['sub'])  : (isset($_SESSION['sub']) ? mysqli_real_escape_string($conn, $_SESSION['sub']) : '');
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;


$user_id = (int)$_SESSION['user_id'];

// user ki wishlist ke products
$sql = "SELECT p.* 
        FROM wishlist w
        JOIN products p ON w.product_id = p.product_id
        WHERE w.user_id=$user_id";
$result = $conn->query($sql);

// user ke wishlist items
$wishlist_items = [];
if($result && $result->num_rows > 0){
    mysqli_data_seek($result, 0);
    while($r = $result->fetch_assoc()){
        $wishlist_items[] = (int)$r['product_id'];
    }
    mysqli_data_seek($result, 0);
}
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
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
    a{
      text-decoration: none;
      color: #111;
    }
    
  .product-actions a {
    background: orange; /* tumhara custom color */
    color: white;
    padding: 6px 12px;
    border-radius: 50px;
    font-size: 14px;
    text-decoration: none;
    transition: 0.3s;
  }

  .product-actions a:hover {
    background: black;
  }

  .wishlist {
    position: absolute;
    top: 10px;
    right: -50px; /* hidden initially */
    transition: all 0.4s ease;
    background: #fff;
    border-radius: 50%;
    padding: 8px 10px;
    color: #6D0E4E;
    font-size: 18px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.2);
  }

  /* Hover Effect */
  .product-card:hover .product-actions {
    left: 10px;
  }
  .product-card:hover .wishlist {
    right: 10px;
  }
    .card {
    position: relative;
    overflow: hidden;
    border: none;
    transition: transform 0.3s ease;
  }

  .card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
  }

  /* Heart icon overlay */
  .wishlist-icon {
    position: absolute;
    top: 10px;
    right: -50px; /* hidden initially */
    background: #fff;
    color: #6D0E4E; /* tumhara desired color */
    font-size: 20px;
    border-radius: 50%;
    padding: 8px 10px;
    box-shadow: 0 3px 6px rgba(0,0,0,0.2);
    transition: all 0.4s ease;
    z-index: 2;
  }

  .card:hover .wishlist-icon {
    right: 10px; /* slide in on hover */
  }

  .product-card {
    position: relative;
    overflow: hidden;
  }

  /* Overlay buttons */
  .product-actions {
    position: absolute;
    top: 10px;
    left: -100px; /* hidden initially */
    display: flex;
    flex-direction: column;
    gap: 10px;
    transition: all 0.4s ease;
  }

</style>
  </head>

  <body>
<?php
include 'preloader.php';
?>

    <div class="container my-4">
  
  <h2 class="text-center mb-4">My Wishlist</h2>

  <div class="row g-4"> <!-- bootstrap row with gap -->

    <?php if($result && $result->num_rows > 0): ?>
        <?php while($row = $result->fetch_assoc()): 
            $pid   = (int)$row['product_id'];
            $name  = htmlspecialchars($row['name']);
            $price = number_format((float)$row['price'], 2);
            $img   = 'images/' . $row['image'];
        ?>
        
        <div class="col-12 col-sm-6 col-md-4 col-lg-3"> <!-- responsive 4 per row -->
          <div class="card product-card h-100" style="border:none; position:relative; width: 280px;">
            
            <!-- Product Image -->
            <a href="product-detail.php?id=<?= $pid ?>">
              <img src="images/<?= $img ?>" class="card-img-top img-fluid" alt="<?= $name ?>" 
                   style="width:100%; height:230px; object-fit:cover;">
            </a>

            <!-- Wishlist Delete (Dustbin Icon) -->
            <a href="wishlist_delete.php?product_id=<?= $pid ?>" 
               onclick="return confirm('Are you sure you want to remove this item from wishlist?')" 
               class="wishlist" 
               style="position:absolute; top:10px; right:10px;">
              <i class="fa-solid fa-trash text-danger"></i>
            </a>

            <div class="card-body">

              <!-- Badge + Ratings -->
              <div class="d-flex justify-content-between mb-2">
                <div class="badge bg-danger">Hot</div>
                <div class="text-end">
                  <small class="text-muted">
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-regular fa-star"></i>
                  </small>
                </div>
              </div>

              <!-- Product Name -->
              <h6 class="mb-2"><?= $name ?></h6>

              <!-- Price + Cart -->
              <div class="d-flex justify-content-between align-items-center">
                <div class="fw-bold">Rs. <?= $price ?></div>
                <div class="product-actions">
                  <a href="product-detail.php?id=<?= $pid ?>&return=index2.php?main=<?= urlencode($main) ?>&sub=<?= urlencode($sub) ?>&page=<?= $page ?>">
                    <i class="bi bi-cart"></i> Add to Cart
                  </a>
                </div>
              </div>

            </div>
          </div>
        </div>

        <?php endwhile; ?>
    <?php else: ?>
        <p class="text-center">Your wishlist is empty.</p>
    <?php endif; ?>

  </div>
</div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
  </body>
</html>





 