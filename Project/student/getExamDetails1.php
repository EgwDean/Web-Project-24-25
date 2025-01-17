<?php
session_start(); // Αναζήτηση συνόδου

if (!isset($_SESSION['email'])) { // Έλεγχος αν ο χρήστης είναι logged in αλλιώς ανακατεύθυνση
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

$servername = "localhost";
$username = "root";
$password = explode(' ', file_get_contents('../dbpassword.txt'))[0];
$dbname = "diplomatiki_support";

$conn = new mysqli($servername, $username, $password, $dbname); // Σύνδεση με τη βάση

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Select statement προς τη βάση
$stm = $conn->prepare(
    "SELECT exam_date, exam_room FROM eksetasi_diplomatikis WHERE email_st = ? ORDER BY exam_date DESC LIMIT 1"
);

// Γίνονται bind οι παράμετροι
$email = $_SESSION['email'];
$stm->bind_param("s", $email);

// Εκτέλεση του statement
$stm->execute();
$result = $stm->get_result();
$status = [];
$status["item"] = $result->fetch_assoc();

// Αποστολή του JSON
header("Content-Type: application/json"); 
echo json_encode($status); // Αποστολή του JSON

// Κλείσιμο σύνδεσης
$stm->close();
$conn->close();
?>