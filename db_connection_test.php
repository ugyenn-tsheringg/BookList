<?php
// db_connection_test.php

// Include database connection file
include 'db_connection.php';

// Test the connection
if ($conn) {
    echo "Database connection successful!";
} else {
    echo "Database connection failed!";
}

// Close connection
$conn->close();
?>
