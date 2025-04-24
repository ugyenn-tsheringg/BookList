<?php
session_start();
include 'db_connection.php'; // Include your database connection file

// Fetch the user's current profile photo
$userId = $_SESSION['user_id'] ?? 0; // Default to 0 if user_id is not set
$sql = "SELECT profile_photo FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="dashboard-container">
        <aside class="dashboard-sidebar">
            <div class="dashboard-profile">
                <img src="uploads/<?php echo $user['profile_photo'] ? $user['profile_photo'] : 'default.jpg'; ?>" alt="Profile Picture" class="dashboard-profile-img">
                <h2><?php echo $_SESSION['user_name'] ?? 'Guest'; ?></h2>
            </div>

            <nav class="dashboard-nav-menu">
                <ul>
                    <li><a href="dashboard.php">Dashboard</a></li>
                    <li><a href="collection.php">Collection</a></li>
                    <li><a href="wishlist.php">Wishlist</a></li>
                    <li><a class="dashboard-active" href="addnew.php">Add New</a></li>
                </ul>
                <form class="logout-btn" action="logout.php" method="post">
                    <button type="submit">Logout</button>
                </form>
            </nav>
        </aside>

        <main class="dashboard-content">
            <section class="dashboard-summary">
                <h1>What book are you reading?</h1>
                <div class="review-container">
                    <div class="review-form-wrapper">
                        <h3>Add to Collection</h3>
                        <form id="review-form-1" action="submit_collection.php" method="post" enctype="multipart/form-data">
                            <div class="review-group">
                                <div class="review-field review-flex">
                                    <label for="book-title-1">Book Title:</label>
                                    <input type="text" id="book-title-1" name="book-title" required>
                                </div>
                                <div class="review-field review-flex">
                                    <label for="author-name-1">Author Name:</label>
                                    <input type="text" id="author-name-1" name="author-name" required>
                                </div>
                            </div>

                            <div class="review-group">
                                <div class="review-field">
                                    <label for="date-started-1">Date Started Reading:</label>
                                    <input type="date" id="date-started-1" name="date-started" required>
                                </div>
                                <div class="review-field">
                                    <label for="date-completed-1">Date Completed Reading:</label>
                                    <input type="date" id="date-completed-1" name="date-completed" required>
                                </div>
                            </div>

                            <div class="review-group">
                                <label for="book-image-1">Upload Book Image:</label>
                                <input type="file" id="book-image-1" name="book-image" accept="image/*" required>
                            </div>

                            <div class="review-group">
                                <label for="review-1">Book Review:</label>
                                <textarea id="review-1" name="review" rows="5" required></textarea>
                            </div>

                            <div class="review-group">
                                <label for="rating-1">Rating:</label>
                                <select id="rating-1" name="rating" required>
                                    <option value="" disabled selected>Select a rating</option>
                                    <option value="1">1 - Poor</option>
                                    <option value="2">2 - Fair</option>
                                    <option value="3">3 - Good</option>
                                    <option value="4">4 - Very Good</option>
                                    <option value="5">5 - Excellent</option>
                                </select>
                            </div>

                            <button type="submit" class="review-submit-btn">Add to Collection</button>
                        </form>
                    </div>

                    <div class="review-form-wrapper">
                        <h3>Add to Wishlist</h3>
                        <form id="wishlist-form" action="submit_wishlist.php" method="post" enctype="multipart/form-data">
                            <div class="review-group">
                                <div class="review-field wishlist-flex">
                                    <label for="book-title-2">Book Title:</label>
                                    <input type="text" id="book-title-2" name="book-title" required>
                                </div>
                                <div class="review-field wishlist-flex">
                                    <label for="author-name-2">Author Name:</label>
                                    <input type="text" id="author-name-2" name="author-name" required>
                                </div>
                            </div>

                            <div class="review-group">
                                <label for="book-image-2">Upload Book Image:</label>
                                <input type="file" id="book-image-2" name="book-image" accept="image/*" required>
                            </div>

                            <div class="review-group">
                                <label for="review-2">Where can I find it?:</label>
                                <textarea id="review-2" name="review" rows="5"></textarea>
                            </div>

                            <button type="submit" class="review-submit-btn">Add to Wishlist</button>
                        </form>
                    </div>
                </div>
            </section>
        </main>
    </div>
    <script src="scripts.js"></script>
</body>
</html>
