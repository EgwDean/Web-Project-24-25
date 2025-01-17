<?php
// Ξεκινάμε τη συνεδρία αν δεν έχει ξεκινήσει
session_start();

// Έλεγχος αν ο χρήστης είναι συνδεδεμένος
if (!isset($_SESSION['email']) OR $_SESSION['type'] != 'PROF') {
    header("Location: logout.php");
    exit();
}

// Χρήστης που συνδέθηκε
$email = $_SESSION['email'];


// Ελέγχουμε αν τα δεδομένα έχουν αποσταλεί μέσω POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Λήψη των δεδομένων από τη φόρμα
    $dipId = $_POST['diplomaId'] ?? null;
    $studentEmail = $_POST['studentEmail'] ?? null;
	$meetNumber = isset($_POST['meetNumber']) && $_POST['meetNumber'] !== '' ? $_POST['meetNumber'] : null; // Set to NULL if hidden or empty
	$meetYear = isset($_POST['meetYear']) && $_POST['meetYear'] !== '' ? $_POST['meetYear'] : null;         // Set to NULL if hidden or empty


    // Ελέγχουμε αν τα δεδομένα της φόρμας υπάρχουν
    if (!$dipId || !$studentEmail) {
       echo json_encode(["success1" => false, "success2" => false, "error" => "Παρακαλώ εισάγετε και τα δύο πεδία: Αριθμό Διπλώματος και Email Φοιτητή."]);
    } else {

        // Στοιχεία σύνδεσης με τη βάση δεδομένων
        $servername = "localhost";
        $username = "root";
        $password = explode(' ', file_get_contents('../dbpassword.txt'))[0];
        $dbname = "diplomatiki_support";

        // Σύνδεση στη βάση δεδομένων
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Έλεγχος σύνδεσης
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
		// Προετοιμασία του SQL query για την εισαγωγή δεδομένων
		$sql = "CALL recall_thesis(?, ?, ?, ?, ?, @output)";

		if ($stmt = $conn->prepare($sql)) {
			// Δέσιμο των παραμέτρων
			$stmt->bind_param("issis", $dipId, $studentEmail, $email, $meetNumber, $meetYear);

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

					switch ($output) {
						case -1:
							echo json_encode(["success1" => false, "success2" => false, "error" => "Η διπλωματική δεν ανήκει στον καθηγητή ή δεν είναι διαθέσιμη."]);
							break;
						case -2:
							echo json_encode(["success1" => false, "success2" => false, "error" => "Δεν έχουν παρέλθει 2 έτη από την οριστική ανάθεση."]);
							break;
						case -3:
							echo json_encode(["success1" => false, "success2" => false, "error" => "Λάθος συνδυασμός φοιτητή/διπλωματικής."]);
							break;
						case -4:
							echo json_encode(["success1" => false, "success2" => false, "error" => "Λάθος στοιχεία συνέλευσης."]);
							break;
						case 1:
							echo json_encode(["success1" => true]);   
							break;
						case 2:
							echo json_encode(["success2" => true]); 
							break;
						default:
						   echo json_encode(["success1" => false, "success2" => false, "error" => "Άγνωστο σφάλμα. Επικοινωνήστε με τον διαχειριστή."]);
					}
				} else {
					 echo json_encode(["success1" => false, "success2" => false, "error" => "Σφάλμα: Η διαδικασία δεν επέστρεψε αποτέλεσμα."]);
				}
				// Κλείσιμο της σύνδεσης
				$conn->close();
			} else {
				echo json_encode(["success1" => false, "success2" => false, "error" => $stmt->error]);
			}
		} else {
			echo json_encode(["success1" => false, "success2" => false, "error" => $conn->error]);
		}
        
    }
}
?>
