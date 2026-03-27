<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit;
}

$uid = (int)$_SESSION['user_id'];

// Fetch all orders with their items in one query
$sql = "SELECT o.order_id, o.order_code, oi.quantity, oi.price, p.name AS product_name, p.image AS product_image
        FROM orders o
        JOIN order_items oi ON o.order_id = oi.order_id
        JOIN products p ON oi.product_id = p.product_id
        WHERE o.user_id = $uid
        ORDER BY o.order_date DESC, o.order_id, oi.item_id";

$result = $conn->query($sql);

// Group items by order_id
$orders = [];
while($row = $result->fetch_assoc()){
    $orders[$row['order_id']]['code'] = $row['order_code'];
    $orders[$row['order_id']]['items'][] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Orders</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background:#f8f9fa; }

    .sidebar a {
      color: #333;
      text-decoration: none;
      display: block;
      margin: 10px 0;
      font-weight: 500;
    }
    .sidebar a:hover { color: #0d6efd; }

    .profile-pic {
      width: 80px; height: 80px;
      border-radius: 50%; object-fit: cover;
      margin-bottom: 15px;
      border: 2px solid #ddd;
    }

    .sidebar {
      width: 300px;
      min-height: 100vh;
      background: #fff;
      border-right: 1px solid #dee2e6;
      padding: 20px;
    }

    .main-content {
      flex-grow: 1;
      padding: 40px;
      max-width: 300rem;
    }

    .product-img {
      width: 60px; height: 60px;
      object-fit: cover; border-radius: 5px;
    }
    .custom-header th{
  background: rgb(149, 31, 51); /* orange gradient */
  color: #fff;
  text-transform: uppercase;
  letter-spacing: 1px;
}

  </style>
</head>
<body>
<div class="d-flex">
  <!-- Sidebar -->
  <div class="sidebar">
    <?php include 'user_sidebar.php'; ?>
  </div>

  <!-- Main Content -->
  <div class="main-content">
    <h3 class="mb-4 text-warning" >My Orders</h3>
    <div class="table-responsive">
      <table class="table table-bordered table-hover">
        <thead class="custom-header">
          <tr>
            <th>Order Code</th>
            <th>Product</th>
            <th>Image</th>
            <th>Quantity</th>
            <th>Price</th>
          </tr>
        </thead>
        <tbody>
          <?php if(!empty($orders)): ?>
            <?php foreach($orders as $order): ?>
              <?php 
                $rowCount = count($order['items']); 
                $first = true; 
              ?>
              <?php foreach($order['items'] as $item): ?>
                <tr>
                  <?php if($first): ?>
                    <td rowspan="<?= $rowCount ?>"><?= htmlspecialchars($order['code']) ?></td>
                    <?php $first = false; ?>
                  <?php endif; ?>
                  <td><?= htmlspecialchars($item['product_name']) ?></td>
                  <td><img src="images/<?= htmlspecialchars($item['product_image'] ?? 'default.png') ?>" class="product-img" alt="Product"></td>
                  <td><?= $item['quantity'] ?></td>
                  <td>$<?= number_format($item['price'],2) ?></td>
                </tr>
              <?php endforeach; ?>
            <?php endforeach; ?>
          <?php else: ?>
            <tr><td colspan="5" class="text-center">No orders found.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
</body>
</html>
