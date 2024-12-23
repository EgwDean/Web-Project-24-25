<?php
session_start(); //Αναζήτηση συνόδου

if (!isset($_SESSION['email'])) { // Ανακατεύθυνση αν ο χρήστης δεν είναι logged in
    header("Location: logout.php");
    exit();
}

if ($_SESSION['type'] != 'STUDENT') {
    header("Location: logout.php");
    exit();
}

if (isset($_SESSION['status'])) {
    if ($_SESSION['status'] != "under examination") {
        header("Location: logout.php");
    }
}

$host = "localhost";
$dbusername = "root";
$dbpassword = "Matsaniarakos9";
$dbname = "diplomatiki_support";

$conn = new mysqli($host, $dbusername, $dbpassword, $dbname); // Σύνδεση με τη βάση

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$email = $_SESSION['email'];
$dateField = $_POST['dateField'];
$roomField = $_POST['roomField']; 

// Update/Insert Stored Procedure προς τη βάση
$sql = "CALL updateExam(?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $email, $dateField, $roomField);
$stmt->execute();
$stmt->close();

// Κλείσιμο σύνδεσης
$conn->close();
?>