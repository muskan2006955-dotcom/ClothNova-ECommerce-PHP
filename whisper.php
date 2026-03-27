<?php
session_start();
include 'db.php';

if(!isset($_SESSION['user_id'])){
    die("Please login to send a whisper.");
}

$user_id = (int)$_SESSION['user_id'];
$product_id = isset($_GET['product_id']) ? (int)$_GET['product_id'] : 0;

// Form submit
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $msg = trim($_POST['message']);
    if($msg){
        $msg = mysqli_real_escape_string($conn, $msg);
        $sql = "INSERT INTO whispers (product_id, user_id, message) 
                VALUES ($product_id, $user_id, '$msg')";
        $conn->query($sql);
        echo "<script>alert('Whisper sent!');</script>";
    }
}

// Get product info
$product = null;
if($product_id){
    $res = $conn->query("SELECT name, image FROM products WHERE product_id=$product_id LIMIT 1");
    $product = $res->fetch_assoc();
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Whisper</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-5">

<?php if($product): ?>
  <h3>Whisper about: <?php echo htmlspecialchars($product['name']); ?></h3>
  <img src="images/<?php echo $product['image']; ?>" width="150"><br><br>
<?php endif; ?>

<form method="POST">
  <textarea name="message" class="form-control" rows="4" placeholder="Type your whisper..."></textarea>
  <button class="btn btn-dark mt-2">Send Whisper</button>
</form>

<hr>
<h5>Previous Whispers</h5>
<?php
$q = $conn->query("SELECT w.message, w.created_at, u.name 
                   FROM whispers w 
                   JOIN users u ON w.user_id=u.user_id
                   WHERE w.product_id=$product_id ORDER BY w.created_at DESC");
while($w = $q->fetch_assoc()){
    echo "<p><b>{$w['name']}:</b> {$w['message']}<br><small>{$w['created_at']}</small></p>";
}
?>
</body>
</html>
