<?php
session_start(); // Ξεκινάμε τη συνεδρία

// Έλεγχος αν ο χρήστης είναι συνδεδεμένος
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
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


$sql = "SELECT ROUND(AVG(TIMESTAMPDIFF(MONTH, start_date, end_date)), 1) AS stats
FROM anathesi_diplomatikis 
INNER JOIN trimelis_epitropi_diplomatikis 
  ON anathesi_diplomatikis.id_diploma = trimelis_epitropi_diplomatikis.id_dipl
WHERE supervisor = '$email' 
  AND anathesi_diplomatikis.status = 'finished'

UNION

SELECT ROUND(AVG(TIMESTAMPDIFF(MONTH, start_date, end_date)), 1) AS avg_completion_days
FROM anathesi_diplomatikis 
INNER JOIN trimelis_epitropi_diplomatikis 
  ON anathesi_diplomatikis.id_diploma = trimelis_epitropi_diplomatikis.id_dipl
WHERE (member1 = '$email' OR member2 = '$email') 
  AND anathesi_diplomatikis.status = 'finished'

UNION

SELECT ROUND(AVG(final_grade), 1) AS mesos_oros
FROM trimelis_epitropi_diplomatikis 
INNER JOIN eksetasi_diplomatikis 
  ON trimelis_epitropi_diplomatikis.id_dipl = eksetasi_diplomatikis.id_diplomatikis
WHERE supervisor = '$email' 
  AND final_grade IS NOT NULL

UNION

SELECT ROUND(AVG(final_grade), 1) AS mesos_oros
FROM trimelis_epitropi_diplomatikis 
INNER JOIN eksetasi_diplomatikis 
  ON trimelis_epitropi_diplomatikis.id_dipl = eksetasi_diplomatikis.id_diplomatikis
WHERE (member1 = '$email' OR member2 = '$email') 
  AND final_grade IS NOT NULL

UNION

SELECT CAST(COUNT(*) AS DECIMAL(10, 1)) AS plithos1
FROM trimelis_epitropi_diplomatikis 
INNER JOIN eksetasi_diplomatikis 
  ON trimelis_epitropi_diplomatikis.id_dipl = eksetasi_diplomatikis.id_diplomatikis
WHERE supervisor = '$email'

UNION ALL

SELECT CAST(COUNT(*) AS DECIMAL(10, 1)) AS plithos2
FROM trimelis_epitropi_diplomatikis 
INNER JOIN eksetasi_diplomatikis 
  ON trimelis_epitropi_diplomatikis.id_dipl = eksetasi_diplomatikis.id_diplomatikis
WHERE member1 = '$email' 
   OR member2 = '$email';";



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