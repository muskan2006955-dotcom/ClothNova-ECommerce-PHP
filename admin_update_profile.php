<?php
session_start();
include "db.php";

if(!isset($_SESSION['admin_id'])){
    header("Location: admin_login.php");
    exit();
}

$admin_id = $_SESSION['admin_id'];

if($_SERVER['REQUEST_METHOD'] == "POST"){
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $profile_pic = "";

    // Agar new image upload hui hai
    if(isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] == 0){
        $ext = pathinfo($_FILES['profile_pic']['name'], PATHINFO_EXTENSION);
        $filename = "uploads/admin_" . $admin_id . "." . $ext;
        move_uploaded_file($_FILES['profile_pic']['tmp_name'], $filename);
        $profile_pic = $filename;
    }

    if($profile_pic){
        $sql = "UPDATE admins SET name='$name', profile_pic='$profile_pic' WHERE admin_id=$admin_id";
        $_SESSION['admin_pic'] = $profile_pic;
    } else {
        $sql = "UPDATE admins SET name='$name' WHERE admin_id=$admin_id";
    }

    if($conn->query($sql)){
        $_SESSION['admin_name'] = $name;
        header("Location: admin_profile.php?success=1");
        exit();
    } else {
        echo "Error updating profile!";
    }
}
?>
