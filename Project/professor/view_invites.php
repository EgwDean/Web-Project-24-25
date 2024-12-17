<?php
session_start();

// Έλεγχος αν ο χρήστης είναι συνδεδεμένος
if (!isset($_SESSION['email'])) {
    header("Location: logout.php");
    exit();
}

// Χρήστης που συνδέθηκε
$userEmail = $_SESSION['email'];

// Λήψη στοιχείων
$id = isset($_GET['id']) ? $_GET['id'] : null;

if ($id) {
    // Στοιχεία σύνδεσης με τη βάση δεδομένων
    $servername = "localhost";
    $username = "root";
    $password = "Matsaniarakos9";
    $dbname = "diplomatiki_support";

    // Σύνδεση στη βάση δεδομένων
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Έλεγχος σύνδεσης
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Προετοιμασία του SQL query
    $sql = "SELECT prof_email, status, invitation_date, reply_date 
            FROM prosklisi_se_trimeli 
            WHERE id_dip = ? AND prof_email != ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $id, $userEmail);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $log_data = [];
		while ($row = $result->fetch_assoc()) {
            $log_data[] = $row;
        }
		echo json_encode($log_data);
    } else {
        echo json_encode(["success" => false, "error" => "No data found"]);
    }
    $stmt->close();
} else {
    echo json_encode(["error" => "Invalid input"]);
}

 $conn->close();
?>
