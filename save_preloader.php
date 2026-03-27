<?php
include "db.php";

if(isset($_POST['preloader_type'])){
    $type = $_POST['preloader_type'];
    $conn->query("UPDATE preloader_settings SET preloader_type='$type' WHERE id=1");
    echo "success";
}
?>
