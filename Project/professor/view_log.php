<?php
session_start();

// Έλεγχος αν ο χρήστης είναι συνδεδεμένος
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// Στοιχεία σύνδεσης με τη βάση δεδομένων
$servername = "localhost";
$username = "root";
$password = "Matsaniarakos9";
$dbname = "diplomatiki_support";

// Σύνδεση στη βάση δεδομένων
$conn = new mysqli($servername, $username, $password, $dbname);

// Έλεγχος αν η σύνδεση ήταν επιτυχής
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


// Λήψη του ID για το log
$id = isset($_GET['id']) ? $_GET['id'] : null;

if ($id) {
    // Ανάκτηση των δεδομένων από τον πίνακα log με το id_di
    $sql = "SELECT record FROM log WHERE id_di = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $log_data = [];
        while ($row = $result->fetch_assoc()) {
            $log_data[] = $row;
        }
        echo json_encode($log_data);
    } else {
        echo json_encode(["error" => "No log data found for this ID"]);
    }

    $stmt->close();
}

$conn->close();
?>