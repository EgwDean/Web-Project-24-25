<?php
session_start();

// Έλεγχος αν ο χρήστης είναι συνδεδεμένος
if (empty($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

$email = $_SESSION['email']; // Το email του συνδεδεμένου χρήστη

// Database credentials
$host = "localhost";
$dbusername = "root";
$dbpassword = "556782340";
$dbname = "diplomatiki_support";

// Σύνδεση με τη βάση δεδομένων
$conn = new mysqli($host, $dbusername, $dbpassword, $dbname);

// Έλεγχος σύνδεσης
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Επεξεργασία του αιτήματος ενημέρωσης
if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    // Αποθήκευση των δεδομένων της φόρμας
    $street = $_POST['street'];
    $number = $_POST['number'];
    $city = $_POST['city'];
    $postcode = $_POST['postcode'];
    $landline_telephone = $_POST['landline_telephone'];
    $mobile_telephone = $_POST['mobile_telephone'];

    // Προετοιμασία του query
    $sql = "UPDATE student 
            SET street = ?, 
                number = ?, 
                city = ?, 
                postcode = ?, 
                landline_telephone = ?, 
                mobile_telephone = ? 
            WHERE email_student = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "sssssss", 
        $street, 
        $number, 
        $city, 
        $postcode, 
        $landline_telephone, 
        $mobile_telephone, 
        $email
    );

    // Εκτέλεση του query και επιστροφή του αποτελέσματος
    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "error" => "Failed to update information."]);  
    }

    $stmt->close();
    $conn->close();
    exit();
}
?>