<?php
session_start(); // Start the session

// Check if the member_email is passed via POST
if (isset($_POST['member_email'])) {
    $_SESSION['member_email'] = $_POST['member_email']; // Store the member email in the session
    echo json_encode(["success" => true]); // Send success response back to JavaScript
} else {
    echo json_encode(["error" => "No member email received"]);
}
?>