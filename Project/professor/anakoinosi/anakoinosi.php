<?php
header("Content-Type: application/json");

$servername = "localhost";
$username = "root";
$password = "556782340";
$dbname = "diplomatiki_support";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

$id = isset($_GET['id']) ? $_GET['id'] : '';
$format = isset($_GET['format']) ? $_GET['format'] : 'json';

$sql = "SELECT CONCAT(student.name, ' ', student.surname) AS student_full_name, student_number AS AM, id_diplomatikis AS id, title, exam_date, exam_room, CONCAT(professor.name, ' ', professor.surname) AS professor_full_name,
		grade1, grade2, grade3, final_grade
		FROM eksetasi_diplomatikis 
		INNER JOIN diplomatiki ON eksetasi_diplomatikis.id_diplomatikis = diplomatiki.id_diplomatiki
		INNER JOIN student ON eksetasi_diplomatikis.email_st = student.email_student
		INNER JOIN professor ON diplomatiki.email_prof = professor.email_professor
		WHERE 1=1;";



if ($id) {
    $sql .= " AND id_diplomatikis = $id";
}

$sql .= " ORDER BY exam_date ASC";

$result = $conn->query($sql);

$data = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

if ($format === 'xml') {
    header("Content-Type: application/xml");
    $xml = new SimpleXMLElement('<announcements/>');
    foreach ($data as $row) {
        $announcement = $xml->addChild('announcement');
        foreach ($row as $key => $value) {
            $announcement->addChild($key, htmlspecialchars($value));
        }
    }
    echo $xml->asXML();
} else {
    echo json_encode($data);
}

$conn->close();
?>
