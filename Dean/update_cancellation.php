<?php
session_start(); // Αναζήτηση συνόδου

if (!isset($_SESSION['email'])) { // Ανακατεύθυνση αν ο χρήστης δεν είναι logged in
    header("Location: login.php");
    exit();
}

$host = "localhost";
$dbusername = "root";
$dbpassword = "Matsaniarakos9";
$dbname = "diplomatiki_support";

$conn = new mysqli($host, $dbusername, $dbpassword, $dbname); // Σύνδεση με τη βάση

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ανάκτηση δεδομένων από τη URL (GET request)
$id = $_GET['id']; // id διπλωματικής
$meetingNumber = $_GET['meetingNumber']; // Αριθμός γενικής συνέλευσης
$meetingYear = $_GET['meetingYear']; // Έτος γενικής συνέλευσης

// Insert statement προς τη βάση
$sql1 = "INSERT INTO cancellations (id_d, meeting_number, meeting_year)
        VALUES
        (?, ?, ?)";

$stmt = $conn->prepare($sql1);
$stmt->bind_param("iii", $id, $meetingNumber, $meetingYear);
$stmt->execute();
$stmt->close();

// Stored procedure που διαγράφει στοιχεία διπλωματικής και αλλάζει την κατάστασή της
$sql2 = "CALL deleteById(?)";

$stmt = $conn->prepare($sql2);
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->close();

// Κλείσιμο σύνδεσης
$conn->close();
?>