<?php

// Ξεκινάμε τη συνεδρία 
session_start();

// Έλεγχος αν ο χρήστης είναι συνδεδεμένος
if (!isset($_SESSION['email']) OR $_SESSION['type'] != 'PROF') {
    header("Location: logout.php");
    exit();
}

// Λήψη του email από τη συνεδρία
$email = $_SESSION['email'];


// Ελέγχουμε αν τα δεδομένα έχουν αποσταλεί μέσω POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Λήψη των δεδομένων από τη φόρμα
    $title = $_POST['title'];  // Τίτλος
    $description = $_POST['description'];  // Περιγραφή

        // Σύνδεση στη βάση δεδομένων
        $servername = "localhost";
        $username = "root";
        $password = "12345theo";
        $dbname = "diplomatiki_support";

        $conn = new mysqli($servername, $username, $password, $dbname);

        // Έλεγχος αν η σύνδεση ήταν επιτυχής
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
        
		// Ετοιμάζουμε την κλήση της stored procedure
        $sql = "CALL insert_to_dip(?, ?, ?, ?, @output)";
            
		if ($stmt = $conn->prepare($sql)) {
			// Δέσιμο των παραμέτρων
			$status = 'available';
			$stmt->bind_param("ssss", $email, $title, $description, $status);

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
						echo json_encode(["success" => false, "error" => "Ο τίτλος χρησιμοποιείται ήδη. Εισάγεται διαφορετικό."]);
					} elseif ($output == 1) {
						echo json_encode(["success" => true]);
					} else {
						echo json_encode(["success" => false, "error" => "Άγνωστο σφάλμα. Επικοινωνήστε με τον διαχειριστή."]);
					}
				} else {
					 echo json_encode(["success" => false, "error" => "Σφάλμα κατά την ανάκτηση του @output: " . $conn->error]);
                }
            } else {
                echo json_encode(["success" => false, "error" => $stmt->error]);
            }
        } else {
            echo json_encode(["success" => false, "error" => $conn->error]); 
        }

        // Κλείσιμο της σύνδεσης
        $conn->close();
}
?>