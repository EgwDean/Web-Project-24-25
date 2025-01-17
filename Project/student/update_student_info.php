<?php
session_start();

// Έλεγχος αν ο χρήστης είναι συνδεδεμένος
if (empty($_SESSION['email'])) {
    header("Location: logout.php");
    exit();
}

if ($_SESSION['type'] != 'STUDENT') {
    header("Location: logout.php");
    exit();
}

$email = $_SESSION['email']; // Το email του συνδεδεμένου χρήστη

// Database credentials
$host = "localhost";
$dbusername = "root";
$dbpassword = explode(' ', file_get_contents('../dbpassword.txt'))[0];
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
	
	
	
	// Validate inputs using regular expressions
    $errors = [];

    // Street: Only letters, numbers, spaces, and basic punctuation
    if (!preg_match("/^[a-zA-Z0-9\s.,'-]+$/", $street)) {
        $errors[] = "Το πεδίο 'Οδός' πρέπει να περιέχει μόνο γράμματα, αριθμούς, κενά και σημεία στίξης (.,'-).";
    }

    // Number: Digits only, up to 10 characters
    if (!preg_match("/^\d{1,10}$/", $number)) {
        $errors[] = "Το πεδίο 'Αριθμός' πρέπει να περιέχει μόνο αριθμούς (1-10 χαρακτήρες).";
    }

    // City: Only letters and spaces
    if (!preg_match("/^[a-zA-Z\s]+$/", $city)) {
        $errors[] = "Το πεδίο 'Πόλη' πρέπει να περιέχει μόνο γράμματα και κενά.";
    }

    // Postcode: Exactly 5 digits
    if (!preg_match("/^\d{5}$/", $postcode)) {
        $errors[] = "Το πεδίο 'Ταχυδρομικός Κώδικας' πρέπει να περιέχει ακριβώς 5 ψηφία.";
    }

    // Landline Telephone: Optional, but must be 10-13 digits
    if (!empty($landline_telephone) && !preg_match("/^\d{10,13}$/", $landline_telephone)) {
        $errors[] = "Το πεδίο 'Τηλέφωνο Σταθερού' πρέπει να περιέχει 10-13 ψηφία.";
    }

    // Mobile Telephone: Required, must be 10-13 digits
    if (!preg_match("/^\d{10,13}$/", $mobile_telephone)) {
        $errors[] = "Το πεδίο 'Κινητό Τηλέφωνο' πρέπει να περιέχει 10-13 ψηφία.";
    }

    // If there are validation errors, return them
    if (!empty($errors)) {
        echo json_encode(["success" => false, "errors" => $errors]);
		exit(); // Σταμάτα την εκτέλεση εδώ
    }
	
	
	
	
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
