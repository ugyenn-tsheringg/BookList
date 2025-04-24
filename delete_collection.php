<?php
session_start();
include 'db_connection.php'; // Include your database connection file

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "You need to log in to delete a collection.";
    exit;
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $collectionId = $_POST['collection_id'];
    $userId = $_SESSION['user_id']; // Get the logged-in user's ID

    // Prepare SQL query to delete data from the collection table
    $sql = "DELETE FROM collection WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $collectionId, $userId);

    // Execute the statement
    if ($stmt->execute()) {
        echo "Collection item deleted successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();

    // Redirect back to the collection page
    header("Location: collection.php");
    exit;
}
?>
