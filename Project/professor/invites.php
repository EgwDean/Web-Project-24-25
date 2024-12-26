<?php
session_start(); // Ξεκινάμε τη συνεδρία


// Έλεγχος αν ο χρήστης είναι συνδεδεμένος
if (!isset($_SESSION['email']) OR $_SESSION['type'] != 'PROF') {
    header("Location: logout.php");
    exit();
}

// Λήψη του email από τη συνεδρία
$email = $_SESSION['email'];



 // Σύνδεση στη βάση δεδομένων
$servername = "localhost";
$username = "root";
$password = "556782340";
$dbname = "diplomatiki_support";

// Δημιουργία σύνδεσης με τη βάση δεδομένων χρησιμοποιώντας mysqli
$conn = new mysqli($servername, $username, $password, $dbname);

// Έλεγχος σύνδεσης
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$status = [];


$sql = "SELECT prosklisi_se_trimeli.student_email AS student, 
prosklisi_se_trimeli.id_dip AS kodikos, 
diplomatiki.title AS titlos, 
diplomatiki.email_prof AS email
FROM prosklisi_se_trimeli INNER JOIN diplomatiki ON prosklisi_se_trimeli.id_dip = diplomatiki.id_diplomatiki
WHERE prosklisi_se_trimeli.prof_email = '$email' AND prosklisi_se_trimeli.status = 'pending'";

$result = $conn->query($sql);

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
$conn->close();
?>