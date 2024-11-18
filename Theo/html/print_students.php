<?php
session_start(); // Ξεκινάμε τη συνεδρία

$servername = "localhost";
$username = "root";
$password = "12345theo";
$dbname = "diplomatiki_support";

// Δημιουργία σύνδεσης με τη βάση δεδομένων χρησιμοποιώντας mysqli
$conn = new mysqli($servername, $username, $password, $dbname);

// Έλεγχος σύνδεσης
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Έλεγχος αν υπάρχει φίλτρο (εισαγωγή από το πεδίο φίλτρου)
$filter = isset($_GET['filter']) ? $_GET['filter'] : ''; // Αν δεν υπάρχει φίλτρο, είναι κενό

$status = [];

// Δημιουργία του βασικού SQL query
$sql = "SELECT name, surname, student_number, email_student FROM student";

// Αν υπάρχει φίλτρο, προσθέτουμε το WHERE στο SQL
if (!empty($filter)) {
    $sql .= " WHERE student_number LIKE ? OR name LIKE ? OR surname LIKE ?"; // Χρησιμοποιούμε prepared statement για ασφαλή query
}

$stmt = $conn->prepare($sql); // Προετοιμάζουμε το query

// Αν υπάρχει φίλτρο, προσθέτουμε την τιμή στο prepared statement
if (!empty($filter)) {
    $filterValue = "%$filter%"; // Δημιουργούμε το φίλτρο για το LIKE
    $stmt->bind_param("sss", $filterValue, $filterValue, $filterValue); // Το "s" σημαίνει ότι το φίλτρο είναι string
}

$stmt->execute(); // Εκτελούμε το query

$result = $stmt->get_result(); // Παίρνουμε τα αποτελέσματα

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
$stmt->close();
$conn->close();
?>
