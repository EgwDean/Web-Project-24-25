<?php
session_start(); // Αναζήτηση συνόδου

if (!isset($_SESSION['email'])) { // Ανακατεύθυνση αν ο χρήστης δεν είναι logged in
    header("Location: login.php");
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

header('Content-Type: application/json'); // Αποστολή δεδομένων τύπου JSON

$email = $_SESSION['email']; // Ανάκτηση email

$sql = "CALL returnId(?, @id)"; // Ανάκτηση id
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $conn->query("SELECT @id AS id"); // Εξαγωγή output variable
$row = $result->fetch_assoc();
$id = $row['id'];
$stmt->close();

// File path
$file_path = $_SERVER['DOCUMENT_ROOT'] . '/Project/uploads/praktiko/' . $id . '_praktiko_simplified.xlsx';

// Απάντηση
$response = array('exists' => 0);

if (file_exists($file_path)) {
    $response['exists'] = 1;
}

echo json_encode($response);
?>