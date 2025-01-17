<?php
session_start();

// Έλεγχος αν ο χρήστης είναι συνδεδεμένος
if (!isset($_SESSION['email']) OR $_SESSION['type'] != 'PROF') {
    header("Location: logout.php");
    exit();
}

// Λήψη στοιχείων
$id = isset($_GET['id']) ? $_GET['id'] : null;

if ($id) {
    // Στοιχεία σύνδεσης με τη βάση δεδομένων
    $servername = "localhost";
    $username = "root";
    $password = explode(' ', file_get_contents('../dbpassword.txt'))[0];
    $dbname = "diplomatiki_support";

    // Σύνδεση στη βάση δεδομένων
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Έλεγχος σύνδεσης
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Προετοιμασία του SQL query
    $sql = "SELECT trimelis_epitropi_diplomatikis.supervisor, trimelis_epitropi_diplomatikis.member1, trimelis_epitropi_diplomatikis.member2, eksetasi_diplomatikis.grade1, eksetasi_diplomatikis.grade2, eksetasi_diplomatikis.grade3, eksetasi_diplomatikis.final_grade
			FROM eksetasi_diplomatikis
			INNER JOIN trimelis_epitropi_diplomatikis ON eksetasi_diplomatikis.id_diplomatikis = trimelis_epitropi_diplomatikis.id_dipl
			WHERE eksetasi_diplomatikis.id_diplomatikis = ?;";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
        echo json_encode($data);
    } else {
        echo json_encode(["error" => "No data found"]);
    }
    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["error" => "Invalid input"]);
}
?>