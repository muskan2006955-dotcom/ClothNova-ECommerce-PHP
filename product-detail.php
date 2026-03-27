<?php
include 'db.php';
session_start();

if(!isset($_GET['id'])){
    die("Product not found!");
}

$id = intval($_GET['id']);

// Fetch product
$sql = "SELECT * FROM products WHERE product_id = $id";
$result = $conn->query($sql);
$product = $result->fetch_assoc();
if(!$product){
    die("Invalid product!");
}

// Fetch variants
$variantSql = "SELECT * FROM product_variants WHERE product_id = $id ORDER BY variant_id";
$variantResult = $conn->query($variantSql);
$variants = [];
$sizes = [];
$colors = [];
while($v = $variantResult->fetch_assoc()){
    $v['size'] = $v['size'] ?? '';
    $v['color'] = $v['color'] ?? '';
    $v['stock'] = (int)$v['stock'];
    $variants[] = $v;

    if($v['size'] && !in_array($v['size'], $sizes)) $sizes[] = $v['size'];
    if($v['color'] && !in_array($v['color'], $colors)) $colors[] = $v['color'];
}

// Fetch product images (gallery)
$imageSql = "SELECT * FROM product_images WHERE product_id = $id LIMIT 4";
$imageResult = $conn->query($imageSql);
$images = [];
while($img = $imageResult->fetch_assoc()){
    $images[] = $img['image'];
}

// Handle add comment
if(isset($_POST['add_comment']) && isset($_SESSION['user_id'])){
    $comment = trim($_POST['comment']);
    $rating = isset($_POST['rating']) ? intval($_POST['rating']) : 0;
    if(!empty($comment)){
        $uid = (int)$_SESSION['user_id'];
        $stmt = $conn->prepare("INSERT INTO product_comments (product_id, user_id, comment, rating) VALUES (?,?,?,?)");
        $stmt->bind_param("iisi", $id, $uid, $comment, $rating);
        $stmt->execute();
        header("Location: product-detail.php?id=".$id);
        exit;
    }
}


