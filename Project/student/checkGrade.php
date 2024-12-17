<?php
session_start(); // Αναζήτηση συνόδου

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

header('Content-Type: application/json'); // Αποστολή δεδομένων τύπου JSON

$grade = null;

$email = $_SESSION['email']; // Ανάκτηση email

$sql = "CALL returnId(?, @id)"; // Ανάκτηση id
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $conn->query("SELECT @id AS id"); // Εξαγωγή output variable
$row = $result->fetch_assoc();
$id = $row['id'];
$stmt->close();

$sql = "SELECT final_grade AS grade FROM eksetasi_diplomatikis WHERE id_diplomatikis = ?"; // Ανάκτηση grade
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$grade = $row['grade']; // Αποθήκευση του grade σε μεταβλητή
$stmt->close();

if ($grade >= 5) {
    $response = 1;
} else {
    $response = 0;
}

echo json_encode(['message' => $response]);
?>