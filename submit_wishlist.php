<?php
// Database connection parameters
include 'db_connection.php';

// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit;
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $bookTitle = $_POST['book-title'];
    $authorName = $_POST['author-name'];
    $review = $_POST['review'] ?? null; // Allow review to be null
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
                // Prepare SQL query to insert data into the wishlist table
                $sql = "INSERT INTO wishlist (book_title, author_name, review, book_image, user_id)
                        VALUES (?, ?, ?, ?, ?)";

                // Prepare and bind the query
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssssi", $bookTitle, $authorName, $review, $fileName, $userId);

                // Execute the statement
                if ($stmt->execute()) {
                    echo '<script>
                            alert("New item added to your wishlist successfully!");
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
