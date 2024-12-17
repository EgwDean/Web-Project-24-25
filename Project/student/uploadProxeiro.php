<?php
session_start(); //Αναζήτηση συνόδου

if (!isset($_SESSION['email'])) { // Ανακατεύθυνση αν ο χρήστης δεν είναι logged in
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

$file = $_FILES['file']; // Λήψη file από το frontend

if ($file['error'] !== UPLOAD_ERR_OK) { // Έλεγχος για upload errors
    http_response_code(400);
    echo json_encode(['error' => 'File upload error.']);
    exit;
}

$fileDir = "../uploads/students/"; // Directory που θα ανέβει το file

$filename = $id . ".pdf"; // Παραγωγή μοναδικού ονόματος file
$filePath = $fileDir . $filename;
$relativePath = substr($filePath, 24);

if (file_exists($filePath)) { // Έλεγχος για αντικατάσταση file
    unlink($filePath);
}

if (move_uploaded_file($file['tmp_name'], $filePath)) { // Τοποθέτηση του file στο σωστό directory στο server
    http_response_code(200);
    echo json_encode(['success' => 'File uploaded successfully.', 'file' => $filename]);

    $sql = "UPDATE anathesi_diplomatikis SET pdf_main_diploma = ? WHERE id_diploma = ?"; // Εισαγωγή του ονόματος του pdf στη βάση
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $relativePath, $id);
    $stmt->execute();
    $stmt->close();
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to move uploaded file.']);
}

// Κλείσιμο σύνδεσης
$conn->close();
?> 
