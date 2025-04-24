<?php
// Start session
session_start(); // Always start the session at the beginning

// Database connection parameters
include 'db_connection.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "You need to log in to add a collection.";
    exit;
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $bookTitle = $_POST['book-title'];
    $authorName = $_POST['author-name'];
    $dateStarted = $_POST['date-started'];
    $dateCompleted = $_POST['date-completed'];
    $review = $_POST['review'];
    $rating = $_POST['rating'];
    $userId = $_SESSION['user_id']; // Get the logged-in user's ID

    // Handle the file upload
    if (isset($_FILES['book-image']) && $_FILES['book-image']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['book-image']['tmp_name'];
        $fileName = $_FILES['book-image']['name'];
        $fileSize = $_FILES['book-image']['size'];
        $fileType = $_FILES['book-image']['type'];

        // Specify the upload directory
        $uploadFileDir = 'uploads/'; // Ensure this directory exists
        $destPath = $uploadFileDir . basename($fileName);

        // Check if the uploaded file is an image
        $allowedFileTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (in_array($fileType, $allowedFileTypes)) {
            // Move the uploaded file to the server
            if (move_uploaded_file($fileTmpPath, $destPath)) {
                // Prepare SQL query to insert data into the collection table
                $sql = "INSERT INTO collection (user_id, book_title, author_name, date_started, date_completed, review, rating, book_image)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

                // Prepare and bind the query
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("isssssss", $userId, $bookTitle, $authorName, $dateStarted, $dateCompleted, $review, $rating, $fileName);

                // Execute the statement
                if ($stmt->execute()) {
                    // Show success message and redirect
                    echo '<script>
                            alert("New collection added successfully!");
                            window.location.href = "addnew.php"; // Redirect to addnew.php
                          </script>';
                } else {
                    echo "Error: " . $stmt->error;
                }
            } else {
                echo "Error moving the uploaded file.";
            }
        } else {
            echo "Invalid file type. Only JPG, PNG, and GIF formats are allowed.";
        }
    } else {
        echo "File upload error!";
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
}
?>
