<?php
session_start(); // Start the session
session_unset(); // Unset all session variables
session_destroy(); // Destroy the session

// Redirect to the sign-in page
header("Location: signin.html");
exit;
?>
