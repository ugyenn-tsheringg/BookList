<?php
// Include database connection file
include 'db_connection.php'; // Ensure this file contains your database connection logic

// Handling form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get and sanitize form data
    $email = htmlspecialchars(trim($_POST['email']));
    $password = htmlspecialchars(trim($_POST['password']));

    // Prepare the SQL select query
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);

    // Check if the prepare statement failed
    if ($stmt === false) {
        echo "Error preparing the SQL statement: " . $conn->error;
        exit;
    }

    // Bind the parameters
    $stmt->bind_param("s", $email);

    // Execute the query
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if user exists
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        // Verify the password
        if (password_verify($password, $user['password'])) {
            // Successful login
            session_start(); // Start a session
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['user_name'] = $user['name'];
            // Redirect to a welcome page or dashboard
            header("Location: dashboard.php");
            exit;
        } else {
            echo '<p style="color: red;">Invalid email or password!</p>';
        }
    } else {
        echo '<p style="color: red;">Invalid email or password!</p>';
    }

    // Close the statement
    $stmt->close();
}

// Close connection
$conn->close();
?>
