<?php
// Include database connection file
include 'db_connection.php'; // Ensure this file contains your database connection logic

// Handling form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get and sanitize form data
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $password = htmlspecialchars(trim($_POST['password']));
    $confirm_password = htmlspecialchars(trim($_POST['confirm_password']));

    // Check if all required fields are filled
    if (!empty($name) && !empty($email) && !empty($password) && !empty($confirm_password)) {
        
        // Check if passwords match
        if ($password !== $confirm_password) {
            echo '<p style="color: red;">Passwords do not match!</p>';
        } else {
            // Hash the password for security
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Prepare the SQL insert query
            $sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);

            // Check if the prepare statement failed
            if ($stmt === false) {
                echo "Error preparing the SQL statement: " . $conn->error;
                exit;
            }

            // Bind the parameters
            $stmt->bind_param("sss", $name, $email, $hashed_password);

            // Execute the query
            if ($stmt->execute()) {
                // Registration successful
                echo '<script>
                        alert("Registered successfully!");
                        window.location.href = "SIGNIN.html"; // Redirect to login page
                      </script>';
            } else {
                // Check for unique email constraint violation (error code 1062)
                if ($conn->errno == 1062) {
                    echo '<p style="color: red;">Email already exists!</p>';
                } else {
                    echo "Error adding user: " . $stmt->error;
                }
            }

            // Close the statement
            $stmt->close();
        }
    } else {
        echo '<p style="color: red;">Please fill all required fields.</p>';
    }
}

// Close connection
$conn->close();
?>
