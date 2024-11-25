<?php
header("Content-Type: application/json");

$servername = "localhost";
$username = "root";
$password = "12345theo";
$dbname = "diplomatiki_support";

// Δημιουργία σύνδεσης με τη βάση δεδομένων
$conn = new mysqli($servername, $username, $password, $dbname);

// Έλεγχος σύνδεσης
if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

// Λήψη των παραμέτρων ημερομηνίας από το URL
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';

$sql = "SELECT info FROM anakoinosi WHERE 1=1";

// Αν υπάρχουν ημερομηνίες, προσθήκη τους στο query
if ($start_date) {
    $sql .= " AND date_exam >= '$start_date 00:00:00'";
}
if ($end_date) {
    $sql .= " AND date_exam <= '$end_date 23:59:59'";
}

// Ταξινόμηση κατά την ημερομηνία
$sql .= " ORDER BY date_exam ASC"; // ή DESC αν θέλεις φθίνουσα σειρά

$result = $conn->query($sql);

$data = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

// Επιστροφή δεδομένων σε μορφή JSON
echo json_encode($data);

// Κλείσιμο σύνδεσης
$conn->close();
?>