<?php
session_start();
include 'db_connection.php'; // Include your database connection file

// Function to handle the file upload
function uploadFile($file, $uploadDir, $allowedTypes) {
    $fileName = basename($file["name"]);
    $targetFilePath = $uploadDir . $fileName;
    $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
    
    // Check if the file type is allowed
    if (!in_array($fileType, $allowedTypes)) {
        return ['success' => false, 'message' => 'File type not allowed.'];
    }

    // Attempt to move the uploaded file
    if (move_uploaded_file($file["tmp_name"], $targetFilePath)) {
        return ['success' => true, 'fileName' => $fileName];
    } else {
        return ['success' => false, 'message' => 'Error moving the uploaded file.'];
    }
}

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: signin.html");
    exit;
}

// Handle profile photo upload
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES['profile_photo'])) {
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    $uploadResult = uploadFile($_FILES['profile_photo'], 'uploads/', $allowedTypes);

    if ($uploadResult['success']) {
        // Update the user's profile photo in the database
        $userId = $_SESSION['user_id'];

        // Prepare SQL statement
        $sql = "UPDATE users SET profile_photo = ? WHERE id = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("si", $uploadResult['fileName'], $userId);
            if ($stmt->execute()) {
                // Redirect to dashboard upon successful update
                header("Location: dashboard.php");
                exit;
            } else {
                echo '<p style="color: red;">Error updating database: ' . htmlspecialchars($stmt->error) . '</p>';
            }
        } else {
            echo '<p style="color: red;">Error preparing statement: ' . htmlspecialchars($conn->error) . '</p>';
        }
    } else {
        echo '<p style="color: red;">' . htmlspecialchars($uploadResult['message']) . '</p>';
    }
}

// Close the database connection
$conn->close();
?>
