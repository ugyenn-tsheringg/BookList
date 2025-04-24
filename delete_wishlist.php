<?php
session_start();
include 'db_connection.php'; // Include your database connection file

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit;
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the ID of the item to delete
    $itemId = $_POST['id'];

    // Prepare SQL query to delete the item from the wishlist
    $sql = "DELETE FROM wishlist WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $itemId, $_SESSION['user_id']);

    // Execute the statement
    if ($stmt->execute()) {
        header("Location: wishlist.php?msg=Item deleted successfully.");
        exit();
    } else {
        echo "Error deleting item: " . $stmt->error;
    }

    // Close statement and connection
    $stmt->close();
}
$conn->close();
?>
