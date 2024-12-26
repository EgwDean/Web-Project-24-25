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
    $dipId = $_POST['studentId'];
    $studentNumber = $_POST['studentNumber'];
    $studentEmail = $_POST['studentEmail'];


        // Σύνδεση στη βάση δεδομένων
        $servername = "localhost";
        $username = "root";
        $password = "556782340";
        $dbname = "diplomatiki_support";

        $conn = new mysqli($servername, $username, $password, $dbname);

         // Έλεγχος σύνδεσης
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}

		// Προετοιμασία του SQL query για την εισαγωγή δεδομένων
		$sql = "CALL assign_thesis(?, ?, ?, ?, @output)";
		
            if ($stmt = $conn->prepare($sql)) {
                // Δέσιμο των παραμέτρων
                $stmt->bind_param("ssss", $dipId, $studentNumber, $studentEmail, $email);

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
                            echo json_encode(["success" => false, "error" => "Λάθος αριθμός διπλωματικής."]);
                        } elseif ($output == 1) {
                            echo json_encode(["success" => false, "error" => "Ο φοιτητής έχει ήδη διπλωματική."]);
                        } elseif ($output == 2) {
                            echo json_encode(["success" => false, "error" => "Λάθος στοιχεία φοιτητή."]);
                        } elseif ($output == 3) {
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