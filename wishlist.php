<?php
session_start();
include 'db_connection.php'; // Include your database connection file

if (!isset($_SESSION['user_id'])) {
    // Redirect to login page or show an error
    header("Location: login.php");
    exit;
}

// Fetch the user's current profile photo
$userId = $_SESSION['user_id'] ?? 0; // Default to 0 if user_id is not set
$sql = "SELECT profile_photo FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Fetch the user's wishlist with item IDs
$sqlWishlist = "SELECT id, book_title, author_name, review, book_image, created_at 
FROM wishlist WHERE user_id = ? ORDER BY created_at DESC";
$stmtWishlist = $conn->prepare($sqlWishlist);
$stmtWishlist->bind_param("i", $userId);
$stmtWishlist->execute();
$resultWishlist = $stmtWishlist->get_result();

// Close connection
$stmtWishlist->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Wishlist</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="dashboard-container">
        <aside class="dashboard-sidebar">
            <div class="dashboard-profile">
                <img src="uploads/<?php echo $user['profile_photo'] ? htmlspecialchars($user['profile_photo']) : 'default.jpg'; ?>" alt="Profile Picture" class="dashboard-profile-img">
                <h2><?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Guest'); ?></h2>
            </div>

            <nav class="dashboard-nav-menu">
                <ul>
                    <li><a href="dashboard.php">Dashboard</a></li>
                    <li><a href="collection.php">Collection</a></li>
                    <li><a class="dashboard-active" href="wishlist.php">Wishlist</a></li>
                    <li><a href="addnew.php">Add New</a></li>
                </ul>
                <form class="logout-btn" action="logout.php" method="post">
                    <button type="submit">Logout</button>
                </form>
            </nav>
        </aside>

        <main class="dashboard-content">
            <section class="dashboard-summary">
                <h1>Your Wishlist</h1>
                <p>Here are the books you wish to read!</p>

                <?php if ($resultWishlist->num_rows > 0): ?>
                    <?php while ($row = $resultWishlist->fetch_assoc()): ?>
                        <div class="collection-card">
                            <div class="collection-card-content">
                                <div class="collection-card-image">
                                    <img src="uploads/<?php echo htmlspecialchars($row['book_image']); ?>" alt="Card Image" />
                                </div>
                                <div class="collection-card-body">
                                    <h5 class="collection-card-title">Book Title: <?php echo htmlspecialchars($row['book_title']); ?></h5>
                                    <p><span class="collection-bold">Author:</span> <?php echo htmlspecialchars($row['author_name']); ?></p>
                                    <p><span class="collection-bold">Comments:</span> <?php echo htmlspecialchars($row['review']); ?></p>
                                    <p class="collection-card-text">
                                        <small class="text-muted">Added on <?php echo date('F j, Y, g:i a', strtotime($row['created_at'])); ?></small>
                                    </p>
                                    <!-- Delete Button Form -->
                                    <form action="delete_wishlist.php" method="post">
                                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id']); ?>">
                                        <button type="submit" class="delete-btn" onclick="return confirm('Are you sure you want to delete this item?');">Delete</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p class="not-found">No items in your wishlist.</p>
                <?php endif; ?>
            </section>
        </main>
    </div>
    <script src="scripts.js"></script>
</body>
</html>
