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
$stmt = $conn->prepare("INSERT INTO professor (password, name, surname, email_professor, topic, landline, mobile, department, university) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

$errors = []; // Για duplicate key ή cannot be null errors

foreach ($data as $professor) {
    $fieldErrors = []; // Errors για τη συγκεκριμένη εγγραφή

    // Έλεγχος αν κάποιο πεδίο είναι null ή κενό
    foreach (['password', 'name', 'surname', 'email_professor', 'topic', 'landline', 'mobile', 'department', 'university'] as $field) {
        if (empty($professor[$field])) {
            $fieldErrors[] = "The field '$field' cannot be NULL or empty for professor: " . ($professor['email_professor']);
        }
    }

    if (!empty($fieldErrors)) { // Προσθήκη κάθε error στο errors array
        $errors = array_merge($errors, $fieldErrors);
        continue; // Επόμενη εγγραφή
    }

    try {
        // Bind parameters στο insert statement
        $stmt->bind_param("sssssssss", 
            $professor['password'], 
            $professor['name'], 
            $professor['surname'], 
            $professor['email_professor'], 
            $professor['topic'], 
            $professor['landline'], 
            $professor['mobile'], 
            $professor['department'], 
            $professor['university']
        );

        $stmt->execute(); // τρέξιμο
    } catch (mysqli_sql_exception $e) {
        if ($e->getCode() == 1062) {
            $errors[] = "Duplicate entry for professor: " . $professor['email_professor'];
        } else {
            $errors[] = "Error inserting professor: " . $professor['email_professor'] . ". MySQL Error: " . $e->getMessage();
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