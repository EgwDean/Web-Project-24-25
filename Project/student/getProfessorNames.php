<?php
session_start(); // Αναζήτηση συνόδου

if (!isset($_SESSION['email'])) { // Έλεγχος αν ο χρήστης είναι logged in αλλιώς ανακατεύθυνση
    header("Location: logout.php");
    exit();
}

if ($_SESSION['type'] != 'STUDENT') {
    header("Location: logout.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "Matsaniarakos9";
$dbname = "diplomatiki_support";

$conn = new mysqli($servername, $username, $password, $dbname); // Σύνδεση με τη βάση

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Select statement προς τη βάση
$stm = $conn->prepare(
    "SELECT professor.name, professor.surname, email_professor
    FROM professor
    WHERE email_professor NOT IN (
        SELECT supervisor FROM trimelis_epitropi_diplomatikis
        INNER JOIN anathesi_diplomatikis ON id_dipl = id_diploma
        WHERE email_stud = ?)
    OR email_professor NOT IN (
        SELECT member1 FROM trimelis_epitropi_diplomatikis
        INNER JOIN anathesi_diplomatikis ON id_dipl = id_diploma
        WHERE email_stud = ?)
    OR email_professor NOT IN (
        SELECT member2 FROM trimelis_epitropi_diplomatikis
        INNER JOIN anathesi_diplomatikis ON id_dipl = id_diploma
        WHERE email_stud = ?
    )"
);

// Γίνονται bind οι παράμετροι
$email = $_SESSION['email'];
$stm->bind_param("sss", $email, $email, $email);

// Εκτέλεση του statement
$stm->execute();
$result = $stm->get_result();

// Επεξεργασία αποτελεσμάτων
$status = [];
if ($result->num_rows > 0) {
    $status["message"] = "Success";
    $status["items"] = [];
    
    while ($row = $result->fetch_assoc()) {
        array_push($status["items"], $row);
    }
} else {
    $status["message"] = "Failure";
}

header("Content-Type: application/json"); // Αρχείο που αποστέλεται είναι τύπου JSON
echo json_encode($status); // Αποστολή του JSON

// Κλείσιμο σύνδεσης
$stm->close();
$conn->close();
?>