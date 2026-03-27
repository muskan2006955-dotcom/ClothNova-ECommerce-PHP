  <style>body { background: #f9fafb; }
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
  color: #fff;
  border-radius: 8px;
}
.card { border-radius: 12px; }
.card h2 { font-size: 28px; font-weight: 600; }
.order-img { width: 50px; height: 50px; object-fit: cover; border-radius: 8px; margin-right: 10px; }
.status-badge { padding: 5px 10px; border-radius: 12px; color: #fff; font-size: 12px; }
.status-pending { background: #ff9800; }
.status-completed { background: #4caf50; }
.status-cancelled { background: #f44336; }

</style>
  <div class="sidebar p-3">
    <h4 class="mb-4">Admin</h4>
    <a href="admin_dashboard.php" class="active1"> Dashboard</a>
      <a href="admin_profile.php" class="active7">Your Profile</a>
    <a href="add_product.php" class="active2">Add Products</a>
    <a href="add_variant.php" class="active8">Add variant</a>

    <a href="view_products.php" class="active3">view products</a>
   <a href="view_all_variants.php" class="active5">view variant</a>

    <a href="view_all_images.php" class="active4">view images</a>
       <a href="view_order.php" class="active10">view order</a>

   <a href="admin-wishlist.php" class="active9">user wishlist</a>

  <a href="subscriber.php" class="active6">user subscriber</a>
  <a href="logout.php">Logout</a>

  </div>