<?php
session_start();

if (empty($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

$email = $_SESSION['email'];

$servername = "localhost";
$username = "root";
$password = "556782340";
$dbname = "diplomatiki_support";
$dateDiff = null;

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$table = [];

// Fetching date difference
$sqlTime = "CALL get_date_diff(?)";
$stmTime = $conn->prepare($sqlTime);
$stmTime->bind_param("s", $email);
$stmTime->execute();
$resultTime = $stmTime->get_result();

if ($rowTime = $resultTime->fetch_assoc()) {
    $dateDiff = $rowTime['dateDiff'];
}
$stmTime->close();

// Fetching first table data
$sql = "SELECT diplomatiki.title, diplomatiki.description, diplomatiki.pdf_link_topic, anathesi_diplomatikis.status
        FROM diplomatiki 
        INNER JOIN anathesi_diplomatikis 
        ON diplomatiki.id_diplomatiki = anathesi_diplomatikis.id_diploma
        WHERE anathesi_diplomatikis.email_stud = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $table["table1"] = [];

    while ($row = $result->fetch_assoc()) {
        $row["dateDiff"] = $dateDiff; // Adding dateDiff to each row
        $table["table1"][] = $row;
    }
} else {
    $table["table1"] = [];
}
$stmt->close();

// Fetching second table data
$sql1 = "SELECT id_diploma FROM anathesi_diplomatikis WHERE email_stud = ?";
$stmt1 = $conn->prepare($sql1);
$stmt1->bind_param("s", $email);
$stmt1->execute();
$stmt1->bind_result($id);
$stmt1->fetch();
$stmt1->close();

$sql2 = "SELECT supervisor, member1, member2 
         FROM trimelis_epitropi_diplomatikis 
         WHERE id_dipl = ?";
$stmt2 = $conn->prepare($sql2);
$stmt2->bind_param("i", $id);
$stmt2->execute();
$result2 = $stmt2->get_result();

$table["table2"] = [];
while ($row2 = $result2->fetch_assoc()) {
    $table["table2"][] = $row2;
}
$stmt2->close();

$conn->close();

header("Content-Type: application/json");
echo json_encode($table);
?>
