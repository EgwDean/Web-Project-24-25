<?php
session_start(); // Αναζήτηση συνόδου

if (!isset($_SESSION['email'])) { // Έλεγχος αν ο χρήστης είναι logged in αλλιώς ανακατεύθυνση
    header("Location: login.php");
    exit();
}

if (isset($_SESSION['status'])) { // Ανακατεύθυνση αν ο χρήστης δεν έχει υπό εξέταση διπλωματική
    if ($_SESSION['status'] != "under examination" && $_SESSION['status'] != "finished") {
        header("Location: logout.php");
    }
}

$servername = "localhost";
$username = "root";
$password = "Matsaniarakos9";
$dbname = "diplomatiki_support";

$conn = new mysqli($servername, $username, $password, $dbname); // Σύνδεση με τη βάση

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$email = $_SESSION['email']; // Ανάκτηση email

$sql = "CALL returnId(?, @id)"; // Ανάκτηση id
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $conn->query("SELECT @id AS id"); // Εξαγωγή output variable
$row = $result->fetch_assoc();
$id = $row['id'];
$stmt->close();

// Select statement προς τη βάση
$stm = $conn->prepare("SELECT record FROM log WHERE id_di = ?");
$stm->bind_param("i", $id);
$stm->execute();
$result = $stm->get_result();

if ($result->num_rows > 0) {
    $status["message"] = "Success";
    $status["items"] = [];

    while ($row = $result->fetch_assoc()) {
        array_push($status["items"], $row);
    }
} else {
    $status["message"] = "Failure";
}

$stm->close();

header("Content-Type: application/json"); // Αρχείο που αποστέλεται είναι τύπου JSON
echo json_encode($status); // Αποστολή του JSON

// Κλείσιμο σύνδεσης
$conn->close();
?>