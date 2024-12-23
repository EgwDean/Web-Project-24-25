<?php
session_start(); // Αναζήτηση συνόδου

if (!isset($_SESSION['email'])) { //Αν δεν έχει γίνει login ανακατεύθυνση
    header("Location: logout.php");
    exit();
}

if ($_SESSION['type'] != 'GRAM') {
    header("Location: logout.php");
    exit();
  }

$host = "localhost";
$dbusername = "root";
$dbpassword = "Matsaniarakos9";
$dbname = "diplomatiki_support";

$conn = new mysqli($host, $dbusername, $dbpassword, $dbname); // Σύνδεση με τη βάση

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$data = json_decode(file_get_contents("php://input"), true); // Αποθήκευση των POST δεδομένων σε PHP array

// insert statement για τη βάση
$stmt = $conn->prepare("INSERT INTO student (password, name, surname, student_number, street, number, city, postcode, father_name, landline_telephone, mobile_telephone, email_student) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

$errors = []; // Για duplicate key ή cannot be null errors

foreach ($data as $student) {
    $fieldErrors = []; // Errors για τη συγκεκριμένη εγγραφή

    // Έλεγχος αν κάποιο πεδίο είναι null ή κενό
    foreach (['password', 'name', 'surname', 'student_number', 'street', 'number', 'city', 'postcode', 'father_name', 'landline_telephone', 'mobile_telephone', 'email_student'] as $field) {
        if (empty($student[$field])) {
            $fieldErrors[] = "The field '$field' cannot be NULL or empty for student: " . ($student['email_student']);
        }
    }

    if (!empty($fieldErrors)) { // Προσθήκη κάθε error στο errors array
        $errors = array_merge($errors, $fieldErrors);
        continue; // Επόμενη εγγραφή
    }

    try {
        // Bind parameters στο insert statement
        $stmt->bind_param("ssssssssssss", 
            $student['password'], 
            $student['name'], 
            $student['surname'], 
            $student['student_number'], 
            $student['street'], 
            $student['number'], 
            $student['city'], 
            $student['postcode'], 
            $student['father_name'],
            $student['landline_telephone'],
            $student['mobile_telephone'],
            $student['email_student']
        );

        $stmt->execute(); // τρέξιμο
    } catch (mysqli_sql_exception $e) {
        if ($e->getCode() == 1062) {
            $errors[] = "Duplicate entry for student: " . $student['email_student'];
        } else {
            $errors[] = "Error inserting student: " . $student['email_student'] . ". Error: " . $e->getMessage();
        }
    }
}

$stmt->close(); // κλείσιμο statement

if (empty($errors)) {
    echo json_encode(['success' => 'Data successfully inserted!']);
} else {
    echo json_encode(['error' => $errors]);
}

$conn->close();
?>