<?php
session_start(); // Ξεκινάμε τη συνεδρία

if (!isset($_SESSION['email'])) { // Ανακατεύθυνση αν ο χρήστης δεν είναι logged in
    header("Location: logout.php");
    exit();
}

if ($_SESSION['type'] != 'STUDENT') {
    header("Location: logout.php");
    exit();
}

if (isset($_SESSION['status'])) { // Ανακατεύθυνση αν ο χρήστης δεν έχει υπό εξέταση διπλωματική
    if ($_SESSION['status'] != "under examination") {
        header("Location: logout.php");
    }
}

$host = "localhost";
$dbusername = "root";
$dbpassword = "Matsaniarakos9";
$dbname = "diplomatiki_support";

$conn = new mysqli($host, $dbusername, $dbpassword, $dbname); // Σύνδεση με τη βάση

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$email = $_SESSION['email']; // Ανάκτηση email

$sql = "CALL returnId(?, @id)"; // Ανάκτηση id
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->close();
$result = $conn->query("SELECT @id AS id"); // Εξαγωγή output variable
$row = $result->fetch_assoc();
$id = $row['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $targetDir = "../uploads/praktiko/";  // Ο φάκελος που θέλεις να αποθηκεύσεις το αρχείο

    // Δημιουργία νέου ονόματος για το αρχείο (π.χ. id και το όνομα του αρχικού αρχείου)
    $newFileName = $id . "_" . basename($_FILES["file"]["name"]);
    $targetFile = $targetDir . $newFileName;

    if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetFile)) {
        echo json_encode(['message' => "Success"]);
        $sql = "UPDATE eksetasi_diplomatikis SET praktiko_bathmologisis = ? WHERE id_diplomatikis = ? ORDER BY exam_date DESC LIMIT 1"; // Εισαγωγή του link πρακτικού στη βάση
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $targetFile, $id);
        $stmt->execute();
        $stmt->close();
    } else {
        echo json_encode(['message' => "Failure"]);
    }
}
?>