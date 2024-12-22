<?php // ίδιος κώδικας με print_diplomatiki_details.php αλλά ο πίνακας στοιχείων έχει και αριθμό πρωτοκόλλου
session_start(); // Αναζήτηση συνόδου

if (!isset($_SESSION['email'])) { // Έλεγχος αν ο χρήστης είναι logged in αλλιώς ανακατεύθυνση
    header("Location: logout.php");
    exit();
}

if ($_SESSION['type'] != 'GRAM') {
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

$id_diplomatiki = $_GET['id_diplomatiki'];  // Ανάκτηση Id διπλωματικής από GET

// Select statement προς τη βάση
$stm = "SELECT id_diplomatiki AS ID, title AS Θέμα , description AS Περιγραφή, anathesi_diplomatikis.status AS Κατάσταση, email_prof AS Επιβλέπων
FROM diplomatiki
INNER JOIN anathesi_diplomatikis ON id_diplomatiki = id_diploma
WHERE (anathesi_diplomatikis.status = 'active' OR anathesi_diplomatikis.status = 'under examination' OR anathesi_diplomatikis.status = 'canceled_by_student' OR anathesi_diplomatikis.status = 'finished') AND id_diplomatiki = ?";

$stmt = $conn->prepare($stm);
$stmt->bind_param("i", $id_diplomatiki);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $status["message"] = "Success";
    $status["item"] = $result->fetch_assoc();  // Γραμμή που περιέχει τα δεδομένα
} else {
    $status["message"] = "Failure";
}

header("Content-Type: application/json"); // Η απάντηση που θα σταλεί είναι τύπου JSON
echo json_encode($status); // Αποστολή JSON απάντησης

$conn->close();
?>