<?php
session_start();

$message = ''; // Αρχικοποίηση της μεταβλητής για το μήνυμα

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
            die("Connection failed: " . $conn->connect_error);
        }
		
        // Ετοιμάζουμε την κλήση της stored procedure
        $sql = "CALL gradeSubmit(?, ?, ?)";
        
        if ($stmt = $conn->prepare($sql)) {
            // Δέσιμο των παραμέτρων
            $stmt->bind_param("sid", $email, $id, $grade);

            // Εκτέλεση της διαδικασίας
            if ($stmt->execute()) {
                // Αποδέσμευση της αποθηκευμένης διαδικασίας
                $stmt->close();
				$message = "Επιτυχής εισαγωγή βαθμού.";
            } else {
                $message = "Σφάλμα κατά την εκτέλεση της διαδικασίας: " . $stmt->error;
            }
        } else {
            $message = "Σφάλμα κατά την προετοιμασία της διαδικασίας.";
        }

        // Κλείσιμο της σύνδεσης
        $conn->close();
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
                window.location.href = "professor3.php"; 
            }
        };
    </script>
</body>
</html>