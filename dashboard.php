<?php
session_start();
include 'db_connection.php'; // Include your database connection file

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: signin.html");
    exit;
}

// Fetch the user's current profile photo
$userId = $_SESSION['user_id'];
$sql = "SELECT profile_photo FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Handle profile photo upload
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['profile_photo'])) {
    // Validate file upload
    if ($_FILES['profile_photo']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['profile_photo']['tmp_name'];
        $fileName = $_FILES['profile_photo']['name'];
        $fileSize = $_FILES['profile_photo']['size'];
        $fileType = $_FILES['profile_photo']['type'];

        // Specify the upload directory
        $uploadFileDir = 'uploads/'; // Make sure this directory exists
        $dest_path = $uploadFileDir . basename($fileName);

        // Check if the uploaded file is an image
        $allowedFileTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($fileType, $allowedFileTypes)) {
            echo '<p style="color: red;">Only JPG, PNG, and GIF files are allowed.</p>';
        } else {
            // Move the uploaded file to the specified directory
            if (move_uploaded_file($fileTmpPath, $dest_path)) {
                // Save the file name in the database
                $sql = "UPDATE users SET profile_photo = ? WHERE user_id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("si", $fileName, $userId);

                if ($stmt->execute()) {
                    echo '<p style="color: green;">Profile photo uploaded successfully!</p>';
                    // Optionally, refresh the page to show the new photo
                    header("Location: dashboard.php");
                    exit;
                } else {
                    echo '<p style="color: red;">Error updating database: ' . $stmt->error . '</p>';
                }
            } else {
                echo '<p style="color: red;">There was an error moving the uploaded file.</p>';
            }
        }
    } else {
        echo '<p style="color: red;">File upload error!</p>';
    }
}
// Fetch the total number of books read by the logged-in user
$query = "SELECT COUNT(*) as total_books FROM collection WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$totalBooks = $row['total_books'];

$query = "SELECT COUNT(*) as total_wishlist FROM wishlist WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$totalBooksWishlist = $row['total_wishlist'];
// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="dashboard-container">
        <aside class="dashboard-sidebar">
            <div class="dashboard-profile isolated-form">
                <img src="uploads/<?php echo $user['profile_photo'] ? $user['profile_photo'] : 'default.jpg'; ?>" alt="Profile Picture" class="dashboard-profile-img">
                <h2><?php echo $_SESSION['user_name']; ?></h2>
                
                <!-- Add the upload form here -->
                <form action="" method="post" enctype="multipart/form-data">
                    <input type="file" name="profile_photo" accept="image/*" required>
                    <button type="submit">Change Profile Photo</button>
                </form>
            </div>

            <nav class="dashboard-nav-menu">
                <ul>
                    <li><a class="dashboard-active" href="dashboard.php">Dashboard</a></li>
                    <li><a href="collection.php">Collection</a></li>
                    <li><a href="wishlist.php">Wishlist</a></li>
                    <li><a href="addnew.php">Add New</a></li>
                </ul>
                <form class="logout-btn" action="logout.php" method="post">
                    <button type="submit">Logout</button>
                </form>
            </nav>
        </aside>

        <main class="dashboard-content">
            <section class="dashboard-summary">
                <h1>Quick Summary</h1>
                <p>Here are some summaries of your collection:</p>
                <div class="dashboard-summary-container">
                    <div class="dashboard-summary-card">
                        <h2>Total Number of Books in your Collection:</h2>
                        <p><?php echo $totalBooks; ?></p> <!-- Display the total number of books here -->
                    </div>
                    <div class="dashboard-summary-card">
                        <h2>Total Number of Books in your Wishlist:</h2>
                        <p><?php echo $totalBooksWishlist; ?></p>
                    </div>
                </div>
            </section>
        </main>
    </div>
    <script src="scripts.js"></script>
</body>
</html>

