<?php

session_start(); // Start the session

// Check if the user is logged in by verifying the session variable
if (empty($_SESSION['email'])) {
    // Redirect to the login page if the user is not logged in
    header("Location: login.php");
    exit();
}
else echo "You are a lucky mfucker";

?>
