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

// Set the character set to UTF-8
$conn->set_charset("utf8");

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
$stm = "SELECT id_diplomatiki AS ID, title AS Θέμα , description AS Περιγραφή, anathesi_diplomatikis.status AS Κατάσταση, email_prof AS Επιβλέπων, email_stud AS Φοιτητής
FROM diplomatiki
INNER JOIN anathesi_diplomatikis ON id_diplomatiki = id_diploma
INNER JOIN trimelis_epitropi_diplomatikis ON id_diplomatiki = id_dipl
WHERE anathesi_diplomatikis.status = 'finished' AND id_diplomatiki = ?";

$stmt = $conn->prepare($stm);
$stmt->bind_param("i", $id);
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
