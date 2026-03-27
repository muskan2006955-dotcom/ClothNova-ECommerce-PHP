<?php
session_start();
include "db.php";

if(!isset($_SESSION['admin_id'])){
    header("Location: admin_login.php");
    exit();
}

// Stats
$total_products = $conn->query("SELECT COUNT(*) AS c FROM products")->fetch_assoc()['c'];
$total_orders   = $conn->query("SELECT COUNT(*) AS c FROM orders")->fetch_assoc()['c'];
$total_users    = $conn->query("SELECT COUNT(*) AS c FROM users")->fetch_assoc()['c'];
$total_sales    = $conn->query("SELECT IFNULL(SUM(total),0) AS s FROM orders")->fetch_assoc()['s'];

// Orders by status
$total_pending   = $conn->query("SELECT COUNT(*) AS c FROM orders WHERE status='Pending'")->fetch_assoc()['c'];
$total_completed = $conn->query("SELECT COUNT(*) AS c FROM orders WHERE status='Completed'")->fetch_assoc()['c'];
$total_cancelled = $conn->query("SELECT COUNT(*) AS c FROM orders WHERE status='Cancelled'")->fetch_assoc()['c'];

// Subscribers
$total_subscribe = $conn->query("SELECT COUNT(*) AS c FROM users WHERE is_subscribed=1")->fetch_assoc()['c'];

