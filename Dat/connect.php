<?php
// connect.php

// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "congtyvienthong";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
$conn->set_charset("utf8mb4");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
