<?php
session_start();
include 'db.php';

// agar user login nahi hai to redirect
if(!isset($_SESSION['user_id'])){
  header("Location: login.php");
  exit;
}

$tracking_result = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_code = trim($_POST['order_code']);

    if (!empty($order_code)) {
        $sql = "SELECT o.order_id, o.order_code, o.status, o.order_date, o.messages,
                       oi.quantity, oi.price, p.name AS product_name, p.image AS product_image
                FROM orders o
                JOIN order_items oi ON o.order_id = oi.order_id
                JOIN products p ON oi.product_id = p.product_id
                WHERE o.order_code = ? AND o.user_id = ?";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $order_code, $_SESSION['user_id']);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()){
                $tracking_result['order_id'] = $row['order_id'];
                $tracking_result['order_code'] = $row['order_code'];
                $tracking_result['status'] = $row['status'];
                $tracking_result['order_date'] = $row['order_date'];
                $tracking_result['messages'] = $row['messages']; // ✅ messages added
                
                $tracking_result['items'][] = $row;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Track Order</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background:#f8f9fa; }
    .sidebar a { color: #333; text-decoration: none; display: block; margin: 10px 0; font-weight: 500; }
    .sidebar a:hover { color: #0d6efd; }
    .profile-pic { width: 80px; height: 80px; border-radius: 50%; object-fit: cover; margin-bottom: 15px; border: 2px solid #ddd; }
    .sidebar { width: 300px; min-height: 100vh; background: #fff; border-right: 1px solid #dee2e6; padding: 20px; }
    .main-content { flex-grow: 1; padding: 40px; max-width: 300rem; }
    .product-img { width: 60px; height: 60px; object-fit: cover; border-radius: 5px; }
    .custom-header th { background: rgb(149, 31, 51); color: #fff; text-transform: uppercase; letter-spacing: 1px; }
    .message-box { background: #f8f9fa; padding: 12px 15px; border-radius: 6px; margin-top: 10px; border-left: 4px solid rgb(149,31,51); }
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
    <h3 class="mb-4 text-warning">Track Order</h3>

    <!-- Tracking Form -->
    <form method="post" class="card p-3 shadow-sm bg-white mb-4">
      <div class="input-group">
        <input type="text" name="order_code" class="form-control" placeholder="Enter Tracking Code" required>
        <button type="submit" class="btn btn-warning">Track</button>
      </div>
    </form>

    <!-- Tracking Result -->
    <?php if($tracking_result): ?>
      <div class="card shadow-sm">
        <div class="card-body">
          <h5>Order Code: <?= htmlspecialchars($tracking_result['order_code']) ?></h5>
          <p>Status: <span class="badge bg-info"><?= htmlspecialchars($tracking_result['status']) ?></span></p>
          <p>Order Date: <?= htmlspecialchars($tracking_result['order_date']) ?></p>

          <!-- Message from admin -->
          <?php if(!empty($tracking_result['messages'])): ?>
          <div class="message-box">
            <strong>Message from Admin:</strong>
            <div><?= $tracking_result['messages'] ?></div>
          </div>
          <?php endif; ?>

          <div class="table-responsive mt-3">
            <table class="table table-bordered">
              <thead class="custom-header">
                <tr>
                  <th>Product</th>
                  <th>Image</th>
                  <th>Qty</th>
                  <th>Price</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach($tracking_result['items'] as $item): ?>
                  <tr>
                    <td><?= htmlspecialchars($item['product_name']) ?></td>
                    <td><img src="images/<?= htmlspecialchars($item['product_image']) ?>" class="product-img"></td>
                    <td><?= $item['quantity'] ?></td>
                    <td>$<?= number_format($item['price'], 2) ?></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    <?php elseif($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
      <div class="alert alert-danger text-center">Invalid tracking code. Please try again.</div>
    <?php endif; ?>
  </div>
</div>
</body>
</html>
