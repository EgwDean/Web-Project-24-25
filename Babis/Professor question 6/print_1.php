<?php
session_start(); // Ξεκινάμε τη συνεδρία

$servername = "localhost";
$username = "root";
$password = "556782340";
$dbname = "diplomatiki_support";

// Δημιουργία σύνδεσης με τη βάση δεδομένων χρησιμοποιώντας mysqli
$conn = new mysqli($servername, $username, $password, $dbname);

// Έλεγχος σύνδεσης
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$status = [];

$email = $_SESSION['email']; 

$sql = "SELECT id_diplomatiki, title, description, pdf_link_topic FROM diplomatiki WHERE email_prof = '$email' AND status = 'available'";
$result = $conn->query($sql);

// Έλεγχος αν υπάρχουν αποτελέσματα
if ($result && $result->num_rows > 0) {
    $status["code"] = 1;
    $status["message"] = "Success!";
    $status["items"] = [];
    
    // Εισαγωγή των αποτελεσμάτων στον πίνακα items
    while ($row = $result->fetch_assoc()) {
        array_push($status["items"], $row);
    }
} else {
    $status["code"] = 0;
    $status["message"] = "No results found";
}

header("Content-Type: application/json");
echo json_encode($status);

// Κλείσιμο σύνδεσης
$conn->close();
?>
