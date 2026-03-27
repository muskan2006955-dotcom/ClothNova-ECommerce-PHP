<?php
session_start();
include 'db.php';

// agar user login nahi hai
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit;
}

$user_id = (int)$_SESSION['user_id'];

// POST data
$name = isset($_POST['name']) ? trim($_POST['name']) : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';
$is_subscribed = isset($_POST['is_subscribed']) ? 1 : 0;

// handle profile picture
$profile_pic = '';
if(isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] == 0){
    $allowed = ['jpg','jpeg','png','gif'];
    $file_name = $_FILES['profile_pic']['name'];
    $file_tmp  = $_FILES['profile_pic']['tmp_name'];
    $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

    if(in_array($ext, $allowed)){
        $new_name = 'profile_'.$user_id.'_'.time().'.'.$ext;
        $upload_dir = 'uploads/';
        if(!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);

        // purani image delete karna (agar koi ho)
        $sql_old = "SELECT profile_pic FROM users WHERE user_id=$user_id";
        $res_old = $conn->query($sql_old);
        if($res_old && $res_old->num_rows){
            $old_file = $res_old->fetch_assoc()['profile_pic'];
            if($old_file && file_exists($upload_dir.$old_file)){
                unlink($upload_dir.$old_file);
            }
        }

        // nayi image move karna
        if(move_uploaded_file($file_tmp, $upload_dir.$new_name)){
            $profile_pic = $new_name;
        }
    }
}

// build SQL updates
$updates = [];
if($name) $updates[] = "name='". $conn->real_escape_string($name) ."'";
if($password) $updates[] = "password='". password_hash($password, PASSWORD_DEFAULT) ."'";
$updates[] = "is_subscribed=$is_subscribed";
if($profile_pic) $updates[] = "profile_pic='". $conn->real_escape_string($profile_pic) ."'";

if(!empty($updates)){
    $sql = "UPDATE users SET ". implode(',', $updates) ." WHERE user_id=$user_id";
    $conn->query($sql);
}

// redirect back to profile page with success flag
header("Location: profile.php?success=1");
exit;
?>
