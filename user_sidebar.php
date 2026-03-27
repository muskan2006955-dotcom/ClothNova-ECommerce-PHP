    <?php

include 'db.php';

if(!isset($_SESSION['user_id'])){
  header("Location: login.php");
  exit;
}

$uid = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE user_id = $uid";
$res = $conn->query($sql);
$user = $res->fetch_assoc();

?>

    <div class="col-md-3 sidebar">
      <div class="text-center">
<img src="uploads/<?= $user['profile_pic'] ?: 'default.png' ?>" class="profile-pic" alt="Profile">
        <h5><?= htmlspecialchars($user['name']) ?></h5>
        <p><?= htmlspecialchars($user['email']) ?></p>
      </div>
      <hr>
      <a href="dashboard.php"> Dashboard</a>
      <a href="profile.php"> Profile</a>
      <a href="my_orders.php"> My Orders</a>
      <a href="track.php">Track order</a>
      <a href="logout.php"> Logout</a>
    </div>