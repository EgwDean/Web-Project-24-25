<?php
session_start(); //Αναζήτηση συνόδου

if (!isset($_SESSION['email'])) { // Ανακατεύθυνση αν ο χρήστης δεν είναι logged in
    header("Location: logout.php");
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

$id = $_POST['id']; // id διπλωματικής 
$protocolNumber = $_POST['protocolNumber']; // Αριθμός πρωτοκόλλου

// Update statement προς τη βάση
$sql = "UPDATE anathesi_diplomatikis 
        SET protocol_number = ? 
        WHERE id_diploma = ?
        AND status = 'active'";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $protocolNumber, $id);
$stmt->execute();
$stmt->close();

// Κλείσιμο σύνδεσης
$conn->close();
?>