<?php
session_start(); // Αναζήτηση συνόδου

// Ανακατεύθυνση αν ο χρήστης δεν είναι logged in
if (!isset($_SESSION['email'])) {
    header("Location: logout.php");
    exit();
}

if ($_SESSION['type'] != 'STUDENT') {
    header("Location: logout.php");
    exit();
}

// Ανακατεύθυνση αν η διπλωματική του χρήστη δεν είναι υπό εξέταση
if (isset($_SESSION['status'])) {
    if ($_SESSION['status'] != "under examination") {
        header("Location: logout.php");
        exit();
    }
}

$host = "localhost";
$dbusername = "root";
$dbpassword = "Matsaniarakos9";
$dbname = "diplomatiki_support";

// Σύνδεση με τη βάση
$conn = new mysqli($host, $dbusername, $dbpassword, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Email φοιτητή
$email = $_SESSION['email'];

$linksString = implode(', ', $_POST['links']); // Όλα τα links σε κοινό string

// Update statement προς τη βάση
$sql = "UPDATE anathesi_diplomatikis SET external_links = ? WHERE email_stud = ? AND status = 'under examination'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $linksString, $email);
if ($stmt->execute()) {
    echo json_encode(['message' => 'Links stored successfully.']);
} else {
    echo json_encode(['message' => 'Error storing links: ' . $stmt->error]);
}
$stmt->close();

$conn->close();
?>
