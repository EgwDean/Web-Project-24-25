<?php

// Ξεκινάμε τη συνεδρία 
session_start();

// Έλεγχος αν ο χρήστης είναι συνδεδεμένος
if (!isset($_SESSION['email'])) {
    header("Location: logout.php");
    exit();
}

// Λήψη του email από τη συνεδρία
$email = $_SESSION['email'];


// Έλεγχος αν το αρχείο PDF έχει επιλεγεί
if (isset($_FILES['pdf_file']) && $_FILES['pdf_file']['error'] == 0) {
    // Λήψη του ID από τη φόρμα
    $id = $_POST['id'];

    // Καθορισμός του μονοπατιού αποθήκευσης του αρχείου
    $uploadDir = '../uploads/pdf_link_topic/';  // Δημιουργήστε τον φάκελο 'uploads' αν δεν υπάρχει
    $fileName = $id . '.pdf';  // Όνομα αρχείου με βάση το ID
    $uploadFile = $uploadDir . $fileName;

    // Έλεγχος αν το αρχείο υπάρχει ήδη
    if (file_exists($uploadFile)) {
        // Αν το αρχείο υπάρχει, κάνουμε overwrite
        if (!unlink($uploadFile)) {
            $message = "Δεν ήταν δυνατή η διαγραφή του υπάρχοντος αρχείου.";
            exit;
        }
    }

    // Μεταφορά του αρχείου στον κατάλογο uploads
    if (move_uploaded_file($_FILES['pdf_file']['tmp_name'], $uploadFile)) {
        echo json_encode(["success" => true]);

        // Σύνδεση στη βάση δεδομένων
        $servername = "localhost";
        $username = "root";
        $password = "Matsaniarakos9";
        $dbname = "diplomatiki_support";

        $conn = new mysqli($servername, $username, $password, $dbname);

        // Έλεγχος σύνδεσης
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
	
		// Κλήση της συνάρτησης upload_pdf στη βάση
		$stmt = $conn->prepare("CALL upload_pdf(?)");
		$stmt->bind_param("i", $id);  // Αν το $id είναι ακέραιος τύπος
		$stmt->execute();

		// Κλείσιμο της σύνδεσης
		$stmt->close();
		$conn->close();
        
    } else {
        echo json_encode(["success" => false, "error" => "Υπήρξε πρόβλημα με την αποθήκευση του αρχείου."]);
        exit;
    }
} else {
    echo json_encode(["success" => false, "error" => "Παρακαλώ επιλέξτε ένα αρχείο PDF για να το ανεβάσετε."]);
    exit;
}
?>