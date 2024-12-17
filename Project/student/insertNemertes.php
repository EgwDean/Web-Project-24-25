<?php
session_start(); //Αναζήτηση συνόδου

if (!isset($_SESSION['email'])) { // Ανακατεύθυνση αν ο χρήστης δεν είναι logged in
    header("Location: logout.php");
    exit();
}

if (isset($_SESSION['status'])) { // Ανακατεύθυνση αν ο χρήστης δεν έχει υπό εξέταση διπλωματική
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

$link = $_POST['link']; // link προς Νημερτής 
$email = $_SESSION['email']; // Email φοιτητή

// Update statement προς τη βάση
$sql = "UPDATE anathesi_diplomatikis SET Nemertes_link = ? WHERE email_stud = ? ORDER BY start_date DESC LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $link, $email);
$stmt->execute();
$stmt->close();

// Κλείσιμο σύνδεσης
$conn->close();
?>