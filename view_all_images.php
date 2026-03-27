<?php
include "db.php";
session_start();

// Sirf admin allow
if(!isset($_SESSION['admin_id'])){
    die("Access denied.");
}

// Products + unki images nikalne ka query
$sql = "SELECT p.product_id, p.name, p.image as main_image, pi.image as extra_image
        FROM products p
        LEFT JOIN product_images pi ON p.product_id = pi.product_id
        ORDER BY p.product_id DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>All Product Images</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body { margin:0; font-family: Arial, sans-serif; }
    .sidebar { width:200px; height:100vh; background:#fff; border-right:1px solid #eee; position:fixed; top:0; left:0; }
    .sidebar a { display:block; padding:12px 20px; color:#333; text-decoration:none; }
    .sidebar a:hover, .sidebar a.active { background:#0d6efd; color:#fff; border-radius:8px; }
    .main-content { margin-left:200px; padding:20px; }
    .card { border-radius:12px; }
    .img-thumb { width:60px; margin-right:5px; margin-bottom:5px; border-radius:4px; border:1px solid #ccc; }
    .main-img { width:80px; border-radius:6px; border:1px solid #ccc; }
    .sidebar a:hover, .sidebar a.active4 {
  background: rgb(149, 31, 51);
  color: #fff;
  border-radius: 8px;
}
.table th{
background: rgb(149, 31, 51);
color: white;
}
  </style>
</head>
<body>
<div class="d-flex">

  <!-- Sidebar -->

<?php 
include 'admin_sidebar.php'
?>
  <!-- Main Content -->
  <div class="main-content flex-grow-1">
            <div class="d-flex justify-content-between align-items-center mb-4">
      <h3>All Product Images</h3>
      <div>
        <i class="bi bi-bell fs-4 me-3"></i>
<?php if(!empty($_SESSION['admin_pic'])): ?>
  <img src="<?= $_SESSION['admin_pic'] ?>" alt="Admin" class="rounded-circle" width="40" height="40" style="object-fit:cover;">
<?php else: ?>
  <i class="bi bi-person-circle fs-4"></i>
<?php endif; ?>
      </div>
    </div>


    <div class="card p-4 shadow-sm">
      <div class="table-responsive">
        <table class="table table-bordered table-hover mb-0 align-middle">
          <thead class="table">
            <tr>
              <th>Product ID</th>
              <th>Product Name</th>
              <th>Main Image</th>
              <th>Extra Images</th>
            </tr>
          </thead>
          <tbody>
            <?php 
            $current_id = null;
            $extra_imgs = [];

            while($row = $result->fetch_assoc()):
              if($current_id !== $row['product_id']){

                  if($current_id !== null){
                      echo "<td>";
                      if(count($extra_imgs) > 0){
                          foreach($extra_imgs as $img){
                              echo "<img src='images/$img' class='img-thumb'>";
                          }
                      } else {
                          echo "<span class='text-muted'>No extra images</span>";
                      }
                      echo "</td></tr>";
                      $extra_imgs = [];
                  }

                  echo "<tr>
                          <td>".$row['product_id']."</td>
                          <td>".$row['name']."</td>
                          <td><img src='images/".$row['main_image']."' class='main-img'></td>";
                          
                  $current_id = $row['product_id'];
              }

              if($row['extra_image']){
                  $extra_imgs[] = $row['extra_image'];
              }

            endwhile;

            // last product close
            if($current_id !== null){
                echo "<td>";
                if(count($extra_imgs) > 0){
                    foreach($extra_imgs as $img){
                        echo "<img src='images/$img' class='img-thumb'>";
                    }
                } else {
                    echo "<span class='text-muted'>No extra images</span>";
                }
                echo "</td></tr>";
            }
            
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
</body>
</html>
