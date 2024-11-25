<?php
session_start();

// Έλεγχος αν ο χρήστης είναι συνδεδεμένος
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

$email = $_SESSION['email'];

// Έλεγχος αν τα δεδομένα έχουν αποσταλεί μέσω POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Λήψη των δεδομένων από τη φόρμα  
    $id = $_POST['diplId'];
    $grade = $_POST['diplomaGrade'];
	
    // Στοιχεία σύνδεσης με τη βάση δεδομένων
    $servername = "localhost";
    $username = "root";
    $password = "556782340";
    $dbname = "diplomatiki_support";

    // Σύνδεση στη βάση δεδομένων
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Έλεγχος σύνδεσης
    if ($conn->connect_error) {
        echo json_encode(["success" => false, "error" => "Connection failed: " . $conn->connect_error]);
        exit();
    }else{
		
		// Ετοιμάζουμε την κλήση της stored procedure
		$sql = "CALL gradeSubmit(?, ?, ?)";
			
		if ($stmt = $conn->prepare($sql)) {
			// Δέσιμο των παραμέτρων
			$stmt->bind_param("sid", $email, $id, $grade);

			// Εκτέλεση της διαδικασίας
			if ($stmt->execute()) {
				echo json_encode(["success" => true]);
			} else {
				echo json_encode(["success" => false, "error" => $stmt->error]);
			}
				$stmt->close();
		} else {
			echo json_encode(["success" => false, "error" => $conn->error]); 
		}
		// Κλείσιμο της σύνδεσης
		$conn->close();
	}
}
?>