// Recent orders with product & user info
$recent_orders = $conn->query("
    SELECT o.order_id, o.status, o.order_date, u.name AS user_name, u.email,
           p.name AS product_name, p.image, oi.quantity
    FROM orders o
    JOIN users u ON o.user_id = u.user_id
    JOIN order_items oi ON oi.order_id = o.order_id
    JOIN products p ON p.product_id = oi.product_id
    ORDER BY o.order_date DESC
    LIMIT 8
");
// Orders per month (last 6 months)
$orders_by_month = $conn->query("
    SELECT DATE_FORMAT(order_date, '%b %Y') AS month, COUNT(*) AS count
    FROM orders
    GROUP BY YEAR(order_date), MONTH(order_date)
    ORDER BY order_date DESC
    LIMIT 6
");

$months = [];
$counts = [];
while($row = $orders_by_month->fetch_assoc()){
    $months[] = $row['month'];
    $counts[] = $row['count'];
}

?>
<?php
// Candle data prepare
$candle_data = [];

$sql = "
    SELECT 
        DATE_FORMAT(order_date, '%b %Y') AS month,
        MIN(total) AS low,
        MAX(total) AS high,
        (SELECT total FROM orders o2 
         WHERE MONTH(o2.order_date) = MONTH(o1.order_date) 
         AND YEAR(o2.order_date) = YEAR(o1.order_date) 
         ORDER BY o2.order_date ASC LIMIT 1) AS open,
        (SELECT total FROM orders o3 
         WHERE MONTH(o3.order_date) = MONTH(o1.order_date) 
         AND YEAR(o3.order_date) = YEAR(o1.order_date) 
         ORDER BY o3.order_date DESC LIMIT 1) AS close
    FROM orders o1
    GROUP BY YEAR(order_date), MONTH(order_date)
    ORDER BY order_date ASC
    LIMIT 6
";

$result = $conn->query($sql);

while($row = $result->fetch_assoc()){
    $candle_data[] = [
        "x" => $row['month'],   // Month label
        "y" => [
            (float)$row['open'],   // Open
            (float)$row['high'],   // High
            (float)$row['low'],    // Low
            (float)$row['close']   // Close
        ]
    ];
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<style>
body { background: #f9fafb; }
.sidebar { min-height: 150vh; background: #fff; border-right: 1px solid #eee; }
.sidebar a { display: block; padding: 12px 20px; color: #333; text-decoration: none; }
.sidebar a:hover, .sidebar a.active { background: #0d6efd; color: #fff; border-radius: 8px; }
.card { border-radius: 12px; }
.card h2 { font-size: 28px; font-weight: 600; }
.order-img { width: 50px; height: 50px; object-fit: cover; border-radius: 8px; margin-right: 10px; }
.status-badge { padding: 5px 10px; border-radius: 12px; color: #fff; font-size: 12px; }
.status-pending { background: #ff9800; }
.status-completed { background: #4caf50; }
.status-cancelled { background: #f44336; }
.sidebar a:hover, .sidebar a.active1 {
  background: rgb(149, 31, 51);
  color: #fff;
  border-radius: 8px;
}

</style>
</head>
<body>
<div class="d-flex">
  <!-- Sidebar -->
  <?php include 'admin_sidebar.php' ?>
<?php

include 'preloader.php';

?>
  <!-- Main -->
  <div class="flex-grow-1 p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h3>Dashboard</h3>
      <div>
        <i class="bi bi-bell fs-4 me-3"></i>
<?php if(!empty($_SESSION['admin_pic'])): ?>
  <img src="<?= $_SESSION['admin_pic'] ?>" alt="Admin" class="rounded-circle" width="40" height="40" style="object-fit:cover;">
<?php else: ?>
  <i class="bi bi-person-circle fs-4"></i>
<?php endif; ?>
      </div>
    </div>

    <!-- Stats Cards -->
   <!-- 4 Top Boxes -->
<div class="row g-3">
  <div class="col-md-3">
    <div class="card p-3 shadow-sm" style="background: linear-gradient(to right, #ffe6f0, #fff0e6);">
      <h6>Total Products</h6>
      <h2><?= $total_products ?></h2>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card p-3 shadow-sm"  style="background: linear-gradient(to right, #ffe6f0, #fff0e6);">
      <h6>Total Orders</h6>
      <h2><?= $total_orders ?></h2>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card p-3 shadow-sm"  style="background: linear-gradient(to right, #ffe6f0, #fff0e6);">
      <h6>Total Users</h6>
      <h2><?= $total_users ?></h2>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card p-3 shadow-sm"  style="background: linear-gradient(to right, #ffe6f0, #fff0e6);">
      <h6>Total Sales</h6>
      <h2>$<?= number_format($total_sales,2) ?></h2>
    </div>
  </div>
</div>

<!-- 4 Bottom Boxes -->
<div class="row g-3 mt-3">
  <div class="col-md-3">
    <div class="card p-3 shadow-sm text-info"  style="background: linear-gradient(to right, #ffe6f0, #fff0e6);">
      <h6>Subscribers</h6>
      <h2><?= $total_subscribe ?></h2>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card p-3 shadow-sm text-warning"  style="background: linear-gradient(to right, #ffe6f0, #fff0e6);">
      <h6>Pending Orders</h6>
      <h2><?= $total_pending ?></h2>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card p-3 shadow-sm text-success"  style="background: linear-gradient(to right, #ffe6f0, #fff0e6);">
      <h6>Completed Orders</h6>
      <h2><?= $total_completed ?></h2>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card p-3 shadow-sm text-danger"  style="background: linear-gradient(to right, #ffe6f0, #fff0e6);">
      <h6>Cancelled Orders</h6>
      <h2><?= $total_cancelled ?></h2>
    </div>
  </div>
</div>



    <!-- Recent Orders Section -->
    <!-- Recent Orders + Chart Side by Side -->
<div class="row g-3 mt-4">
  <!-- Recent Orders Table -->
  <div class="col-md-7">
    <div class="card p-3 shadow-sm h-100">
      <h6>Recent Orders</h6>
      <table class="table table-hover mt-3">
        <thead>
          <tr>
            <th>Product</th>
            <th>User</th>
            <th>Quantity</th>
            <th>Status</th>
            <th>Order Date</th>
          </tr>
        </thead>
        <tbody>
          <?php while($row = $recent_orders->fetch_assoc()): ?>
          <tr>
            <td class="d-flex align-items-center">
              <img src="images/<?= $row['image'] ?>" class="order-img" alt="">
              <?= $row['product_name'] ?>
            </td>
            <td><?= $row['user_name'] ?></td>
            <td><?= $row['quantity'] ?></td>
            <td>
              <?php
                $status_class = '';
                if(strtolower($row['status']) == 'pending') $status_class = 'status-pending';
                elseif(strtolower($row['status']) == 'completed') $status_class = 'status-completed';
                elseif(strtolower($row['status']) == 'cancelled') $status_class = 'status-cancelled';
              ?>
              <span class="status-badge <?= $status_class ?>"><?= ucfirst($row['status']) ?></span>
            </td>
            <td><?= date('d M Y', strtotime($row['order_date'])) ?></td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Orders Candlestick Chart -->
  <div class="col-md-5">
    <div class="card p-3 shadow-sm h-100">
      <h6 class="mb-3">📊 Orders Candlestick Chart</h6>
      <div id="ordersCandle"></div>
    </div>
  </div>
</div>

</div>
</body>
</html>
<!-- Include ApexCharts -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
var options = {
  chart: {
    type: 'candlestick',
    height: 350,
    toolbar: {
      show: true,
      tools: {
        download: true, // chart image export option
        zoom: true,
        pan: true
      }
    }
  },
  series: [{
    data: <?= json_encode($candle_data) ?>
  }],
  xaxis: {
    type: 'category',
    labels: { style: { fontWeight: 600 } }
  },
  yaxis: {
    tooltip: { enabled: true },
    labels: { style: { fontWeight: 600 } }
  },
  title: {
    text: 'Monthly Orders (Trading Style)',
    align: 'left',
    style: { fontSize: '16px', fontWeight: 'bold' }
  }
};

var chart = new ApexCharts(document.querySelector("#ordersCandle"), options);
chart.render();
</script>