// Handle delete comment
if(isset($_POST['delete_comment']) && isset($_SESSION['user_id'])){
    $cid = intval($_POST['delete_id']);
    $uid = intval($_SESSION['user_id']);
    $del = $conn->prepare("DELETE FROM product_comments WHERE comment_id=? AND user_id=?");
    $del->bind_param("ii",$cid,$uid);
    $del->execute();
 
    header("Location: product-detail.php?id=".$id."#commentsSection");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo htmlspecialchars($product['name']); ?> - Cloth Nova</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
<link rel="stylesheet" href="style.css">
<link rel="stylesheet" href="javascript/magnify.css">

<style>
.container-product { margin-top:50px; }
.big-image { width:450px; height:450px; object-fit:cover; border-radius:10px; box-shadow:2px 2px 8px rgba(0,0,0,0.15);}
.small-image { width:100%; height:95px; object-fit:cover; cursor:pointer; border-radius:8px; margin-top:10px; transition:0.3s; border:1px solid gray;}
.small-image:hover { border-color:darkred; }
.comment-box { background:#fff; padding:12px; border-radius:8px; margin-bottom:12px; box-shadow:0 2px 4px rgba(0,0,0,0.1);}
.comment-box small { color:#888; }
.delete-btn { font-size:13px; color:red; text-decoration:none; margin-left:10px; }
.delete-btn:hover { text-decoration:underline; }
</style>
</head>
<body>
<?php include 'navbar.php'; include 'header.php'; ?>

<div class="container container-product dettail">
  <div class="row justify-content-around">
    <!-- Left: Images -->
    <div class="col-md-6 text-center">
      <a href="images/<?php echo htmlspecialchars($product['image']); ?>" id="mainLink">
        <img src="images/<?php echo htmlspecialchars($product['image']); ?>"
             id="mainImage"
             class="zoom big-image"
             data-magnify-src="images/<?php echo htmlspecialchars($product['image']); ?>"
             alt="<?php echo htmlspecialchars($product['name']); ?>">
      </a>
      <div class="row mt-2">
        <?php foreach($images as $img): ?>
          <div class="col-3">
            <img src="images/<?php echo htmlspecialchars($img); ?>"
                 onclick="changeImage('images/<?php echo htmlspecialchars($img); ?>')"
                 class="small-image">
          </div>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- Right: Details -->
    <div class="col-md-5">
      <h6>Cloth Nova</h6>
      <h3><?php echo htmlspecialchars($product['name']); ?></h3>
      <h5>Rs. <?php echo number_format($product['price'],2); ?></h5>

      <!-- Stars + Reviews -->
      <div class="mb-2">
        <i class="fa-solid fa-star text-warning"></i>
        <i class="fa-solid fa-star text-warning"></i>
        <i class="fa-solid fa-star text-warning"></i>
        <i class="fa-solid fa-star text-warning"></i>
        <i class="fa-regular fa-star text-warning"></i>
        <span class="ms-2">54 Reviews</span>
      </div>

      <!-- Add to cart form -->
      <form method="POST" action="add_to_cart.php" id="cartForm">
        <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
        <input type="hidden" name="variant_id" id="variant_id" value="">

        <div class="row g-2">
          <div class="col-4">
            <select name="size" id="sizeSelect" class="form-select">
              <option value="">Size</option>
              <?php foreach($sizes as $s): ?>
              <option value="<?php echo htmlspecialchars($s); ?>"><?php echo htmlspecialchars($s); ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-4">
            <select name="color" id="colorSelect" class="form-select">
              <option value="">Color</option>
              <?php foreach($colors as $c): ?>
              <option value="<?php echo htmlspecialchars($c); ?>"><?php echo htmlspecialchars($c); ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-4">
            <input type="number" name="quantity" id="qty" value="1" min="1" class="form-control">
          </div>
        </div>
        <div id="variantMessage" class="text-danger mt-2"></div>
        <div class="mt-3 d-flex gap-2">
          <button type="submit" class="btn btn-dark w-50"><i class="bi bi-cart"></i> Add to Cart</button>
          <button type="button" class="btn btn-outline-dark w-50">Download Details</button>
        </div>
      </form>

      <div class="product-description mt-4">
        <h4>Product Details</h4>
        <p><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
      </div>
    </div>
  </div>
</div>

<!-- Tabs -->
<div class="text-center my-4">
  <a href="#" class="switch-link btn btn-outline-dark mx-2" data-target="commentsSection">Comments & Reviews</a>
  <a href="#" class="switch-link btn btn-outline-dark mx-2" data-target="moreProductsSection">More Products</a>
</div>

<!-- Comments -->
<div id="commentsSection" style="display:none;" class="container mt-4">
  <h2 class="text-center mb-4">Comments & Reviews</h2>
  <?php if(isset($_SESSION['user_id'])): ?>
<?php if(isset($_SESSION['user_id'])): ?>
  <div id="commentsList">
  <form method="POST">
    <div class="mb-2">
      <label class="form-label">Your Rating:</label><br>
      <div class="rating-input" style="font-size:22px; color:orange; cursor:pointer;">
        <input type="hidden" name="rating" id="ratingValue" value="0">
        <i class="fa-regular fa-star" data-value="1"></i>
        <i class="fa-regular fa-star" data-value="2"></i>
        <i class="fa-regular fa-star" data-value="3"></i>
        <i class="fa-regular fa-star" data-value="4"></i>
        <i class="fa-regular fa-star" data-value="5"></i>
      </div>
    </div>
    <textarea name="comment" class="form-control mb-2" placeholder="Write your comment..." required></textarea>
    <button type="submit" name="add_comment" class="btn btn-dark">Submit</button>
  </form>
  </div>
<?php else: ?>
  <p class="text-muted">Please <a href="login.php">login</a> to add a comment.</p>
<?php endif; ?>

  <?php else: ?>
    <p class="text-muted">Please <a href="login.php">login</a> to add a comment.</p>
  <?php endif; ?>
  <hr>
  <?php
$csql = "SELECT c.comment_id, c.comment, c.rating, c.created_at, u.name, u.user_id 
         FROM product_comments c 
         JOIN users u ON c.user_id=u.user_id 
         WHERE c.product_id=$id 
         ORDER BY c.created_at DESC";
;
$cresult = $conn->query($csql);

if($cresult->num_rows > 0){
  while($crow = $cresult->fetch_assoc()){
    echo "<div class='mb-3 p-2 border rounded'>";
    echo "<strong>".htmlspecialchars($crow['name'])."</strong> ";
    
    // Stars display
    $stars = (int)$crow['rating'];
    for($i=1; $i<=5; $i++){
      if($i <= $stars){
        echo "<i class='fa-solid fa-star' style='color:orange;'></i>";
      } else {
        echo "<i class='fa-regular fa-star' style='color:orange;'></i>";
      }
    }

    echo "<br><small class='text-muted'>".$crow['created_at']."</small>";
    echo "<p>".htmlspecialchars($crow['comment'])."</p>";
    
    // Delete option only for owner
    if(isset($_SESSION['user_id']) && $_SESSION['user_id'] == $crow['user_id']){
echo "<form method='POST' style='display:inline;' 
      onsubmit=\"return confirm('Are you sure you want to delete this comment?');\">
        <input type='hidden' name='delete_id' value='".$crow['comment_id']."'>
        <button type='submit' name='delete_comment' class='btn btn-sm btn-danger'>Delete</button>
      </form>";

    }

    echo "</div>";
  }
} else {
  echo "<p>No comments yet. Be the first!</p>";
}

  ?>
</div>

<!-- More Products -->
<div id="moreProductsSection" class="container mt-4">
  <h2 class="text-center mb-4">More from this Category</h2>
  <div class="d-flex flex-wrap justify-content-center gap-3">
    <?php
    $mainCat = $product['main_category'];
    $subCat  = $product['sub_category'];
    $relatedSql = "SELECT * FROM products WHERE main_category=? AND sub_category=? AND product_id<>? LIMIT 8";
    $stmt=$conn->prepare($relatedSql);
    $stmt->bind_param("ssi",$mainCat,$subCat,$product['product_id']);
    $stmt->execute();
    $relatedResult=$stmt->get_result();
    while($row=$relatedResult->fetch_assoc()):
    ?>
      <div class="card" style="width:250px;">
        <a href="product-detail.php?id=<?php echo $row['product_id']; ?>">
          <img src="images/<?php echo $row['image']; ?>" class="card-img-top" style="height:200px;object-fit:cover;">
        </a>
        <div class="card-body text-center">
          <h6><?php echo $row['name']; ?></h6>
          <p class="text-danger mb-0">Rs. <?php echo number_format($row['price'],2); ?></p>
        </div>
      </div>
    <?php endwhile; ?>
  </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="javascript/jquery.magnify.js"></script>
<script>
$(document).ready(function(){ $('.zoom').magnify(); });

const variants = <?php echo json_encode($variants); ?>;
const sizeSelect=document.getElementById('sizeSelect');
const colorSelect=document.getElementById('colorSelect');
const variantIdInput=document.getElementById('variant_id');
const qtyInput=document.getElementById('qty');
const variantMessage=document.getElementById('variantMessage');

function updateVariant(){
  const s=sizeSelect.value;
  const c=colorSelect.value;
  variantMessage.textContent='';
  variantIdInput.value='';
  qtyInput.value=1;
  if(!s||!c) return;
  const v=variants.find(x=>x.size===s && x.color===c);
  if(v){
    variantIdInput.value=v.variant_id;
    qtyInput.max=v.stock;
    variantMessage.textContent='In stock: '+v.stock;
  } else {
    variantMessage.textContent='Not available';
    qtyInput.max=1;
  }
}
sizeSelect.addEventListener('change',updateVariant);
colorSelect.addEventListener('change',updateVariant);

document.getElementById('cartForm').addEventListener('submit',function(e){
  if(!variantIdInput.value){
    e.preventDefault();
    alert('Please select size and color');
  }
});

function changeImage(src){
  let mainImg=document.getElementById("mainImage");
  let link=document.getElementById("mainLink");
  mainImg.src=src;
  link.href=src;
  mainImg.setAttribute("data-magnify-src",src);
  $('.zoom').each(function(){ $(this).trigger('zoom.destroy'); });
  $('.zoom').magnify();
}

document.querySelectorAll(".switch-link").forEach(link=>{
  link.addEventListener("click",function(e){
    e.preventDefault();
    document.getElementById("commentsSection").style.display="none";
    document.getElementById("moreProductsSection").style.display="none";
    document.getElementById(this.dataset.target).style.display="block";
  });
});
document.getElementById("moreProductsSection").style.display="block";
</script>
<script>
// ⭐ Rating Stars Click Function
document.querySelectorAll(".rating-input i").forEach(star => {
  star.addEventListener("click", function(){
    let val = this.getAttribute("data-value");
    document.getElementById("ratingValue").value = val;

    // sab ko reset karo
    document.querySelectorAll(".rating-input i").forEach(s => {
      s.classList.remove("fa-solid");
      s.classList.add("fa-regular");
    });

    // jo select kiya us tak fill karo
    for(let i=1; i<=val; i++){
      let current = document.querySelector(`.rating-input i[data-value='${i}']`);
      current.classList.remove("fa-regular");
      current.classList.add("fa-solid");
    }
  });
});
document.getElementById("commentForm").addEventListener("submit", function(e){
  e.preventDefault();
  let formData = new FormData(this);

  fetch("add_comment.php", {
    method: "POST",
    body: formData
  })
  .then(res => res.text())
  .then(data => {
    document.getElementById("commentsList").innerHTML = data; // comments reload
    this.reset();
    document.getElementById("ratingValue").value = 0;
    document.querySelectorAll(".rating-input i").forEach(s=>{
      s.classList.remove("fa-solid"); s.classList.add("fa-regular");
    });
  });
});

</script>

</body>
</html>
