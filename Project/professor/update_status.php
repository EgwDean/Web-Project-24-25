<?php
session_start();

// Έλεγχος αν ο χρήστης είναι συνδεδεμένος
if (!isset($_SESSION['email']) OR $_SESSION['type'] != 'PROF') {
    header("Location: logout.php");
    exit();
}

// Έλεγχος αν υπάρχει το id
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);  // Sanitize the ID

    // Στοιχεία σύνδεσης με τη βάση δεδομένων
    $servername = "localhost";
    $username = "root";
    $password = "556782340";
    $dbname = "diplomatiki_support";

    // Σύνδεση στη βάση δεδομένων
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Έλεγχος σύνδεσης με τη βάση δεδομένων
    if ($conn->connect_error) {
        echo json_encode(["success" => false, "error" => "Connection failed: " . $conn->connect_error]);
        exit();
    }

    // Ενημέρωση της κατάστασης
    $sql = "CALL setUnderExam(?, @output)";
    $stmt = $conn->prepare($sql);
    
    // Bind the parameters and execute
    $stmt->bind_param("i", $id);

    // Εκτέλεση της διαδικασίας
	if ($stmt->execute()) {
		// Αποδέσμευση της αποθηκευμένης διαδικασίας
		$stmt->close();
		while ($conn->next_result()) {;} // Αδειάζει τα υπόλοιπα αποτελέσματα

		// Ανάκτηση της τιμής του @output
		$result = $conn->query("SELECT @output AS output");
		if ($result) {
			$row = $result->fetch_assoc();
			$output = $row['output'] ?? null;

			if ($output === null) {
				echo json_encode(["success" => false, "error" => "Σφάλμα κατά την ανάκτηση του αποτελέσματος."]); 
			} elseif ($output == 0) {
				echo json_encode(["success" => true]);
			} elseif ($output == 1) {
				echo json_encode(["success" => false, "error" => "Δεν έχει εισαχθεί Αριθμός Πρωτοκόλου από τη Γραμματεία ακόμη."]);
			} else {
				echo json_encode(["success" => false, "error" => "Άγνωστο σφάλμα. Επικοινωνήστε με τον διαχειριστή."]);
			}
		} else {
			echo json_encode(["success" => false, "error" => "Σφάλμα κατά την ανάκτηση του @output: " . $conn->error]);
		}
	} else {
			echo json_encode(["success" => false, "error" => $stmt->error]);
	}

    $conn->close();
} else {
    echo json_encode(["success" => false, "error" => "Invalid request"]);
}
?>
