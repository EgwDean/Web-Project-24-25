<?php
session_start();

// Έλεγχος αν ο χρήστης είναι συνδεδεμένος
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// Έλεγχος αν υπάρχει το id
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);  // Sanitize the ID

    // Στοιχεία σύνδεσης με τη βάση δεδομένων
    $servername = "localhost";
    $username = "root";
    $password = "Matsaniarakos9";
    $dbname = "diplomatiki_support";

    // Σύνδεση στη βάση δεδομένων
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Έλεγχος σύνδεσης με τη βάση δεδομένων
    if ($conn->connect_error) {
        echo json_encode(["success" => false, "error" => "Connection failed: " . $conn->connect_error]);
        exit();
    }

    // Ενημέρωση της κατάστασης
    $sql = "UPDATE anathesi_diplomatikis SET status = 'under examination' WHERE id_diploma = ? AND status = 'active'";
    $stmt = $conn->prepare($sql);
    
    // Bind the parameters and execute
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "error" => $stmt->error]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["success" => false, "error" => "Invalid request"]);
}
?>