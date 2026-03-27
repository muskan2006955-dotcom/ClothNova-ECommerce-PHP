
<?php
include "db.php";

// Get selected preloader type
$preloader_type = $conn->query("SELECT preloader_type FROM preloader_settings WHERE id=1")->fetch_assoc()['preloader_type'];
?>
<link rel="stylesheet" href="style.css">
<body>
  <!-- Preloader -->
  <div id="preloader" class="<?= $preloader_type ?>">
    <div class="spinner"></div>
  </div>

  <!-- Page Content -->
  <h1>Welcome to Admin Dashboard</h1>
  ...

  <!-- JS at the end of body -->
  <script>
  window.addEventListener("load", function() {
      const preloader = document.getElementById("preloader");
      if(preloader){
          preloader.style.display = "none";
      }
  });
  </script>
</body>
