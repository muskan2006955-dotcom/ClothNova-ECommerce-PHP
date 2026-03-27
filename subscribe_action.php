<?php
include 'db.php';
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "message" => "Please login first."]);
    exit;
}

$userId = $_SESSION['user_id'];

if (isset($_GET['action'])) {
    if ($_GET['action'] == 'subscribe') {
        mysqli_query($conn, "UPDATE users SET is_subscribed = 1 WHERE user_id = '$userId'");
        echo json_encode(["success" => true, "message" => "You have successfully subscribed!"]);
        exit;
    } elseif ($_GET['action'] == 'unsubscribe') {
        mysqli_query($conn, "UPDATE users SET is_subscribed = 0 WHERE user_id = '$userId'");
        echo json_encode(["success" => true, "message" => "You have unsubscribed."]);
        exit;
    }
}

echo json_encode(["success" => false, "message" => "Invalid action."]);
exit;
