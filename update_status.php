<?php
session_start();
include "db.php";

// Sirf admin allow
if(!isset($_SESSION['admin_id'])){
    die("Access denied. Please login as admin.");
}

// Check order_id in GET
if(!isset($_GET['order_id']) || empty($_GET['order_id'])){
    die("Invalid order.");
}

$order_id = (int)$_GET['order_id'];

// Handle form submission
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $status = $_POST['status'] ?? 'Pending';
    $message = $_POST['message'] ?? '';

    $stmt = $conn->prepare("UPDATE orders SET status=?, messages=? WHERE order_id=?");
    $stmt->bind_param("ssi", $status, $message, $order_id);
    if($stmt->execute()){
        $_SESSION['success'] = "Order updated successfully!";
        header("Location: view_order.php"); // back to orders list
        exit;
    } else {
        $error = "Failed to update order. Try again.";
    }
}

// Fetch order details
$stmt = $conn->prepare("SELECT o.*, u.name AS user_name, u.email FROM orders o JOIN users u ON o.user_id=u.user_id WHERE o.order_id=?");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();
if(!$order){
    die("Order not found.");
}

// Status options
$status_options = ['Pending', 'Processing', 'Shipped', 'Delivered', 'Cancelled'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Update Order Status</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { margin:0; font-family: Arial, sans-serif; }
    .sidebar { width:200px; height:100vh; background:#fff; border-right:1px solid #eee; position:fixed; top:0; left:0; }
    .sidebar a { display:block; padding:12px 20px; color:#333; text-decoration:none; }
    .sidebar a:hover, .sidebar a.active { background:rgb(149,31,51); color:#fff; border-radius:8px; }
    .main-content { margin-left:200px; padding:20px; }
    .card { border-radius:12px; }
        #message{
      margin-top: 20px;
      height: 200px;
      width: 900px;
    }
     .ck-editor__editable {
    min-height: 200px;  /* yahan apni desired height do */
  }
  </style>
</head>
<body>
<div class="d-flex">
  <?php include "admin_sidebar.php"; ?>
  <div class="main-content flex-grow-1">
    <div class="card p-4 shadow-sm">
      <h3>Update Order: <?= htmlspecialchars($order['order_code']) ?></h3>
      <p><strong>User:</strong> <?= htmlspecialchars($order['user_name']) ?> (<?= htmlspecialchars($order['email']) ?>)</p>
      <?php if(isset($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
      <?php endif; ?>
      <form method="post">
        <div class="mb-3">
          <label for="status" class="form-label">Status</label>
          <select name="status" id="status" class="form-select" required>
            <?php foreach($status_options as $opt): ?>
              <option value="<?= $opt ?>" <?= $order['status']==$opt?'selected':'' ?>><?= $opt ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="mb-3">
          <label for="message">Message</label>
<textarea name="message" id="message" placeholder="give message about courier status"></textarea>
        </div>
        <button type="submit" class="btn btn-success">Update Order</button>
        <a href="view_order.php" class="btn btn-secondary">Cancel</a>
      </form>
    </div>
  </div>
</div>
</body>
</html>
<script src="https://cdn.ckeditor.com/ckeditor5/41.0.0/classic/ckeditor.js"></script>


<script>
  ClassicEditor
    .create(document.querySelector('#message'))
    .catch(error => {
        console.error(error);
    });
</script>

