<?php
session_start(); // Ξεκινάμε τη συνεδρία

if (!isset($_SESSION['email'])) { // Ανακατεύθυνση αν ο χρήστης δεν είναι logged in
    header("Location: logout.php");
    exit();
}

if (isset($_SESSION['status'])) { // Ανακατεύθυνση αν ο χρήστης δεν έχει υπό εξέταση διπλωματική
    if ($_SESSION['status'] != "under examination") {
        header("Location: logout.php");
    }
}

$filePath = $_SERVER['DOCUMENT_ROOT'] . '/Project/uploads/praktiko/template/praktiko_simplified.xlsx'; // File path

// Έλεγχος αν υπάρχει
if (file_exists($filePath)) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="' . basename($filePath) . '"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($filePath));

    // Διάβασμα του file
    readfile($filePath);
    exit;
} else {
    http_response_code(404);
    echo json_encode(['error' => 'File not found']);
    exit;
}
?>