<?php
session_start();


// Έλεγχος αν ο χρήστης είναι συνδεδεμένος
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// Λήψη email χρήστη από τη συνεδρία
$email = $_SESSION['email'];



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


// Λήψη παραμέτρων φίλτρου από το GET
$status = isset($_GET['status']) ? $_GET['status'] : 'none';
$role = isset($_GET['role']) ? $_GET['role'] : 'none';

// Δημιουργία του βασικού SQL query
$sql = "SELECT trimelis_epitropi_diplomatikis.id_dipl as id, supervisor, member1, member2, status, final_grade
FROM trimelis_epitropi_diplomatikis 
LEFT JOIN eksetasi_diplomatikis ON trimelis_epitropi_diplomatikis.id_dipl = eksetasi_diplomatikis.id_diplomatikis
INNER JOIN anathesi_diplomatikis ON anathesi_diplomatikis.id_diploma = trimelis_epitropi_diplomatikis.id_dipl				
WHERE (member1 = '$email' OR member2 = '$email' OR supervisor = '$email') 
AND (status = 'pending' OR status = 'active' OR status = 'under examination' OR status = 'finished')";

// Εφαρμογή φίλτρου status, αν υπάρχει
if ($status !== 'none') {
    $sql .= " AND status = '$status'";
}

// Εφαρμογή φίλτρου role, αν υπάρχει
if ($role === 'supervisor') {
    $sql .= " AND supervisor = '$email'";
} elseif ($role === 'member') {
    $sql .= " AND (member1 = '$email' OR member2 = '$email')";
}

// Εκτέλεση του SQL query
$result = $conn->query($sql);

// Προετοιμασία απάντησης
$status = [];
if ($result && $result->num_rows > 0) {
    $status["code"] = 1;
    $status["message"] = "Success!";
    $status["items"] = [];

    // Ανάγνωση αποτελεσμάτων
    while ($row = $result->fetch_assoc()) {
        array_push($status["items"], $row);
    }
} else {
    $status["code"] = 0;
    $status["message"] = "No results found";
}

// Επιστροφή δεδομένων σε JSON μορφή
header("Content-Type: application/json");
echo json_encode($status);

// Κλείσιμο σύνδεσης με τη βάση
$conn->close();
?>
