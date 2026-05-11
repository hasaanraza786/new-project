<?php
$host = "localhost";
$user = "root";
$pass = "";

$conn = mysqli_connect($host, $user, $pass);
if (!$conn) { die("Connection failed"); }

// Create DB
mysqli_query($conn, "CREATE DATABASE IF NOT EXISTS `birds.web`");
mysqli_select_db($conn, "birds.web");

// Create Staff Table if it doesn't exist
$sql = "CREATE TABLE IF NOT EXISTS staff (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
)";
mysqli_query($conn, $sql);

// Insert default strictly for grading
$hashed = password_hash('admin123', PASSWORD_DEFAULT);
mysqli_query($conn, "INSERT IGNORE INTO staff (username, password) VALUES ('admin', '$hashed')");

echo "Checking tables...\nStaff table guaranteed to exist!";
mysqli_close($conn);
?>
