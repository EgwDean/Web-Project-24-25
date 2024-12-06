<?php
// Ξεκινάμε τη συνεδρία αν δεν έχει ξεκινήσει
session_start();

// Έλεγχος αν ο χρήστης είναι συνδεδεμένος
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// Χρήστης που συνδέθηκε
$email = $_SESSION['email'];

$message = ''; // Αρχικοποιούμε τη μεταβλητή για το μήνυμα

// Ελέγχουμε αν τα δεδομένα έχουν αποσταλεί μέσω POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Λήψη των δεδομένων από τη φόρμα
    $dipId = $_POST['diplomaId'] ?? null;
    $studentEmail = $_POST['studentEmail'] ?? null;
	$meetNumber = isset($_POST['meetNumber']) && $_POST['meetNumber'] !== '' ? $_POST['meetNumber'] : null; // Set to NULL if hidden or empty
	$meetYear = isset($_POST['meetYear']) && $_POST['meetYear'] !== '' ? $_POST['meetYear'] : null;         // Set to NULL if hidden or empty


    // Ελέγχουμε αν τα δεδομένα της φόρμας υπάρχουν
    if (!$dipId || !$studentEmail) {
        $message = "Παρακαλώ εισάγετε και τα δύο πεδία: Αριθμό Διπλώματος και Email Φοιτητή.";
    } else {

        // Στοιχεία σύνδεσης με τη βάση δεδομένων
        $servername = "localhost";
        $username = "root";
        $password = "556782340";
        $dbname = "diplomatiki_support";

        // Σύνδεση στη βάση δεδομένων
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Έλεγχος σύνδεσης
        if ($conn->connect_error) {
            $message = "Η σύνδεση απέτυχε: " . $conn->connect_error;
        } else {
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
                                $message = "Η διπλωματική δεν ανήκει στον καθηγητή ή δεν είναι διαθέσιμη.";
                                break;
                            case -2:
                                $message = "Δεν έχουν παρέλθει 2 έτη από την οριστική ανάθεση.";
                                break;
                            case -3:
                                $message = "Λάθος συνδυασμός φοιτητή/διπλωματικής.";
                                break;
                            case -4:
                                $message = "Λάθος στοιχεία συνέλευσης.";
                                break;
                            case 1:
                                $message = "Η ανάκληση της ανάθεσης πραγματοποιήθηκε επιτυχώς!";
                                break;
                            case 2:
                                $message = "Η ακύρωση της ανάθεσης διπλωματικής πραγματοποιήθηκε επιτυχώς!";
                                break;
                            default:
                                $message = "Άγνωστο σφάλμα. Επικοινωνήστε με τον διαχειριστή.";
                        }
                    } else {
                        $message = "Σφάλμα: Η διαδικασία δεν επέστρεψε αποτέλεσμα.";
                    }
                    // Κλείσιμο της σύνδεσης
                    $conn->close();
                } else {
                    $message = "Σφάλμα στην εκτέλεση της διαδικασίας: " . $stmt->error;
                }
            } else {
                $message = "Σφάλμα στην προετοιμασία της διαδικασίας: " . $conn->error;
            }
        }
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
    <!-- Εμφάνιση του μηνύματος -->
    <div id="message" data-message="<?php echo htmlspecialchars($message); ?>"></div>

    <script type="text/javascript">
        window.onload = function() {
            // Λήψη του μηνύματος από το data-attribute
            var message = document.getElementById('message').getAttribute('data-message');
			
            // Αν υπάρχει μήνυμα, το εμφανίζουμε με alert
            if (message) {
                alert(message);
                
                // Αμέσως μετά την εμφάνιση του alert, ανακατεύθυνση στην σελίδα professor2_2.php
                window.location.href = "professor2_2.php"; 
            }
        };
    </script>
</body>
</html>