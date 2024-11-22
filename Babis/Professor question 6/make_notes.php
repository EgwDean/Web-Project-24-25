<?php
session_start();

// Στοιχεία σύνδεσης με τη βάση δεδομένων
$servername = "localhost";
$username = "root";
$password = "556782340";
$dbname = "diplomatiki_support";

// Σύνδεση στη βάση δεδομένων
$conn = new mysqli($servername, $username, $password, $dbname);

// Έλεγχος αν η σύνδεση ήταν επιτυχής
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Έλεγχος αν ο χρήστης είναι συνδεδεμένος
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}	


$email = $_SESSION['email'];

$message = ''; // Αρχικοποιούμε τη μεταβλητή για το μήνυμα	
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Λήψη των δεδομένων από τη φόρμα
    $notes = $_POST['notes'];  
	$id = $_POST['diplomaId'];
	$status = $_POST['diplomaStatus'];
	$status = trim($status); // Αφαιρεί κενά
   
    
	if($status !== 'active'){ $message = "Η διπλωματική πρέπει να είναι ενεργή για να προσθέσετε σημειώσεις.";	
	}else{
	// Ετοιμάζουμε την κλήση της stored procedure
	$sql = "CALL createNotes(?, ?, ?, @output)";
				
	if ($stmt = $conn->prepare($sql)) {
		// Δέσιμο των παραμέτρων
		$stmt->bind_param("sis", $email, $id, $notes);

		// Εκτέλεση της διαδικασίας
		if ($stmt->execute()) {
			// Αποδέσμευση της αποθηκευμένης διαδικασίας
			$stmt->close();
			while ($conn->next_result()) {;} // Αδειάζει τα υπόλοιπα αποτελέσματα

			// Ανάκτηση του error_code
            $result = $conn->query("SELECT @output as output");
            if ($result) {
                $row = $result->fetch_assoc();
                $output = (int)$row['output'];
                

					if ($output === 0) {
						$message = "Οι σημειώσεις καταχωρήθηκαν επιτυχώς.";
					} else if ($output === null) {
						$message = "Σφάλμα κατά την ανάκτηση του αποτελέσματος.";
					} elseif ($output === 1) {
						$message = "Πρέπει να είστε επιβλέπων ή μέλος για να προσθέσετε σημειώσεις.";
				    } else {
						$message = "Άγνωστο σφάλμα. Επικοινωνήστε με τον διαχειριστή.";
					}
			} else {
				$message = "Σφάλμα κατά την ανάκτηση του @output: " . $conn->error;
			}
		} else {
			$message = "Σφάλμα κατά την εκτέλεση της διαδικασίας: " . $stmt->error;
		}
	} else {
			$message = "Σφάλμα κατά την προετοιμασία της διαδικασίας.";
	}
	// Κλείσιμο της σύνδεσης
		$conn->close();
	}
}       
?>
<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Μήνυμα</title>
</head>
<body>
    <!-- Το υπόλοιπο περιεχόμενο της σελίδας -->
    <!-- Αποθήκευση του μηνύματος στο data-attribute -->
    <div id="message" data-message="<?php echo htmlspecialchars($message); ?>"></div>

    <script type="text/javascript">
        window.onload = function() {
            // Λήψη του μηνύματος από το data-attribute
            var message = document.getElementById('message').getAttribute('data-message');
            
            // Αν υπάρχει μήνυμα, να το εμφανίσουμε με alert
            if (message) {
                alert(message);
                
                // Αμέσως μετά την εμφάνιση του alert, ανακατεύθυνση στην σελίδα professor3.php
                window.location.href = "professor3.php"; // Προσαρμόστε τη διεύθυνση εάν χρειάζεται
            }
        };
    </script>
</body>
</html>