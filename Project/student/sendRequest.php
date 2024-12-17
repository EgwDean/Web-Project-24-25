<?php
session_start(); // Αναζήτηση συνόδου

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

header('Content-Type: application/json'); // Αποστολή δεδομένων τύπου JSON

$selectedEmail = $_GET['selectedEmail']; // Ανάκτηση email καθηγητή
$stud_email = $_SESSION['email']; // Ανάκτηση email φοιτητή
$result_status = null; // Αρχικοποίηση status variable

$stmt = $conn->prepare("CALL sendRequest(?, ?, @result_status)"); // Κλήση stored procedure για την εισαγωγή αίτησης
$stmt->bind_param("ss", $stud_email, $selectedEmail);
$stmt->execute();
$stmt->close();

$result = $conn->query("SELECT @result_status AS status"); // Εξαγωγή output variable
$row = $result->fetch_assoc();
$status = $row['status'];

if ($status == 1) { // Επιτυχής εισαγωγή
    echo json_encode(['message' => 'Request successfully sent.']);
} else { // Ανεπιτυχής εισαγωγή
    echo json_encode(['message' => 'Professor has already received a request.']);
}
?>