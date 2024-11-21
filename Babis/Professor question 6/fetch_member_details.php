<?php
session_start();

// Στοιχεία σύνδεσης
$servername = "localhost";
$username = "root";
$password = "556782340";
$dbname = "diplomatiki_support";

// Σύνδεση στη βάση δεδομένων
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Έλεγχος αν ο χρήστης είναι συνδεδεμένος
if (!isset($_SESSION['email'])) {
    echo json_encode(["error" => "You need to log in"]);
    exit();
}



// Λήψη στοιχείων
$member_email = isset($_GET['member']) ? $_GET['member'] : null;
$id = isset($_GET['id']) ? $_GET['id'] : null;

if ($member_email && $id) {
    $sql = "SELECT status, invitation_date, reply_date 
            FROM prosklisi_se_trimeli 
            WHERE prof_email = ? AND id_dip = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $member_email, $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
        echo json_encode($data);
    } else {
        echo json_encode(["error" => "No data found"]);
    }
    $stmt->close();
} else {
    echo json_encode(["error" => "Invalid input"]);
}

$conn->close();
?>
