<?php
// db_connection.php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "booklist";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// Optional: you can log a message or set a flag to indicate success
// echo "Database connection successful!";
?>
