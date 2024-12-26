<?php
header("Content-Type: text/html");

$servername = "localhost";
$username = "root";
$password = "556782340";
$dbname = "diplomatiki_support";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo "<p style='color: red;'>Connection failed: " . $conn->connect_error . "</p>";
    exit;
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    echo "<p style='color: red;'>Invalid or missing ID.</p>";
    exit;
}

$sql = "SELECT CONCAT(student.name, ' ', student.surname) AS student_full_name, 
               student_number AS AM, 
               id_diplomatikis AS id, 
               title, 
               exam_date, 
               exam_room, 
               CONCAT(professor.name, ' ', professor.surname) AS professor_full_name,
               grade1, 
               grade2, 
               grade3, 
               final_grade
        FROM eksetasi_diplomatikis 
        INNER JOIN diplomatiki ON eksetasi_diplomatikis.id_diplomatikis = diplomatiki.id_diplomatiki
        INNER JOIN student ON eksetasi_diplomatikis.email_st = student.email_student
        INNER JOIN professor ON diplomatiki.email_prof = professor.email_professor
        WHERE id_diplomatikis = $id
        ORDER BY exam_date ASC";

$result = $conn->query($sql);

if (!$result) {
    echo "<p style='color: red;'>SQL error: " . $conn->error . "</p>";
    exit;
}

if ($result->num_rows > 0) {
    $output = "<div style='font-family: Arial, sans-serif; color: #333;'>";
    while ($row = $result->fetch_assoc()) {
        // Constructing the descriptive text with HTML styling
        $output .= "<p><strong>The diploma with ID " . $row['id'] . "</strong> belongs to <strong>" . $row['student_full_name'] . "</strong> (Student Number: <strong>" . $row['AM'] . "</strong>).</p>";
        $output .= "<p>The exam for this diploma is to take place on <strong>" . $row['exam_date'] . "</strong> in <strong>" . $row['exam_room'] . "</strong>, and the professor responsible is <strong>" . $row['professor_full_name'] . "</strong>.</p>";
        $output .= "<p>The title of the diploma is <strong>" . $row['title'] . "</strong>.</p>";
        $output .= "<p>The student received the following grades:</p>";
        $output .= "<ul>";
        $output .= "<li>Grade 1: <strong>" . $row['grade1'] . "</strong></li>";
        $output .= "<li>Grade 2: <strong>" . $row['grade2'] . "</strong></li>";
        $output .= "<li>Grade 3: <strong>" . $row['grade3'] . "</strong></li>";
        $output .= "<li>Final Grade: <strong>" . $row['final_grade'] . "</strong></li>";
        $output .= "</ul>";
        $output .= "<hr style='border: 1px solid #ccc;'>";
    }
    $output .= "</div>";
    echo $output;
} else {
    echo "<p style='color: red;'>No data found for the given ID.</p>";
}

$conn->close();
?>
