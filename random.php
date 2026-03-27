<?php
include 'db.php';

// Parameters
$category = isset($_GET['category']) ? $_GET['category'] : 'all';
$sort = isset($_GET['sort']) ? $_GET['sort'] : '';

// Base query
$sql = "SELECT * FROM products WHERE 1=1";

// Category filter
if ($category != "all") {
    $sql .= " AND category = '" . $conn->real_escape_string($category) . "'";
}

// Sorting
if ($sort == "low_high") {
    $sql .= " ORDER BY price ASC";
} elseif ($sort == "high_low") {
    $sql .= " ORDER BY price DESC";
} elseif ($sort == "newest") {
    $sql .= " ORDER BY created_at DESC";
}

$result = $conn->query($sql);
?>

<div class="row g-4">
<?php while($row = $result->fetch_assoc()): ?>
  <div class="col-md-3 col-6">
    <div class="product-card">
      <img src="<?= $row['image'] ?>" alt="" class="img-fluid">
      <h6 class="mt-2"><?= $row['name'] ?></h6>
      <p class="text-muted">$<?= $row['price'] ?></p>
      <button class="btn btn-sm btn-dark">Add to Cart</button>
    </div>
  </div>
<?php endwhile; ?>
</div>

<select class="form-select w-auto" onchange="window.location='index2.php?sort='+this.value">
  <option disabled <?= !isset($_GET['sort']) ? 'selected' : '' ?>>Sort by</option>
  <option value="low_high" <?= (isset($_GET['sort']) && $_GET['sort']=='low_high') ? 'selected' : '' ?>>Price: Low to High</option>
  <option value="high_low" <?= (isset($_GET['sort']) && $_GET['sort']=='high_low') ? 'selected' : '' ?>>Price: High to Low</option>
  <option value="newest" <?= (isset($_GET['sort']) && $_GET['sort']=='newest') ? 'selected' : '' ?>>Newest Arrivals</option>
</select>

<ul class="list-unstyled">
  <li><a href="index2.php?category=Active Wear" class="text-dark">Active Wear</a></li>
  <li><a href="products.php?category=Beauty" class="text-dark">Beauty</a></li>
  <li><a href="products.php?category=Candles" class="text-dark">Candles</a></li>
  <li><a href="products.php?category=Fashion" class="text-dark">Fashion</a></li>
</ul>