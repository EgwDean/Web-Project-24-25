<?php
header("Content-Type: application/json");

$servername = "localhost";
$username = "root";
$password = "12345theo";
$dbname = "diplomatiki_support";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';
$format = isset($_GET['format']) ? $_GET['format'] : 'json';

$sql = "SELECT id_diplomatikis AS id, email_st, exam_date, exam_room, grade1, grade2, grade3, final_grade, praktiko_bathmologisis FROM eksetasi_diplomatikis WHERE 1=1";

if ($start_date) {
    $sql .= " AND exam_date >= '$start_date 00:00:00'";
}
if ($end_date) {
    $sql .= " AND exam_date <= '$end_date 23:59:59'";
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
