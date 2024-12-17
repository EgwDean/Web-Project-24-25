<?php
session_start(); // Αναζήτηση συνόδου

if (!isset($_SESSION['email'])) { // Έλεγχος αν ο χρήστης είναι logged in αλλιώς ανακατεύθυνση
    header("Location: logout.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "Matsaniarakos9";
$dbname = "diplomatiki_support";

$conn = new mysqli($servername, $username, $password, $dbname); // Σύνδεση με τη βάση

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Select statement προς τη βάση
$stm = "SELECT id_diplomatiki, title FROM diplomatiki INNER JOIN anathesi_diplomatikis ON id_diplomatiki = id_diploma WHERE anathesi_diplomatikis.status = 'active' OR anathesi_diplomatikis.status = 'under examination'";
$result = $conn->query($stm);

// Έλεγχος αν υπάρχουν αποτελέσματα
if ($result->num_rows > 0) {
    $status["message"] = "Success";
    $status["items"] = [];
    
    // Εισαγωγή των αποτελεσμάτων στον πίνακα items
    while ($row = $result->fetch_assoc()) {
        array_push($status["items"], $row);
    }
} else {
    $status["message"] = "Failure";
}

header("Content-Type: application/json"); // Αρχείο που αποστέλεται είναι τύπου JSON
echo json_encode($status); // Αποστολή του JSON

// Κλείσιμο σύνδεσης
$conn->close();
?>