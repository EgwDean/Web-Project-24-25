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
    $id = $_POST['Id'];  // Αριθμός ID για το accept


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
	
	
	// Προετοιμασία του SQL query για την εισαγωγή δεδομένων
	$sql = "CALL accept(?, ?, @output)";
	
	if ($stmt = $conn->prepare($sql)) {
		// Δέσιμο των παραμέτρων
		$stmt->bind_param("ss", $email, $id);

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
				} elseif ($output == 1) {
					echo json_encode(["success" => false, "error" => "Η τριμελής επιτροπή είναι πλήρης!"]); 
				} elseif ($output == 2) {
					echo json_encode(["success" => false, "error" => "Δεν υπάρχει διπλωματική με αυτό το ID"]);
				} else {
					echo json_encode(["success" => true]);
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
