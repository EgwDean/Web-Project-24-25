<?php
session_start(); // Αναζήτηση συνόδου

if (!isset($_SESSION['email'])) { // Ανακατεύθυνση αν ο χρήστης δεν είναι logged in
    header("Location: logout.php");
    exit();
}

if ($_SESSION['type'] != 'GRAM') {
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

$id = $_GET['id']; // Ανάκτηση id
$result_status = null; // Αρχικοποίηση output variable

$stmt = $conn->prepare("CALL changeToFinished(?, @result_status)"); // Κλήση stored procedure για έλεγχο αν η διπλωματική μπορεί να περατωθεί
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->close();

$result = $conn->query("SELECT @result_status AS status"); // Εξαγωγή output variable
$row = $result->fetch_assoc();
$status = $row['status'];

if ($status == 1) { // Επιτυχής περάτωση
    echo json_encode(['message' => 'Diploma status successfully updated to "finished".']);
    $sql = "UPDATE anathesi_diplomatikis SET status = 'finished' WHERE id_diploma = ? AND status = 'under examination'"; // Update statement προς τη βάση
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    $sql = "UPDATE anathesi_diplomatikis SET end_date = CURDATE() WHERE id_diploma = ? AND status = 'finished'"; // Update statement προς τη βάση
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
} else { // Ανεπιτυχής περάτωση
    echo json_encode(['message' => 'Diploma cannot be updated. Check the conditions.']);
}
?>