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

$status = [];


$id = 1;

$sql = " SELECT 
    t.id_dipl AS diploma_id,
    CONCAT(p1.name, ' ', p1.surname) AS supervisor_full_name,
    CONCAT(p2.name, ' ', p2.surname) AS member1_full_name,
    CONCAT(p3.name, ' ', p3.surname) AS member2_full_name,
    CONCAT(s.name, ' ', s.surname) AS student_full_name,
    s.number as am,
    e.exam_date AS examination_date,
    e.exam_room AS examination_room,
    e.grade1 AS grade_member1,
    e.grade2 AS grade_member2,
    e.grade3 AS grade_member3,
    e.final_grade AS final_grade,
    d.title AS diploma_title,
    a.protocol_number AS protocol_number
FROM 
    trimelis_epitropi_diplomatikis t
INNER JOIN 
    professor p1 ON t.supervisor = p1.email_professor
INNER JOIN 
    professor p2 ON t.member1 = p2.email_professor
INNER JOIN 
    professor p3 ON t.member2 = p3.email_professor
INNER JOIN 
    eksetasi_diplomatikis e ON t.id_dipl = e.id_diplomatikis
INNER JOIN 
    anathesi_diplomatikis a ON t.id_dipl = a.id_diploma
INNER JOIN 
    diplomatiki d ON t.supervisor = d.email_prof
INNER JOIN 
    student s ON a.email_stud = s.email_student 
WHERE t.id_dipl = $id AND a.status = 'finished' ";




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