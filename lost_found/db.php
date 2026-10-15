<?php
session_start();
$host = "localhost";
$user = "root";
$password = "";
$database = "lost_found_db";

$conn = new mysqli($host, $user, $password, $database);
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");
?>