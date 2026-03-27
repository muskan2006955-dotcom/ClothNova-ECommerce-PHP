<?php
$servername = "localhost";
$username = "root";   // apna MySQL username
$password = "";       // apna MySQL password
$database = "cloth_nova_db";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
